<?php

$size = $_GET['size'];
$speed = $_GET['speed'];
$note = isset($_GET['note']) ? $_GET['note'] . '-': 'log-';
header("Content-Length: $size");
header("Content-Type: video/mp4");
header("Content-Disposition: attachment; filename=\"big-$size-byte-file.mp4\";");

if ($_SERVER['REQUEST_METHOD'] == "HEAD") {
	$h = fopen('slow/' . $note . time() . '-' . rand(100000, 999999) . '.head', 'w');
	fputs($h, 'head request');
	fclose($h);
} else {
	$sent = 0;
	$fn = 'slow/' . $note . time() . '-' . rand(100000,999999) . '.txt';
	$h = fopen($fn, 'w');
	while ($sent + $speed <= $size) {
		echo str_repeat('x', $speed);
		$sent += $speed;
		ob_flush();
		flush();
		fputs($h, "Send $speed bytes ($sent of $size) " . $_SERVER['REQUEST_METHOD'] . " " . $note . "\r\n");
		sleep(1);
	}
	echo str_repeat('x',  $size - $sent);
}

