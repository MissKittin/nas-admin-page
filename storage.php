<?php
	/* Storage center */

	include('functions-login.php');
	include('functions-system.php'); //sync

	function gettextf($file)
	{
		$rfile = fopen($file, "r") or die('Unable to open file!</pre></div></body></html>');
		echo fread($rfile,filesize($file));
		fclose($rfile);
	}

	$MESSAGE=''; //initialize
	if(isset($_GET['mountpoint']) && isset($_GET['action']))
		switch($_GET['action'])
		{
			case 'mount':
				$MESSAGE=shell_exec('/bin/mount /media/' . $_GET['mountpoint'] . ' 2>&1');
				break;
			case 'umount':
				$MESSAGE=shell_exec('/bin/umount /media/' . $_GET['mountpoint'] . ' 2>&1');
				break;
		}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $_SESSION['logged_user']; ?>@<?php echo $_SERVER['HTTP_HOST']; ?> mount</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="bars.css">
		<?php include('./favicons/favicon-header.php'); ?>
		<style type="text/css">
			body {
				margin: 0;
				padding: 0;
				background-color: #dddddd;
			}
			.content {
				margin: 5px;
				padding: 5px;
			}
			a, a:hover, a:visited {
				text-decoration: none;
				color: #0000ff;
			}
			table {
				border: 1px solid #000000;
			}
		</style>
	</head>
	<body>
		<div class="content">
			<h1>Storage</h1>
<!-- tables -->
			<table style="border: none;"><tr><td style="vertical-align: top;">
				<table>
					<tr><th>Mount point</th><th>Action</th></tr>
					<?php echo shell_exec('./shell.sh check_mountpoints'); ?>
				</table></td><td style="vertical-align: top;">
				<table>
					<tr><th>Disk/Part</th><th>Size</th><th>Used</th><th>Avail</th><th>Dev</th><th>Percentage</th><th>Used</th></tr>
					<?php echo shell_exec('./shell.sh disk_usage'); ?>
				</table></td></tr>
			</table>
			<table style="border: none;"><tr><td style="vertical-align: top;">
				<table>
					<tr><th>Disk</th><th>Status</th><th>Gov</th><th>Temp</th><th style="text-align: right;">Model</th><th>Chn</th></tr>
					<?php echo shell_exec('./shell.sh disks_status'); ?>
				</table></td></tr>
			</table>

			<table style="border: none;"><tr><td style="vertical-align: top;">
			<table>
				<tr><th>TmpFs</th><th>Size</th><th>Used</th><th>Avail</th><th>Percentage</th><th>Used</th></tr>
				<?php echo shell_exec('./shell.sh disk_usage tmp nodev'); ?>
				<?php echo shell_exec('./shell.sh disk_usage udev nodev'); ?>
				<?php echo shell_exec('./shell.sh disk_usage log nodev'); ?>
				<?php echo shell_exec('./shell.sh disk_usage spool nodev'); ?>
				<?php echo shell_exec('./shell.sh disk_usage homes nodev'); ?>
			</table>
			</table>
<!-- messages - todo: test -->
			<?php if($MESSAGE != '') echo '<div><h4>Message:</h4> ' , $MESSAGE , ' </div>'; ?>
			<form action="storage.php" method="post">
				<input type="submit" name="useraction" value="Commit disk buff">
			</form>
			<span><a href="..">Back</a></span>
			<hr>
<!-- infos -->
			<h3>mount</h3>
<pre><?php echo shell_exec('/bin/mount'); ?></pre>
			<h3>fstab</h3>
<pre><?php echo shell_exec('cat /etc/fstab | sed -e "s/</(/g" | sed -e "s/>/)/g"'); ?></pre>
			<h3>diskfree</h3>
<pre><?php echo shell_exec('/bin/df'); ?></pre>
			<span><a href="..">Back</a></span>
		</div>
	</body>
</html>