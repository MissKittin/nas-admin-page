<?php
	/* Logs reader */

	include('functions-login.php');

	if(!isset($_GET['log']) && !isset($_GET['cmd']))
	{
		echo '<!DOCTYPE html>
			<html>
				<head>
					<title>'.$_SERVER["HTTP_HOST"].'</title>
					<meta http-equiv="refresh" content="0; url=.">
				</head>
			</html>
		';
		exit();
	}

	function getlog($file)
	{
		$rfile = fopen($file, "r") or die('Unable to open file!</pre></div></body></html>');
		echo fread($rfile,filesize($file));
		fclose($rfile);
	}

	// docs extension
	if(isset($_GET['fileopen']))
		if($_GET['fileopen'] === 'cat')
		{
			if($_GET['fileopen_method'] === 'binary') // send files in binary mode
			{
				header('Content-Disposition: attachment; filename='.$_GET['file'].';');
				header('Content-Transfer-Enconding: binary');
			}
			getlog('/home/userhome/docs/' . $_GET['file']);
			exit();
		}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $_SESSION['logged_user']; ?>@<?php echo $_SERVER['HTTP_HOST']; ?> logs</title>
		<meta charset="utf-8">
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
		</style>
	</head>
	<body>
		<div id="log" class="content">
		<?php 
			if(isset($_GET['log']))
			{
				echo '<h1>Log:</h1>';
				echo '<h3>'.$_GET['log'].'</h3>';
			}
			else
			{
				echo '<h1>Command:</h1>';
				echo '<h3>'.$_GET['cmd'].'</h3>';
			}
		?>
		<span><a href="..">Back</a></span>
			<pre>
<?php
	if(isset($_GET['log'])) switch($_GET['log'])
	{
		case 'acpid.log':
			getlog('/tmp/.nas/acpid.log');
			break;
		case 'acpid-autosuspend.log':
			getlog('/tmp/.nas/acpid-autosuspend.log');
			break;
		case 'execute-on-storage.log':
			getlog('/tmp/.execute-on-storage.log');
			break;
		case 'hdparm_spindown.log':
			getlog('/tmp/.hdparm_spindown.log');
			break;
		case 'checkroot':
			getlog('/var/log/fsck/checkroot');
			break;
		case 'checkfs':
			getlog('/var/log/fsck/checkfs');
			break;
		case 'mpd.log':
			getlog('/tmp/.mpd.log');
			break;
		case 'php.log':
			getlog('/tmp/.php.log');
			break;
		case 'ipsec.log':
			getlog('/tmp/.ipsec.log');
			break;
		case 'obexftpd.log':
			getlog('/tmp/.obexftpd.log');
			break;
		case 'samba.log':
			echo shell_exec('smbstatus') . '<hr>';
			getlog('/tmp/.samba.log');
			break;
	}
	if(isset($_GET['cmd'])) switch($_GET['cmd'])
	{
		case 'dmesg':
			echo shell_exec('/bin/dmesg');
			break;
		case 'last':
			echo shell_exec('/usr/bin/last');
			break;
		case 'lspci':
			echo shell_exec('/usr/local/bin/lspci.bin -i /usr/local/share/misc/pci.ids.gz');
			echo "</pre><hr><pre>";
			echo shell_exec('/usr/local/bin/lspci.bin -vvv -i /usr/local/share/misc/pci.ids.gz | sed -e "s/</(/g" | sed -e "s/>/)/g"');
			break;
		case 'lsusb':
			echo shell_exec('/bin/su -c "/usr/local/bin/lsusb"');
			echo "</pre><hr><pre>";
			echo shell_exec('/bin/su -c "/usr/local/bin/lsusb -v"');
			break;
		case 'lsmod':
			echo shell_exec('/bin/lsmod');
			break;
		case 'lsblk':
			echo shell_exec('/bin/lsblk');
			break;
		case 'lsof':
			echo shell_exec('/usr/bin/lsof');
			break;
		case 'sensors':
			echo shell_exec('/usr/bin/sensors');
			break;
		case 'hddtemp':
			echo shell_exec('/usr/sbin/hddtemp /dev/sd? 2>&1');
			break;
		case 'ifconfig':
			echo shell_exec('/sbin/ifconfig -a');
			break;
		case 'ethtool':
			echo shell_exec('./shell.sh ethtool.cmd');
			break;
		case 'docs':
			if(isset($_GET['file'])) // if file selected
			{
				getlog('/home/userhome/docs/' . $_GET['file']);
				echo '<br><a href="logs.php?cmd=docs">More docs</a>';
			}
			else // display docs
				if ($handle = opendir('/home/userhome/docs'))
				{
					while (false !== ($file = readdir($handle)))
						if (($file != ".") && ($file != ".."))
							if(preg_match('/\.(?:txt)$/', $file))
								echo '<LI><a href="logs.php?cmd=docs&file='.$file.'">'.$file.'</a>';
							elseif(preg_match('/\.(?:htm|html)$/', $file))
								echo '<LI><a target="_blank" href="logs.php?cmd=docs&file='.$file.'&fileopen=cat">'.$file.'</a>';
							else
								echo '<LI><a href="logs.php?cmd=docs&file='.$file.'&fileopen=cat&fileopen_method=binary">'.$file.'</a>';
					closedir($handle);
				}
			break;
	}

?>
			</pre>
		</div>
		<div class="content">
			<a href="..">Back</a>
		</div>
	</body>
</html>