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


?>
