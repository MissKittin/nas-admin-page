<?php
	/* Request uri control library
		provide goto_home() and
			prevent_direct()
	*/

	if(!function_exists('goto_home'))
	{
		function goto_home()
		{
			echo '
				<!DOCTYPE html>
				<html>
					<head>
						<title>'.$_SERVER["HTTP_HOST"].'</title>
						<meta http-equiv="refresh" content="0; url=.">
					</head>
				</html>
			';
		}

		function prevent_direct($name)
		{
			if(strtok($_SERVER['REQUEST_URI'],  '?') === "/$name")
			{
				goto_home();
				exit();
			}
		}
	}
?>