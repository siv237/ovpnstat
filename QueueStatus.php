<?php
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
echo "<tr><td>Очередь<td>Канал<td>Ожидание<td>Номер<td>Имя</td>";

foreach($column as $str)
	{
	if(strstr($str[Event],"QueueEntry"))
		{
		echo 
                "<tr>".
                "<td>".$str[Queue].
                "<td>".$str[Channel].
                "<td>".$str[Wait].
                "<td>".$str[CallerIDNum].
                "<td>".$str[CallerIDName]
        	;
		}
	}
//	if (in_array("QueueEntry",$str))
//		{
//		echo $str[Event]."\n";
//		}
//	else
//		{
//		}
//	}
//            [Event] => QueueEntry
//            [Queue] => 1
//            [Position] => 1
//            [Channel] => SIP/redcom-00000000
//            [Uniqueid] => 1356400025.0
//            [CallerIDNum] => 84212543777
//            [CallerIDName] => OFS-84212543777
//            [Wait] => 40

?>

