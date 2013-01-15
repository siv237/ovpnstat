<?php 
include 'formatnum.php'; // FormatTelNum() Форматирование и геостатус номера



// Ищем в файле конфигурации FreePBX логин и пароль к базе
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

mysql_connect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asterisk") or die(mysql_error());


$strSQL = ("select extension from users");
mysql_query("SET lc_time_names = 'ru_RU'");
$rs = mysql_query($strSQL);

//Для src "and (src='42' or src='41' or src='04')"
$str_src="(";

while($row = mysql_fetch_array($rs)) 
 {$str_src=$str_src."src='".$row[0]."' or ";}

$str_src=$str_src."src='0')";

//Строка для dst
$rs = mysql_query($strSQL);
$str_dst="(";
while($row = mysql_fetch_array($rs)) 
 {$str_dst=$str_dst."dst='".$row[0]."' or ";}

$str_dst=$str_dst."src='0')";

// Ищем в файле конфигурации FreePBX логин и пароль к базе
//$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
//$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

// Запрос к базе CDR
mysql_connect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asteriskcdrdb") or die(mysql_error());

$strSQL = 
("
 (	select 	calldate,
		clid,
		src,
		dst,
		duration,
		billsec,
		uniqueid,
		'OUT' 
	from 	cdr 
	where 	lastapp='Dial' 
	and 	".$str_src." 
	and not	".$str_dst." 
	order 	by calldate 
	desc 	limit 100
 )
	union
 (
        select  calldate,
                clid,
                dst,
                src,
                duration,
                billsec,
                uniqueid,
                'IN' 
        from    cdr 
        where   disposition='ANSWERED' 
        and     ".$str_dst." 
        and not ".$str_src." 
        order   by calldate  desc    
	limit 100
 )
order   by calldate desc
limit 200

");

mysql_query("SET lc_time_names = 'ru_RU'");
$rs = mysql_query($strSQL);

echo "<table border='1'>";
echo "<tr><td>Дата звонка<td>Внутренний<td>Направление<td>Внешний<td>CLID<td>Разговор<td>Ожидание<td>ID</td>";

while($row = mysql_fetch_array($rs))

{

echo "<tr>" .
               "<td>" . $row[0] . 
               "<td>" . $row[2] .
               "<td>" . $row[7] .
               "<td>" . FormatTelNum($row[3]) .
               "<td>" . $row[1] .
               "<td>" . $row[5] .
               "<td>" . ($row[4]-$row[5]) .
               "<td>" . $row[6] .
               "</td>";

}


        // Закрыть соединение с БД
        mysql_close();


?>



