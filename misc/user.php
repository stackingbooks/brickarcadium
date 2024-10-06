<?php
	if (!empty($_COOKIE['TOKEN--DONOTSHARE'])) {
		$stmt = $conn->prepare("SELECT USER_ID FROM tokens WHERE TOKEN=?");
		$stmt->bindParam(1, $_COOKIE['TOKEN--DONOTSHARE'], PDO::PARAM_STR);
		$stmt->execute();
		$uid = $stmt->fetchColumn();
		if($uid) {
			$stmt = $conn->prepare("SELECT * FROM users WHERE ID=?");
			$stmt->bindParam(1, $uid, PDO::PARAM_STR);
			$stmt->execute();
			$user = $stmt->fetch();
			if(!$user) {
				header("Location: /");
				die();
			} else {
				if($user["BANNED"] == 'Y' && basename(__FILE__) !== 'logout.php') {
					include($_SERVER["DOCUMENT_ROOT"].'/banned.php');
					die();
				}
			}
		} else {
			header("Location: /");
			die();
		}
	} else {
		header("Location: /");
		die();
	}
?>