<?php
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
define("DATABASE_HOST", "127.0.0.1");
define("DATABASE_PASSWORD", "$password");
define("DATABASE_USERNAME", "$login");
define("DATABASE_NAME","asteriskcdrdb");

mysql_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD )
or die ("<p>Соединение с Сервером MySQL: ERROR  " . mysql_error() . "</p>"  );


?>