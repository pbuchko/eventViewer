<?php
session_start ();

//
// If this session does not have the camerasAndDirectories set then it never ran the login page, redirect
//

if (! isset ( $_SESSION ["camerasAndDirectories"] )) {
	header ( "Location: index.php" );
	return;
}

if (empty ( $_GET ['camera'] )) {
	echo "display events called without passing in a camera name.\n";
	echo "<br/><br/><br/>";
	echo "Please check configuration\n";
	return;
}

$camerasAndDirectories = $_SESSION ['camerasAndDirectories'];
$directory = null;
foreach ( $camerasAndDirectories as $camera ) {
	if ($camera [0] [0] == $_GET ['camera']) {
		$directory = $camera [0] [1];
	}
}

if (empty ( $directory )) {
	echo "Error resolving directory for camera recordings.\n";
	echo "<br/><br/><br/>";
	echo "Please check configuration\n";
	return;
}

$files1 = scandir ( $directory );
$relativeDirectory = str_replace ( getcwd () . "/", "", $directory ) . "/";

//
// Cache event mapping to file in the session
//

$eventsToFiles = $_SESSION ['eventsToFiles'];
if (empty ( $eventsToFiles )) {
	$eventsToFiles = array ();
}

echo "<table border=\"0\">";
echo "  <col width=\"230\">";
echo "  <col width=\"80\">";

foreach ( $files1 as $file ) {
	//
	// Check if the filename has the camera name
	//
	
	if ((strpos ( strtolower ( $file ), strtolower ( $_GET ['camera'] ) ) !== false) && strpos ( strtolower ( $file ), ".mkv" ) !== false) {
		$size = filesize ( $directory . "/" . $file );
		$sz = 'BKMGTP';
		$factor = floor ( (strlen ( $size ) - 1) / 3 );
		$humanSize = sprintf ( "%.2f", $size / pow ( 1024, $factor ) ) . @$sz [$factor];
		
		//
		// Pull the video title to display the event times
		//
		
		if ( empty($eventsToFiles [$file]) ) {
			$mkvinfoCommand = "mkvinfo " . $directory . "/" . $file . " | grep -i title";
			
			$eventsFromTitle = exec ( $mkvinfoCommand );
			
			$eventsToFiles [$file] = $eventsFromTitle;
			
		} else {
			$eventsFromTitle = $eventsToFiles [$file];
		}
		
		//
		// Reverse the datetime of the segment from the filename
		//
		
		$ymd = DateTime::createFromFormat('YmdH i:s', substr($file, 0, 10)." 00:00");
		$fiveMinuteChunks = intval(substr($file, -6, 2));
		$fiveMinuteChunks = $fiveMinuteChunks*5;
		$ymd->add(new DateInterval("PT".$fiveMinuteChunks. "M"));
		
		$formatedDate = $ymd->format('M jS l H:i');
		
		
		
		
		
		echo "<tr>";
		
		//echo "<td>" ."<input type=\"checkbox\" name=\"myTextEditBox\"  />". "<a href=\"playVideo.php" . "?file=" . $file . "&camera=".$_GET ['camera']."&events=".$eventsFromTitle."\">" . $file . "</a></td>";
		echo "<td>" ."<input type=\"checkbox\" name=\"myTextEditBox\"  />". "<a href=\"playVideo.php" . "?file=" . $file . "&camera=".$_GET ['camera']."&events=".$eventsFromTitle."\">" . $formatedDate . "</a></td>";
		echo "<td>" . $humanSize . "</td>";
		//echo "<td>".$formatedDate."</td>";
		echo "<td>" . $eventsFromTitle . "</td>";
		
		echo "</tr>";
	}
}
echo "</table>";

$_SESSION ['eventsToFiles'] = $eventsToFiles;

?>


