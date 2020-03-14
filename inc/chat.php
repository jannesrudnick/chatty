<div class="container">
    <?php

        // reset patner
        if (isset($_GET['a']) && $_GET['a'] == 'reset') {
            $_SESSION['patner'] = '';
        } else {

            echo '<a href="./index.php?do=13">Nachrichten Synchronisieren</a><br>';

            // send message to patner from session
            if (count($_POST) == 2 && isset($_SESSION['patner']) && strlen($_SESSION['patner']) > 0) {

                $subject = $_POST['subject'];
                $text = $_POST['text'];

                if ($stmt = $con->prepare("INSERT INTO is_message (betreff, nachricht, senderuid) VALUES (?, ?, ?)")) {
                    $stmt->bind_param("sss", $subject, $text, $_SESSION['uid']);
                    $stmt->execute();
                    $mid = $stmt->insert_id;
                    $stmt->close();

                    if ($stmt = $con->prepare("INSERT INTO is_empfang (uid, mid) VALUES (?, ?)")) {
                        $stmt->bind_param("ss", $_SESSION['patner'], $mid);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            
            } 
            // show chat with patner
            if (isset($_POST['patner']) || isset($_SESSION['patner'])) {
                // reset button
                echo '<a href="./index.php?do=13&a=reset">Patner Zurücksetzen</a>';

                echo '
                <div>
                    <form action="./index.php?do=13" method="post" class="top-space">
                        <input type="text" class="form-control" placeholder="Betreff eingeben" name="subject" required>
                        <textarea class="form-control" rows="5" placeholder="Nachricht eingeben" name="text" required></textarea>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div class="chat-box">';
                
                if (isset($_SESSION['patner']) && strlen($_SESSION['patner']) > 0) {
                    $patner = $_SESSION['patner'];
                } else {
                    $patner = get_uid($_POST['patner']);
                    $_SESSION['patner'] = $patner;
                }
                

                $sql = "SELECT username, datumzeit, betreff, nachricht
                FROM is_empfang e
                JOIN is_message m
                JOIN is_user u
                ON (e.mid=m.mid) AND (m.senderuid=u.uid)
                WHERE (m.senderuid=? AND e.uid=?) 
                OR (m.senderuid=? AND e.uid=?)
                ORDER BY datumzeit DESC";

                if ($stmt = $con->prepare($sql)) {
                    $stmt->bind_param("ssss", $patner, $_SESSION['uid'], $_SESSION['uid'], $patner);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    while ($row = $result->fetch_array()) {
                        
                        
                        $class = ' chat-patner';
                        if (get_uid($row[0]) == $_SESSION['uid']) {
                            // align message right
                            $class = ' chat-sender';
                        }
                        echo "
                        <div class='chat-wrapper'>
                            <div class='chat $class'>
                                <div class='chat-header'>$row[2]</div>
                                <div class='chat-body'>$row[3]</div>
                                <code class='chat-timestamp'>$row[1]</code>
                            </div>
                        </div>";

                    }
                }

                echo '</div>';

                return;
            }

        }

        

    ?>

    
    <div class="top-space">
        <h3>Chat's</h3>
        <p>Wähle einen User aus, mit welchen du einen Chat beginnen oder fortführen willst.</p>
        <form action="./index.php?do=13" method="post">
            <select class="form-control" name="patner" required>
                <?php 
                    $sql = "SELECT username FROM is_user";
                    $result = $con->query($sql);
                
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_array()) {
                            echo "<option>$row[0]</option>";
                        }
                    } 
                ?>
            </select>
            <button type="submit" class="btn btn-primary">Starten</button>
        </form>
    </div>
</div>