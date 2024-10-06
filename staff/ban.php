<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/user.php';
	
	if($user["RANK"] == "USER") {
		http_response_code(403);
		include($docr. '/errors/403.php');
		die();
	} else {
		if(empty($_GET["id"])) {
			header("Location: /");
			die();
		} else {
			$id = $_GET["id"];
		}
		$stmt = $conn->prepare("SELECT * FROM users WHERE ID=?");
		$stmt->bindParam(1, $id, PDO::PARAM_STR);
		$stmt->execute();
		$usr = $stmt->fetch();
		if(!$usr) {
			http_response_code(404);
			include($docr. '/errors/404.php');
			die();
		} else {
			if($usr["RANK"] !== "USER") {
				http_response_code(403);
				include($docr. '/errors/403.php');
				die();
			}
			if($usr["BANNED"] == 'Y') {
				http_response_code(403);
				include($docr. '/errors/403.php');
				die();
			}
		}
	}
?>
<html>
	<?php require $docr . '/misc/htmls/head.html'; ?>
	<body>
		<?php require $docr . '/misc/navbar.php'; ?>
		<br>
		<br><br>
		<center>
		<div style="width:85%;">
		<h1 style="float: left !important;">Ban</h1><br><br><br>
		<div style="display:inline-block;vertical-align:top;">
			<label style="lol2">Panel</label><br>
			<div class="platform2" style="width:900px;height:400px;padding:0;text-align: left;padding-left:10px;padding-right:10px;">
				<br>
				<p>Banning <?php echo $usr[1] ?></p>
				<br>
				<form action='' method='POST'>
					<label for='reason'>Reason</label><br>
					<input type='text' id='reason' name='reason' maxchar='200'></input><br>
					<label for="length">Length</label><br>

					<select name="length" id="length">
						<option value="-1">Warning</option>
						<option value="3600">1 hour</option>
						<option value="43200">12 hours</option>
						<option value="86400">1 day</option>
						<option value="259200">3 days</option>
						<option value="604800">1 week</option>
						<option value="1209600">2 weeks</option>
						<option value="2629800">1 month</option>
						<option value="2629800000">Terminate</option>
					</select> <br><br>
					<button type='submit'>Ban</button>
					<?php
						if(!empty($_POST['reason']) && !empty($_POST['length'])) {
							
							$time = time();
							$duration = $time + intval($_POST['length']);
							
							$stmt = $conn->prepare("INSERT INTO bans VALUES (NULL, ?, ?, ?, ?, 'Y')");
							$stmt->bindParam(1, $id, PDO::PARAM_STR);
							$stmt->bindParam(2, $time, PDO::PARAM_STR);
							$stmt->bindParam(3, $duration, PDO::PARAM_STR);
							$stmt->bindParam(4, $_POST['reason'], PDO::PARAM_STR);
							$stmt->execute();
							
							$stmt = $conn->prepare("UPDATE users SET BANNED='Y' WHERE ID=?");
							$stmt->bindParam(1, $id, PDO::PARAM_STR);
							$stmt->execute();
							
							header('Location: /user/profile/'.$id);
							die();
						};
					?>
				</form>
			</div>
		</div>
		</div>
		</center>
		</div>
	</body>
</html>