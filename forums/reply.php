<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/user.php';
	
	if(empty($_GET["id"])) {
		header("Location: /");
		die();
	} else {
		$id = $_GET["id"];
	}
	
	$msg = "";
	
	$stmt = $conn->prepare("SELECT * FROM forum_threads WHERE ID=?");
	$stmt->bindParam(1, $id, PDO::PARAM_STR);
	$stmt->execute();
	$th = $stmt->fetch();
	if(!$th) {
		http_response_code(404);
		include($docr. '/errors/404.php');
		die();
	} else {
		if($th['LOCKED'] == 'Y') {
			header("Location: /forums/thread/" . $id);
			die();
		} else {
			$stmt = $conn->prepare("SELECT * FROM forum_categories WHERE ID=?");
			$stmt->bindParam(1, $th["CATEGORY_ID"], PDO::PARAM_STR);
			$stmt->execute();
			$cat = $stmt->fetch();
			$stmt = $conn->prepare("SELECT * FROM users WHERE ID=?");
			$stmt->bindParam(1, $th["USER_ID"], PDO::PARAM_STR);
			$stmt->execute();
			$tuser = $stmt->fetch();
		}
	}
	
	if (!empty($_POST["body"])) {
		if (strlen($_POST["body"]) == 400 or strlen($_POST["body"]) < 400 && strlen($_POST["body"]) > 5 or strlen($_POST["body"]) == 5) {
				$time = time();
				$intid =  intval($id);
				$stmt = $conn->prepare("INSERT INTO forum_replies VALUES (NULL, ?, ?, ?, ?)");
				$stmt->bindParam(1, $_POST["body"], PDO::PARAM_STR);
				$stmt->bindParam(2, $user[0], PDO::PARAM_INT);
				$stmt->bindParam(3, $intid, PDO::PARAM_INT);
				$stmt->bindParam(4, $time, PDO::PARAM_INT);
				$stmt->execute();
				$newt = $cat['POSTS'] + 1;
				$stmt = $conn->prepare("UPDATE forum_categories SET POSTS=? WHERE ID=?");
				$stmt->bindParam(1, $newt, PDO::PARAM_INT);
				$stmt->bindParam(2, $cat[0], PDO::PARAM_INT);
				$stmt->execute();
				$newt = $th['REPLIES'] + 1;
				$stmt = $conn->prepare("UPDATE forum_threads SET REPLIES=? WHERE ID=?");
				$stmt->bindParam(1, $newt, PDO::PARAM_INT);
				$stmt->bindParam(2, $th[0], PDO::PARAM_INT);
				$stmt->execute();
				$newt = $user['FORUM_POSTS'] + 1;
				$stmt = $conn->prepare("UPDATE users SET FORUM_POSTS=? WHERE ID=?");
				$stmt->bindParam(1, $newt, PDO::PARAM_INT);
				$stmt->bindParam(2, $user[0], PDO::PARAM_INT);
				$stmt->execute();
				header("Location: /forums/thread/" . $id);
				die();
		} else {
			$msg = '<div class="redmsg">Body must be 5 - 1000 characters long.</div><br>';
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
		<div style="width:90%;">
		<h1 style="float: left !important;">Forums </h1><br><br><br>
		<p style="float: left !important;"><a href="/forums/">Forums </a><i class="fa-solid fa-chevrons-right"></i> <a href="/forums/category/<?php echo $cat[0]; ?>"><?php echo $cat["NAME"]; ?></a> <i class="fa-solid fa-chevrons-right"></i>  <a href="/forums/thread/<?php echo $th[0]; ?>"><?php echo $th[1]; ?></a> </p><br><br>
		<?php echo $msg; ?>
		<div style="display:inline-block;vertical-align:top;width:100%;">
			<div class="forumplatform" style="float:left;width:100%;">
				<form action="" method='POST' style="float: left;">
					<textarea type="text" name="body" id="body" style="width:1000px;height:30%;" placeholder="I like this." minlength="5" maxlength="400"></textarea><br><br>
					<button type="submit">Post</button>
				</form>
			</div>
		</div>
		</div>
		</center>
		</div>
	</body>
</html>