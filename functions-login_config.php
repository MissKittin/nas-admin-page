<?php include('functions-prevent_direct.php'); prevent_direct('functions-login_config.php'); ?>
<?php
	/* Logins and Passwords - config file
		Provide single user (unencrypted),
			multiuser (unencrypted) and
			login by unix auth (encrypted) config
			language setting
		See functions-login.php
	*/

	/* ********************************************
	* Warning:                                    *
	* if you use single-user, comment multi-user  *
	* and vice versa                              *
	******************************************** */

	//settings - login method: 'single', 'multi' or 'pam', language
	$login_method='multi';
	$ui_lang='pl';

	//configuration - single user
	/* disabled
	$USER='username';
	$PASSWORD='userpassword';
	*/

	//configuration - multiuser
	$USER=['username'];
	$PASSWORD=['userpassword'];

	//configuration - pam
	$login_allowed_users=['username'];
?>