<?php
    include('./dbconfig.php');

	// get message
	if ($stmt = $con->prepare("SELECT betreff, nachricht, datumzeit FROM is_message WHERE mid=?")) {
		$stmt->bind_param("s", $_GET['mid']);
		$stmt->execute();
		$stmt->bind_result($subject, $message, $timestamp);
		$stmt->fetch();
        $stmt->close();
    }

    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$timestamp.txt");
    echo $_GET['f'].' -> '.$_GET['t'].PHP_EOL.PHP_EOL;
    echo $subject.PHP_EOL.PHP_EOL.PHP_EOL;
    echo $message;
?>