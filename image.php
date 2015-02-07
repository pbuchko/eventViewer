<?php

session_start();



//
// If this session does not have the camerasAndDirectories set then it never ran the login page, redirect
//

if ( ! isset($_SESSION["camerasAndDirectories"]) )
{
	//header("Location: index.php");
	return;
}

if ( empty($_GET['camera']) )
{
	echo "play video called without passing in a camera name.\n";
	echo "<br/><br/><br/>";
	echo "Please check configuration\n";
	return;
}

if ( empty($_GET['file']) )
{
	echo "play video called without passing in a file name.\n";
	echo "<br/><br/><br/>";
	echo "Please check configuration\n";
	return;
}
$file = $_GET['file'];


$camerasAndDirectories = $_SESSION['camerasAndDirectories'];

$directory = null;
foreach($camerasAndDirectories as $camera)
{
	if ( $camera[0][0] == $_GET['camera'] )
	{
		$directory = $camera[0][1];
	}
}

//echo "File to play: ".$directory."/".$file."<br/>";

session_write_close();

header('Content-Type: video/x-matroska');
  //readfile($_GET['file']); */
  
readfile($directory."/".$file);

exit();

  //readfile("/Users/paulbuchkowski/onvif/mailserver/sampleFiles/2015012017PatioNorth00006.mkv");
  ?>