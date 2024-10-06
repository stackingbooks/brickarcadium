<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/user.php';
	
	$category = "HAT";
	if (!empty($_POST["category"])) {
		if($_POST["category"] == "HAT" or $_POST["category"] == "FACE" or $_POST["category"] == "HAT_2") {
			$category = $_POST["category"];
		}
	}
	
	if (!empty($_POST["itemw"])) {
		if(intval($_POST["itemw"])) {
			$stmt = $conn->prepare("SELECT * FROM inventory WHERE USER_ID=? AND ITEM_ID=?;");
			$stmt->bindParam(1, $user[0], PDO::PARAM_INT);
			$stmt->bindParam(2, $_POST["itemw"], PDO::PARAM_INT);
			$stmt->execute();
			$isown = $stmt->fetch();
			if($isown) {
				$stmt = $conn->prepare("SELECT * FROM items WHERE ID=?;");
				$stmt->bindParam(1, $_POST["itemw"], PDO::PARAM_INT);
				$stmt->execute();
				$itemw = $stmt->fetch();
				if ($itemw["CATEGORY"] == "HAT") {
					$stmt = $conn->prepare("UPDATE avatars SET HAT=? WHERE USER_ID=?;");
					$stmt->bindParam(1, $itemw[0], PDO::PARAM_INT);
					$stmt->bindParam(2, $user[0], PDO::PARAM_INT);
					$stmt->execute();
					echo "<script>window.addEventListener('load', function () {
						set();
					})</script>";
				} elseif ($itemw["CATEGORY"] == "FACE") {
					$stmt = $conn->prepare("UPDATE avatars SET FACE=? WHERE USER_ID=?;");
					$stmt->bindParam(1, $itemw[0], PDO::PARAM_INT);
					$stmt->bindParam(2, $user[0], PDO::PARAM_INT);
					$stmt->execute();
					echo "<script>window.addEventListener('load', function () {
						set();
					})</script>";
				} elseif ($itemw["CATEGORY"] == "HAT_2") {
					$stmt = $conn->prepare("UPDATE avatars SET HAT_2=? WHERE USER_ID=?;");
					$stmt->bindParam(1, $itemw[0], PDO::PARAM_INT);
					$stmt->bindParam(2, $user[0], PDO::PARAM_INT);
					$stmt->execute();
					echo "<script>window.addEventListener('load', function () {
						set();
					})</script>";
				}
			}
		}
	}
	
	if (!empty($_POST["catr"])) {
		if ($_POST["catr"] == "HAT") {
			$stmt = $conn->prepare("UPDATE avatars SET HAT=0 WHERE USER_ID=?;");
			$stmt->bindParam(1, $user[0], PDO::PARAM_INT);
			$stmt->execute();
			echo "<script>window.addEventListener('load', function () {
				set();
			})</script>";
		} elseif ($_POST["catr"] == "FACE") {
			$stmt = $conn->prepare("UPDATE avatars SET FACE=0 WHERE USER_ID=?;");
			$stmt->bindParam(1, $user[0], PDO::PARAM_INT);
			$stmt->execute();
			echo "<script>window.addEventListener('load', function () {
				set();
			})</script>";
		}  elseif ($_POST["catr"] == "HAT_2") {
			$stmt = $conn->prepare("UPDATE avatars SET HAT_2=0 WHERE USER_ID=?;");
			$stmt->bindParam(1, $user[0], PDO::PARAM_INT);
			$stmt->execute();
			echo "<script>window.addEventListener('load', function () {
				set();
			})</script>";
		}
	}
	
	$stmt = $conn->prepare("SELECT * FROM avatars WHERE USER_ID=?");
	$stmt->bindParam(1, $user[0], PDO::PARAM_INT);
	$stmt->execute();
	$avatar = $stmt->fetch();

