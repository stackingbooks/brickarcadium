<?php
$docr = $_SERVER["DOCUMENT_ROOT"];
require $docr . '/misc/connect.php';
require $docr . '/misc/user.php';

if($user["RANK"] !== "ADMIN" && $user["RANK"] !== "MOD") {
	http_response_code(403);
	include($docr. '/errors/403.php');
	die();
}


if( empty($_GET["hatid"]) && empty($_POST["hatid"])) {
	header("Location: /");
	die();
}

if (!empty($_POST["img"]) && !empty($_POST["hatid"])) {
		$lol = $_POST["hatid"];
		$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST["img"]));	
		$png = fopen($docr . "/imgs/previews/" . $lol . ".png", "w");
		fwrite($png, $data);
		fclose($png);
		$path = "/imgs/previews/" . $lol . ".png?" . time();
		$stmt = $conn->prepare("UPDATE items SET PREVIEWIMG=? WHERE ID=?");
		$stmt->bindParam(1, $path, PDO::PARAM_STR);
		$stmt->bindParam(2, $lol, PDO::PARAM_STR);
		$stmt->execute();
		header("Location: ". $path);
		die();
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

const larmcolor = "#ffffff";
const rarmcolor = "#ffffff";
const llegcolor = "#ffffff";
const rlegcolor = "#ffffff";
const headcolor = "#ffffff";
const torsocolor = "#ffffff";

const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
renderer.setPixelRatio(1.6);
renderer.setSize( 470, 500 );
document.body.appendChild( renderer.domElement );

camera.position.z = 5;

const loader = new GLTFLoader();

let pfpcamera;

loader.load( 'stuff/hatcam.glb', function ( gltf ) {
	scene.add( gltf.scene );
	pfpcamera = gltf.cameras[0];
})

EOT;


	$stmt = $conn->prepare("SELECT * FROM items WHERE ID=?");
	$stmt->bindParam(1, $_GET["hatid"], PDO::PARAM_INT);
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

echo <<<EOT
loader.load( 'stuff/Character.glb', function ( gltf ) {

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
const texture = new THREE.TextureLoader().load('stuff/Face.png' ); 
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
 	camera = pfpcamera;

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
if (counter === 130) {
	dataURL = canvas.toDataURL("image/png");
	document.getElementById('img').value = dataURL;
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
<textarea type="hidden" id="hatid" name="hatid" style="display:none;">
EOT;
echo $_GET["hatid"];
echo <<<EOT
</textarea>
    <textarea type="hidden" id="img" name="img" style="display:none;"></textarea>
</form>
</body>
</html>

EOT;


};

?>



