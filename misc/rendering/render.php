<?php
$docr = $_SERVER["DOCUMENT_ROOT"];
require $docr . '/misc/connect.php';
require $docr . '/misc/user.php';

$stmt = $conn->prepare("SELECT * FROM avatars WHERE USER_ID=?");
$stmt->bindParam(1, $user[0], PDO::PARAM_INT);
$stmt->execute();
$avatar = $stmt->fetch();

if ($user['ISRENDER'] == 1) {
	$stmt = $conn->prepare("UPDATE users SET ISRENDER=2 WHERE ID=?");
	$stmt->bindParam(1, $user[0], PDO::PARAM_STR);
	$stmt->execute();
}
if (!empty($_POST["img"])) {
	if ($user["ISRENDER"] == 2) {
		$stmt = $conn->prepare("UPDATE users SET ISRENDER=0 WHERE ID=?");
		$stmt->bindParam(1, $user[0], PDO::PARAM_STR);
		$stmt->execute();
		$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST["img"]));	
		$png = fopen($docr . "/imgs/avatars/" . $user[0] . ".png", "w");
		fwrite($png, $data);
		fclose($png);
		$path = "/imgs/avatars/" . $user[0] . ".png?" . time();
		$stmt = $conn->prepare("UPDATE users SET AVATARIMG=? WHERE ID=?");
		$stmt->bindParam(1, $path, PDO::PARAM_STR);
		$stmt->bindParam(2, $user[0], PDO::PARAM_STR);
		$stmt->execute();
		$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST["img2"]));	
		$png = fopen($docr . "/imgs/headshots/" . $user[0] . ".png", "w");
		fwrite($png, $data);
		fclose($png);
		$path = "/imgs/headshots/" . $user[0] . ".png?" . time();
		$stmt = $conn->prepare("UPDATE users SET HEADSHOTIMG=? WHERE ID=?");
		$stmt->bindParam(1, $path, PDO::PARAM_STR);
		$stmt->bindParam(2, $user[0], PDO::PARAM_STR);
		$stmt->execute();
		header("Location: /avatar/set");
		die();
	}
} else {

echo <<<EOT
<script type="importmap">
  {
"imports": {
  "three": "https://unpkg.com/three@0.161.0/build/three.module.js",
  "three/addons/": "https://unpkg.com/three@0.161.0/examples/jsm/"
}
  }
</script>
<script type="module">
import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
const scene = new THREE.Scene();
var camera = new THREE.PerspectiveCamera( 111, window.innerWidth / window.innerHeight, 0.1, 1000 );

const larmcolor = "
EOT;
echo $avatar["LARMC"];
echo <<<EOT
";
const rarmcolor = "
EOT;
echo $avatar["RARMC"];
echo <<<EOT
";
const llegcolor = "
EOT;
echo $avatar["LLEGC"];
echo <<<EOT
";
const rlegcolor = "
EOT;
echo $avatar["RLEGC"];
echo <<<EOT
";
const headcolor = "
EOT;
echo $avatar["HEADC"];
echo <<<EOT
";
const torsocolor = "
EOT;
echo $avatar["TORSOC"];
echo <<<EOT
";

const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
renderer.setPixelRatio(1.6);
renderer.setSize( 470, 500 );
document.body.appendChild( renderer.domElement );

camera.position.z = 5;

const loader = new GLTFLoader();

let pfpcamera;

loader.load( 'stuff/headshotcam.glb?lol', function ( gltf ) {
	scene.add( gltf.scene );
	pfpcamera = gltf.cameras[0];
})

EOT;

if ($avatar["HAT"] !== 0) {
	
	$stmt = $conn->prepare("SELECT * FROM items WHERE ID=?");
	$stmt->bindParam(1, $avatar["HAT"], PDO::PARAM_INT);
	$stmt->execute();
	$hat = $stmt->fetch();
	
	echo <<<EOT

	loader.load( '
	EOT;
	echo $hat["GLB"];
	echo <<<EOT
		', function ( gltf ) {

		scene.add( gltf.scene );
		const texture = new THREE.TextureLoader().load('
		EOT;
		echo $hat["TEXTURE"];
		echo <<<EOT
		' ); 
		texture.flipY = false;
		const hatmaterial = new THREE.MeshPhongMaterial( {
			map: texture,
			side: THREE.DoubleSide
		} );
		gltf.scene.traverse( function( child ) {
			child.material = hatmaterial;
		} );
	} );


	EOT;
}

if ($avatar["HAT_2"] !== 0) {
	
	$stmt = $conn->prepare("SELECT * FROM items WHERE ID=?");
	$stmt->bindParam(1, $avatar["HAT_2"], PDO::PARAM_INT);
	$stmt->execute();
	$hat = $stmt->fetch();
	
	echo <<<EOT

	loader.load( '
	EOT;
	echo $hat["GLB"];
	echo <<<EOT
		', function ( gltf ) {

		scene.add( gltf.scene );
		const texture = new THREE.TextureLoader().load('
		EOT;
		echo $hat["TEXTURE"];
		echo <<<EOT
		' ); 
		texture.flipY = false;
		const hatmaterial = new THREE.MeshPhongMaterial( {
			map: texture
		} );
		gltf.scene.traverse( function( child ) {
			child.material = hatmaterial;
		} );
	} );


	EOT;
}
echo <<<EOT
loader.load( 'stuff/Character.glb?idk', function ( gltf ) {

scene.add( gltf.scene );
camera = gltf.cameras[0];
const light = new THREE.AmbientLight( 0x404040, 20 ); // soft white light
gltf.scene.add( light );
const directionalLight = new THREE.DirectionalLight( 0xffffff, 2 );
scene.add( directionalLight );
gltf.scene.traverse( child => {

if ( child.material ) child.material.metalness = 0;

} );
let facematerial;
let headmaterial;
let torsomaterial;
let larmmaterial;
let rarmmaterial;
let llegmaterial;
let rlegmaterial;
const loader = new THREE.TextureLoader();

// load a resource
const texture = new THREE.TextureLoader().load('
EOT;

if ($avatar["FACE"] !== 0) {
	
	$stmt = $conn->prepare("SELECT * FROM items WHERE ID=?");
	$stmt->bindParam(1, $avatar["FACE"], PDO::PARAM_INT);
	$stmt->execute();
	$hat = $stmt->fetch();
	
	echo $hat["TEXTURE"];
} else {
	echo "stuff/Face.png";
}

echo <<<EOT
' ); 
texture.flipY = false;
facematerial = new THREE.MeshPhongMaterial( {
map: texture,
transparent: true
} );
headmaterial = new THREE.MeshPhongMaterial({
	color: new THREE.Color(headcolor)
});
larmmaterial = new THREE.MeshPhongMaterial({
	color: new THREE.Color(larmcolor)
});
rarmmaterial = new THREE.MeshPhongMaterial({
	color: new THREE.Color(rarmcolor)
});
llegmaterial = new THREE.MeshPhongMaterial({
	color: new THREE.Color(llegcolor)
});
rlegmaterial = new THREE.MeshPhongMaterial({
	color: new THREE.Color(rlegcolor)
});
torsomaterial = new THREE.MeshPhongMaterial({
	color: new THREE.Color(torsocolor)
});

gltf.scene.traverse( function( child ) {
child.material = headmaterial;
if ( child.name === "Head") {
var head = child.clone();
child.material = facematerial;

head.material = headmaterial;
scene.add(head);
}
if ( child.name === "LLeg") {
child.material = llegmaterial;
}
if ( child.name === "RLeg") {
child.material = rlegmaterial;
}
if ( child.name === "LArm") {
child.material = larmmaterial;
}
if ( child.name === "RArm") {
child.material = rarmmaterial;
}
if ( child.name === "Torso") {
child.material = torsomaterial;
}
 } );
 


}, undefined, function ( error ) {

console.error( error );

} );

var dataURL;
var counter = 0;
async function animate() {
requestAnimationFrame( animate );
await renderer.render( scene, camera );
const canvas = document.querySelector('canvas');
counter++;
if (typeof dataURL === 'undefined' && counter === 100) {
	dataURL = canvas.toDataURL("image/png");
	document.getElementById('img').value = dataURL;
	camera = pfpcamera;
}
if (counter === 120) {
	dataURL = canvas.toDataURL("image/png");
	document.getElementById('img2').value = dataURL;
	document.forms[0].submit();
}
}
animate();
</script>

<html>
<head>
<meta charset="utf-8">
<title>Render testing</title>
<style>
body { margin: 0; }
</style>
<script type="importmap">
  {
"imports": {
  "three": "https://unpkg.com/three@0.160.0/build/three.module.js",
  "three/addons/": "https://unpkg.com/three@0.160.0/examples/jsm/"
}
  }
</script>
</head>
<body>
<img src="/imgs/render.png" style="height:100%;width:auto;">
<form action="" method="POST" id="fdsfsdfsdfy">
    <textarea type="hidden" id="img" name="img" style="display:none;"></textarea>
	<textarea type="hidden" id="img2" name="img2" style="display:none;"></textarea>
</form>
</body>
</html>

EOT;


};

?>



