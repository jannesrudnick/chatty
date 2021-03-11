<?php

include('./src/utils/db_setup.php');


function special_chars($string) {
    $search = array("Ä", "Ö", "Ü", "ä", "ö", "ü", "ß", "´");
    $replace = array("Ae", "Oe", "Ue", "ae", "oe", "ue", "ss", "");
    return str_replace($search, $replace, $string);
}

function generate_username($firstname, $lastname): string {
    return strtolower(substr(special_chars($firstname), 0, 3) . substr(special_chars($lastname), 0, 3));
}

function get_uid($username) {
	$uid = null;
	if ($stmt = $GLOBALS['db']->prepare("SELECT uid FROM user WHERE username=?")) {
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$stmt->bind_result($uid);
		$stmt->fetch();
		$stmt->close();

		return $uid;
	}
	return;
}

function get_username($uid) {
	$username = null;
	if ($stmt = $GLOBALS['db']->prepare("SELECT username FROM user WHERE uid=?")) {
		$stmt->bind_param("s", $uid);
		$stmt->execute();
		$stmt->bind_result($username);
		$stmt->fetch();
		$stmt->close();

	}
	return $username;
}

function get_read_icon($str): string {
	if ($str == 1) {
		$read = '✅';
	} else {
		$read = '🆕';
	}

	return $read;
}

// --- A C T I O N S -------------------------------------------------------------------------------

// action -> register  -----------------------------------------------------------------------------
function register($gender, $firstname, $lastname, $password) {
	$con = $GLOBALS['db'];
	$username = generate_username($firstname, $lastname);

	// check if username already exists
	if (is_numeric(get_uid($username))) {
		echo "
		<div class='top-space'>
			<h3>❌ Error</h3>
			<p>Unfortunately this user already exists</p>
			<a role='button' class='btn btn-light' href='index.php?do=2'>Register</a>
		</div>
		";

		return;
	}

	$stmt = $con->prepare("INSERT INTO user (gender, lastname, firstname, username, password) VALUES (?, ?, ?, ?, md5(?))");
	$stmt->bind_param("sssss", $gender, $lastname, $firstname, $username, $password);

	if ($stmt->execute()) {
		echo "
		<div class='top-space'>
			<h3>✅ Congrats</h3>
			<p>Just login and send messages.</p>
			<p>username: <code>$username</code></p>
			<a role='button' class='btn btn-light' href='index.php?do=1'>Login</a>
		</div>
		";
	}
	
	echo '</div>';
}

// action -> login  --------------------------------------------------------------------------------
function login($username, $password) {
	$con = $GLOBALS['db'];

	$uid = null;
	$password_db = null;
	$password_hash = null;

	if ($stmt = $con->prepare("SELECT uid, password FROM user WHERE username=?")) {
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$stmt->bind_result($uid, $password_db);
		$stmt->fetch();
		$stmt->close();

		if ($stmt = $con->prepare("SELECT md5(?)")) {
			$stmt->bind_param("s", $password);
			$stmt->execute();
			$stmt->bind_result($password_hash);
			$stmt->fetch();
			$stmt->close();

			if ($password_hash != $password_db) {
				echo '
				<div class="top-space">
					<h3>❌ Error</h3>
					<p>Unfortunately your input is invalid.</p>
					<a role="button" class="btn btn-light" href="index.php?do=1">Login</a>
				</div>
				';

				return;
			}

			$_SESSION["uid"] = $uid;

			echo '
			<div class="top-space">
				<h3>🚀 Let\'s go</h3>
				<p>You successfully logged in, now you can send messages.</p>
				<a role="button" class="btn btn-light" href="index.php?do=0">Let\'s go</a>
			</div>
			';
		}
	}
	
	echo '</div>';
}

