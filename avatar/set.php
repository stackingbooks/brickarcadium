<?php
$docr = $_SERVER["DOCUMENT_ROOT"];
require $docr . '/misc/connect.php';
require $docr . '/misc/user.php';

if (!empty($_GET["head"]) && !empty($_GET["larm"]) && !empty($_GET["rarm"]) && !empty($_GET["lleg"]) && !empty($_GET["rarm"]) && !empty($_GET["torso"])) {
	$headcolor = "#" . $_GET["head"];
	$torsocolor = "#" . $_GET["torso"];
	$larmcolor = "#" . $_GET["larm"];
	$rarmcolor = "#" . $_GET["rarm"];
	$llegcolor = "#" . $_GET["lleg"];
	$rlegcolor = "#" . $_GET["rleg"];
	$stmt = $conn->prepare("UPDATE avatars SET HEADC=?, TORSOC=?, LARMC=?, RARMC=?, LLEGC=?, RLEGC=? WHERE USER_ID=?");
	$stmt->bindParam(1, $headcolor, PDO::PARAM_STR);
	$stmt->bindParam(2, $torsocolor, PDO::PARAM_STR);
	$stmt->bindParam(3, $larmcolor, PDO::PARAM_STR);
	$stmt->bindParam(4, $rarmcolor, PDO::PARAM_STR);
	$stmt->bindParam(5, $llegcolor, PDO::PARAM_STR);
	$stmt->bindParam(6, $rlegcolor, PDO::PARAM_STR);
	$stmt->bindParam(7, $user[0], PDO::PARAM_STR);
	$stmt->execute();
	$stmt = $conn->prepare("UPDATE users SET ISRENDER=1 WHERE ID=?");
	$stmt->bindParam(1, $user[0], PDO::PARAM_INT);
	$stmt->execute();
	header("Location: /misc/rendering/render");
	exit();
} else {
	echo "<img src='" . $user["AVATARIMG"] . "' style='height:100%;width:auto;'>";
}
?>