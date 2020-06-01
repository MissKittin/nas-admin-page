<?php include('functions-prevent_direct.php'); prevent_direct('functions-system_services.php'); ?>
<?php
	/* Functions and hooks - root-privileged services commands */

	if(isset($_POST['ssh'])) switch($_POST['ssh'])
	{
		case 'Restart':
			exec('./shell.sh service ssh restart');
			break;
		case 'Start':
			exec('./shell.sh service ssh start');
			break;
		case 'Stop':
			exec('./shell.sh service ssh stop');
			break;
	}
	if(isset($_POST['samba'])) switch($_POST['samba'])
	{
		case 'Start':
			exec('./shell.sh service samba start');
			break;
		case 'Stop':
			exec('./shell.sh service samba stop');
			break;
		case 'Restart':
			exec('./shell.sh service samba restart');
			break;
	}
	if(isset($_POST['vsftpd'])) switch($_POST['vsftpd'])
	{
		case 'Start':
			exec('./shell.sh service vsftpd start');
			break;
		case 'Stop':
			exec('./shell.sh service vsftpd stop');
			exec('/usr/bin/killall vsftpd');
			break;
		case 'Restart':
			exec('./shell.sh service vsftpd restart');
			break;
	}
	if(isset($_POST['webdav'])) switch($_POST['webdav'])
	{
		case 'Start':
			exec('./shell.sh service webdav start');
			break;
		case 'Stop':
			exec('./shell.sh service webdav stop');
			break;
		case 'Restart':
			exec('./shell.sh service webdav restart');
			break;
	}
	if(isset($_POST['pptpd'])) switch($_POST['pptpd'])
	{
		case 'Start':
			exec('./shell.sh service pptpd start');
			break;
		case 'Stop':
			exec('./shell.sh service pptpd stop');
			break;
		case 'Restart':
			exec('./shell.sh service pptpd restart');
			break;
	}
	if(isset($_POST['xl2tpd'])) switch($_POST['xl2tpd'])
	{
		case 'Start':
			exec('./shell.sh service xl2tpd start');
			break;
		case 'Stop':
			exec('./shell.sh service xl2tpd stop');
			break;
		case 'Restart':
			exec('./shell.sh service xl2tpd restart');
			break;
	}
	if(isset($_POST['ipsec'])) switch($_POST['ipsec'])
	{
		case 'Start':
			exec('./shell.sh service ipsec start');
			break;
		case 'Stop':
			exec('./shell.sh service ipsec stop');
			break;
		case 'Restart':
			exec('./shell.sh service ipsec restart');
			break;
	}
	if(isset($_POST['bluez'])) switch($_POST['bluez'])
	{
		case 'Start':
			exec('./shell.sh service bluetooth start');
			break;
		case 'Stop':
			exec('./shell.sh service bluetooth stop');
			break;
		case 'Restart':
			exec('./shell.sh service bluetooth restart');
			break;
	}
	if(isset($_POST['ufw'])) switch($_POST['ufw'])
	{
		case 'Start':
			exec('./shell.sh service ufw start');
			break;
		case 'Stop':
			exec('./shell.sh service ufw stop');
			break;
		case 'Restart':
			exec('./shell.sh service ufw restart');
			break;
	}
?>