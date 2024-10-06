<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/redirectUsers.php';
?>
<html>
	<?php require $docr . '/misc/htmls/head.html'; ?>
	<body>
		<?php require $docr . '/misc/navbar.php'; ?>
		<br>
		<h1>Welcome to Beta</h1>
		<p>stuff like Bunches, Friends, Inventory will come after this testing</p>
		<br>
		<a href="login"><button>Login..</button></a> <a href="register"><button>Register..</button></a>
		</div>
	</body>
</html>