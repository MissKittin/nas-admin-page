<?php include('functions-prevent_direct.php'); prevent_direct('functions-login_login.php'); ?>
<?php
	/* Body file - Login form */

	//session_start();

	if(isset($_SESSION['logged']))
		if($_SESSION['logged'])
		{
			include('functions-login_loading.php');
			exit();
		}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $_SERVER['HTTP_HOST']; ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php include('./favicons/favicon-header.php'); ?>
		<style type="text/css">
			body {
				margin: 0;
				padding: 0;
				background-color: #dddddd;
			}
			#login {
				margin: 5px;
				padding: 5px;
			}
			div {
				margin: 2px;
			}
			@media screen and (max-width: 800px), @media screen and (max-width: 400px) {
				#hostname {
					display: none;
				}
			}
		</style>
	</head>
	<body>
		<div id="login">
			<h1><img src="favicons/android-icon-36x36.png" alt="server">Login</h1>
			<form action="." method="post">
				Username: <input type="text" name="user"><span id="hostname">@<?php echo $_SERVER['HTTP_HOST']; ?></span><br>
				Password: <input type="password" name="password"><br>
				<input type="submit" value="Login">
			</form>
			<?php echo $WRONG_PASSWORD; ?>
		</div>
	</body>
</html>