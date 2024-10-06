<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/isuser.php';
	
	if (!empty($_POST["query"])) {
		header("Location: /user/list/1?search=" . $_POST["query"]);
		die();
	}
	
	$page = 1;
	if (!empty($_GET["page"])) {
		$page = $_GET["page"];
	}
	
	if (!intval($page)) {
		$page = 1;
	}
	
	$msg = "";
	
	$perpage = 14;
	
	$to = $page * $perpage;
	$from = $to - $perpage;
	if ($from == 0) {
		$idk=0;
	} else { 
		$idk = $from-1;
	};
	
	if (!empty($_GET["search"])) {
		$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE USERNAME LIKE ? LIMIT ?,?;");
		$thing = '%' . $_GET["search"] . '%';
		$stmt->bindParam(1, $thing, PDO::PARAM_STR);
		$stmt->bindParam(2, $from, PDO::PARAM_INT);
		$stmt->bindParam(3, $to, PDO::PARAM_INT);
		$stmt->execute();
		$count = $stmt->fetchColumn();
		if ($count == 0) {
			if($page !== 1) {
				header("Location: /user/list/?search=" . $_GET["search"]);
				die();
			} else {
				$msg = '<br><p style="color: gray;">No users found :(';
			}
		}
	} else {
		$stmt = $conn->prepare("SELECT * FROM users WHERE ID=?;");
		$stmt->bindParam(1, $from, PDO::PARAM_STR);
		$stmt->execute();
		$userp = $stmt->fetch();
		if(!$userp) {
			if($page !== 1) {
				header("Location: /user/list");
				die();
			}
		}
	}
	
?>
<html>
	<?php require $docr . '/misc/htmls/head.html'; ?>
	<body>
		<?php require $docr . '/misc/navbar.php'; ?>
		<br>
		<br>
		<h2>Userlist</h2>
		<br>
		<form action="" method="POST">
			<input type="text" name="query" id="query" value="<?php if (!empty($_GET["search"])) { echo htmlentities($_GET["search"]); }; ?>"  placeholder="Search for users..." style="width:80%;display:inline-block;" maxlength="100">
			<button type="submit" style="width:30px;height:30px;display:inline-block;padding:0;"><i class='fas fa-search'></i></button>
		</form>
		<?php
			if($page == 1 && empty($_GET["search"])) {
				echo <<<EOT
						<label>Online</label><br>
						<div style="height: 250px;overflow:auto;"><center>
						EOT;
							$time = time () - 60;
							$stmt = $conn->prepare("SELECT * FROM users WHERE LAST_ONLINE > ?;");
							$stmt->bindParam(1, $time, PDO::PARAM_INT);
							$stmt->execute();
							$count = 0;
							while ($row = $stmt->fetch()) {
								echo "<div class='user'> <center><a href='/user/profile/" . $row[0] . "'><img src='" . $row["AVATARIMG"] . "' style='width:150px;height:auto;'><div style='height:20px !important;' class='idk'><i class='fas fa-circle' style='color:green;display:inline-block;'></i><p style='display:inline-block;'>" . $row[1] . "</p></div></a></center></div>";
								$count = $count + 1;
							}
							if ($count == 0) {
								echo "<br><p style='color: gray;'>Nobody's online :(</p>";
							}
						echo <<<EOT
						</center></div><br><br>
				EOT;
			}
		?>
		<label>All</label><br>
		<div style="height: 500px;overflow:auto;"><center>
		<?php
			if ($msg == "") {
				$time = time () - 60;
				if (!empty($_GET["search"])) {
					$stmt = $conn->prepare("SELECT * FROM users WHERE USERNAME LIKE ? LIMIT ?,?;");
					$thing = '%' . $_GET["search"] . '%';
					$stmt->bindParam(1, $thing, PDO::PARAM_STR);
					$stmt->bindParam(2, $from, PDO::PARAM_INT);
					$stmt->bindParam(3, $to, PDO::PARAM_INT);
				} else {
					$stmt = $conn->prepare("SELECT * FROM users LIMIT ? OFFSET ?;");
					$stmt->bindParam(1, $perpage, PDO::PARAM_INT);
					$stmt->bindParam(2, $idk, PDO::PARAM_INT);
				}
				$stmt->execute();
				$count = 0;
				while ($row = $stmt->fetch()) {
					echo "<div class='user'> <center><a href='/user/profile/" . $row[0] . "'><img src='" . $row["AVATARIMG"] . "' style='width:150px;height:auto;'><div style='height:20px !important;' class='idk'>";
					if ($row["LAST_ONLINE"] > $time) {
						echo "<i class='fas fa-circle' style='color:green;display:inline-block;'>";
					} else {
						echo "<i class='fas fa-circle' style='color:gray;display:inline-block;'>";
					}
					echo "</i><p style='display:inline-block;'>" . $row[1] . "</p></div></a></center></div>";
				}	
			} else {
				echo $msg;
			}
		?>
		</center></div>
		<center><a href="/user/list/<?php if (!empty($_GET["search"])) { echo  strval($page - 1) . '?search=' . $_GET['search']; } else { echo $page - 1; }; ?>"><i class="fas fa-arrow-alt-circle-left"></i></a> <b><?php echo $page ?></b> <a href="/user/list/<?php if (!empty($_GET["search"])) { echo  strval($page + 1) . '?search=' . $_GET['search']; } else { echo $page + 1; }; ?>"><i class="fas fa-arrow-alt-circle-right"></i></a></center>
		</div>
	</body>
</html>