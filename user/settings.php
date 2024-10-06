<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/user.php';
	
	$msg = "";
	
	if(!empty($_POST["bio"])) {
		if(strlen($_POST["bio"]) > 1500) {
			$msg = "<p style='color: red;float:left !important;'>Bio is too long!</p><br>";
		} else {
			$hebio = $_POST["bio"];
			$finalbio = str_replace("<br>", "\n", $hebio);
			$stmt = $conn->prepare("UPDATE users SET BIO=? WHERE ID=?");
			$stmt->bindParam(1, $finalbio, PDO::PARAM_STR);
			$stmt->bindParam(2, $user[0], PDO::PARAM_STR);
			$stmt->execute();
			$msg = "<p style='color: green;float:left !important;'>Successfully set bio!</p><br>";
		}
	}
?>
<html>
	<?php require $docr . '/misc/htmls/head.html'; ?>
	<body>
		<?php require $docr . '/misc/navbar.php'; ?>
			<br><br><br><br>
			<div class="platform2 center">
				<form action="" method='POST'>
					<label for="bio">Bio</label><br>
					<textarea type="text" id="bio" name="bio" maxlength="1500" style="width:500px;height:200px;"><?php echo str_replace("<br>", "\n", htmlentities($user["BIO"])); ?></textarea><br><br>
					<?php echo $msg; ?>
					<input class="btn" type="submit" value="Set Bio">
				</form> 
			</div>
		</div>
	</body>
</html>