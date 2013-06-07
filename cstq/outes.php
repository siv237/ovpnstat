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


select 

     src,max(datetime)

from

    ((select 

        src, max(calldate) as datetime, 'in' as tr

    from

        cdr

    where

        dstchannel NOT REGEXP '[local]'

            and dst IN (010 , 011, 012, 016,905)

            and calldate > (NOW() - INTERVAL 1 day)

    group by src) UNION (select 

       RIGHT (dst,10) as src, max(calldate) as datetime, 'out'

    from

        cdr

    where

        calldate > (NOW() - INTERVAL 1 day)

            and disposition = 'ANSWERED'

            and dst >= '7'

    group by dst)) as st

where

    tr = 'in'

group by src

order by datetime


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


