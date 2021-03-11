<?php
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

    if (
        count($_GET) == 4 &&
        isset($_GET['download']) &&
        isset($_GET['from']) &&
        isset($_GET['to'])
    )  {
        download_msg(
            $_GET['download'],
            $_GET['from'],
            $_GET['to'],
        );
        return;
    } 
?>


<div class="container">
    <?php
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
    
    <div class="row top-space">
        <div class="col-sm-8">
            <h3>🗒 Protocol</h3>
            <p>Get all messages you send or received.</p>
            <div>
                <a role="button" class="btn btn-light" href="index.php?do=11&m=all">all</a>
                <a role="button" class="btn btn-light" href="index.php?do=11&m=in">incoming</a>
                <a role="button" class="btn btn-light" href="index.php?do=11&m=out">outgoing</a>
            </div>
            <?php 
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
            <h3>🖋 Send</h3>
            <p>Just send messages.</p>
            <form action="./index.php?do=11" method="post" class="top-space">
                <select multiple class="form-control" name="receivers[]" required>
                    <?php 
                        $sql = "SELECT username FROM user";
                        $result = $con->query($sql);
                    
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_array()) {
                                echo "<option>$row[0]</option>";
                            }
                        } 
                    ?>
                </select>
                
                <input type="text" class="form-control" placeholder="subject" name="subject" required>
                <textarea class="form-control" rows="5" placeholder="message" name="text" required></textarea>
                <button type="submit" class="btn btn-primary">send</button>
            </form>
        </div>
    </div>
</div>