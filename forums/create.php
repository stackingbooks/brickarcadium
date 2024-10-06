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
	
	$stmt = $conn->prepare("SELECT * FROM forum_categories WHERE ID=?");
	$stmt->bindParam(1, $id, PDO::PARAM_STR);
	$stmt->execute();
	$cat = $stmt->fetch();
	if(!$cat) {
		http_response_code(404);
		include($docr. '/errors/404.php');
		die();
	}
	
	if (!empty($_POST["body"]) && !empty($_POST["title"])) {
		if (strlen($_POST["body"]) == 1000 or strlen($_POST["body"]) < 1000 && strlen($_POST["body"]) > 5 or strlen($_POST["body"]) == 5) {
			if (strlen($_POST["title"]) == 40 or strlen($_POST["title"]) < 40 && strlen($_POST["title"]) > 5 or strlen($_POST["title"]) == 5) {
				$time = time();
				$intid =  intval($id);
				$hebio = $_POST["body"];
				$finalbio = str_replace("\n", "<br>", $hebio);
				$stmt = $conn->prepare("INSERT INTO forum_threads VALUES (NULL, ?, ?, 'N', 'N', 0, 0, 0, ?, ?, ?, ?)");
				$stmt->bindParam(1, $_POST["title"], PDO::PARAM_STR);
				$stmt->bindParam(2, $finalbio, PDO::PARAM_STR);
				$stmt->bindParam(3, $user[0], PDO::PARAM_INT);
				$stmt->bindParam(4, $time, PDO::PARAM_INT);
				$stmt->bindParam(5, $time, PDO::PARAM_INT);
				$stmt->bindParam(6, $intid, PDO::PARAM_INT);
				$stmt->execute();
				$tid = $conn->lastInsertId();
				$newt = $cat['THREADS'] + 1;
				$stmt = $conn->prepare("UPDATE forum_categories SET THREADS=? WHERE ID=?");
				$stmt->bindParam(1, $newt, PDO::PARAM_INT);
				$stmt->bindParam(2, $cat[0], PDO::PARAM_INT);
				$stmt->execute();
				$newt = $user['FORUM_POSTS'] + 1;
				$stmt = $conn->prepare("UPDATE users SET FORUM_POSTS=? WHERE ID=?");
				$stmt->bindParam(1, $newt, PDO::PARAM_INT);
				$stmt->bindParam(2, $user[0], PDO::PARAM_INT);
				$stmt->execute();
				header("Location: /forums/thread/" . $tid);
				die();
			} else {
				$msg = '<div class="redmsg">Title must be 5 - 40 characters long.</div><br>';
			}
		} else {
			$msg = '<div class="redmsg">Body must be 5 - 1000 characters long.</div><br>';
		}
	} elseif (!empty($_POST["body"]) or !empty($_POST["title"])) {
		$msg = '<div class="redmsg">Fill all fields!</div><br>';
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
		<p style="float: left !important;"><a href="/forums/">Forums </a><i class="fa-solid fa-chevrons-right"></i> <a href="/forums/category/<?php echo $cat[0]; ?>"><?php echo $cat["NAME"]; ?></a> </p><br><br><br>
		<?php echo $msg; ?>
		<div style="display:inline-block;vertical-align:top;width:100%;">
			<div class="forumplatform" style="float:left;width:100%;">
				<form action="" method='POST' style="float: left;">
					<label for="title">Title</label><br>
					<input type="text" name="title" id="title" placeholder="My cool forum post" style="width:1000px;" minlength="5" maxlength="40"><br>
					<label for="body">Body</label><br>
					<textarea type="text" name="body" id="body" style="width:1000px;height:30%;" placeholder="This is my cool forum post." minlength="5" maxlength="1000"></textarea><br><br>
					<button type="submit">Post</button>
				</form>
			</div>
		</div>
		</div>
		</center>
		</div>
	</body>
</html>