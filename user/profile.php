<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/isuser.php';
	
	if(empty($_GET["id"])) {
		header("Location: /");
		die();
	} else {
		$id = $_GET["id"];
	}
	
	$stmt = $conn->prepare("SELECT * FROM users WHERE ID=?");
	$stmt->bindParam(1, $id, PDO::PARAM_STR);
	$stmt->execute();
	$userp = $stmt->fetch();
	if(!$userp) {
		http_response_code(404);
		include($docr. '/errors/404.php');
		die();
	} else {
		if (!empty($user)) {
			$stmt = $conn->prepare("SELECT PROFILE_VIEWS FROM users WHERE ID=?");
			$stmt->bindParam(1, $id, PDO::PARAM_STR);
			$stmt->execute();
			$pv = $stmt->fetchColumn();
			$newpv = $pv + 1;
			$stmt = $conn->prepare("UPDATE users SET PROFILE_VIEWS=? WHERE ID=?");
			$stmt->bindParam(1, $newpv, PDO::PARAM_STR);
			$stmt->bindParam(2, $id, PDO::PARAM_STR);
			$stmt->execute();
			if ($user['RANK'] !== 'USER' && $userp['RANK'] == 'USER') {
				if (!empty($_POST['revokeu'])) {
					$revokeuname = '[ Username Revoked ' . $id . ' ]';
					$stmt = $conn->prepare("UPDATE users SET USERNAME=? WHERE ID=?");
					$stmt->bindParam(1, $revokeuname, PDO::PARAM_STR);
					$stmt->bindParam(2, $id, PDO::PARAM_STR);
					$stmt->execute();
				}
			}
		}
	}
?>
<html>
	<?php require $docr . '/misc/htmls/head.html'; ?>
	<body>
		<?php require $docr . '/misc/navbar.php'; ?>
		<br>
		<?php
			if ($userp["BANNED"] == 'Y') {
				echo "<center><div class='redmsg'>This user is banned!</div></center><br>";
			}
		?>
		<div style="display: flex;align-items: center;" class="platform3">
		<img src="<?php echo $userp["HEADSHOTIMG"]; ?>" class="headshot">&nbsp; &nbsp; 
		<?php
			$time = time () - 60;
			if ($userp["LAST_ONLINE"] > $time) {
				echo "<i class='fas fa-circle' style='color:green;display:inline-block;'></i>";
			} else {
				echo "<i class='fas fa-circle' style='color:gray;display:inline-block;'></i>";
			}
		?>
		<div>
		<h2 <?php if (!empty($userp["STATUS"])) {
				echo 'style="margin-bottom: 8px;';
				if ($userp["RANK"] == "ADMIN") {
					echo 'color: red;';
				}
				if ($userp["RANK"] == "MOD") {
					echo 'color: blue;';
				}
				echo '"';
			} elseif ($userp["RANK"] == "ADMIN") {
				echo 'style="color: red;"';
			} elseif ($userp["RANK"] == "MOD") {
				echo 'style="color: blue;"';
			}; ?>>&nbsp; <?php if ($userp["RANK"] !== "USER") { echo '<i class="fas fa-gavel"></i> '; }; echo $userp[1]; ?></h2>
		<?php if (!empty($userp["STATUS"])) { echo '&nbsp;<i style="color:gray;">&nbsp;"' . htmlentities($userp["STATUS"]) . '"</i>'; }; ?>
		<p>&nbsp; &nbsp; <b><?php if (!empty($user)) {echo $userp["PROFILE_VIEWS"] + 1;} else {echo $userp["PROFILE_VIEWS"];}; ?></b> Profile Views</p>
		<?php if(!empty($user)) { if ($user["RANK"] !== 'USER' && $userp['RANK'] == 'USER') { echo "<form action='' method='POST'>&nbsp; &nbsp; <input type='hidden' name='revokeu' id='revokeu' value='revokeu'><button type='submit' style='background-color: unset;color:black;padding:0;'>Revoke Username</button></form>"; }; }; ?>
		</div>
		</div>
		<br><br>
		<center>
		<div style="height:350px;">
		<div style="display:inline-block;">
			<label>Avatar</label><br>
			<img src="<?php echo $userp["AVATARIMG"]; ?>" class="avatarimg" style="height:320px;">
		</div><div style="display:inline-block;vertical-align:top;">
			<label>Information</label><br>
			<div class="platform2" style="width:800px;height:320px;padding:0;">
				<br>
				<div style="overflow: auto; width: 100%; height: 100px;"><?php echo '<i>"' . str_replace("\n", "<br>", htmlentities(str_replace("<br>", "\n", $userp["BIO"]))) . '"</i>'; ?></div><br><br>
				<?php echo '<b>Joined Brickarcadium at</b> ' .  gmdate("d M Y", $userp["JOIN_DATE"]); ?>
				<br>
				<?php if(!empty($user)) { if ($user["RANK"] !== 'USER' && $userp['RANK'] == 'USER') { echo "<a href='/staff/ban/" . $userp[0] . "'><button style='background-color: unset;color:black;padding:0;'>Ban User</button></a>"; }; }; ?>
			</div>
		</div>
		</div>
		</center>
		</div>
	</body>
</html>