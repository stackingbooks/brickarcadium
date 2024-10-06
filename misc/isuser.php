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
			$isuser = $stmt->fetch();
			if($isuser) {
				$user = $isuser;
				if($user["BANNED"] == 'Y') {
					include($_SERVER["DOCUMENT_ROOT"].'/banned.php');
					die();
				}
			}
		}
	}
?>