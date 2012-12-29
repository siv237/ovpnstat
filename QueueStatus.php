<?php
include 'funtime.php';  // showDate() Функция - примерное время
include 'formatnum.php'; // FormatTelNum() Форматирование и геостатус номера

$cname="";
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

Action: QueueStatus

Action: Logoff

";

$str="(printf '".$stcom."')|nc -q 30 ".$ast_addr." ".$port;
$str=shell_exec($str);

//echo $str;
$str=explode("\n",$str);
$rows = 1;
foreach ($str as $string)
	{
	if ($string != "\r")
		{
		 $value=explode(": ",$string);
		 $column[$rows][$value[0]]=$value[1];
		}

	else	{
		 $rows++;
		}
	}

//[Event] =>  QueueEntry
//print_r($column);
echo "<table border='1'>";
echo "<tr><td>Очередь<td>Канал<td>Ожидание<td>Номер<td>Имя<td>Информация<td>Действие</td>";

foreach($column as $str)
	{
	if(strstr($str[Event],"QueueEntry"))
		{
		echo 
                "<tr>".
                "<td>".$str[Queue].
                "<td>".$str[Channel].
                "<td>".showDate(time()-$str[Wait]).
                "<td>".$str[CallerIDNum].
                "<td>".$str[CallerIDName].
		"<td>".FormatTelNum(substr($str[CallerIDNum],0,-1)).
		"<td><a href=redirform.php?chan=".$str[Channel].">Ответить</a>"
        	;
		}
	}
echo '<meta http-equiv="refresh" content="6">';
?>

