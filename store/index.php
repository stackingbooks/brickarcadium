<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/isuser.php';
	
	if (!empty($_POST["query"])) {
		header("Location: /store/?search=" . $_POST["query"]);
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
	
	if (!empty($_GET["search"])) {
		$stmt = $conn->prepare("SELECT COUNT(*) FROM items WHERE NAME LIKE ? LIMIT ?,?;");
		$thing = '%' . $_GET["search"] . '%';
		$stmt->bindParam(1, $thing, PDO::PARAM_STR);
		$stmt->bindParam(2, $from, PDO::PARAM_INT);
		$stmt->bindParam(3, $to, PDO::PARAM_INT);
		$stmt->execute();
		$count = $stmt->fetchColumn();
		if ($count == 0) {
			if($page !== 1) {
				header("Location: /store/?search=" . $_GET["search"]);
				die();
			} else {
				$msg = '<br><p style="color: gray;">No items found :(';
			}
		}
	} else {
		$stmt = $conn->prepare("SELECT * FROM items WHERE ID=?;");
		$stmt->bindParam(1, $from, PDO::PARAM_STR);
		$stmt->execute();
		$userp = $stmt->fetch();
		if(!$userp) {
			if($page !== 1) {
				header("Location: /store/");
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
		<h2>Store</h2>
		<br>
		<form action="" method="POST">
			<input type="text" name="query" id="query" value="<?php if (!empty($_GET["search"])) { echo htmlentities($_GET["search"]); }; ?>"  placeholder="Search for items..." style="width:80%;display:inline-block;" maxlength="100">
			<button type="submit" style="width:30px;height:30px;display:inline-block;padding:0;"><i class='fas fa-search'></i></button>
		</form>
		<label>All</label><br>
		<div style="height: 500px;overflow:auto;"><center>
		<?php
			if ($msg == "") {
				$time = time () - 60;
				if (!empty($_GET["search"])) {
					$stmt = $conn->prepare("SELECT * FROM items WHERE NAME LIKE ? ORDER BY ID DESC LIMIT ?,?;");
					$thing = '%' . $_GET["search"] . '%';
					$stmt->bindParam(1, $thing, PDO::PARAM_STR);
					$stmt->bindParam(2, $from, PDO::PARAM_INT);
					$stmt->bindParam(3, $to, PDO::PARAM_INT);
				} else {
					$stmt = $conn->prepare("SELECT * FROM items ORDER BY ID DESC LIMIT ?,?;");
					$stmt->bindParam(1, $from, PDO::PARAM_INT);
					$stmt->bindParam(2, $to, PDO::PARAM_INT);
				}
				$stmt->execute();
				$count = 0;
				while ($row = $stmt->fetch()) {
					echo "<div class='user' style='padding-bottom:35px;'> <center><a href='/store/item/" . $row[0] . "'><img src='" . $row["PREVIEWIMG"] . "' style='width:auto;height:150px;'><br><br><div style='height:20px !important;' class='idk'><p>" . $row[1] . "</p></div>";
					if ($row['BUX'] !== 0) {
						echo '<div style="color: green;"><i class="far fa-money-bill-wave"></i>' . $row['BUX'] . '</div>';
					}
					if ($row['COINS'] !== 0) {
						echo '<div style="color: orange;"><i class="far fa-coins"></i>' . $row['COINS'] . '</div> ';
					}
					if ($row['BUX'] == 0 && $row['COINS'] == 0) {
						echo '<div style="color: green;">FREE</div>';
					}
					echo"</a></center></div>";
				}	
			} else {
				echo $msg;
			}
		?>
		</center></div>
		<center><a href="/store/<?php if (!empty($_GET["search"])) { echo  strval($page - 1) . '?search=' . $_GET['search']; } else { echo $page - 1; }; ?>"><i class="fas fa-arrow-alt-circle-left"></i></a> <b><?php echo $page ?></b> <a href="/store/<?php if (!empty($_GET["search"])) { echo  strval($page + 1) . '?search=' . $_GET['search']; } else { echo $page + 1; }; ?>"><i class="fas fa-arrow-alt-circle-right"></i></a></center>
		</div>
	</body>
</html>