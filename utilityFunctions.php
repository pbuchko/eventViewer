<?php

function getCameraArray() {
	
	//
	// open file containing cameras
	//
	$cameras = file ( $_SERVER ['CAMERA_DIRECTORY'] );
	
	if (empty ( $cameras )) {
		echo "Error, could not load camera file.\n";
		echo "<br/><br/><br/>";
		echo "Please check configuration\n";
		return;
	}
	
	$actualCameras = array ();
	foreach ( $cameras as $camera ) {
		if ($camera [0] != '#') {
			$actualCameras [] = $camera;
		}
	}
	
	if (empty ( $actualCameras )) {
		echo "Error, could not find any cameras in camera file.\n";
		echo "<br/><br/><br/>";
		echo "Please check configuration\n";
		return;
	}
	
	$camerasAndDirectories = array ();
	foreach ( $actualCameras as $camera ) {
		$cameraName = trim ( strtok ( $camera, "," ) );
		strtok ( "," ); // Skip past the events directory
		$videoSegments = trim ( strtok ( "," ) );
		// print_r( "Camera: ".$cameraName." Directory: ".$videoSegments) ;
		// echo "<br/><br/>";
		
		if (empty ( $cameraName ) || empty ( $videoSegments )) {
			echo "Error, could not parse both camera name and video file directory.\n";
			echo "<br/><br/><br/>";
			echo "Please check configuration\n";
			return;
		}
		
		$camerasAndDirectories [] [] = array (
				$cameraName,
				$videoSegments 
		);
	}
	
	return $camerasAndDirectories;
}

function getFilesForCamera($camerasAndDirectories, $selectedCamera) {
	
	
	$directory = getCameraDirectory($camerasAndDirectories, $selectedCamera);
	
	if (empty ( $directory )) {
		echo "Error resolving directory for camera recordings.\n";
		echo "<br/><br/><br/>";
		echo "Please check configuration\n";
		return;
	} 
	
	$files1 = scandir ( $directory );
	
	return $files1;
}

function getCameraDirectory($camerasAndDirectories, $selectedCamera)
{
	
	$directory = null;
	foreach ( $camerasAndDirectories as $camera ) {
		if ($camera [0] [0] == $selectedCamera) {
			$directory = $camera [0] [1];
		}
	}
	
	return $directory;
	
	
	
} 