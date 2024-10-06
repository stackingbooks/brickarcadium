<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/redirectUsers.php';
	
	if (!empty($_POST['uname']) && !empty($_POST['pwd']) && !empty($_POST['key'])) {
		$pwd = $_POST['pwd'];
		$uname = $_POST['uname'];
		$key = $_POST['key'];
		if(strlen($pwd) < 10) {
			$error = 'Password must atleast 10 characters long.';
		} else {
			if(strlen($pwd) > 2000) {
				$error = 'Password is too long.';
			} else {
				if(strlen($key) > 255) {
					$error = 'Beta Key is too long.';
				} else {
					if(strlen($uname) > 16 or strlen($uname) < 3) {
						$error = 'Username must be 3-15 characters long.';
					} else {
						// checking if username is valid
						if (!ctype_alnum($uname)) {
							$error = "Username must be alphanumeric. (a-z, A-Z, 0-9, no spaces)";
						} else {
							// checking if username already used
							$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE USERNAME=?");
							$stmt->bindParam(1, $uname, PDO::PARAM_STR);
							$stmt->execute();					
							$num = $stmt->fetchColumn();
							$stmt = $conn->prepare("SELECT COUNT(*) FROM beta_keys WHERE BETA_KEY=? AND USED='N'");
							$stmt->bindParam(1, $key, PDO::PARAM_STR);
							$stmt->execute();					
							$num2 = $stmt->fetchColumn();
							if ($num > 0) {
								$error = 'Username is already used. Maybe try ' . $uname . rand(10000,99999) . '?';
							} else {
								if ($num2 == 0) {
									$error = 'Beta key invalid';
								} else {
									// user can be registered... yay!
									
									// make beta key used
									$stmt = $conn->prepare("UPDATE beta_keys SET USED='Y' WHERE BETA_KEY=?");
									$stmt->bindParam(1, $key, PDO::PARAM_STR);
									$stmt->execute();	
									
									// hashing!!!!!
									$hPWD = password_hash($pwd, PASSWORD_DEFAULT);
									
									// register user
									$time = time();
									$desc = "Hi! I'm new to Brickarcadium!";
									$stmt = $conn->prepare("INSERT INTO users VALUES (NULL, :name, :pwd, '', :desc, 0, :time2, 'USER', 99, 99, :time, 0, 'N', '/imgs/avatars/Default.png', '/imgs/headshots/Default.png', 0)");
									$stmt->bindParam(':name', $uname, PDO::PARAM_STR);
									$stmt->bindParam(':pwd', $hPWD, PDO::PARAM_STR);
									$stmt->bindParam(':time', $time, PDO::PARAM_STR);
									$stmt->bindParam(':time2', $time, PDO::PARAM_STR);
									$stmt->bindParam(':desc', $desc, PDO::PARAM_STR);
									$stmt->execute();
									$uid = $conn->lastInsertId();
									$stmt = $conn->prepare("INSERT INTO avatars VALUES (NULL, ?, 0, 0, 0, '#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff')");
									$stmt->bindParam(1, $uid, PDO::PARAM_STR);
									$stmt->execute();
									
									// generate token
									function ranStr($length) {
										$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
										$charactersLength = strlen($characters);
										$randomString = '';
										for ($i = 0; $i < $length; $i++) {
											$randomString .= $characters[random_int(0, $charactersLength - 1)];
										}
										return $randomString;
									}
									
									$token = ranStr(80);
									
									// add token
									$stmt = $conn->prepare("INSERT INTO tokens VALUES (NULL, ?, ?)");
									$stmt->bindParam(1, $uid, PDO::PARAM_STR);
									$stmt->bindParam(2, $token, PDO::PARAM_STR);
									$stmt->execute();
									
									// time to set cookie and redirect user
									setcookie("TOKEN--DONOTSHARE", $token, time()+604800000);
									header("Location: /user/home");
									die();
								}
							}
						}
					}
				}
			}
		}
	} else {
		if (!empty($_POST['uname']) or !empty($_POST['pwd']) or !empty($_POST['key'])) {
			$error = 'Please fill in all fields!';
		}
	}
?>
<html>
	<?php require $docr . '/misc/htmls/head.html'; ?>
	<body>
		<?php require $docr . '/misc/navbar.php'; ?>
		<center>
			<br><br><br><br>
			<div class="platform">
				<form action="" method='POST'>
					<label for="uname" style="lol2">Username</label><br>
					<input type="text" id="uname" name="uname" minlength="3" maxlength="15"><br>
					<label for="pwd" style="lol2">Password</label><br>
					<input type="password" id="pwd" name="pwd" minlength="10" maxlength="2000"><br>
					<label for="pwd" style="lol2">Beta Key</label><br>
					<input type="text" id="key" name="key" maxlength="100"><br><br>
					<?php if (!empty($error)){echo"<label style='color:red;'>" . $error . "</label><br>";}?>
					<input class="btn" type="submit" value="Register">
				</form> 
			</div>
		</center>
		</div>
	</body>
</html>