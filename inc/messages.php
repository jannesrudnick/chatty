<?php
    // ACTION -> send_message  ## send messages
    if (
        count($_POST) == 3 &&
        isset($_POST['receivers']) &&
        isset($_POST['subject']) &&
        isset($_POST['text'])
    )  {
        send_message(
            $_POST['receivers'],
            $_POST['subject'],
            $_POST['text'],
        );
    } 

    // ACTION -> download_message  ## download messages
    if (
        count($_GET) == 4 &&
        isset($_GET['download']) &&
        isset($_GET['from']) &&
        isset($_GET['to'])
    )  {
        download_message(
            $_GET['download'],
            $_GET['from'],
            $_GET['to'],
        );
        return;
    } 
?>


<div class="container">
    <!-- VIEW1  ## message detail -->
    <?php
        // VIEW1 -> display_message_detail  ## display a certain message by id
        if (
            count($_GET) == 4 &&
            isset($_GET['detail']) &&
            isset($_GET['from']) &&
            isset($_GET['to'])
        ) {                
            display_message_detail(
                $_GET['detail'],
                $_GET['from'],
                $_GET['to'],
            );

            return;
        } 
    ?>
    
    <!-- VIEW2  ## protocol & send form -->
    <div class="row top-space">
        <div class="col-sm-8">
            <h3>Protokoll</h3>
            <p>Hier siehst du alle Nachrichten die dir zugeschickt wurden oder du gesendet hast.</p>
            <div>
                <a role="button" class="btn btn-light" href="index.php?do=11&m=all">Alle</a>
                <a role="button" class="btn btn-light" href="index.php?do=11&m=in">Eingehend</a>
                <a role="button" class="btn btn-light" href="index.php?do=11&m=out">Ausgehend</a>
            </div>
            <?php 
                // VIEW2 -> display_message_table  ## display protocol
                if (
                    count($_GET) == 2 &&
                    isset($_GET['m']) &&
                    ($_GET['m'] == 'in' || $_GET['m'] == 'out' || $_GET['m'] == 'all')
                ) {
                    display_message_table($_GET['m']);  
                } else {
                    display_message_table('all');
                }
            ?>
        </div>
        <div class="col-sm-4">
            <h3>Senden</h3>
            <p>Hier kannst du Nachrichten senden.</p>
            <form action="./index.php?do=11" method="post" class="top-space">
                <select multiple class="form-control" name="receivers[]" required>
                    <?php 
                        // VIEW2  ## user list to choose receiver/s
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
                <button type="submit" class="btn btn-primary">Absenden</button>
            </form>
        </div>
    </div>
</div>