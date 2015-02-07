<?php
session_start();


//
// If this session does not have the camerasAndDirectories set then it never ran the login page, redirect
//

if ( ! isset($_SESSION["camerasAndDirectories"]) )
{
	header("Location: index.php");
	return;
}

if ( empty($_GET['camera']) )
{
	echo "display events called without passing in a camera name.\n";
	echo "<br/><br/><br/>";
	echo "Please check configuration\n";
	return;
}


$camerasAndDirectories = $_SESSION['camerasAndDirectories'];
$directory = null;
foreach($camerasAndDirectories as $camera)
{
	if ( $camera[0][0] == $_GET['camera'] )
	{
		$directory = $camera[0][1];
	}
}	

if ( empty($directory) )
{
	echo "Error resolving directory for camera recordings.\n";
	echo "<br/><br/><br/>";
	echo "Please check configuration\n";
	return;
}


$files1 = scandir($directory);
$relativeDirectory = str_replace(getcwd ( )."/", "", $directory)."/";


foreach($files1 as $file)
{
	//
	// Check if the filename has the camera name
	//
	if ( ( strpos(strtolower($file), strtolower($_GET['camera']) ) !== false ) &&
			strpos(strtolower($file),".mkv") !== false )
	{
		$size = filesize($directory."/".$file);
		$sz = 'BKMGTP';
		$factor = floor((strlen($size) - 1) / 3);
		$humanSize = sprintf("%.2f", $size / pow(1024, $factor)) . @$sz[$factor];
				
		echo "<a href=\"playVideo.php"."?file=".$file."&camera=".$_GET['camera']."\">".$file."  ".$humanSize."</a><br/><br>";
		
		
	}
		
}


?>


