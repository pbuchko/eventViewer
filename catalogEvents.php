<?php
session_start ();
include 'authentication.php';
include 'utilityFunctions.php';
validateUser ();

// Read the object

// temp set server var to work
//$_SERVER ['CAMERA_DIRECTORY'] = "/Users/paulbuchkowski/onvif/mailserver/cleanUpVideoFiles/cleanUpVideoFiles/resources/cameras";

$eventsToFiles = array ();

$filePath = getcwd () . DIRECTORY_SEPARATOR . "persistedEvents";
if (file_exists ( $filePath )) {
	$objData = file_get_contents ( $filePath );
	$eventsToFiles = unserialize ( $objData );
}

$camerasAndDirectories = getCameraArray ();


foreach ( $camerasAndDirectories as $camera ) {
	
	echo "<br/>Processing Camera: ".$camera [0] [0] ."<br/>";
	
	$directory = getCameraDirectory($camerasAndDirectories, $camera [0] [0]);
	
	
	$files1 = getFilesForCamera ( $camerasAndDirectories, $camera [0] [0] );
	
	foreach ( $files1 as $file ) {
		
		//
		// if no events recorded yet, extract them
		//
		if (empty ( $eventsToFiles [$file] )) {
			$mkvinfoCommand = "mkvinfo " . $directory . "/" . $file . " | grep -i title";
			
			$eventsFromTitle = exec ( $mkvinfoCommand );
			
			$eventsToFiles [$file] = $eventsFromTitle;
			}
	}
}

// Write the object

$objData = serialize ( $eventsToFiles );
$filePath = getcwd () . DIRECTORY_SEPARATOR . "persistedEvents";
//if (is_writable ( $filePath )) {
	$fp = fopen ( $filePath, "w" );
	fwrite ( $fp, $objData );
	fclose ( $fp );
//} else {
//	echo "Could not write event file";
//	return;
//}

echo "<br/><br/>Wrote array: ".print_r($eventsToFiles)."<br/><br/>";
