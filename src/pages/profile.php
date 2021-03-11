<div class="container">
    <?php
    if (count($_POST) == 2) {
        if ($stmt = $con->prepare("UPDATE user SET lastname=?, firstname=? WHERE uid=?")) {
            $stmt->bind_param("sss", $_POST['lastname'], $_POST['name'], $_SESSION['uid']);
            if ($stmt->execute()) {
                echo '
                <div class="top-space">
                    <h3>✅ Congrats</h3>
                    <p>The data was changed successful!</p>
                    <a role="button" class="btn btn-light" href="index.php?do=1">Home</a>
                </div>
                ';
            } else {
                echo '
                <div class="top-space">
                    <h3>❌ Error</h3>
                    <p>Unfortunately changing the data caused a error!</p>
                </div>
                ';
            }
            $stmt->close();
        }

    }    

    if ($stmt = $con->prepare("SELECT uid, gender, lastname, firstname, username FROM user WHERE uid=?")) {
        $stmt->bind_param("s", $_SESSION['uid']);
        $stmt->execute();
        $stmt->bind_result($uid, $gender, $lastname, $name, $username);
        $stmt->fetch();
        $stmt->close();


        if ($gender == 'male') {
            $male_checked = 'checked';
            $female_checked = '';
        } else {
            $male_checked = '';
            $female_checked = 'checked';
        }

        echo '
        <form action="./index.php?do=12" method="post" class="top-space">
            <div class="form-check-inline">
                <input type="radio" class="form-check-input" name="gender" value="male" '.$male_checked.' disabled>male
            </div>
            <div class="form-check-inline">
                <input type="radio" class="form-check-input" name="gender" value="female" '.$female_checked.' disabled>female
            </div>
            <input type="text" class="form-control" name="lastname" value="'.$lastname.'">
            <input type="text" class="form-control" name="name" value="'.$name.'">
            <input type="text" class="form-control" value="'.$username.'" disabled>
            <button type="submit" class="btn btn-primary">change data</button>
        </form>
        ';


        return;
    }
    ?>
<div class="container">