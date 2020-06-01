<?php include('functions-prevent_direct.php'); prevent_direct('functions-login_loading.php'); ?>
<?php
	/* Body file - Loading... page */
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $_SERVER['HTTP_HOST']; ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="refresh" content="0">
		<?php include('./favicons/favicon-header.php'); ?>
		<style type="text/css">
			body {
				background-color: #dddddd;
			}
		</style>
	</head>
	<body>
		<h1>Loading...</h1>
	</body>
</html>