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

Action: DongleShowDevices

Action: Logoff

";

$str="(printf '".$stcom."')|nc -q 30 ".$ast_addr." ".$port;
$str=shell_exec($str);
//echo $str;
// Если в ответе нет строки QueueEntry, значит ожидающих нет
if (!strstr($str, "IMEIState")){
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

        else    {
                 $rows++;
                }
        }

//print_r($column);
echo "<table border 1>";

foreach($column as $str)
        {
        if(strstr($str[Event],"DongleDeviceEntry"))
                {
                echo 
                "<tr>".
                "<td>".$str[Device].
                "<td>".$str[Manufacturer]." ".$str[Model].
                "<td>".$str[ProviderName].
                "<td>".$str[IMEISetting].
                "<td>".$str[GSMRegistrationStatus].
                "<td>".$str[Mode].
                "<td>".$str[RSSI].
		"<td>".$str[CurrentDeviceState].
		"<td>";	
		 	if($str[Active] != 0){ echo "Разговор ";}
			if($str[held] != 0){ echo "На удержании ";}
			if($str[Dialing] != 0){ echo "Исходящий вызов ";}
			if($str[Alerting] != 0){ echo "Тревога ";}
			if($str[Incoming] != 0){ echo "Входящий ";}
			if($str[Waiting] != 0){ echo "Ожидание ";}
			if($str[Releasing] != 0){ echo "Разъединение ";}
			if($str[Initializing] != 0){ echo "Инициализация ";}
                $n++;
                $alltime=$alltime+$str[Wait];
                }
        }

}
?>

