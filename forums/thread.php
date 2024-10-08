<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/isuser.php';
	
	if(empty($_GET["id"])) {
		header("Location: /");
		die();
	} else {
		$id = $_GET["id"];
	}
	
		
	$page = 1;
	if (!empty($_GET["page"])) {
		$page = $_GET["page"];
	}
	if (!intval($page)) {
		$page = 1;
	}
	
	$msg = "";
	
	$perpage = 12;
	
	$to = $page * $perpage;
	$from = $to - $perpage;
	
	$idk = $from + 1;
	
	$stmt = $conn->prepare("SELECT * FROM forum_threads WHERE ID=?");
	$stmt->bindParam(1, $id, PDO::PARAM_STR);
	$stmt->execute();
	$th = $stmt->fetch();
	if(!$th) {
		http_response_code(404);
		include($docr. '/errors/404.php');
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
		$newv = $th['VIEWS'] + 1;
		$stmt = $conn->prepare("UPDATE forum_threads SET VIEWS=? WHERE ID=?");
		$stmt->bindParam(1, $newv, PDO::PARAM_STR);
		$stmt->bindParam(2, $th[0], PDO::PARAM_STR);
		$stmt->execute();
	}
	
	$stmt = $conn->prepare("SELECT * FROM forum_replies WHERE THREAD_ID=? LIMIT ?,?;");
	$stmt->bindParam(1, $id, PDO::PARAM_INT);
	$stmt->bindParam(2, $idk, PDO::PARAM_INT);
	$stmt->bindParam(3, $idk, PDO::PARAM_INT);
	$stmt->execute();
	$userp = $stmt->fetch();
	if(!$userp) {
		if($page !== 1) {
			header("Location: /forums/thread/" . $id);
			die();
		}
	}
	
	if (!empty($user)) {
		if ($user["RANK"] !== 'USER') {
			if(!empty($_POST["pin"])) {
				if($th['PINNED'] == 'N') {
					$stmt = $conn->prepare("UPDATE forum_threads SET PINNED='Y' WHERE ID=?");
					$stmt->bindParam(1, $th[0], PDO::PARAM_STR);
					$stmt->execute();
				} else {
					$stmt = $conn->prepare("UPDATE forum_threads SET PINNED='N' WHERE ID=?");
					$stmt->bindParam(1, $th[0], PDO::PARAM_STR);
					$stmt->execute();
				}
			}
			if(!empty($_POST["lock"])) {
				if($th['LOCKED'] == 'N') {
					$stmt = $conn->prepare("UPDATE forum_threads SET LOCKED='Y' WHERE ID=?");
					$stmt->bindParam(1, $th[0], PDO::PARAM_STR);
					$stmt->execute();
				} else {
					$stmt = $conn->prepare("UPDATE forum_threads SET LOCKED='N' WHERE ID=?");
					$stmt->bindParam(1, $th[0], PDO::PARAM_STR);
					$stmt->execute();
				}
			}
			if(!empty($_POST["delete"])) {
				$stmt = $conn->prepare("DELETE FROM forum_threads WHERE ID=?");
				$stmt->bindParam(1, $th[0], PDO::PARAM_STR);
				$stmt->execute();
				header("Location: /forums/category/" . $cat[0]);
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
		<br><br>
		<center>
		<div style="width:90%;">
		<h1 style="float: left !important;">Forums </h1><br><br><br>
		<p style="float: left !important;"><a href="/forums/">Forums </a><i class="fa-solid fa-chevrons-right"></i> <a href="/forums/category/<?php echo $cat[0]; ?>"><?php echo $cat["NAME"]; ?></a> <i class="fa-solid fa-chevrons-right"></i>  <a href="/forums/thread/<?php echo $th[0]; ?>"><?php echo htmlspecialchars($th[1]); ?></a> </p>
		<br><br><a href="/forums/reply/<?php echo $th[0]; ?>"><button style="float: right !important;">Reply</button></a><br><br><br>
		<div style="display:inline-block;vertical-align:top;width:100%;">
			<div class="forumplatform" style="border-top-left-radius: 20px;border-top-right-radius: 20px;float:left;width:100%;">
				 <table>
					 <tr style="border-top-left-radius: 20px;border-top-right-radius: 20px;">
						<th style="border-top-left-radius: 20px;;border-top-right-radius: 20px; text-align:left;"><?php
						if ($th['PINNED'] == 'Y') {
							echo ' <i class="fa-solid fa-thumbtack"></i> ';
						}
						if ($th['LOCKED'] == 'Y') {
							echo ' <i class="fa-solid fa-lock"></i> ';
						}
						echo $th[1]; ?></th>
					 </tr>
					 <?php
						if ($page == 1) {
							echo '<tr>
							<td><div style="width:100%;height:auto;"><a href="/user/profile/' . $tuser[0] .'"><div style="float: left;height:300px;width:200px;"><img style="width:100%;height:auto;" src="' . $tuser['AVATARIMG'] . '"><br><center><b ';
							if ($tuser["RANK"] == "ADMIN") {
								echo 'style="color: red;"';
							} elseif ($tuser["RANK"] == "MOD") {
								echo 'style="color: blue;"';
							};
							echo ">";
							if ($tuser["RANK"] !== "USER") { echo '<i class="fas fa-gavel"></i> '; };
							echo $tuser[1];
							echo '</b><br><br><b>' . $tuser["FORUM_POSTS"] . '</b> Forum Posts<br>Joined: <b>' . gmdate("d M Y", $tuser["JOIN_DATE"]) . '</b></center></div></a>
							<i style="float: right;color:gray;">' . gmdate("d/m/Y H:i:s", $th["TIME"]) . ' UTC </i><br>  <div style="float: right; width: 80%;">' . str_replace("\n", "<br>", htmlentities(str_replace("<br>", "\n", $th[2])));
							if (!empty($user)) {
							if ($user["RANK"] !== "USER") {
								echo "<br><br><br><center><form action='' method='POST' style='display: inline-block;'><input type='hidden' name='pin' id='pin' value='pin'><button type='submit'>";
								if($th['PINNED'] == 'N') {
									echo "Pin";
								} else {
									echo "Unpin";
								}
								echo "</button></form> <form action='' method='POST' style='display: inline-block;'><input type='hidden' name='lock' id='lock' value='lock'><button type='submit'>";
								if($th['LOCKED'] == 'N') {
									echo "Lock";
								} else {
									echo "Unlock";
								}
								echo "</button></form> <form action='' method='POST' style='display: inline-block;'><input type='hidden' name='delete' id='delete' value='delete'><button type='submit'>Delete</button></form></center>";
							}
							echo '</div></div>';
							echo '</td></tr>';
						}
						}
					 ?>
					 <?php
						$stmt = $conn->prepare("SELECT * FROM forum_replies WHERE THREAD_ID = ? LIMIT ?,?;");
						$stmt->bindParam(1, $id, PDO::PARAM_STR);
						$stmt->bindParam(2, $from, PDO::PARAM_INT);
						$stmt->bindParam(3, $to, PDO::PARAM_INT);
						$stmt->execute();
						while ($row = $stmt->fetch()) {
							$stmt2 = $conn->prepare("SELECT * FROM users WHERE ID=?");
							$stmt2->bindParam(1, $row['USER_ID'], PDO::PARAM_STR);
							$stmt2->execute();
							$ruser = $stmt2->fetch();
							echo '<tr>
						<td><div style="width:100%;height:auto;"><a href="/user/profile/' . $ruser[0] .'"><div style="float: left;height:300px;width:200px;"><img style="width:100%;height:auto;" src="' . $ruser['AVATARIMG'] . '"><br><center><b ';
						if ($ruser["RANK"] == "ADMIN") {
				echo 'style="color: red;"';
			} elseif ($ruser["RANK"] == "MOD") {
				echo 'style="color: blue;"';
			};
			echo ">";
			if ($ruser["RANK"] !== "USER") { echo '<i class="fas fa-gavel"></i> '; };
			echo $ruser[1];
			echo '</b><br><br><b>' . $ruser["FORUM_POSTS"] . '</b> Forum Posts<br>Joined: <b>' . gmdate("d M Y", $ruser["JOIN_DATE"]) . '</b></center></div></a>
			<i style="float: right;color: gray;">' . gmdate("d/m/Y H:i:s", $row["TIME"]) . ' UTC </i><br> <div style="float: right; width: 80%;">' . str_replace("\n", "<br>", htmlentities(str_replace("<br>", "\n", $row[1]))) . '</div></div></td></tr>';
						}
					?>
				</table>			
			</div>
			<div><center><br><br><a href="/forums/thread/<?php echo $id . '?page=' . $page + 1; ?>"><i class="fas fa-arrow-alt-circle-left"></i></a> <b><?php echo $page ?></b> <a href="/forums/thread/<?php echo $id . '?page=' . $page + 1; ?>"><i class="fas fa-arrow-alt-circle-right"></i></a></center></div>
		</div>
		</div>
		</center>
		</div>
	</body>
</html>
