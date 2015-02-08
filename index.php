<?php
session_start();
include 'authentication.php';
validateUser();

if(!session_id()) session_start();


//
// open file containing cameras
//

$cameras = file($_SERVER['CAMERA_DIRECTORY']);

if ( empty($cameras) )
{
	echo "Error, could not load camera file.\n";
	echo "<br/><br/><br/>";
	echo "Please check configuration\n";	
	return;
}

$actualCameras = array();
foreach($cameras as $camera)
{
	if($camera[0] != '#' ) {
		$actualCameras[] = $camera;
	}	
}

if ( empty($actualCameras) )
{
	echo "Error, could not find any cameras in camera file.\n";
	echo "<br/><br/><br/>";
	echo "Please check configuration\n";	
	return;
}

//
// Iterate through the cameras
//
$camerasAndDirectories = array();
foreach($actualCameras as $camera)
{
	$cameraName = trim(strtok( $camera, ","));
	strtok( ","); // Skip past the events directory
	$videoSegments = trim(strtok( ","));
	//print_r( "Camera: ".$cameraName."  Directory: ".$videoSegments) ;
	//echo "<br/><br/>";
	
	if ( empty($cameraName) || empty($videoSegments) )
	{
		echo "Error, could not parse both camera name and video file directory.\n";
		echo "<br/><br/><br/>";
		echo "Please check configuration\n";
		return;
	}
	
	$camerasAndDirectories[][] = array($cameraName, $videoSegments);
}

echo "Cameras to Click On<br/><br/>";
foreach($camerasAndDirectories as $camera)
{
	echo "<a href=\"displayEvents.php"."?camera=".$camera[0][0]."\">".$camera[0][0]."</a><br/><br>";
	
}

echo "<a href=\"displayEvents.php"."?camera="."BadCamera"."\">"."BadCamera"."</a><br/><br>";


$_SESSION['camerasAndDirectories'] = $camerasAndDirectories;

echo "<br/>Session Id: ".session_id();


//print_r( get_defined_vars () );


?>