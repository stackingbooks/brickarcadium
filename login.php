<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/redirectUsers.php';
	
	if (!empty($_POST['uname']) && !empty($_POST['pwd'])) {
		$pwd = $_POST['pwd'];
		$uname = $_POST['uname'];
		
		// checking if account exists
		$stmt = $conn->prepare("SELECT * FROM users WHERE USERNAME=?");
		$stmt->bindParam(1, $uname, PDO::PARAM_STR);
		$stmt->execute();					
		$user = $stmt->fetch();
		if (!$user) {
			$error = "Account doesn't exist";
		} else {
				// account does exist, but is the password correct?
				if(password_verify($pwd, $user[2])) {
					// user succesfully logged in! :D
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
					
					// edit token
					$stmt = $conn->prepare("SELECT * FROM tokens WHERE USER_ID=?");
					$stmt->bindParam(1, $user[0], PDO::PARAM_STR);
					$stmt->execute();					
					$prevtoken = $stmt->fetch();
					if(!$prevtoken) {
						// i guess we can generate a new one if it somehow disappeared which will never happen
						$stmt = $conn->prepare("INSERT INTO tokens VALUES (NULL, ?, ?)");
						$stmt->bindParam(1, $uid, PDO::PARAM_STR);
						$stmt->bindParam(2, $token, PDO::PARAM_STR);
						$stmt->execute();
					} else {
						// alr, edit the previous one
						$stmt = $conn->prepare("UPDATE tokens SET TOKEN=? WHERE USER_ID=?");
						$stmt->bindParam(1, $token, PDO::PARAM_STR);
						$stmt->bindParam(2, $user[0], PDO::PARAM_STR);
						$stmt->execute();
					}
					// time to set cookie and redirect user
					setcookie("TOKEN--DONOTSHARE", $token, time()+604800000);
					header("Location: /user/home");
					die();
				} else {
					$error = "Invalid password";
				}
		}
	} else {
		if (!empty($_POST['uname']) or !empty($_POST['pwd'])) {
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
					<input type="text" id="uname" name="uname" minlength="3" maxlength="60"><br>
					<label for="pwd" style="lol2">Password</label><br>
					<input type="password" id="pwd" name="pwd" minlength="10" maxlength="2000"><br><br>
					<?php if (!empty($error)){echo"<label style='color:red;'>" . $error . "</label><br>";}?>
					<input class="btn" type="submit" value="Login">
				</form> 
			</div>
		</center>
		</div>
	</body>
</html>