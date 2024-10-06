<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/isuser.php';
	
	if(empty($_GET["item"])) {
		header("Location: /");
		die();
	} else {
		$id = $_GET["item"];
	}
	
	$msg = "";
	
	$stmt = $conn->prepare("SELECT * FROM items WHERE ID=?");
	$stmt->bindParam(1, $id, PDO::PARAM_STR);
	$stmt->execute();
	$item = $stmt->fetch();
	if(!$item) {
		http_response_code(404);
		include($docr. '/errors/404.php');
		die();
	}
	
	if (!empty($user)) {
		$stmt = $conn->prepare("SELECT * FROM inventory WHERE USER_ID=? AND ITEM_ID=?");
		$stmt->bindParam(1, $user[0], PDO::PARAM_INT);
		$stmt->bindParam(2, $item[0], PDO::PARAM_INT);
		$stmt->execute();
		$isown = $stmt->fetch();
		if (!empty($_POST["bux"]) && empty($_POST["coins"])) {
			if($isown) {
				$msg = "<div class='redmsg'>You already own " . $item[1] . "!</div><br>";
			} else {
				if($user["BUX"] > $item["BUX"] or $user["BUX"] == $item["BUX"]) {
					$newb = $user["BUX"] - $item["BUX"];
					$stmt = $conn->prepare("UPDATE users SET BUX=? WHERE ID=?");
					$stmt->bindParam(1, $newb, PDO::PARAM_STR);
					$stmt->bindParam(2, $user[0], PDO::PARAM_STR);
					$stmt->execute();
					$newsa = $item["SALES"] + 1;
					$stmt1 = $conn->prepare("UPDATE items SET SALES=? WHERE ID=?");
					$stmt1->bindParam(1, $newsa, PDO::PARAM_STR);
					$stmt1->bindParam(2, $item[0], PDO::PARAM_STR);
					$stmt1->execute();
					$stmt2 = $conn->prepare("INSERT INTO inventory VALUES(NULL, ?, ?, ?)");
					$stmt2->bindParam(1, $user[0], PDO::PARAM_STR);
					$stmt2->bindParam(2, $item[0], PDO::PARAM_STR);
					$stmt2->bindParam(3, $newsa, PDO::PARAM_STR);
					$stmt2->execute();
					$msg = "<div class='greenmsg'>Successfully bought " . $item[1] . "!</div><br>";
				} else {
					$msg = "<div class='redmsg'>Not enough Bux!</div><br>";
				}
			}
		} elseif (!empty($_POST["coins"]) && empty($_POST["bux"])) {
			if($isown) {
				$msg = "<div class='redmsg'>You already own " . $item[1] . "!</div><br>";
			} else {
				if($user["COINS"] > $item["COINS"] or $user["COINS"] == $item["COINS"]) {
					$newc = $user["COINS"] - $item["COINS"];
					$stmt = $conn->prepare("UPDATE users SET COINS=? WHERE ID=?");
					$stmt->bindParam(1, $newc, PDO::PARAM_STR);
					$stmt->bindParam(2, $user["ID"], PDO::PARAM_STR);
					$stmt->execute();
					$newsa = $item["SALES"] + 1;
					$stmt1 = $conn->prepare("UPDATE items SET SALES=? WHERE ID=?");
					$stmt1->bindParam(1, $newsa, PDO::PARAM_STR);
					$stmt1->bindParam(2, $item[0], PDO::PARAM_STR);
					$stmt1->execute();
					$stmt2 = $conn->prepare("INSERT INTO inventory VALUES(NULL, ?, ?, ?)");
					$stmt2->bindParam(1, $user[0], PDO::PARAM_STR);
					$stmt2->bindParam(2, $item[0], PDO::PARAM_STR);
					$stmt2->bindParam(3, $newsa, PDO::PARAM_STR);
					$stmt2->execute();
					$msg = "<div class='greenmsg'>Successfully bought " . $item[1] . "!</div><br>";
				} else {
					$msg = "<div class='redmsg'>Not enough Coins!</div><br>";
				}
			}
		} elseif (!empty($_POST["free"])) {
			if ($item["COINS"] == 0 && $item["BUX"] == 0) {
				if($isown) {
					$msg = "<div class='redmsg'>You already own " . $item[1] . "!</div><br>";
				} else {
					$newsa = $item["SALES"] + 1;
					$stmt1 = $conn->prepare("UPDATE items SET SALES=? WHERE ID=?");
					$stmt1->bindParam(1, $newsa, PDO::PARAM_STR);
					$stmt1->bindParam(2, $item[0], PDO::PARAM_STR);
					$stmt1->execute();
					$stmt2 = $conn->prepare("INSERT INTO inventory VALUES(NULL, ?, ?, ?)");
					$stmt2->bindParam(1, $user[0], PDO::PARAM_STR);
					$stmt2->bindParam(2, $item[0], PDO::PARAM_STR);
					$stmt2->bindParam(3, $newsa, PDO::PARAM_STR);
					$stmt2->execute();
					$msg = "<div class='greenmsg'>Successfully bought " . $item[1] . "!</div><br>";
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
		<br><br>
		<center>
		<div style="width:85%;">
		<?php echo '<h1 style="float: left !important;">' . $item["NAME"] . '</h1><br><br><br>'; ?>
		<?php echo $msg; ?>
		<div style="display:inline-block;">
			<label>Preview</label><br>
			<?php
				if(!empty($user)) {
					if(!empty($_GET["hatrender"]) && $item['CATEGORY'] == 'HAT' or !empty($_GET["hatrender"]) && $item['CATEGORY'] == 'HAT_2') {
						if ($user["RANK"] !== 'USER' && $user["RANK"] !== 'MOD') {
							echo '<iframe src="/misc/rendering/hatRender?hatid='. $id . '" class="avatarimg" style="height:320px;overflow-x:hidden;overflow-y:hidden;display:inline-block;" class="avatarimg" scrolling="no"></iframe>';
						} else {
							echo '<img src="' . $item["PREVIEWIMG"] . '" class="avatarimg" style="height:320px;">';
						}
					} else {
						if(!empty($_GET["facerender"]) && $item['CATEGORY'] == 'FACE') {
							if ($user["RANK"] !== 'USER' && $user["RANK"] !== 'MOD') {
								echo '<iframe src="/misc/rendering/faceRender?hatid='. $id . '" class="avatarimg" style="height:320px;overflow-x:hidden;overflow-y:hidden;display:inline-block;" class="avatarimg" scrolling="no"></iframe>';
							} else {
								echo '<img src="' . $item["PREVIEWIMG"] . '" class="avatarimg" style="height:320px;">';
							}
						} else {
							echo '<img src="' . $item["PREVIEWIMG"] . '" class="avatarimg" style="height:320px;">';
						}
					}
				} else {
					echo '<img src="' . $item["PREVIEWIMG"] . '" class="avatarimg" style="height:320px;">';
				}
				
			?>
		</div>
		<div style="display:inline-block;vertical-align:top;">
			<label>Information</label><br>
			<div class="platform2" style="width:700px;height:320px;padding:0;text-align: left;padding-left:10px;padding-right:10px;">
				<br>
				<?php echo '<i>"' . $item["DESCRIPTION"] . '"</i>'; ?>
				<br>
				<br>
				<b>Sales: </b><?php echo $item["SALES"]; ?>
				<br>
				<?php echo '<b>Created: </b> ' .  gmdate("d M Y", $item["UPLOADED"]); ?>
				<br>
				<br>
				<?php	
					if ($item['BUX'] !== 0) {
						echo '<div style="color: green; display: inline-block;"><i class="far fa-money-bill-wave"></i> ' . $item['BUX'] . '&nbsp;</div>';
					}
					if ($item['COINS'] !== 0) {
						echo '<div style="color: orange;  display: inline-block;"><i class="far fa-coins"></i> ' . $item['COINS'] . '&nbsp;</div> ';
					}
					if ($item['BUX'] == 0 && $item['COINS'] == 0) {
						echo '<div style="color: green;  display: inline-block;">FREE</div>';
					}
					
					if (!empty($user)) {
						echo "<div>";
						if ($item['BUX'] !== 0) {
							echo '<form action="" method="POST" style="display:inline-block;">
								<button type="submit" class="btn bux" style="display:inline-block;">Buy with Bux</button>
								<input type="hidden" id="bux" name="bux" value="bux" style="display:none;">
							</form>&nbsp;';
						};
						if ($item['COINS'] !== 0) {
							echo '<form action="" method="POST" style="display:inline-block;">
								<button type="submit" class="btn coins">Buy with Coins</button>
								<input type="hidden" id="coins" name="coins" value="coins" style="display:none;">
							</form>';
						};
						if ($item['BUX'] == 0 && $item['COINS'] == 0) {
							echo '<form action="" method="POST" style="display:inline-block;">
								<button type="submit" class="btn">Get it</button>
								<input type="hidden" id="free" name="free" value="free" style="display:none;">
							</form>';
						}
						echo "</div>";
						if ($user["RANK"] !== 'USER' && $user["RANK"] !== 'MOD' && $item['CATEGORY'] == 'HAT' or $user["RANK"] !== 'USER' && $user["RANK"] !== 'MOD' && $item['CATEGORY'] == 'HAT_2') {
							echo "<br><br><b><a href='?hatrender=1' style='color:gray;'>Rerender</a></b>";
						}
						if ($user["RANK"] !== 'USER' && $user["RANK"] !== 'MOD' && $item['CATEGORY'] == 'FACE') {
							echo "<br><br><b><a href='?facerender=1' style='color:gray;'>Rerender</a></b>";
						}
					}
				
				?>
			</div>
		</div>
		</div>
		</center>
		</div>
	</body>
</html>