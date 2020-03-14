<div class="container">
    <?php
        if (count($_POST) == 3) {
            $receivers = $_POST['receivers'];
            $subject = $_POST['subject'];
			$text = $_POST['text'];

			if ($stmt = $con->prepare("INSERT INTO is_message (betreff, nachricht, senderuid) VALUES (?, ?, ?)")) {
                $stmt->bind_param("sss", $subject, $text, $_SESSION['uid']);
                $stmt->execute();
                $mid = $stmt->insert_id;
                $stmt->close();

                if ($stmt = $con->prepare("INSERT INTO is_empfang (uid, mid) VALUES (?, ?)")) {
                    $stmt->bind_param("ss", $receiver_id, $mid);
                    
                    foreach ($_POST['receivers'] as $receiver) {
                        $receiver_id = get_uid($receiver);
                        $stmt->execute();
                    }

                    $stmt->close();
                }
            }
        } 
    ?>

    <div class="row top-space">
        <div class="col-sm-8">

            <?php

                if (count($_GET) >= 3) {
                    if (isset($_GET['detail']) && isset($_GET['user'])) {
                        if ($stmt = $con->prepare("SELECT betreff, nachricht, datumzeit FROM is_message WHERE mid=?")) {
                            $stmt->bind_param("s", $_GET['detail']);
                            $stmt->execute();
                            $stmt->bind_result($subject, $message, $timestamp);
                            $stmt->fetch();
                            $stmt->close();

                            $user = $_GET['user'];

                            $pre = 'gesendet von';

                            echo "
                            <a href='./index.php?do=11'>< zurück</a>
                            <div class='top-space'>
                                <code>$timestamp</code><br>
                                $pre <code>$user</code><br><br>
                                <h1>$subject</h1>
                                <p>$message</p>
                            </div>
                            ";

                            
                            return; 
                        }                    
                    }
                }   
            ?>


            <h3>Protokoll</h3>
            <p>Hier siehst du alle Nachrichten die dir zugeschickt wurden oder du gesendet hast.</p>
            <?php

                $sql = 'SELECT e.mid, datumzeit, betreff, username, e.uid
                FROM is_empfang e
                JOIN is_message m
                JOIN is_user u
                ON (e.mid=m.mid) AND (m.senderuid=u.uid)
                WHERE m.senderuid=? OR e.uid=?
                ORDER BY datumzeit DESC';

                if ($stmt = $con->prepare($sql)) {
                    $stmt->bind_param("ss", $_SESSION['uid'], $_SESSION['uid']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    // table head
                    echo '
                    <table class="table table-striped top-space">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Zeitpunkt</th>
                                <th>Betreff</th>
                                <th>von</th>
                                <th>nach</th>
                                <th>Aktionen</th>
                            </tr>
                        </thead>
                    <tbody>
                    ';

                    // table body
                    while ($row = $result->fetch_array()) {
                        echo '<tr>';
                            if ($row[4] == $_SESSION['uid']) {
                                $dir = "->";
                            } else {
                                $dir = "<-";
                            }

                            echo "<td>$dir</td>";

                            for($s=1; $s < $result->field_count;$s++) {
                                $tabledata = $row[$s];
                                if ($s == 4) $tabledata = get_username($row[4]);

                                echo "<td>$tabledata</td>";
                            }
                            echo "<td><a href='./index.php?do=11&detail=$row[0]&user=$row[3]'><i class='fas fa-expand'></i></a></td>";
                        echo '</tr>';
                    }

                    echo '</tbody></table>';

                    $stmt->close();

                }

            ?>
        </div>
        <div class="col-sm-4">
            <h3>Senden</h3>
            <p>Hier kannst du Nachrichten senden.</p>
            <form action="./index.php?do=11" method="post" class="top-space">
                <select multiple class="form-control" name="receivers[]" required>
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
                
                <input type="text" class="form-control" placeholder="Betreff eingeben" name="subject" required>
                <textarea class="form-control" rows="5" placeholder="Nachricht eingeben" name="text" required></textarea>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            <h3>Chat's</h3>
            <p>Hier kannst du einen Chat starten.</p>
            <a role="button" class="btn btn-light" href="index.php?do=13">Chat</a>
        </div>
    </div>
</div>