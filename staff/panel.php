<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/user.php';
	
	if($user["RANK"] !== "ADMIN" && $user["RANK"] !== "MOD") {
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
		<h1 style="float: left !important;">Staff Panel</h1><br><br><br>
		<div style="display:inline-block;vertical-align:top;">
			<label style="lol2">Panel</label><br>
			<div class="platform2" style="width:900px;height:400px;padding:0;text-align: left;padding-left:10px;padding-right:10px;">
				<h3>Moderators</h3>
				<a href="/staff/hatupload" style="display: inline-block;"><button>Upload hat</button></a> <a href="/staff/faceupload" style="display: inline-block;"><button>Upload face</button></a>
				<br>
				<br>
				<a href="/misc/rendering/stuff/Character.glb">click to download character.glb. no way</a>
				<br>
				<a href="/misc/rendering/stuff/Face.png">click to download face.png. no way</a>
				<?php
					if ($user["RANK"] == "ADMIN") {
						echo <<<EOT
							<h3>Admins</h3>
							<br>
							<br>
							<a href="/staff/keys" style="display: inline-block;"><button>Beta keys</button></a>
						EOT;
					}
				?>
			</div>
		</div>
		</div>
		</center>
		</div>
	</body>
</html>