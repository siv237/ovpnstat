<?php
// Получаем аргументы
$num_from=$_POST["v1"];
$num_to=$_POST["v2"];

// Ищем параметры подключения к AMI в конфигаруции Asterisk
$port=exec("grep -m 1 ^port /etc/asterisk/manager.conf|awk -F '= ' '{print $2}'");
$ast_addr="127.0.0.1";
//$ast_username="admin";
$ast_username=exec("grep -E ']|secret' /etc/asterisk/manager.conf |tail -n2|grep '\['|grep -Po '\w+'");
//$ast_password="amp111";
$ast_password=exec("grep -E ']|secret' /etc/asterisk/manager.conf|tail -n1|awk -F '= ' '{print $2}'");


// Строка системного запроса подключения к AMI
$stcom=
"
Action: Login
Username:".$ast_username."
Secret:".$ast_password."

Action:Originate
channel:Local/".$num_from."@from-internal
exten:".$num_to."@from-internal
Priority:1
CallerID:".$num_from."

";
$string="(printf '".$stcom."')|nc ".$ast_addr." ".$port;
$result=exec($string);
echo "Ожидайте ответа абонента ".$num_to;
?>
