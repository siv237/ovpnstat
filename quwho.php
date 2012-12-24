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

Action: Queues

Action: Logoff

";

$str="(printf '".$stcom."')|nc -q 30 ".$ast_addr." ".$port;
$str=shell_exec($str);
//$str=str_replace('\^\$','ggggggggrr',$str);
//echo $str;

preg_match_all("/\d+ has +\d calls|\d+\..+\)/",$str,$str);
$str=$str[0];
//print_r($str);
echo "<table border='1'>";
echo "<tr><td>Очередь<td>Канал<td>Ожидание</td>";

for ($x=0; $x<=count($str)-1; $x++)
	{
	if (strstr($str[$x],'has'))
		{
		 preg_match("/\d+/",$str[$x],$qname);
		}
	else
		{
		 $strt=explode(' ',$str[$x]);
		 $cname=$strt[1];
		 $qtime=explode(',',$strt[3]);
		 $qtime=$qtime[0];
		}

	if ($cname != "")
		{
//	echo "Очередь номер ".$qname[0]." Канал ".$cname." Время ожидания ".$qtime."\n";
	echo 
		"<tr>".
		"<td>".$qname[0].
		"<td>".$cname.
		"<td>".$qtime
	;



		}
	$qtime="";
	$qtime="";
	$cname="";
	}
echo "</table>";

?>
