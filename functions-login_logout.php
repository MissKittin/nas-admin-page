<?php include('functions-prevent_direct.php'); prevent_direct('functions-login_logout.php'); ?>
<?php
	/* Body file - Logged out */

	session_start();
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
			#content {
				margin: 5px;
				padding: 5px;
			}
			div {
				margin: 2px;
			}
			a, a:hover, a:visited {
				text-decoration: none;
				color: #0000ff;
			}
		</style>
	</head>
	<body>
		<div id="content">
			<h1>Logged out</h1>
			<a href=".">Login</a>
		</div>
	</body>
</html>