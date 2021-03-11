<div class="container">
    <?php
        // action -> reset partner
        if (isset($_GET['a']) && $_GET['a'] == 'reset') {
            $_SESSION['partner'] = '';
        
        } else {
            echo '<div class="top-space"><a href="./index.php?do=13">sync messages</a></div><br>';

            if (
                count($_POST) == 2 &&
                isset($_SESSION['partner']) &&
                strlen($_SESSION['partner']) > 0
            ) {
                $partners = array(get_username($_SESSION['partner']));
                send_message($partners, $_POST['subject'], $_POST['text']);
            } 

            if (
                count($_GET) == 2 &&
                isset($_GET['read'])
            ) { 
                set_read($_GET['read']);
            }

            if (isset($_POST['partner']) || (isset($_SESSION['partner']) && strlen($_SESSION['partner']) > 0)) {
                display_chat($_POST['partner']);
                  
                return;
            }
        }

    ?>

    <div class="top-space">
        <h3>Chat's</h3>
        <p>Choose a user and start chatting with him.</p>
        <form action="./index.php?do=13" method="post">
            <select class="form-control" name="partner" required>
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
            <button type="submit" class="btn btn-primary">start</button>
        </form>
    </div>
</div>