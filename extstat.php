<?php
function ExtStatus($str) {

// Ищем параметры подключения к AMI в конфигаруции Asterisk
$port=exec("grep -m 1 ^port /etc/asterisk/manager.conf|awk -F '= ' '{print $2}'");
$ast_addr="127.0.0.1";
//$ast_username="admin";
$ast_username=exec("grep -E ']|secret' /etc/asterisk/manager.conf |tail -n2|grep '\['|grep -Po '\w+'");
//$ast_password="amp111";
$ast_password=exec("grep -E ']|secret' /etc/asterisk/manager.conf|tail -n1|awk -F '= ' '{print $2}'");


// Строка системного запроса подключения к AMI
$AmiStr= 
"Action: Login
Username: ".$ast_username."
Secret: ".$ast_password."

Action: ExtensionState
Exten: ".$str."

Action: Logoff

";
$str=shell_exec("(printf '".$AmiStr."')|nc -q 30 ".$ast_addr." ".$port);
//$str= mb_eregi('/\d+', $str);
$str=explode("\n",$str);
$str=$str[13];
return $str;
}
?>