// action -> send a message ------------------------------------------------------------------------
function send_message($receivers, $subject, $text) {
	$con = $GLOBALS['db'];
	if ($stmt = $con->prepare("INSERT INTO messages (subject, message, sender) VALUES (?, ?, ?)")) {
		$stmt->bind_param("sss", $subject, $text, $_SESSION['uid']);
		$stmt->execute();
		$mid = $stmt->insert_id;
		$stmt->close();

		$receiver_id = null;
		if ($stmt = $con->prepare("INSERT INTO `read` (uid, mid, seen) VALUES (?, ?, 0)")) {
			$stmt->bind_param("ss", $receiver_id, $mid);
			
			foreach ($receivers as $receiver) {
				$receiver_id = get_uid($receiver);
				$stmt->execute();
			}

			$stmt->close();
		}
	}

}

// action -> set read state of message -------------------------------------------------------------
function set_read($mid) {
	$con = $GLOBALS['db'];
	if ($stmt = $con->prepare("UPDATE `read` SET seen=1 WHERE mid=?")) {
		$stmt->bind_param("s", $mid);
		$stmt->execute();
		$stmt->close();
	}
}

// action -> download a message ------------------------------------------------------------------------
function download_msg($from, $to, $mid) {
	$con = $GLOBALS['db'];

	$subject = null;
	$message = null;
	$timestamp = null;

	if ($stmt = $con->prepare("SELECT subject, message, timestamp FROM messages WHERE mid=?")) {
		$stmt->bind_param("s", $mid);
		$stmt->execute();
		$stmt->bind_result($subject, $message, $timestamp);
		$stmt->fetch();
		$stmt->close();
	}

	header("Content-type: text/plain");
	header("Content-Disposition: attachment; filename=$timestamp.txt");
	echo $from.' -> '.$to.PHP_EOL.PHP_EOL;
	echo $subject.PHP_EOL.PHP_EOL.PHP_EOL;
	echo $message;
}
// -------------------------------------------------------------------------------------------------


// --- V I E W S -----------------------------------------------------------------------------------
// view -> display message detail ------------------------------------------------------------------
function display_message_detail($mid, $from, $to) {
	$con = $GLOBALS['db'];

	$subject = null;
	$message = null;
	$timestamp = null;

	if ($stmt = $con->prepare("SELECT subject, message, timestamp FROM messages WHERE mid=?")) {
		$stmt->bind_param("s", $mid);
		$stmt->execute();
		$stmt->bind_result($subject, $message, $timestamp);
		$stmt->fetch();
		$stmt->close();

		$user = get_username($_SESSION['uid']);
		$post = '';
		
		if ($to == $user) {
			set_read($mid);
			$post = '✅';
		}

		echo "
		<a href='./index.php?do=11'>< back</a>
		<div class='top-space'>
			<code>🕑 $timestamp</code><br>
			<code>👥 $from ➡ $to</code><br><br>
			<h3>$subject</h3>
			<p>$message</p>
			<div class='top-space'>$post</div>
		</div>
		";
	}    
}

