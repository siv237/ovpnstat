<?php
//include 'connect-script/telnet-connect.php';

// Строка системного запроса подключения к AMI
$cname="";
// Ищем параметры подключения к AMI в конфигаруции Asterisk
$port=exec("grep -m 1 ^port /etc/asterisk/manager.conf|awk -F '= ' '{print $2}'");
$ast_addr="127.0.0.1";
//$ast_username="admin";
$ast_username=exec("grep -E ']|secret' /etc/asterisk/manager.conf |tail -n2|grep '\['|grep -Po '\w+'");
//$ast_password="amp111";
$ast_password=exec("grep -E ']|secret' /etc/asterisk/manager.conf|tail -n1|awk -F '= ' '{print $2}'");

$stcom=
"Action: Login
Username: ".$ast_username."
Secret: ".$ast_password."

Action: Events
Eventmask: off

Action: Command
command: queue show 010


Action: Logoff

";

$str="(printf '".$stcom."')|nc -q 30 ".$ast_addr." ".$port;
$str=shell_exec($str);

foreach ($array as $one)
     $str .= $one."\r\n";


/*$string=explode(" " , $str);
print_r($string);
*/
// вывод номеров
//$patern = '/\/[0-9]{3,4}/';
$patern='/yet.*/';
preg_match_all($patern, $str, $result );
print_r($result);
//echo nl2br($str);
?>