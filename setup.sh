#!/bin/sh
[ -e .git ] && rm -r -f .git
rm LICENSE
rm README.md
chmod 600 functions-login_config.php
chmod 750 shell.sh
chmod 755 shell_user.sh
rm setup.sh
echo 'Change #!/bin/su username in shell_user.sh'
echo 'Configure login credentials in functions-login_config.php'
exit 0
