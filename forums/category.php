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
	
	$msg = "";
	
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
	
	$stmt = $conn->prepare("SELECT * FROM forum_categories WHERE ID=?");
	$stmt->bindParam(1, $id, PDO::PARAM_STR);
	$stmt->execute();
	$cat = $stmt->fetch();
	if(!$cat) {
		http_response_code(404);
		include($docr. '/errors/404.php');
		die();
	}
	
	$idk = $from + 1;
	$stmt = $conn->prepare("SELECT * FROM forum_threads WHERE CATEGORY_ID=? ORDER BY ID DESC LIMIT ?,?;");
	$stmt->bindParam(1, $cat[0], PDO::PARAM_INT);
	$stmt->bindParam(2, $idk, PDO::PARAM_INT);
	$stmt->bindParam(3, $idk, PDO::PARAM_INT);
	$stmt->execute();
	$userp = $stmt->fetch();
	if(!$userp) {
		if($page !== 1) {
			header("Location: /forums/category/" . $id);
			die();
		}
	}

	function timeAgo($time_ago){
$cur_time 	= time();
$time_elapsed 	= $cur_time - $time_ago;
$seconds 	= $time_elapsed ;
$minutes 	= round($time_elapsed / 60 );
$hours 		= round($time_elapsed / 3600);
$days 		= round($time_elapsed / 86400 );
$weeks 		= round($time_elapsed / 604800);
$months 	= round($time_elapsed / 2600640 );
$years 		= round($time_elapsed / 31207680 );
// Seconds
if($seconds <= 60){
	return "$seconds seconds ago";
}
//Minutes
else if($minutes <=60){
	if($minutes==1){
		return "1 minute ago";
	}
	else{
		return "$minutes minutes ago";
	}
}
//Hours
else if($hours <=24){
	if($hours==1){
		return "1 hour ago";
	}else{
		return "$hours hours ago";
	}
}
//Days
else if($days <= 7){
	if($days==1){
		return "1 day ago";
	}else{
		return "$days days ago";
	}
}
//Weeks
else if($weeks <= 4.3){
	if($weeks==1){
		return "1 week ago";
	}else{
		return "$weeks weeks ago";
	}
}
//Months
else if($months <=12){
	if($months==1){
		return "1 month ago";
	}else{
		return "$months months ago";
	}
}
//Years
else{
	if($years==1){
		return "1 year ago";
	}else{
		return "$years years ago";
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
		<p style="float: left !important;"><a href="/forums/">Forums </a><i class="fa-solid fa-chevrons-right"></i> <a href="/forums/category/<?php echo $cat[0]; ?>"><?php echo $cat["NAME"]; ?></a> </p>
		<br><br><a href="/forums/create/<?php echo $cat[0]; ?>"><button style="float: right !important;">Create</button></a><br><br><br>
		<div style="display:inline-block;vertical-align:top;width:100%;">
			<div class="forumplatform" style="border-top-left-radius: 20px;border-top-right-radius: 20px;float:left;width:100%;">
				 <table>
					 <tr style="border-top-left-radius: 20px;border-top-right-radius: 20px;">
						<th style="border-top-left-radius: 20px;">Thread</th>
						<th>Replies</th>
						<th>Views</th>
						<th style="border-top-right-radius: 20px;">Updated</th>
					 </tr>
					 <?php
						if ($page == 1) {
						$stmt = $conn->prepare("SELECT * FROM forum_threads WHERE CATEGORY_ID = ? AND PINNED = 'Y'");
						$stmt->bindParam(1, $id, PDO::PARAM_STR);
						$stmt->execute();
						while ($row = $stmt->fetch()) {
							$stmt2 = $conn->prepare("SELECT * FROM users WHERE ID=?");
							$stmt2->bindParam(1, $row['USER_ID'], PDO::PARAM_STR);
							$stmt2->execute();
							$tuser = $stmt2->fetch();
							echo '<tr><td><div><a href="/forums/thread/' . $row[0] . '"><b><i class="fa-solid fa-thumbtack">';
							if ($row['LOCKED'] == 'Y') {
								echo ' <i class="fa-solid fa-lock"></i>';
							}
							echo ' </i> ' . $row[1] . '</b></a><br> by <b><a href="/user/profile/' . $tuser[0] . '">' . $tuser[1] . '</a></b></div></td><td>' . $row['REPLIES'] . '</td><td>' . $row['VIEWS'] . '</td><td>' . timeAgo($row['UPDATED']) . '</td></tr>';
						}
						}
					?>
					<?php
						$stmt = $conn->prepare("SELECT * FROM forum_threads WHERE CATEGORY_ID = ? AND PINNED = 'N' ORDER BY ID DESC LIMIT ?,?;");
						$stmt->bindParam(1, $id, PDO::PARAM_STR);
						$stmt->bindParam(2, $from, PDO::PARAM_INT);
						$stmt->bindParam(3, $to, PDO::PARAM_INT);
						$stmt->execute();
						while ($row = $stmt->fetch()) {
							$stmt2 = $conn->prepare("SELECT * FROM users WHERE ID=?");
							$stmt2->bindParam(1, $row['USER_ID'], PDO::PARAM_STR);
							$stmt2->execute();
							$tuser = $stmt2->fetch();
							echo '<tr><td><div><a href="/forums/thread/' . $row[0] . '"><b>';
							if ($row['LOCKED'] == 'Y') {
								echo ' <i class="fa-solid fa-lock"></i> ';
							}
							echo $row[1] . '</b></a><br> by <b><a href="/user/profile/' . $tuser[0] . '">' . $tuser[1] . '</a></b></div></td><td>' . $row['REPLIES'] . '</td><td>' . $row['VIEWS'] . '</td><td>' . timeAgo($row['UPDATED']) . '</td></tr>';
						}
					?>
				</table>			
			</div>
			<div><center><br><br><a href="/forums/category/<?php echo $id . '?page=' . $page + 1; ?>"><i class="fas fa-arrow-alt-circle-left"></i></a> <b><?php echo $page ?></b> <a href="/forums/category/<?php echo $id . '?page=' . $page + 1; ?>"><i class="fas fa-arrow-alt-circle-right"></i></a></center></div>
		</div>
		</div>
		</center>
		</div>
	</body>
</html>