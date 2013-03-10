<?php 
$dt=getdate();

if(isset($_GET[date_from])) {$date_from=$_GET[date_from];} else {$date_from=date('m/d/Y',($dt[0]-604800));}
if(isset($_GET[time_from])) {$time_from=$_GET[time_from];} else {$time_from='00:00:00';}
if(isset($_GET[date_to])) {$date_to=$_GET[date_to];} else {$date_to=date('m/d/Y',$dt[0]);}
//if(isset($_GET[time_to])) {$time_to=$_GET[time_to];} else {$time_to=date('H:i:s',$dt[0]);}
if(isset($_GET[time_to])) {$time_to=$_GET[time_to];} else {$time_to='23:59:59';}

if(isset($_GET[str_find])) {$str_find=$_GET[str_find];} else {$str_find='local';}
if(isset($_GET[str_limit])) {$str_limit=$_GET[str_limit];} else {$str_limit='100';}
if(isset($_GET[grdate])) {$grdate=$_GET[grdate];} else {$grdate='%d.%m.%Y';}

$name=basename($_SERVER['SCRIPT_NAME']);
//Форма выборки
echo "
<! -- Добавляем скрипт календаря для удобства задания периода в форме поиска/--!>
<form method='get' action=''>
 <link rel='stylesheet' type='text/css' href='cal/tcal.css' />
 <script type='text/javascript' src='cal/tcal.js'></script>
Искать с:   <input type='text'   name='date_from' class='tcal' value='".$date_from."' SIZE=8>
            <input type='text'   name='time_from'              value='".$time_from."' SIZE=4> 
по:         <input type='text'   name='date_to'   class='tcal' value='".$date_to."'   SIZE=8>
            <input type='text'   name='time_to'                value='".$time_to."'   SIZE=4> 
<br><a href='cstq/namechan.php' target='all channel'>Канал:<a>  <input type='text'   name='str_find'               value='".$str_find."'>
Лимит строк:<input type='text'   name='str_limit'              value='".$str_limit."' SIZE=4>
<br>Группировка по <a href='http://dev.mysql.com/doc/refman/5.6/en/date-and-time-functions.html#function_date-format' target='man'>
формату даты</a>: <input type='text'   name='grdate'                value='".$grdate."'>
            <input type='submit'>
</form>
";

if(empty($_GET)){echo "<br>Задайте критерий поиска";}
else{

// Ищем в файле конфигурации FreePBX логин и пароль к базе
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

// Подключаемся к базе
mysql_pconnect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asteriskcdrdb") or die(mysql_error());

// Текст запроса к базе
mysql_query("set @a:=0");
mysql_query("set @b:=0");
mysql_query("set @c:=0");
//$chan="'%local%'";
$chan=$str_find;
//$grdate='%d.%m.%Y ';
$from_datetime=$date_from." ".$time_from;
$to_datetime=$date_to." ".$time_to;

$wh="
where ((channel like '%".$chan."%' 
	and dst !='s') 
or (dstchannel like '%".$chan."%')) 
and (calldate BETWEEN STR_TO_DATE('".$from_datetime."', '%m/%d/%Y %H:%i:%s') 
and STR_TO_DATE('".$to_datetime."', '%m/%d/%Y %H:%i:%s'))

";

//	max(if(@a+incr<0,@a:=0,@a:=@a+incr)) as maxchannel,
//	avg(if(@a+incr<0,@a:=0,@a:=@a+incr)) as avgchannel
//group by DATE_FORMAT(FROM_UNIXTIME(tevent),'".$grdate."')

//	@c:=tevent,
//		dtime,max_src,max_dst,avg_src, avg_dst

$strSQL = ("

select dtime,max_src,max_dst,(max_src+max_dst)as max_sum,avg_src,avg_dst,(avg_src+avg_dst) as avg_sum
from
(
select 	

	DATE_FORMAT(FROM_UNIXTIME(tevent),'".$grdate."') as dtime,
	max(@a2:=if(dst != 's' and channel like '%".$chan."%',if(@a+incr<0,0,@a:=@a+incr),@a)) as max_src,
	max(@b2:=if(dstchannel like '%".$chan."%',if(@b+incr<0,0,@b:=@b+incr),@b)) as max_dst,
	if(tevent-@c>14400,@a:=0 and @b:=0,0) as x,
	@c:=tevent,
	round(avg(@a2),2) as avg_src,
	round(avg(@b2),2) as avg_dst





from ((select
	UNIX_TIMESTAMP(calldate) as tevent,
	channel,
	dstchannel,
	dst,
	src,
	1 as incr

from cdr 
".$wh."
)

union

(
select
        UNIX_TIMESTAMP(calldate)+duration as tevent,
        channel,
        dstchannel,
	dst,
	src,
	-1 as incr

from cdr 
".$wh."
)
order by tevent) as tb1
group by DATE_FORMAT(FROM_UNIXTIME(tevent),'".$grdate."')
order by tevent
limit ".$str_limit."
) as tb3
");

// Выполняем запрос
$rs = mysql_query($strSQL);

echo "<a href='".$name."'>Очистить</a><br>";
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
}
?>
