<div class="container">
    <!-- VIEW1  ## show chat with one user -->
    <?php
        // ACTION reset patner -> VIEW2
        if (isset($_GET['a']) && $_GET['a'] == 'reset') {
            $_SESSION['patner'] = '';
        
        } else {
            // actions button
            echo '<div class="top-space"><a href="./index.php?do=13">Nachrichten Synchronisieren</a></div><br>';

            // ACTION -> send_message  ## send messages
            if (
                count($_POST) == 2 &&
                isset($_SESSION['patner']) &&
                strlen($_SESSION['patner']) > 0
            ) {
                $partners = array(get_username($_SESSION['patner']));
                send_message($partners, $_POST['subject'], $_POST['text']);
            } 

            // ACTION -> set_read  ## set read state to yes
            if (
                count($_GET) == 2 &&
                isset($_GET['read'])
            ) { 
                set_read($_GET['read']);
            }

            // VIEW1 -> display_chat  ## display chat & message form
            if (isset($_POST['patner']) || (isset($_SESSION['patner']) && strlen($_SESSION['patner']) > 0)) {
                display_chat($_POST['patner']);
                  
                return;
            }

        }

        

    ?>

    <!-- VIEW2  ## choose chat patner -->
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