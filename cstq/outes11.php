<?php 
// Ищем в файле конфигурации FreePBX логин и пароль к базе
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

// Подключаемся к базе
mysql_pconnect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asteriskcdrdb") or die(mysql_error());

// max(IN-CNT), // max(IN-BS), // max(IN-DT), // max(OUT-CNT), // max(OUT-BS), // max(OUT-DT)

$strdate="BETWEEN STR_TO_DATE('2013-06-10 9:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('2013-06-13 23:59:59', '%Y-%m-%d %H:%i:%s')";

$strSQL = 
("


select s1.*,s2.* from
(



select * from
(select 	t1.numb as Phone,
	t1.mdate as IN_FAIL, 
	if(isnull(t2.mdate),FROM_UNIXTIME(0),t2.mdate) as LastIN,
	if(isnull(t3.mdate),FROM_UNIXTIME(0),t3.mdate) as LastOUT

 from 
(
select max(calldate)as mdate,RIGHT(src,10)as numb from cdr where calldate $strdate
        and LENGTH(RIGHT(src,10)) = 10
        and dstchannel=''
        and lastapp='Queue'
group by RIGHT(src,10)
)as t1

left join

(select max(calldate) as mdate,RIGHT(src,10)as numb from cdr where calldate $strdate
        and LENGTH(RIGHT(src,10)) = 10
        and lastapp='Dial'
        and disposition='ANSWERED'
group by RIGHT(src,10)
)as t2
on t2.numb=t1.numb

left join

(select max(calldate) as mdate,RIGHT(dst,10)as numb from cdr where calldate $strdate
        and LENGTH(RIGHT(concat('4212',dst),10)) = 10
        and lastapp='Dial'
        and disposition='ANSWERED'
group by RIGHT(dst,10)
) as t3 
on t1.numb=t3.numb
order by t1.mdate

) as result

where
IN_FAIL>LastIN and IN_FAIL>LastOUT
)as s1

left join

(select * from cdr where calldate $strdate)as s2

on s1.IN_FAIL=s2.calldate
and s1.Phone=RIGHT(s2.src,10)




where s2.dst='010'






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
