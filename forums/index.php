<?php
	$docr = $_SERVER["DOCUMENT_ROOT"];
	require $docr . '/misc/connect.php';
	require $docr . '/misc/isuser.php';
?>
<html>
	<?php require $docr . '/misc/htmls/head.html'; ?>
	<body>
		<?php require $docr . '/misc/navbar.php'; ?>
		<br>
		<br><br>
		<center>
		<div style="width:90%;">
		<h1 style="float: left !important;">Forums</h1><br><br><br>
		<div style="display:inline-block;vertical-align:top;width:100%;">
			<div class="forumplatform" style="border-top-left-radius: 20px;border-top-right-radius: 20px;float:left;width:800px;">
				 <table>
					 <tr style="border-top-left-radius: 20px;border-top-right-radius: 20px;">
						<th style="border-top-left-radius: 20px;">Category</th>
						<th>Threads</th>
						<th>Posts</th>
						<th style="border-top-right-radius: 20px;">Most Recent</th>
					 </tr>
					<?php
						$stmt = $conn->prepare("SELECT * FROM forum_categories;");
						$stmt->execute();
						while ($row = $stmt->fetch()) {
							$stmt2 = $conn->prepare("SELECT * FROM forum_threads WHERE CATEGORY_ID = ? ORDER BY UPDATED DESC LIMIT 1;");
							$stmt2->bindParam(1, $row[0], PDO::PARAM_STR);
							$stmt2->execute();
							$RECENT = $stmt2->fetch();
							if($RECENT) {
								$stmt3 = $conn->prepare("SELECT * FROM users WHERE ID=?");
								$stmt3->bindParam(1, $RECENT['USER_ID'], PDO::PARAM_STR);
								$stmt3->execute();
								$tuser = $stmt3->fetch();
							}
							echo '<tr><td><a href="/forums/category/' . $row[0] . '"><div><b>' . $row[1] . '</b><br>' . $row[2] . '</div></a></td><td>' . $row['THREADS'] . '</td><td>' . $row['POSTS'] . '</td><td>';
							if ($RECENT) {
								echo '<div><a href="/forums/thread/' . $RECENT[0] . '"><b>' . $RECENT[1] . '</b></a><br> by <b><a href="/user/profile/' . $RECENT['USER_ID'] . '">' . $tuser[1] . '</a></b></div>'; 
							};
							echo '</td></tr>';
						}
					?>
				</table>			
			</div>
			<div class="forumplatform" style="width:500px;border-top-left-radius: 20px;border-top-right-radius: 20px;float:right;">
				 <table>
					 <tr style="border-top-left-radius: 20px;border-top-right-radius: 20px;">
						<th style="border-top-left-radius: 20px;">Thread</th>
						<th style="border-top-right-radius: 20px;">Replies</th>
					 </tr>
					<?php
						$stmt = $conn->prepare("SELECT * FROM forum_threads ORDER BY ID DESC LIMIT 5;");
						$stmt->execute();
						while ($row = $stmt->fetch()) {
							$stmt2 = $conn->prepare("SELECT * FROM users WHERE ID=?");
							$stmt2->bindParam(1, $row['USER_ID'], PDO::PARAM_STR);
							$stmt2->execute();
							$tuser = $stmt2->fetch();
							echo '<tr><td><div><a href="/forums/thread/' . $row[0] . '"><b>' . $row[1] . '</b></a><br> by <b><a href="/user/profile/' . $tuser[0] . '">' . $tuser[1] . '</a></b></div></td><td>' . $row['REPLIES'] . '</td></tr>';
						}
					?>
				</table> 
			</div>	
		</div>
		</div>
		</center>
		</div>
	</body>
</html>