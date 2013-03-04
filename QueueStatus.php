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
// Если в ответе нет строки QueueEntry, значит ожидающих нет
if (!strstr($str, "QueueEntry")){
echo "Нет ожидающих абонентов";
//Меняем заголовок
echo '<head><script language="JavaScript"><!--
parent.document.title="Очередь пуста";</script></head>';

}


// а если есть, то выполняем обработку
else {

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
// Рисуем заголовок таблицы
echo "<table border 1>";
echo "<tr><th>Очередь<th>Имя<th>Информация о номере<th>Ожидание<th>Действие</th>";

// Ищем нужные поля и собираем из них таблицу ожидающих абонентов
$n=0;
$alltime=0;
foreach($column as $str)
	{
	if(strstr($str[Event],"QueueEntry"))
		{
		echo 
                "<tr>".
                "<td>".$str[Queue].
                "<td>".$str[CallerIDName].
		"<td>".FormatTelNum(substr($str[CallerIDNum],0,-1)).
                "<td>".showDate(time()-$str[Wait]).
		"<td><a href=redirform.php?chan=".$str[Channel]." target='call'>Ответить</a>"
        	;
		$n++;
		$alltime=$alltime+$str[Wait];
		}
	}
// Выводим суммарную информацию
echo "</td></tr><br> Колличество ожидающих: ".$n."<br> Общее время ожидания: ".showDate(time()-$alltime)."<br>";
// Привлекаем внимание изменением заголовка окна
echo '<head><script language="JavaScript"><!-- 
setTimeout(function() { parent.document.title="Ожидает ответа" }, 2000);
parent.document.title="'.$n.':'.$alltime.'с";
</script></head>';

}
echo '<meta http-equiv="refresh" content="4">';
?>

