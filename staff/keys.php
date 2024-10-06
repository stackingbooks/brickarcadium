<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/user.php';
	
	if($user["RANK"] !== "ADMIN") {
		http_response_code(403);
		include($docr. '/errors/403.php');
		die();
	} else {
		
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
		<h1 style="float: left !important;">Beta Keys</h1><br><br><br>
		<div style="display:inline-block;vertical-align:top;">
			<label style="lol2">Panel</label><br>
			<div class="platform2" style="width:900px;height:400px;padding:0;text-align: left;padding-left:10px;padding-right:10px;">
				<script>
					var list = '<?php
						echo 'Y = Yes, N = No\n';
						$stmt = $conn->prepare("SELECT * FROM beta_keys;");
						$stmt->execute();
						while ($row = $stmt->fetch()) {
							echo $row['BETA_KEY'] . ' - Used: ' . $row['USED'] . '\n';
						}
					?>';
					function view() {
						var element = document.createElement('a');
						element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(list));
						element.setAttribute('download', 'betakeylist.txt');

						element.style.display = 'none';
						document.body.appendChild(element);

						element.click();

						document.body.removeChild(element);
					}
				</script>
				<?php
					$stmt = $conn->prepare("SELECT COUNT(*) FROM beta_keys WHERE USED='N';");
					$stmt->execute();
					$available = $stmt->fetchColumn();
					$stmt = $conn->prepare("SELECT COUNT(*) FROM beta_keys WHERE USED='Y';");
					$stmt->execute();
					$used = $stmt->fetchColumn();
					$stmt = $conn->prepare("SELECT COUNT(*) FROM beta_keys;");
					$stmt->execute();
					$total = $stmt->fetchColumn();
				?>
				
				<br>
				<p><b><?php echo $available ?></b> Available</p>
				<p><b><?php echo $used ?></b> Used</p>
				<p><b><?php echo $total ?></b> Total</p>
				
				<button onclick="view()">Download list</button>
				<br>
				<br>
				<form action='' method='POST'>
					<input type='hidden' name='lol' id='lol' value='lol'>
					<button type='submit'>Generate Key</button>
					<?php
						if(!empty($_POST['lol'])) {
							if($_POST['lol'] == 'lol') {
								function ranStr($length) {
									$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
									$charactersLength = strlen($characters);
									$randomString = '';
									for ($i = 0; $i < $length; $i++) {
										$randomString .= $characters[random_int(0, $charactersLength - 1)];
									}
									return $randomString;
								}
								
								$key = 'BAbeta-' . ranStr(20);
								
								$stmt = $conn->prepare("INSERT INTO beta_keys VALUES (NULL, ?, 'N')");
								$stmt->bindParam(1, $key, PDO::PARAM_STR);
								$stmt->execute();
								
								echo $key;
							};
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