<?php
// Получаем аргументы
$chan=$_POST["chan"];
$num_to=$_POST["num_to"];

// Ищем параметры подключения к AMI в конфигаруции Asterisk
$port=exec("grep -m 1 ^port /etc/asterisk/manager.conf|awk -F '= ' '{print $2}'");
$ast_addr="127.0.0.1";
//$ast_username="admin";
$ast_username=exec("grep -E ']|secret' /etc/asterisk/manager.conf |tail -n2|grep '\['|grep -Po '\w+'");
//$ast_password="amp111";
$ast_password=exec("grep -E ']|secret' /etc/asterisk/manager.conf|tail -n1|awk -F '= ' '{print $2}'");


// Строка системного запроса подключения к AMI
$stcom=
"Action: Login
Username: ".$ast_username."
Secret: ".$ast_password."

Action: Events
Eventmask: off

Action: Command
command: channel redirect ".$chan." from-internal,".$num_to.",1

Action: Events
Eventmask: off

Action: Logoff

";

$string="(printf '".$stcom."')|nc -q 30 ".$ast_addr." ".$port;
system($string);
//echo "<br><h1>Ожидайте ответа абонента ".$num_to."</h>";

echo "<meta http-equiv='Refresh' content='2; URL=QueueStatus.php'>";
?>
