<?php 
// Ищем в файле конфигурации FreePBX логин и пароль к базе
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

// Подключаемся к базе
mysql_pconnect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asteriskcdrdb") or die(mysql_error());

// max(IN-CNT), // max(IN-BS), // max(IN-DT), // max(OUT-CNT), // max(OUT-BS), // max(OUT-DT)

$strdate="BETWEEN STR_TO_DATE('2013-06-03 14:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('2013-06-03 23:59:59', '%Y-%m-%d %H:%i:%s')";

$strSQL = 
("
SELECT t2.mt as LastTime,RIGHT(t1.dst,10) as numb,t1.disposition,t1.dstchannel FROM 
(select *,RIGHT(dst,10) as numb from cdr where calldate $strdate) as t1,
(select max(calldate) as mt,RIGHT(dst,10) as numb from cdr where calldate $strdate group by dst) as t2 

where (t1.calldate=t2.mt and t1.numb=t2.numb)
 and  LENGTH(t1.dst) >= 10
 and t1.disposition='ANSWERED'

order by t2.mt
");

// Выполняем запрос
$rs = mysql_query($strSQL);


echo "<table border='1'>";

// Вытаскиваем имена полей и формируем заголовок таблицы результатов
$field = mysql_num_fields( $rs );

echo "<tr>";
for ( $i = 0; $i < $field; $i++ ) { 
	$rsclmn = mysql_field_name($rs,$i);
	echo "<td>".$rsclmn;
}
echo "</td>";

// Извлекаем значения и формируем таблицу результатов
while($id=mysql_fetch_row($rs))
	{ 
	echo "<tr>";
	for ($x=0; $x<=count($id)-1; $x++) 
		{
		echo "<td>".$id[$x];
		}
	echo "</td>";
	}
echo "</td></table>";
echo "<br>Текст запроса:";
?>

<pre>
<?
echo $strSQL
?>
</pre>


