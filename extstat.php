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
if (strpos($str,'-1')){$str = "Номер не существует";}
if (strpos($str,' 0')){$str = "<b><p style='color:green'>Свободен</p></b>";}
if (strpos($str,' 1')){$str = "<p style='color:red'>Разговор</p>";}
if (strpos($str,' 2')){$str = "<b><p style='color:red'>Занят</p></b>";}
if (strpos($str,' 4')){$str = "<p style='color:gray'>Не подключен</p>";}
if (strpos($str,' 8')){$str = "Идет вызов";}
if (strpos($str,'16')){$str = "На удержании";}
return $str;
}
?>

