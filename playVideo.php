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
	echo "play video called without passing in a camera name.\n";
	echo "<br/><br/><br/>";
	echo "Please check configuration\n";
	return;
}
$InputCamera = $_GET['camera'];

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

//
// At this point we have the file directory from the session and file name passed in.
//


echo "<table>";
echo "<tr>";
echo "<td>";
echo "<video class=\"video\" autoplay controls><source src=\"image.php?camera=".$InputCamera."&file=".$file."\"></video>";
echo "</td>.<td align=\"center\">".$_GET['events']."</td>";
echo "</tr>";
echo "</table>";


exit();
