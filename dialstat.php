<?php 
include 'formatnum.php'; // FormatTelNum() Форматирование и геостатус номера
include 'localname.php';

// Считываем переданые параметры поиска и если их нет задаем дефолты
$date_to=$_POST["date_to"];
$date_from=$_POST["date_from"];
$str_find=$_POST["str_find"];
if ($_POST["str_limit"] == "" ) 
	{$str_limit="100";}
else	{$str_limit=$_POST["str_limit"];}

if(!isset($date_from)){$date_from=date("m/01/Y");}
if(!isset($date_to)){$date_to=date("m/d/Y");}

// Рисуем форму для поиска
//echo "<table border='0'><td><td><td>Поиск<td>Лимит<td></tr>";
echo "<table border='0'>";
echo "
<form method='post' action=''>

<! -- Добавляем скрипт календаря для удобства задания периода в форме поиска/--!>

 <link rel='stylesheet' type='text/css' href='cal/tcal.css' />
 <script type='text/javascript' src='cal/tcal.js'></script>

<td>	Дата начала: <input type='text' name='date_from' class='tcal' value='".$date_from."' SIZE=8> 
<td>	Дата окончания: <input type='text' name='date_to' class='tcal' value='".$date_to."' SIZE=8>
<td>	Поиск: <input type='text' name='str_find' value='".$str_find."'>
<td>	Лимит: <input type='text' name='str_limit' value='".$str_limit."' SIZE=4>
<td>	<input type='submit' name='submit' value='Найти'>
</td>
</table>
</form>
<br>
";
$FindDate="and (calldate BETWEEN STR_TO_DATE('".$date_from." 00:00:00','%m/%d/%Y %H:%i:%s') AND STR_TO_DATE('".$date_to." 23:59:59','%m/%d/%Y %H:%i:%s'))";
$FindStr="and concat(clid,'|',src,'|',dst,'|',uniqueid,'|',recordingfile) like '%".$str_find."%'";


// Ищем в файле конфигурации FreePBX логин и пароль к базе
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

mysql_connect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asterisk") or die(mysql_error());


$strSQL = ("select * from users");
mysql_query("SET lc_time_names = 'ru_RU'");
$rs_users = mysql_query($strSQL);


//Для src "and (src='42' or src='41' or src='04')"
$str_src="(";

while($row = mysql_fetch_array($rs_users)) 
 {$str_src=$str_src."src='".$row[0]."' or ";}

$str_src=$str_src."src='0')";

//Строка для dst
$rs = mysql_query($strSQL);
$str_dst="(";
while($row = mysql_fetch_array($rs)) 
 {$str_dst=$str_dst."dst='".$row[0]."' or ";}

$str_dst=$str_dst."src='0')";

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
		'&#9658',
		recordingfile 
	from 	cdr 
	where 	lastapp='Dial' 
	and 	".$str_src." 
	and not	".$str_dst." 
	$FindDate
	$FindStr
	order 	by calldate desc 	
	limit $str_limit
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
                '&#9668',
		recordingfile 
        from    cdr 
        where   disposition='ANSWERED' 
        and     ".$str_dst." 
        and not ".$str_src." 
        $FindDate
	$FindStr
	order   by calldate  desc    
	limit $str_limit
 )

        union
 (
        select  calldate,
                clid,
                src,
                dst,
                duration,
                billsec,
                uniqueid,
                'T&#9658',
                recordingfile 
        from    cdr 
        where   disposition='ANSWERED' 
        and not ".$str_dst." 
        and not ".$str_src." 
	and lastapp='Dial'
	$FindDate
	$FindStr
        order   by calldate  desc    
        limit $str_limit
 )


order   by calldate desc
limit $str_limit

");

mysql_query("SET lc_time_names = 'ru_RU'");
$rs = mysql_query($strSQL);

echo "<table border='1'>";
echo "<tr><th>Дата звонка<th>Внутренний<th>Напр.<th>Внешний<th>CLID<th>Разговор<th>Ожидание<th>ID<th>Запись</td>";
while($row = mysql_fetch_array($rs))

{
if($row[8] !=''){$dwn="<a href=mon.php?recordingfile=$row[8] target='mon'>Скачать</a>";}else{$dwn="";}
echo "<tr>" .
               "<td>" . $row[0] . 
               "<td>" . LocalName($row[2]) .
               "<td align='center'>" . $row[7] .
               "<td>" . FormatTelNum($row[3]) .
               "<td>" . $row[1] .
               "<td>" . $row[5] .
               "<td>" . ($row[4]-$row[5]) .
               "<td>" . $row[6] .
               "<td>" . $dwn .

               "</td>";

}


        // Закрыть соединение с БД
        mysql_close();


?>



