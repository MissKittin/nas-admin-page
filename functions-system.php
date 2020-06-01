<?php include('functions-prevent_direct.php'); prevent_direct('functions-system.php'); ?>
<?php
	/* Functions and hooks - system-wide commands */

	$styleA='<!DOCTYPE html><html><head><title>'.$USER.'@'.$_SERVER['HTTP_HOST'].'</title><meta charset="utf-8"><style type="text/css">body{background-color: #dddddd;}</style></head><body><h1>';
	$styleB='</h1></body></html>';

	$messages=''; //initialize

	if(isset($_POST['close'])) switch($_POST['close'])
	{
		case 'Halt':
			exec('/sbin/halt');
			exit($styleA.'Halt'.$styleB);
			break;
		case 'Reboot':
			exec('/sbin/reboot');
			exit($styleA.'Reboot'.$styleB);
			break;
		case 'Suspend':
			exec('/usr/bin/nohup /usr/local/sbin/acpid-suspend suspend > /dev/null 2>&1 &');
			exit($styleA.'Suspend'.$styleB);
			break;

	}

	if(isset($_POST['kick_user']))
	{
		//OLD METHOD: shell_exec('/usr/bin/pkill -KILL -u ' . $_POST['kick_user']);
		exec('/usr/bin/pkill -9 -t ' . $_POST['kick_user']);
	}

	if(isset($_POST['useraction'])) switch($_POST['useraction'])
	{
		case 'Sync':
			$messages=$messages.'<p>'.shell_exec('/usr/sbin/ntpdate-debian').'</p>';
			break;
		case 'Save to rtc':
			exec('/sbin/hwclock --systohc');
			$messages=$messages.'<p>Clock saved</p>';
			break;
		case 'Lock suspend':
			exec('/bin/touch /tmp/.Xphp-lock');
			break;
		case 'Release suspend':
			exec('/bin/rm /tmp/.Xphp-lock');
			break;
		case 'Commit disk buff':
			exec('/bin/sync');
			$messages=$messages.'<p>Disk buffer commited</p>';
			break;
			
	}

	if(isset($_POST['sndvol'])) switch($_POST['sndvol'])
	{
		case '-25':
			exec('/usr/bin/amixer set Master 25-');
			break;
		case '-10':
			exec('/usr/bin/amixer set Master 10-');
			break;
		case '-5':
			exec('/usr/bin/amixer set Master 5-');
			break;
		case '-1':
			exec('/usr/bin/amixer set Master 1-');
			break;
		case '+1':
			exec('/usr/bin/amixer set Master 1+');
			break;
		case '+5':
			exec('/usr/bin/amixer set Master 5+');
			break;
		case '+10':
			exec('/usr/bin/amixer set Master 10+');
			break;
		case '+25':
			exec('/usr/bin/amixer set Master 25+');
			break;
	}

	// Update reminder
	if(exec('./shell.sh update_reminder') === 'update')
		$messages=$messages.'<p>&#9888; <span style="color: #cc0000; font-weight: bold;">Update system</span> &#9888;</p>';
	// Check root readonly
	if(exec('./shell.sh check_root_ro') === 'readonly')
		$messages=$messages.'<p style="color: #cc0000;">&#9888;&#9888;&#9888; <span style="font-weight: bold;">Root is read-only, run fsck.</span> &#9888;&#9888;&#9888;</p>';
?>