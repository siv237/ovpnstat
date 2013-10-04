<?php 
// Ищем в файле конфигурации FreePBX логин и пароль к базе
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

// Подключаемся к базе
mysql_pconnect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asteriskcdrdb") or die(mysql_error());

//        max(IN-CNT),
//        max(IN-BS),
//        max(IN-DT),
//        max(OUT-CNT),
//        max(OUT-BS), 
//        max(OUT-DT) 

// Текст запроса к базе
$strSQL = 
("
select 	EXT,
        max(INCNT) as 'Принято звонков',
        SEC_TO_TIME(max(INBS)) as 'Время входящих',
        SEC_TO_TIME(max(INDT)-max(INBS)) as 'Ожидание входящих',
        max(OUTCNT) as 'Выполнено звонков',
        SEC_TO_TIME(max(OUTBS)) as 'Время исходящих', 
        SEC_TO_TIME(max(OUTDT)-max(OUTBS)) as 'Ожидание при исходящих' 


from(
(
select 	dst as 'EXT',
	count(*) as 'INCNT',
	sum(billsec) as 'INBS',
	sum(duration) as 'INDT',
	0 as 'OUTCNT',
	0 as 'OUTBS',
	0 as 'OUTDT' 
from 	cdr 
where 	(dst < 199 and dst >100) 
	and not 
	(src < 199 and src >100) 
group by dst 
order by sum(billsec) desc 
)

UNION

(
select  src as 'EXT',
        0 as 'INCNT',
        0 as 'INBS',
        0 as 'INDT',
        count(*) as 'OUTCNT',
        sum(billsec) as 'OUTBS',
        sum(duration) as 'OUTDT' 
from    cdr 
where   (src < 199 and src >100) 
        and not 
        (dst < 199 and dst >100) 
group by src 
order by sum(billsec) desc
)
) as ff 

group by EXT
order by EXT
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


