<?php
	error_reporting(E_ALL & ~E_NOTICE);
	if (!empty($user)) {
		$stmt = $conn->prepare("UPDATE users SET LAST_ONLINE=? WHERE ID=?");
		$stmt->bindParam(1, time(), PDO::PARAM_INT);
		$stmt->bindParam(2, $user[0], PDO::PARAM_STR);
		$stmt->execute();
		echo '<ul class="navul">
				<li class="two" style="height: 56px; !important;">
					<div class="two">
						<a href="/user/home" class="navli nava"><i class="fas fa-home"></i> Home</a>
						<a href="/user/list" class="navli nava"><i class="fas fa-users"></i> Userlist</a>
						<a href="/store" class="navli nava"><i class="fas fa-store"></i> Store</a>
						<a href="/forums" class="navli nava"><i class="fas fa-comments-alt"></i> Forums</a>
						<a href="/avatar" class="navli nava"><i class="fas fa-user-edit"></i> Avatar</a>';
						
					if ($user["RANK"] == "ADMIN" or $user["RANK"] == "MOD") {
						echo '<a href="/staff/panel" class="navli nava"><i class="fas fa-gavel"></i> Panel</a>';
					}
				echo '
					</div>
				</li>
				<li style="float: right !important; margin-right: 60px;" class="navli nava"><p  class="navli"> <div class="info"> <a href="/user/profile/' . $user[0] . '">' . $user[1] . '</a> <div class="dropdown">
				  <script>
					var isdrop = false;
					function dropdown() {
						var dropdown = document.querySelector(".dropdown:hover .dropdown-content");
						if (isdrop == false) {
							isdrop = true;
							dropdown.style = "display: block;";
						} else {
							isdrop = false;
							dropdown.style = "";
						}
					}
				  </script>
				  <button class="dropbtn" onclick="dropdown()"><i class="fas fa-chevron-down"></i></button>
				  <div class="dropdown-content">
					<a href="/user/settings">Settings</a>
					<a href="/user/profile/' . $user[0] . '">My Profile</a>
					<a href="/logout">Logout</a>
				  </div>
				</div>&nbsp;|&nbsp;&nbsp;<i class="far fa-money-bill-wave"></i> &nbsp;'. $user['BUX'] .' &nbsp;<i class="far fa-coins"></i> &nbsp;'. $user['COINS'] .'</p></div></li>
			</ul>
			<div style="padding:20px;margin-top:30px;overflow-x: hidden; height: 89%;">';
			if ($user["RANK"] == "USER") {
				echo "<script disable-devtool-auto src='https://cdn.jsdelivr.net/npm/disable-devtool'></script>
				<script>console.log('Congrats. If you see this, you have beaten my security. I do not even care.')</script>";
			}
	} else {
		echo '<ul class="navul">
				<li class="two" style="height: 56px; !important;">
					<div class="two">
						<a href="/" class="navli nava"><i class="fas fa-home"></i> Home</a>
						<a href="/user/list" class="navli nava"><i class="fas fa-users"></i> Userlist</a>
						<a href="/store" class="navli nava"><i class="fas fa-store"></i> Store</a>
						<a href="/forums" class="navli nava"><i class="fas fa-comments-alt"></i> Forums</a>
					</div>
				</li>
				<li style="float: right !important; margin-right: 60px;margin-top:4px;" class="navli nava"><p  class="navli"> <a href="/login">Login</a> | <a href="/register">Register</a></p></li>
			</ul>
			<div style="padding:20px;margin-top:30px;overflow-x: hidden; height: 89%;">
			<script disable-devtool-auto src="https://cdn.jsdelivr.net/npm/disable-devtool"></script>
			<script>console.log("Congrats. If you see this, you have beaten my security. I do not even care.")</script>';
	}
?>