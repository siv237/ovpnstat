<?php 

// Ищем в файле конфигурации FreePBX логин и пароль к базе
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

mysql_connect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asterisk") or die(mysql_error());


$strSQL = ("select extension from users");

 mysql_query("SET lc_time_names = 'ru_RU'");
 $rs = mysql_query($strSQL);
$str="(";

	while($row = mysql_fetch_array($rs)) 
{
$str=$str."src='".$row[0]."' or ";
}
$str=$str."src='0')";
//echo $str;
	// Закрыть соединение с БД
	mysql_close();

// and (src='42' or src='41' or src='04')
//select calldate,clid,src,dst,duration,billsec from cdr where lastapp='Dial' and (src='42' or src='41' or src='04') order by calldate desc limit 100;

// Ищем в файле конфигурации FreePBX логин и пароль к базе
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

mysql_connect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asteriskcdrdb") or die(mysql_error());

$strSQL = 
("
	select 	calldate,
		clid,
		src,
		dst,
		duration,
		billsec
	from 	cdr 
	where 	lastapp='Dial' 
	and 	".$str." 
	order 	by calldate 
	desc 	limit 100
");

//echo $strSQL;
include 'formatnum.php'; // FormatTelNum() Форматирование и геостатус номера


 mysql_query("SET lc_time_names = 'ru_RU'");
 $rs = mysql_query($strSQL);

echo "<table border='1'>";
echo "<tr><th ALIGN=left>Дата звонка</th><th>Кто</th><th>Кто</th><th></th><th>Кому</th><th>Разговор</th><th>Ожидание</th></th></tr>";

while($row = mysql_fetch_array($rs))

{

echo "<tr>" .
               "<td>" . $row[0] . 
               "<td>" . $row[1] .
               "<td>" . $row[2] .
               "<td ALIGN=centre>" . ">" .
               "<td>" . FormatTelNum($row[3]) .
               "<td>" . $row[5] .
               "<td>" . ($row[4]-$row[5]) .
               "</td>";

//echo $row[0]." ".$row[1]." ".$row[3]." ".$row[4]."\n";

}
        // Закрыть соединение с БД
        mysql_close();


	?>



