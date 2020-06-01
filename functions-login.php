<?php
	/* Login and logout control library
		provide single user (deprecated) or
			multiuser auth
	*/

	include('functions-login_config.php'); // username and password
	session_start();

	// LOGOUT
	if(isset($_POST['script'])) switch($_POST['script'])
	{
		case 'Logout': // if clicked logout
			session_destroy();
			include('functions-login_logout.php');
			exit();
			break;
	}

	// LOGGED
	if(isset($_SESSION['logged']) && $_SESSION['logged'])
		$login_method='script_already_logged';

	// LOGIN
	$WRONG_PASSWORD=''; // empty message
	switch($login_method)
	{
		case 'single':
			 // // single user
			if(isset($_POST['user']) && isset($_POST['password']))
				if($_POST['user'] === $USER && $_POST['password'] === $PASSWORD) // validate
				{
					$_SESSION['logged']=true;
					/* reload */ include('functions-login_login.php'); // clear $_POST array
					exit();
				}
				else
					$WRONG_PASSWORD='<script type="text/javascript"> alert("Wrong username or password"); </script>'; // wrong password message
		break;
		case 'multi':
			 // // multiuser
			if(isset($_POST['user']) && isset($_POST['password']))
				for($i=0, $cnt=count($USER); $i<$cnt; $i++)
				{
					if($_POST['user'] === $USER[$i]) // find user
						if($_POST['password'] === $PASSWORD[$i]) // check passwd
						{
							$WRONG_PASSWORD=''; // Correct password
							$_SESSION['logged_user']=$USER[$i];
							$_SESSION['logged']=true; // success!!!
							/* reload */ include('functions-login_login.php'); // clear $_POST array
							exit();
						}
						else // wrong passwd
							$WRONG_PASSWORD='<script type="text/javascript"> alert("Wrong username or password"); </script>'; // wrong password message
					else
						$WRONG_PASSWORD='<script type="text/javascript"> alert("Wrong username or password"); </script>'; // wrong password message		
				}
		break;
		case 'pam':
			 // // unix auth
			if(isset($_POST['user']) && isset($_POST['password']))
			{
				$login_userName=$_POST['user'];
				$login_userPasswd=$_POST['password'];
				$login_passwdFile='/etc/shadow';
			
				for($i=0, $cnt=count($login_allowed_users); $i<$cnt; $i++)
				{
					if($_POST['user'] === $login_allowed_users[$i]) // allowed
					{
						$login_users=file($login_passwdFile);
						if(!$login_user=preg_grep("/^$login_userName/",$login_users))
						{
							$WRONG_PASSWORD='<script type="text/javascript"> alert("Wrong username or password"); </script>'; // wrong username message
						}
						else
						{
							list(,$login_passwdInDB)=explode(':',array_pop($login_user));
							if(crypt($login_userPasswd,$login_passwdInDB) == $login_passwdInDB)
							{
								$_SESSION['logged_user']=$login_allowed_users[$i];
								$_SESSION['logged']=true; // success!!!
								/* reload */ include('functions-login_login.php'); // clear $_POST array
								break;
							}
							else
							{
								$WRONG_PASSWORD='<script type="text/javascript"> alert("Wrong username or password"); </script>'; // wrong password message
							}
						}
					}
				}

				// not allowed
				$WRONG_PASSWORD='<script type="text/javascript"> alert("Not allowed"); </script>'; // not allowed message
			}
		break;
		case 'script_already_logged':
			//none
		break;
	}
	unset($login_method);

	if(!$_SESSION['logged']) // login form
	{
		include('functions-login_login.php');
		exit();
	}
?>