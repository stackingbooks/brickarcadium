<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/user.php';
	
	$msg = "";
	
	if($user["RANK"] == "USER") {
		http_response_code(403);
		include($docr. '/errors/403.php');
		die();
	} else {
		if(!empty($_FILES["texture"]) && !empty($_POST["name"]) && !empty($_POST["desc"])) {
			if (empty($_FILES["texture"]["error"]) && intval($_POST["bux"]) && intval($_POST["coins"])) {
				if ($_FILES["texture"]["type"] == "image/png") {
					if ($_FILES["texture"]["size"] < 4000000) {
						$size = getimagesize($_FILES["texture"]["tmp_name"]);
						$wxh = $size[0] . "x" . $size[1];
						if( $wxh == "512x512" ) {
							function ranStr($length) {
								$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
								$charactersLength = strlen($characters);
								$randomString = '';
								for ($i = 0; $i < $length; $i++) {
									$randomString .= $characters[random_int(0, $charactersLength - 1)];
								}
								return $randomString;
							}
							
							$token = ranStr(12);
							$coins = 0;
							if (!empty($_POST["coins"])) {
								$coins = $_POST["coins"];
							}
							$bux = 0;
							if (!empty($_POST["bux"])) {
								$bux = $_POST["bux"];
							}
							$texturepath = '/assets/textures/' . $token . '.png';
							if (move_uploaded_file($_FILES['texture']['tmp_name'], $docr . $texturepath)) {
								$time = time();
								$stmt = $conn->prepare("INSERT INTO items VALUES (NULL, ?, ?, ?, ?, '', ?, ?, 0, 'FACE', ?, ?)");
								$stmt->bindParam(1, $_POST["name"], PDO::PARAM_STR);
								$stmt->bindParam(2, $_POST["desc"], PDO::PARAM_STR);
								$stmt->bindParam(3, $bux, PDO::PARAM_STR);
								$stmt->bindParam(4, $coins, PDO::PARAM_STR);
								$stmt->bindParam(5, $texturepath, PDO::PARAM_STR);
								$stmt->bindParam(6, $texturepath, PDO::PARAM_STR);
								$stmt->bindParam(7, $user[0], PDO::PARAM_INT);
								$stmt->bindParam(8, $time, PDO::PARAM_INT);
								$stmt->execute();
								$itemid = $conn->lastInsertId();
								header("Location: /store/item/" . $itemid . '?facerender=1');
								die();	
							} else {
								$msg = '<div class="redmsg">Something went wrong..</div><br>';
							}
						} else {
							$msg = '<div class="redmsg">Texture must be 512x512.</div><br>';
						}
					} else {
						$msg = '<div class="redmsg">File too big. (max: 4MB)</div><br>';
					}
				} else {
					$msg = '<div class="redmsg">Invalid file type</div><br>';
				}
			} else {
				$msg = '<div class="redmsg">Something went wrong..</div><br>';
			}
		} else {
			if(!empty($_POST) or !empty($_FILES) or !empty($_POST) && !empty($_FILES)) {
				$msg = '<div class="redmsg">Fill all fields!</div><br>';
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
		<h1 style="float: left !important;">Upload a Hat</h1><br><br><br>
		<?php echo $msg; ?>
		<div style="display:inline-block;vertical-align:top;">
			<label>Panel</label><br>
			<div class="platform2" style="width:900px;height:500px;padding:0;text-align: left;padding-left:10px;padding-right:10px;">
				<br>
				<form action="" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="MAX_FILE_SIZE" value="4000000" />
					<label for="texture">Face Texture</label><br>
					<input type="file" name="texture" id="texture"><br>
					<label for="name">Name</label><br>
					<input type="text" placeholder="Awesome Face" name="name" id="name"><br>
					<label for="desc">Description</label><br>
					<textarea type="text" name="desc" id="desc" placeholder="It's awesome." style="height:100px;"></textarea><br><br>
					<div style="display: inline-block;">
						<label for="name">Bux</label><br>
						<input type="number" name="bux" id="bux" placeholder="1"><br>
					</div>
					<div style="display: inline-block;">
						<label for="name">Coins</label><br>
						<input type="number" name="coins" placeholder="10" id= "coins"><br>
					</div><br><br>
					<button type="submit">Upload</button>
				</form>
			</div>
		</div>
		</div>
		</center>
		</div>
	</body>
</html>
