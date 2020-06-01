<?php include('functions-prevent_direct.php'); prevent_direct('functions-user_services.php'); ?>
<?php
	/* Functions and hooks - user-privileged services commands */

	if(isset($_POST['mocp'])) switch($_POST['mocp'])
	{
		case 'Stop':
			exec('./shell.sh user_service mocp');
			break;
	}
	if(isset($_POST['mpd'])) switch($_POST['mpd'])
	{
		case 'Start':
			exec('./shell.sh user_service mpd start');
			break;
		case 'Stop':
			exec('./shell.sh user_service mpd stop');
			break;
		case 'Clear playlist':
			exec('/usr/bin/mpc -P xdr54ESZ clear');
			break;
		case 'Update database':
			$messages=$messages.'<pre>'.shell_exec('/usr/bin/mpc -P YOURMPDPASSWORD update').'</pre>';
			break;
	}
	if(isset($_POST['obexftpd'])) switch($_POST['obexftpd'])
	{
		case 'Start':
			exec('/usr/local/bin/obexftpd-starter start ' . $_SESSION['logged_user']);
			break;
		case 'Stop':
			exec('/usr/local/bin/obexftpd-starter stop');
			break;
	}
?>