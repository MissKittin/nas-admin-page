<?php
	/* Find files */

	include('functions-login.php');

	$result='What do you want to search for?';
	if(isset($_POST['grepstring']))
		$result=shell_exec('sh -c "find /media | grep \"' . $_POST['grepstring'] . '\""');
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $_SESSION['logged_user']; ?>@<?php echo $_SERVER['HTTP_HOST']; ?> find</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="bars.css">
		<?php include('./favicons/favicon-header.php'); ?>
		<style type="text/css">
			body {
				margin: 5px;
				padding: 0;
				background-color: #dddddd;
			}
		</style>
	</head>
	<body>
		<h1>Find files in libraries</h1>
		<form action="find.php" method="post">
			<input type="text" name="grepstring">
			<input type="submit" value="&#128269;">
		</form>
		<a href=".">Back</a>
		<pre><?php echo $result; ?></pre>
		<a href=".">Back</a>
	</body>
</html>