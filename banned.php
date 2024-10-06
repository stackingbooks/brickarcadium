<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/idfk.php';
	
	$stmt = $conn->prepare("SELECT COUNT(*) FROM bans WHERE USER_ID=? AND ACTIVE='Y'");
	$stmt->bindParam(1, $user[0], PDO::PARAM_STR);
	$stmt->execute();					
	$lul = $stmt->fetchColumn();
	$stmt = $conn->prepare("SELECT * FROM bans WHERE USER_ID=? AND ACTIVE='Y'");
	$stmt->bindParam(1, $user[0], PDO::PARAM_STR);
	$stmt->execute();			
	$ban = $stmt->fetch();
	if ($lul > 0) {
		if (time() > $ban['DURATION']) {
			if (!empty($_POST['yeeeee'])) {
				$stmt = $conn->prepare("UPDATE bans SET ACTIVE='N' WHERE USER_ID=? AND ACTIVE='Y'");
				$stmt->bindParam(1, $user[0], PDO::PARAM_STR);
				$stmt->execute();
				$stmt = $conn->prepare("UPDATE users SET BANNED='N' WHERE ID=?");
				$stmt->bindParam(1, $user[0], PDO::PARAM_STR);
				$stmt->execute();
				header('Location: /');
				die();
			}
		}
	} else {
		http_response_code(403);
		include($docr. '/errors/403.php');
		die();
	}
?>
<html>
	<?php require $docr . '/misc/htmls/head.html'; ?>
	<body>
		<?php require $docr . '/misc/navbar.php'; ?>
		<br>
		<br>
		<br>
		<p>How did you manage to get banned in beta testing lmfao</p><br><br>
		<b>Reason:</b> <?php echo $ban['REASON']; ?><br>
		<b>Banned at:</b> <?php echo gmdate("d/m/Y H:i:s", $ban["BANNED_AT"]); ?><br>
		<b>Duration:</b> <?php echo gmdate("d/m/Y H:i:s", $ban["DURATION"]); ?><br>
		<?php
			if (time() > $ban['DURATION']) {
				echo "<form action='' method='POST'><input type='hidden' name='yeeeee' id='yeeeee' value='yeeeee'><button type='submit' style='background-color: unset;color:black;padding:0;'>Reactivate account</button></form>";
			}
		?>
		</div>
	</body>
</html>