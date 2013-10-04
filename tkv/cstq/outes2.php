<?php 
// Ищем в файле конфигурации FreePBX логин и пароль к базе
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

// Подключаемся к базе
mysql_pconnect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asteriskcdrdb") or die(mysql_error());

// max(IN-CNT), // max(IN-BS), // max(IN-DT), // max(OUT-CNT), // max(OUT-BS), // max(OUT-DT)


$strSQL = 
("
select calldate,RIGHT (src,10),disposition,dstchannel from
(select  max(calldate) as max_t,RIGHT(src,10) as numb
from cdr
where
	calldate > (NOW() - INTERVAL 1 day)
	and LENGTH(src) >= 10 
	and not (disposition='ANSWERED' and dstchannel !='')	
group by numb
order by calldate) as zp1
left join cdr

ON calldate > (NOW() - INTERVAL 1 day) and zp1.max_t=cdr.calldate and zp1.numb=RIGHT(cdr.src,10)

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