// view -> display message table -------------------------------------------------------------------
function display_message_table($mode) {
	$con = $GLOBALS['db'];

	$sql_all = 'SELECT em.mid, timestamp, subject, us.username, ue.username, em.seen
	FROM user ue JOIN `read` em JOIN messages m JOIN user us
	ON (ue.uid=em.uid)AND(em.mid=m.mid) AND (m.sender=us.uid)
	WHERE m.sender=? OR em.uid=?
	ORDER BY timestamp DESC';

	$sql_sender = 'SELECT em.mid, timestamp, subject, us.username, ue.username, em.seen
	FROM user ue JOIN `read` em JOIN messages m JOIN user us
	ON (ue.uid=em.uid)AND(em.mid=m.mid) AND (m.sender=us.uid)
	WHERE m.sender=? OR m.sender=?
	ORDER BY timestamp DESC';

	$sql_receiver = 'SELECT em.mid, timestamp, subject, us.username, ue.username, em.seen
	FROM user ue JOIN `read` em JOIN messages m JOIN user us
	ON (ue.uid=em.uid)AND(em.mid=m.mid) AND (m.sender=us.uid)
	WHERE em.uid=? OR em.uid=?
	ORDER BY timestamp DESC';

	$sql = '';
	switch ($mode) {
		case 'in':
			$sql = $sql_receiver;
			break;
		case 'out':
			$sql = $sql_sender;
			break;
		case 'all':
			$sql = $sql_all;
			break;
	}

	if ($stmt = $con->prepare($sql)) {
		$stmt->bind_param("ss", $_SESSION['uid'], $_SESSION['uid']);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows > 0) {
		
			echo '
			<table class="table table-striped top-space">
				<thead>
					<tr>
						<th></th>
						<th>datetime</th>
						<th>subject</th>
						<th>actor</th>
						<th></th>
						<th></th>
					</tr>
				</thead>
			<tbody>
			';

			while ($row = $result->fetch_array()) {
				echo '<tr>';
					if ($row[4] == get_username($_SESSION['uid'])) {
						$dir = "➡";
					} else {
						$dir = "⬅";
					}
					echo "<td>$dir</td>";

					for($s=1; $s <= 2;$s++) {
						$tabledata = $row[$s];
						echo "<td>$tabledata</td>";
					}

					$from = $row[3];
					$to = $row[4];
					echo "<td>$from ➡ $to</td>";

					$read_icon = get_read_icon($row[5]);
					echo "<td>$read_icon</td>"; 

					echo "<td>
						<a href='./index.php?do=11&detail=$row[0]&from=$from&to=$to'>
							🔎 
						</a>
						<a href='./index.php?do=11&download=$row[0]&from=$from&to=$to'>
							 ⬇
						</a>
					</td>";
				echo '</tr>';
			}

			echo '</tbody></table>';

			$stmt->close();
		}
		
	}
}

// view -> display chat table ----------------------------------------------------------------------
function display_chat($post_partner) {
	$con = $GLOBALS['db'];

	if (isset($_SESSION['partner']) && strlen($_SESSION['partner']) > 0) {
		$partner = $_SESSION['partner'];
	} else {
		$partner = get_uid($post_partner);
		$_SESSION['partner'] = $partner;
	}

	$partnername = get_username($partner);
	echo "<code>$partnername  </code>";

	echo '<a href="./index.php?do=13&a=reset">change chat partner</a>';

	echo '
	<div>
		<form action="./index.php?do=13" method="post" class="top-space">
			<input type="text" class="form-control" placeholder="subject" name="subject" required>
			<textarea class="form-control" rows="5" placeholder="message" name="text" required></textarea>
			<button type="submit" class="btn btn-primary">send</button>
		</form>
	</div>
	<div class="chat-box">';
	

	$sql = "SELECT username, timestamp, subject, message, e.mid, e.seen
	FROM `read` e
	JOIN messages m
	JOIN user u
	ON (e.mid=m.mid) AND (m.sender=u.uid)
	WHERE (m.sender=? AND e.uid=?) 
	OR (m.sender=? AND e.uid=?)
	ORDER BY timestamp DESC";

	if ($stmt = $con->prepare($sql)) {
		$stmt->bind_param("ssss", $partner, $_SESSION['uid'], $_SESSION['uid'], $partner);
		$stmt->execute();
		$result = $stmt->get_result();
		
		while ($row = $result->fetch_array()) {
			
			$read_icon = get_read_icon($row[5]);
			$read = $read_icon;
			
			$class = ' chat-sender';
			if (get_uid($row[0]) != $_SESSION['uid']) {
				$class = ' chat-partner';

				if ($row[5] == 'n') {
					$read = "<a href='./index.php?do=13&read=$row[4]'>$read_icon</a>";
				}
			}

			echo "
			<div class='chat-wrapper'>
				<div class='chat $class'>
					<div class='chat-header'>$row[2]</div>
					<div class='chat-body'>$row[3]</div>
					<code class='chat-timestamp'>$row[1]</code>
					$read
				</div>
			</div>";

		}
	}

	echo '</div>';
}

// -------------------------------------------------------------------------------------------------

?>
