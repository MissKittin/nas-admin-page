<?php
	/* Main page
		imported login and logout form
			system-wide commands
			root- and user-privileged services
		provide acpid-autosuspend prevent switch
			services start/restart/stop/status
			storage and harddrives info
	*/

	include('functions-login.php');
	include('functions-system.php');
	include('functions-system_services.php');
	include('functions-user_services.php');

	// Suspend lock
	if(file_exists('/tmp/.Xphp-lock'))
	{
		$suslock['background']='ffaaaa';
		$suslock['label']='Release suspend';
	}
	else
	{
		$suslock['background']='dddddd';
		$suslock['label']='Lock suspend';
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $_SESSION['logged_user']; ?>@<?php echo $_SERVER['HTTP_HOST']; ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="bars.css">
		<?php include('./favicons/favicon-header.php'); ?>
		<style type="text/css">
			body {
				margin: 0;
				padding: 0;
				background-color: #<?php echo $suslock['background']; ?>
			}
			#logout {
				position: absolute;
				right: 5px;
				top: 5px;
			}
			#ctent {
				position: relative;
			}
			.content {
				margin: 5px;
				padding: 5px;
			}
			#content-left {
				top: 0;
				position: relative;
			}
			#content-right {
				right: 0;
				top: 0;
				position: absolute;
			}
			table {
				border: 1px solid #000000;
			}
			#commandsnlogs table {
				border: none;
			}
			div {
				margin: 2px;
			}
			a, a:hover, a:visited {
				text-decoration: none;
				color: #0000ff;
			}
			#diskstate {
				float: right;
			}
			@media (max-width: 790px) {
				body, input, select, button {
					font-size: 10px;
				}
				#userinfo {
					margin-top: 4px;
					text-align: center;
				}
				#logout {
					position: static;
					float: right;
					margin-right: 5px;
				}
				.content {
					margin: 0;
					padding: 0;
				}
				#content-left {
					position: static;
				}
				#content-right {
					position: static;
					float: left;
					width: 100%;
				}
				#content-right table {
					margin: 0;
				}
				.show-hide-services {
					margin-top: 2px;
					width: 100%;
					text-align: center;
				}
				#sndvol, #useractions, #reboot {
					margin-left: 3px;
				}
				table {
					margin: auto;
				}
				div {
					margin: 0;
					margin-bottom: 3px;
				}
				#diskstate {
					float: left;
					margin-left: 3px;
				}
				#messages {
					margin: 3px;;
				}
			}
		</style>
	</head>
	<body>
	<!-- HEADER -->
		<div id="info" class="content">
			<div id="userinfo">
				Logged as <?php /* echo $USER; */ echo $_SESSION['logged_user'];?>, <?php echo shell_exec('uptime | awk \'{print $2 " " $3 " " $4}\''); ?> clock: <?php echo shell_exec('/bin/date "+&#128197; %d.%m.%Y &#128336; %H:%M"'); ?><!-- , IP: <?php //echo shell_exec('wget -qO- http://ipecho.net/plain'); ?> -->
			</div>
			<div id="logout">
				<form action="." method="post">
					<input type="submit" name="script" value="Logout">
				</form>
			</div>
		</div>

	<!-- FIRST COLUMN -->
		<div id="ctent">
			<div id="content-left" class="content">
				<h1>System <a href="find.php">&#128269;</a></h1>
				<div id="diskusage">
					<table>
						<tr><th><a href="storage.php">Disk/Part</a></th><th>Size</th><th>Used</th><th>Avail</th><th>Dev</th><th>Percentage</th><th>Used</th></tr>
						<?php echo shell_exec('./shell.sh disk_usage'); ?>
					</table>
				</div>
				<div id="ramusage">
					<table>
						<tr><th>Type</th><th>Used</th><th>Total</th><th>Shr</th><th>Buff</th><th>Cchd</th><th>Percentage</th></tr>
						<?php echo shell_exec('./shell.sh ram_usage'); ?>
					</table>
				</div>
				<div id="loggedusers">
					<form action="." method="post">
						<table>
							<tr><th>User</th><th>Term</th><th>Date</th><th>IP</th></tr>
							<?php echo shell_exec('./shell.sh logged_users'); ?>
							<?php echo shell_exec('./shell.sh vpn_user_logged'); ?>
						</table>
					</form>
				</div>

				<div id="user-services">
					<?php
						// user services visibility
						if(!isset($_SESSION['user_services_visibility'])) //init
							$_SESSION['user_services_visibility']='hide';
						if(isset($_GET['show-hide-user_services'])) //get value
							$_SESSION['user_services_visibility']=$_GET['show-hide-user_services'];
						if($_SESSION['user_services_visibility'] === 'User services') //apply
						{
					?>
					<form action="." method="post">
						<table>
							<tr>
								<td>MediaOnConsole</td>
								<td><input type="submit" name="mocp" value="Stop"></td>
								<td><?php echo shell_exec('./shell.sh check_user_service mocp'); ?></td>
							</tr>
							<tr>
								<td>MusicPlayer d</td>
								<td>
									<input type="submit" name="mpd" value="Start">
									<input type="submit" name="mpd" value="Stop">
									<input type="submit" name="mpd" value="Clear">
									<input type="submit" name="mpd" value="Update" style="margin-top: 4px;">
								</td>
								<td><?php echo shell_exec('./shell.sh check_user_service mpd'); ?></td>
							</tr>
							<tr>
								<td>BT ObexFtpd</td>
								<td style="<?php echo shell_exec('/usr/local/bin/obexftpd-starter status css'); ?>">
									<input type="submit" name="obexftpd" value="Start">
									<input type="submit" name="obexftpd" value="Stop">
								</td>
								<td><?php echo shell_exec('./shell.sh check_user_service obexftpd'); ?></td>
							</tr>
						</table>
					</form>
					<?php
							echo '<div class="show-hide-services">
								<form action="." method="get">
									<input type="submit" name="show-hide-user_services" value="Hide"> &#8593;
								</form>
							</div>';
						}
						else
							echo '<div class="show-hide-services">
								<form action="." method="get">
									<input type="submit" name="show-hide-user_services" value="User services"> &#8595;
								</form>
							</div>';
					?>
				</div>
				<div id="system-services">
					<?php
						// system services visibility
						if(!isset($_SESSION['system_services_visibility'])) //init
							$_SESSION['system_services_visibility']='hide';
						if(isset($_GET['show-hide-system_services'])) //get value
							$_SESSION['system_services_visibility']=$_GET['show-hide-system_services'];
						if($_SESSION['system_services_visibility'] === 'System services') //apply
						{
					?>
					<form action="." method="post">
						<table>
							<tr>
								<td>Autosuspend</td>
								<td></td>
								<td><?php echo shell_exec('./shell.sh check_service acpid-autosuspend'); ?></td>
							</tr>
							<tr>
								<td>Secure Shell </td>
								<td>
									<input type="submit" name="ssh" value="Start">
									<input type="submit" name="ssh" value="Restart" OnClick="return confirm('Are you sure???')">
									<input type="submit" name="ssh" value="Stop" OnClick="return confirm('Are you sure???')">
								</td>
								<td><?php echo shell_exec('./shell.sh check_service ssh'); ?></td>
							</tr>
							<tr>
								<td>Samba </td>
								<td>
									<input type="submit" name="samba" value="Start">
									<input type="submit" name="samba" value="Restart">
									<input type="submit" name="samba" value="Stop">
								</td>
								<td><?php echo shell_exec('./shell.sh check_service samba'); ?></td>
							</tr>
							<tr>
								<td><a href="ftp://<?php echo $_SERVER['HTTP_HOST']; ?>">VSFtpD</a> </td>
								<td>
									<input type="submit" name="vsftpd" value="Start">
									<input type="submit" name="vsftpd" value="Restart">
									<input type="submit" name="vsftpd" value="Stop">
								</td>
								<td><?php echo shell_exec('./shell.sh check_service vsftpd'); ?></td>
							</tr>
							<tr>
								<td>WebDAV </td>
								<td>
									<input type="submit" name="webdav" value="Start">
									<input type="submit" name="webdav" value="Restart">
									<input type="submit" name="webdav" value="Stop">
								</td>
								<td><?php echo shell_exec('./shell.sh check_service webdav'); ?></td>
							</tr>
							<tr>
								<td>Poptopd </td>
								<td>
									<input type="submit" name="pptpd" value="Start">
									<input type="submit" name="pptpd" value="Restart">
									<input type="submit" name="pptpd" value="Stop">
								</td>
								<td><?php echo shell_exec('./shell.sh check_service pptpd'); ?></td>
							</tr>
							<tr>
								<td>Xl2tpd </td>
								<td style="<?php echo shell_exec('./shell.sh check_service xl2tpd css-xl2tpd'); ?>">
									<input type="submit" name="xl2tpd" value="Start">
									<input type="submit" name="xl2tpd" value="Restart">
									<input type="submit" name="xl2tpd" value="Stop">
								</td>
								<td><?php echo shell_exec('./shell.sh check_service xl2tpd'); ?></td>
							</tr>
							<tr>
								<td>Ipsec </td>
								<td style="<?php echo shell_exec('./shell.sh check_service xl2tpd css-ipsec'); ?>">
									<input type="submit" name="ipsec" value="Start">
									<input type="submit" name="ipsec" value="Restart">
									<input type="submit" name="ipsec" value="Stop">
								</td>
								<td><?php echo shell_exec('./shell.sh check_service ipsec'); ?></td>
							</tr>
							<tr>
								<td>BlueZ </td>
								<td style="<?php echo shell_exec('./shell.sh check_user_service obexftpd css'); ?>">
									<input type="submit" name="bluez" value="Start">
									<input type="submit" name="bluez" value="Restart">
									<input type="submit" name="bluez" value="Stop">
								</td>
								<td><?php echo shell_exec('./shell.sh check_service bluetooth'); ?></td>
							</tr>
							<tr>
								<td>Ufw </td>
								<td>
									<input type="submit" name="ufw" value="Load">
									<input type="submit" name="ufw" value="Reload">
									<input type="submit" name="ufw" value="Stop">
								</td>
								<td><?php echo shell_exec('./shell.sh check_loaded_service ufw'); ?></td>
							</tr>
							<tr>
								<td>Athcool </td>
								<td>
									<input type="submit" name="athcool" value="Set">
									<input type="submit" name="athcool" value="Unset">
								</td>
							</tr>
						</table>
					</form>
					<?php
							echo '<div class="show-hide-services">
								<form action="." method="get">
									<input type="submit" name="show-hide-system_services" value="Hide"> &#8593;
								</form>
							</div>';
						}
						else
							echo '<div class="show-hide-services">
								<form action="." method="get">
									<input type="submit" name="show-hide-system_services" value="System services"> &#8595;
								</form>
							</div>';
					?>
				</div>

				<div id="sndvol">
					<form action="." method="post">
						&#128266; 
						<input type="submit" name="sndvol" value="-25">
						<input type="submit" name="sndvol" value="-10">
						<input type="submit" name="sndvol" value="-5">
						<input type="submit" name="sndvol" value="-1">
						<input type="submit" name="sndvol" value="+1">
						<input type="submit" name="sndvol" value="+5">
						<input type="submit" name="sndvol" value="+10">
						<input type="submit" name="sndvol" value="+25">
					</form>
				</div>

				<div id="useractions">
					<form action="." method="post">
						<div style="padding-left: 0; margin-left: 0;">
							&#128336;
							<input type="submit" name="useraction" value="Sync">
							<input type="submit" name="useraction" value="Save to rtc">
						</div>
						&#9940; <input type="submit" name="useraction" value="<?php echo $suslock['label']; ?>">
						&#128190; <input type="submit" name="useraction" value="Commit disk buff">
					</form>
				</div>

				<div id="reboot">
					<form action="." method="post">
						&#9889;
						<input type="submit" name="close" value="Halt">
						<input type="submit" name="close" value="Reboot">
						<input type="submit" name="close" value="Suspend">
					</form>
				</div>

			</div>

	<!-- SECOND COLUMN -->
			<div id="content-right" class="content">
				<div id="commandsnlogs">
					<table><tr><td style="vertical-align: top;">
					<div>
						<h1>Logs</h1>
						<a href="logs.php?log=acpid.log">acpid.log</a><br>
						<a href="logs.php?log=acpid-autosuspend.log">acpid-autosuspend.log</a><br>
						<a href="logs.php?log=execute-on-storage.log">execute-on-storage.log</a><br>
						<a href="logs.php?log=hdparm_spindown.log">hdparm_spindown.log</a><br>
						<a href="logs.php?cmd=last">last</a><br>
						<a href="logs.php?log=checkroot">checkroot</a><br>
						<a href="logs.php?log=checkfs">checkfs</a><br>
						<a href="logs.php?log=mpd.log">mpd.log</a><br>
						<a href="logs.php?log=php.log">php.log</a><br>
						<a href="logs.php?log=ipsec.log">ipsec.log</a><br>
						<a href="logs.php?log=obexftpd.log">obexftpd.log</a><br>
						<a href="logs.php?log=samba.log">samba.log</a>
					</div></td><td style="vertical-align: top;">
					<div>
						<h1>S/Hw info</h1>
						<a href="logs.php?cmd=dmesg">dmesg</a><br>
						<a href="logs.php?cmd=lspci">lspci</a><br>
						<a href="logs.php?cmd=lsusb">lsusb</a><br>
						<a href="logs.php?cmd=lsmod">lsmod</a><br>
						<a href="logs.php?cmd=lsblk">lsblk</a><br>
						<a href="logs.php?cmd=lsof">lsof</a><br>
						<a href="lshw.php">lshw</a><br>
						<a href="logs.php?cmd=sensors">sensors</a><br>
						<a href="logs.php?cmd=hddtemp">hddtemp</a><br>
						<a href="logs.php?cmd=ifconfig">ifconfig</a><br>
						<a href="logs.php?cmd=ethtool">ethtool</a><br>
						<a href="powertop.php">powertop</a><br>
						<a href="phpinfo.php">phpinfo</a><br>
						<a href="logs.php?cmd=docs">docs</a>
					</div></td></tr></table>
				</div>
				<div id="diskstate">
					<table>
						<tr><th>Disk</th><th>Status</tr>
						<?php echo shell_exec('./shell.sh disks_status_mini'); ?>
					</table>
				</div>
			</div>
		</div>
		<div id="messages" class="content">
			<?php if($messages != '') echo '<h1>Messages</h1>'; ?>
			<?php echo $messages; ?>
		</div>
		<!--debug-->
			<?php
				//print_r(get_defined_vars());
			?>
		<!--/debug-->
	</body>
</html>