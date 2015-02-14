<?php
session_start();
include 'authentication.php';
include 'utilityFunctions.php';
validateUser();

if(!session_id()) session_start();



//
// Iterate through the cameras
//

$camerasAndDirectories = getCameraArray();


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