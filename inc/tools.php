<?php

include('./inc/dbconfig.php');


function special_chars($string) {
    $search = array("Ä", "Ö", "Ü", "ä", "ö", "ü", "ß", "´");
    $replace = array("Ae", "Oe", "Ue", "ae", "oe", "ue", "ss", "");
    return str_replace($search, $replace, $string);
}

function generate_username($surname, $name) {
    return strtolower(substr(special_chars($surname), 0, 3) . substr(special_chars($name), 0, 3));
}

function print_table($sql) {
	$result = $GLOBALS['db']->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		echo '<table>';
		
		$fields = $result->fetch_fields();
		echo '<tr>';
			for($s=0; $s < $result->field_count;$s++) {
				$name = $fields[$s]->name;
				echo '<th><a href="ausgabe.php?order='.$name.'">'.$name.'<a/></th>';
			}
		echo'</tr>';
		
		
		while($row = $result->fetch_array()) {
			echo '<tr>';
				
				for($s=0; $s < $result->field_count;$s++) {
					echo '<td>'.$row[$s].'</td>';
				}
				
			echo'</tr>';
		}
		
		echo '
		</table>
		';
		
	} else {
		echo "0 results";
	}
}

function get_uid($username) {
	if ($stmt = $GLOBALS['db']->prepare("SELECT uid FROM is_user WHERE username=?")) {
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
	if ($stmt = $GLOBALS['db']->prepare("SELECT username FROM is_user WHERE uid=?")) {
		$stmt->bind_param("s", $uid);
		$stmt->execute();
		$stmt->bind_result($username);
		$stmt->fetch();
		$stmt->close();

		return $username;
	}
	return;
}

function get_read_icon($str) {
	if ($str == 'j') {
		$read = '✅';
	} else {
		$read = '🆕';
	}

	return $read;
}

// --- A C T I O N S -------------------------------------------------------------------------------

// action -> register  -----------------------------------------------------------------------------
function register($salutation, $name, $surname, $password) {
	$con = $GLOBALS['db'];
	$username = generate_username($surname, $name);

	// check if username already exists
	if (is_numeric(get_uid($username))) {
		echo "
		<div class='top-space'>
			<h3>Fehler!</h3>
			<p>Leider gibt es dieses Benutzerkonto bereits!</p>
			<a role='button' class='btn btn-light' href='index.php?do=2'>Registrieren</a>
		</div>
		";

		return;
	}

	$stmt = $con->prepare("INSERT INTO is_user (anrede, nachname, vorname, username, passwort) VALUES (?, ?, ?, ?, md5(?))");
	$stmt->bind_param("sssss", $salutation, $name, $surname, $username, $password);

	if ($stmt->execute()) {
		echo "
		<div class='top-space'>
			<h3>Glückwunsch</h3>
			<p>Du kannst dich nun anmelden um Nachrichten zu versenden.</p>
			<p>Dein Benutzername lautet: <code>$username</code></p>
			<a role='button' class='btn btn-light' href='index.php?do=1'>Anmelden</a>
		</div>
		";
	} else {
		echo "Error: " . $sql . "<br>" . $con->error;
	}
	
	echo '</div>';
}

// action -> login  --------------------------------------------------------------------------------
function login($username, $password) {
	$con = $GLOBALS['db'];
	// get user
	if ($stmt = $con->prepare("SELECT uid, passwort FROM is_user WHERE username=?")) {
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$stmt->bind_result($uid, $password_db);
		$stmt->fetch();
		$stmt->close();

		// hash input password
		if ($stmt = $con->prepare("SELECT md5(?)")) {
			$stmt->bind_param("s", $password);
			$stmt->execute();
			$stmt->bind_result($password_hash);
			$stmt->fetch();
			$stmt->close();

			// input password != database password
			if ($password_hash != $password_db) {
				echo '
				<div class="top-space">
					<h3>Fehler!</h3>
					<p>Du hast ungültige Anmeldedaten eingegeben!</p>
					<a role="button" class="btn btn-light" href="index.php?do=1">Anmelden</a>
				</div>
				';

				return;
			}

			// right password -> start session with uid
			$_SESSION["uid"] = $uid;

			echo '
			<div class="top-space">
				<h3>Let\'s go!</h3>
				<p>Du bist nun angemeldet und kannst Nachrichten verschicken.</p>
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
	if ($stmt = $con->prepare("INSERT INTO is_message (betreff, nachricht, senderuid) VALUES (?, ?, ?)")) {
		$stmt->bind_param("sss", $subject, $text, $_SESSION['uid']);
		$stmt->execute();
		$mid = $stmt->insert_id;
		$stmt->close();

		if ($stmt = $con->prepare("INSERT INTO is_empfang (uid, mid) VALUES (?, ?)")) {
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
	if ($stmt = $con->prepare("UPDATE is_empfang SET gelesen='j' WHERE mid=?")) {
		$stmt->bind_param("s", $mid);
		$stmt->execute();
		$stmt->close();
	}
}

// action -> send a message ------------------------------------------------------------------------
function download_message($mid, $from, $to) {
	header("Location: ./inc/download.php?mid=$mid&f=$from&t=$to");
}
// -------------------------------------------------------------------------------------------------


// --- V I E W S -----------------------------------------------------------------------------------
// view -> display message detail ------------------------------------------------------------------
function display_message_detail($mid, $from, $to) {
	$con = $GLOBALS['db'];
	// get message
	if ($stmt = $con->prepare("SELECT betreff, nachricht, datumzeit FROM is_message WHERE mid=?")) {
		$stmt->bind_param("s", $mid);
		$stmt->execute();
		$stmt->bind_result($subject, $message, $timestamp);
		$stmt->fetch();
		$stmt->close();

		$user = get_username($_SESSION['uid']);
		$post = '';
		
		// if inbox -- set read & display
		if ($to == $user) {	
			set_read($mid);
			$post = '✅';
		}

		// print message
		echo "
		<a href='./index.php?do=11'>< zurück</a>
		<div class='top-space'>
			<code>🕑 $timestamp</code><br>
			<code>👥 $from</code> ➡ <code>$to</code><br><br>
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

	$sql_all = 'SELECT em.mid, datumzeit, betreff, us.username, ue.username, em.gelesen
	FROM is_user ue JOIN is_empfang em JOIN is_message m JOIN is_user us
	ON (ue.uid=em.uid)AND(em.mid=m.mid) AND (m.senderuid=us.uid)
	WHERE m.senderuid=? OR em.uid=?
	ORDER BY datumzeit DESC';

	$sql_sender = 'SELECT em.mid, datumzeit, betreff, us.username, ue.username, em.gelesen
	FROM is_user ue JOIN is_empfang em JOIN is_message m JOIN is_user us
	ON (ue.uid=em.uid)AND(em.mid=m.mid) AND (m.senderuid=us.uid)
	WHERE m.senderuid=? OR m.senderuid=?
	ORDER BY datumzeit DESC';

	$sql_receiver = 'SELECT em.mid, datumzeit, betreff, us.username, ue.username, em.gelesen
	FROM is_user ue JOIN is_empfang em JOIN is_message m JOIN is_user us
	ON (ue.uid=em.uid)AND(em.mid=m.mid) AND (m.senderuid=us.uid)
	WHERE em.uid=? OR em.uid=?
	ORDER BY datumzeit DESC';

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
		
			// table header
			echo '
			<table class="table table-striped top-space">
				<thead>
					<tr>
						<th></th>
						<th>Zeitpunkt</th>
						<th>Betreff</th>
						<th>Akteur</th>
						<th></th>
						<th></th>
					</tr>
				</thead>
			<tbody>
			';

			// table body
			while ($row = $result->fetch_array()) {
				echo '<tr>';
					// send direction
					if ($row[4] == get_username($_SESSION['uid'])) { 
						$dir = "➡";
					} else {
						$dir = "⬅";
					}
					echo "<td>$dir</td>";

					// timestamp & subject ---
					for($s=1; $s <= 2;$s++) {
						$tabledata = $row[$s];
						echo "<td>$tabledata</td>";
					}

					// users -----------------
					$from = $row[3];
					$to = $row[4];
					echo "<td>$from ➡ $to</td>"; 

					// read state ------------
					$read_icon = get_read_icon($row[5]);
					echo "<td>$read_icon</td>"; 

					// actions ---------------
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
function display_chat($post_patner) {
	$con = $GLOBALS['db'];

	if (isset($_SESSION['patner']) && strlen($_SESSION['patner']) > 0) {
		$patner = $_SESSION['patner'];
	} else {
		$patner = get_uid($post_patner);
		$_SESSION['patner'] = $patner;
	}

	$patnername = get_username($patner);
	echo "<code>$patnername  </code>";

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
	

	$sql = "SELECT username, datumzeit, betreff, nachricht, e.mid, e.gelesen
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
			
			$read_icon = get_read_icon($row[5]);
			$read = $read_icon;
			
			$class = ' chat-sender';
			if (get_uid($row[0]) != $_SESSION['uid']) {
				// align message right
				$class = ' chat-patner';

				// if not sender & unread -> able to mark as read
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
