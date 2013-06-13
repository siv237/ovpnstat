<?php 
// Ищем в файле конфигурации FreePBX логин и пароль к базе
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

// Подключаемся к базе
mysql_pconnect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asteriskcdrdb") or die(mysql_error());

// max(IN-CNT), // max(IN-BS), // max(IN-DT), // max(OUT-CNT), // max(OUT-BS), // max(OUT-DT)

$strdate="BETWEEN STR_TO_DATE('2013-06-07 16:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('2013-06-07 23:59:59', '%Y-%m-%d %H:%i:%s')";

$strSQL = 
("

select t1.calldate,RIGHT(t1.src, 10) as numb_in from
(select * from cdr where calldate $strdate) as t1,
(select max(calldate) as m_date,src from cdr where calldate $strdate group by src) as t2

where 	t2.m_date=t1.calldate 
	and RIGHT(t1.src,5)=RIGHT(t2.src,5)
	and LENGTH(t1.src) >= 10
	and dstchannel=''
	and lastapp='Queue'
order by t1.calldate



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


