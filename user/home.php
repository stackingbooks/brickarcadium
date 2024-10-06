<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/user.php';
	
	$msg = "";
	
	if(!empty($_POST["status"])) {
		if(strlen($_POST["status"]) > 100) {
			$msg = "<p style='color: red;float:left !important;'>Status is too long!</p>";
		} else {
			$stmt = $conn->prepare("UPDATE users SET STATUS=? WHERE ID=?");
			$stmt->bindParam(1, $_POST["status"], PDO::PARAM_STR);
			$stmt->bindParam(2, $user[0], PDO::PARAM_STR);
			$stmt->execute();
			$msg = "<p style='color: green;float:left !important;'>Successfully set status!</p>";
		}
	}
?>
<html>
	<?php require $docr . '/misc/htmls/head.html'; ?>
	<body>
		<?php require $docr . '/misc/navbar.php'; ?>
		<br><br>
		<center>
		<div class="welcomepfp">
			<center>
				<br>
				<h2>Welcome, <?php echo $user[1]; ?></h2>
				<br>
				<img src="<?php echo $user["HEADSHOTIMG"]; ?>" class="headshot">
			</center>
		</div>
		<div class="platform2" style="vertical-align:top;width:65% !important;">
			<form action="" method="POST">
				<input type="text" name="status" id="status" placeholder="I'm currently..." style="width:80%;display:inline-block;" maxlength="100">
				<button type="submit" style="width:30px;height:30px;display:inline-block;padding:0;"><i class='fad fa-comments'></i></button>
			</form>
			<?php echo $msg; ?>
			<script>
				var texts = [
					"I'm currently...",
					"Right now I'm...",
					"I like...",
					"I'm feeling...",
					"My favourite game is..."
				];
				var text = texts[Math.floor(Math.random()*texts.length)];
				document.getElementById("status").placeholder = text;
			</script>
		</div>
		<br>
		</center>
		</div>
	</body>
</html>