?>
<html>
	<?php require $docr . '/misc/htmls/head.html'; ?>
	<body>
		<?php require $docr . '/misc/navbar.php'; ?>
		<script>
			var cd = false;
			async function set() {
				if (cd == false) {
					cd = true;
					document.getElementById("updatebtn").style = "background-color: gray !important;";
					var link = "set?head=" + document.getElementById("head").value.replace("#", "") + "&torso=" + document.getElementById("torso").value.replace("#", "") + "&larm=" + document.getElementById("larm").value.replace("#", "") + "&rarm=" + document.getElementById("rarm").value.replace("#", "") + "&lleg=" + document.getElementById("lleg").value.replace("#", "") + "&rleg=" + document.getElementById("rleg").value.replace("#", "");
					document.getElementById("avatar").src = link;
					await new Promise(r => setTimeout(r, 7000));
					document.getElementById("updatebtn").style = "";
					cd = false;
				}
			}
		</script>
		<br><br>
		<label>Avatar</label><br><iframe src="set" class="avatarimg" style="height:333px;width:313px;overflow-x:hidden;overflow-y:hidden;display:inline-block;" scrolling="no" id="avatar"></iframe>
		<div class='platform2' style="display:inline-block;vertical-align: top;height:800px;">
			<div style="display:inline-block;">
				<label for="head">Head</label><br>
				<input type="color" id="head" name="head">
			</div>
			<div style="display:inline-block;">
				<label for="head">Torso</label><br>
				<input type="color" id="torso" name="torso">
			</div>
			<div style="display:inline-block;">
				<label for="head">Left Leg</label><br>
				<input type="color" id="lleg" name="lleg">
			</div>
			<div style="display:inline-block;">
				<label for="head">Right Leg</label><br>
				<input type="color" id="rleg" name="rleg">
			</div>
			<div style="display:inline-block;">
				<label for="head">Left Arm</label><br>
				<input type="color" id="larm" name="larm">
			</div>
			<div style="display:inline-block;">
				<label for="head">Right Arm</label><br>
				<input type="color" id="rarm" name="rarm">
			</div><br><br>
			<input class="btn" id="updatebtn" type="submit" value="Set Body Colors" onclick="set()"><br><br>
			<label>Inventory</label>
			<br>
			<form action="" method='POST' style="display: inline-block"><input type="hidden" name="category" value="HAT"><input class="btn" type="submit" value="Hat"></form> <form action="" method='POST' style="display: inline-block"><input type="hidden" name="category" value="HAT_2"><input class="btn" type="submit" value="Second Hat"></form> <form action="" method='POST' style="display: inline-block"><input type="hidden" name="category" value="FACE"><input class="btn" type="submit" value="Face"></form>
			<br><br>
			<?php
				$stmt = $conn->prepare("SELECT * FROM inventory WHERE USER_ID=?;");
				$stmt->bindParam(1, $user[0], PDO::PARAM_INT);
				$stmt->execute();
				$count = 0;
				while ($row = $stmt->fetch()) {
					$stmt2 = $conn->prepare("SELECT * FROM items WHERE ID=? AND CATEGORY=?;");
					$stmt2->bindParam(1, $row[2], PDO::PARAM_INT);
					$stmt2->bindParam(2, $category, PDO::PARAM_STR);
					$stmt2->execute();
					while ($row2 = $stmt2->fetch()) {
						$count = $count + 1;
						echo "<div class='user' style='width:140px!important;'> <center><a href='/store/item/". $row2[0] ."'><img src='" . $row2["PREVIEWIMG"] . "' style='width:auto;height:120px;'><div style='height:20px !important;' class='idk'><p style='display:inline-block;'>" . $row2[1] . "</p></div></a>" . '<form action="" method="POST" style="display: inline-block"><input type="hidden" name="itemw" value="' . $row2[0]. '"><input type="hidden" name="category" value="' . $category. '"><br><input class="btn" style="padding:5px;background-color:lime;border:1px solid darkgreen;" type="submit" value="Wear"></form>' . "</center></div>";
					}
				}
				if ($count == 0) {
					echo "<br><p style='color: gray;'>No items :(</p>";
				}
			?>
			<br><br>
			<label>Wearing</label>
			<br>
			<?php
				if (!empty($avatar["HAT"])) {
					$stmt = $conn->prepare("SELECT * FROM items WHERE ID=?;");
					$stmt->bindParam(1, $avatar["HAT"], PDO::PARAM_INT);
					$stmt->execute();
					$itemr = $stmt->fetch();
					echo "<div class='user' style='width:140px!important;'> <center><a href='/store/item/". $itemr[0] ."'><img src='" . $itemr["PREVIEWIMG"] . "' style='width:auto;height:120px;'><div style='height:20px !important;' class='idk'><p style='display:inline-block;'>" . $itemr[1] . "</p></div></a>" . '<form action="" method="POST" style="display: inline-block"><input type="hidden" name="catr" value="HAT"><input type="hidden" name="category" value="' . $category. '"><br><input class="btn" style="padding:5px;background-color:red;border:1px solid darkred;" type="submit" value="Remove"></form>' . "</center></div>";
				}
				if (!empty($avatar["HAT_2"])) {
					$stmt = $conn->prepare("SELECT * FROM items WHERE ID=?;");
					$stmt->bindParam(1, $avatar["HAT_2"], PDO::PARAM_INT);
					$stmt->execute();
					$itemr = $stmt->fetch();
					echo "<div class='user' style='width:140px!important;'> <center><a href='/store/item/". $itemr[0] ."'><img src='" . $itemr["PREVIEWIMG"] . "' style='width:auto;height:120px;'><div style='height:20px !important;' class='idk'><p style='display:inline-block;'>" . $itemr[1] . "</p></div></a>" . '<form action="" method="POST" style="display: inline-block"><input type="hidden" name="catr" value="HAT_2"><input type="hidden" name="category" value="' . $category. '"><br><input class="btn" style="padding:5px;background-color:red;border:1px solid darkred;" type="submit" value="Remove"></form>' . "</center></div>";
				}
				if (!empty($avatar["FACE"])) {
					$stmt = $conn->prepare("SELECT * FROM items WHERE ID=?;");
					$stmt->bindParam(1, $avatar["FACE"], PDO::PARAM_INT);
					$stmt->execute();
					$itemr = $stmt->fetch();
					echo "<div class='user' style='width:140px!important;'> <center><a href='/store/item/". $itemr[0] ."'><img src='" . $itemr["PREVIEWIMG"] . "' style='width:auto;height:120px;'><div style='height:20px !important;' class='idk'><p style='display:inline-block;'>" . $itemr[1] . "</p></div></a>" . '<form action="" method="POST" style="display: inline-block"><input type="hidden" name="catr" value="FACE"><input type="hidden" name="category" value="' . $category. '"><br><input class="btn" style="padding:5px;background-color:red;border:1px solid darkred;" type="submit" value="Remove"></form>' . "</center></div>";
				}
			?>
		</div>
		<br>
		<script>document.getElementById("head").value = "<?php echo $avatar["HEADC"]; ?>";</script>
		<script>document.getElementById("torso").value = "<?php echo $avatar["TORSOC"]; ?>";</script>
		<script>document.getElementById("lleg").value = "<?php echo $avatar["LLEGC"]; ?>";</script>
		<script>document.getElementById("rleg").value = "<?php echo $avatar["RLEGC"]; ?>";</script>
		<script>document.getElementById("larm").value = "<?php echo $avatar["LARMC"]; ?>";</script>
		<script>document.getElementById("rarm").value = "<?php echo $avatar["RARMC"]; ?>";</script>
		</div>
	</body>
</html>