<div class="container">
    <?php
    if (count($_POST) == 2) { // alle Argumente gegeben  UPDATE `is_user` SET `vorname` = 'Waltere' WHERE `is_user`.`uid` = 6;
        if ($stmt = $con->prepare("UPDATE is_user SET vorname=?, nachname=? WHERE uid=?")) {
            $stmt->bind_param("sss", $_POST['surname'], $_POST['name'], $_SESSION['uid']);
            if ($stmt->execute()) {
                echo '
                <div class="top-space">
                    <p>Du hast deine Daten erfolgreich geändert!</p>
                    <a role="button" class="btn btn-light" href="index.php?do=1">Home</a>
                </div>
                ';
            } else {
                echo '
                <div class="top-space">
                    <h3>Fehler!</h3>
                    <p>Beim ändern der Daten ist ein Fehler aufgetreten!</p>
                </div>
                ';
            }
            $stmt->close();
        }

    }    

    if ($stmt = $con->prepare("SELECT uid, anrede, nachname, vorname, username FROM is_user WHERE uid=?")) {
        $stmt->bind_param("s", $_SESSION['uid']);
        $stmt->execute();
        $stmt->bind_result($uid, $salutation, $name, $surname, $username);
        $stmt->fetch();
        $stmt->close();


        if ($salutation == 'Herr') {
            $male_checked = 'checked';
            $female_checked = '';
        } else {
            $male_checked = '';
            $female_checked = 'checked';
        }

        echo '
        <form action="./index.php?do=12" method="post" class="top-space">
            <div class="form-check-inline">
                <input type="radio" class="form-check-input" name="salutation" value="Herr" '.$male_checked.' disabled>Herr
            </div>
            <div class="form-check-inline">
                <input type="radio" class="form-check-input" name="salutation" value="Frau" '.$female_checked.' disabled>Frau
            </div>
            <input type="text" class="form-control" name="surname" value="'.$surname.'">
            <input type="text" class="form-control" name="name" value="'.$name.'">
            <input type="text" class="form-control" value="'.$username.'" disabled>
            <button type="submit" class="btn btn-primary">Daten ändern</button>
        </form>
        ';


        return;
    }
    ?>
<div class="container">