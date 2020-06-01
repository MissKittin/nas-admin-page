<?php
	/* PowerTop wrapper */

	include('functions-login.php');

	shell_exec('/usr/sbin/powertop --html="/tmp/powertop.log"');

	$file=shell_exec('/bin/ls /tmp/powertop-*.html | tr --delete "\n"');
	$rfile = fopen($file, "r") or die('Unable to open file!');
	echo fread($rfile,filesize($file));
	fclose($rfile);

	shell_exec('/bin/rm /tmp/powertop-*.html');
?>