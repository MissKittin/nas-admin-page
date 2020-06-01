<?php
	/* lshw wrapper */

	include('functions-login.php');

	echo shell_exec('/usr/bin/lshw -html');
?>