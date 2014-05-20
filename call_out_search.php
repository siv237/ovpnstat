
<?php
include 'formatnum.php'; // FormatTelNum() Форматирование и геостатус номера
include 'funtime.php';  // showDate() Функция - примерное время
include 'ncal.php'; // Поле календаря class='datepickerTimeField'


include 'page-css.php';    
include 'connect-script/mysql_connect.php';




$dt=getdate();

if(isset($_GET[date_from])) {$date_from=$_GET[date_from];} else {$date_from=date('d.m.Y 08:30:00');}
if(isset($_GET[date_to])) {$date_to=$_GET[date_to];} else {$date_to=date('d.m.Y 23:59:59');}

if(isset($_GET[str_find])) 	{
				$str_find=$_GET[str_find];
				SetCookie('CooQStr',$str_find,0x6FFFFFFF);
				} 
else 				{$str_find=$_COOKIE['CooQStr'];}

if(isset($_GET[str_kodg])) 	{$str_kodg=$_GET[str_kodg];
				SetCookie('CooKodG',$str_kodg,0x6FFFFFFF);
				} 
else 				{$str_kodg= $_COOKIE['CooKodG'];}


echo "
<! -- Добавляем скрипт календаря для удобства задания периода в форме поиска/--!>
<form method='get' action=''>
 <link rel='stylesheet' type='text/css' href='cal/tcal.css' />
 <script type='text/javascript' src='cal/tcal.js'></script>
Код города: <input type='text'   name='str_kodg'              value='".$str_kodg."' SIZE=2><br>
Номер очереди: <input type='text'   name='str_find'              value='".$str_find."' SIZE=2>
<br>
Искать с:   <input type='text'   name='date_from' class='datepickerTimeField' value='".$date_from."' SIZE=14>
по:         <input type='text'   name='date_to'   class='datepickerTimeField' value='".$date_to."'   SIZE=14>
            <input type='submit'>
</form>
";
if ($str_find==''){$q_str='';}else{$q_str="where s2.dst='".$str_find."'";}

mysql_select_db("asteriskcdrdb") or die(mysql_error());


$strdate="BETWEEN STR_TO_DATE('".$date_from."', '%d.%m.%Y %H:%i:%s') AND STR_TO_DATE('".$date_to."', '%d.%m.%Y %H:%i:%s')";
$zapros =("



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
        and lastapp IN ('Goto', 'Queue')
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
        and LENGTH(RIGHT(concat('".$str_kodg."',dst),10)) = 10
        and lastapp='Dial'
        and disposition='ANSWERED'
group by RIGHT(dst,10)
) as t3 
on t1.numb=t3.numb

) as result

where
IN_FAIL>LastIN and IN_FAIL>LastOUT
)as s1

left join

(select * from cdr where calldate $strdate)as s2

on s1.IN_FAIL=s2.calldate
and s1.Phone=RIGHT(s2.src,10)




#where s2.dst='".$str_find."'
$q_str

order by s1.IN_FAIL desc




");
 
$call=mysql_query($zapros);

$countcall=0;
$cw=0;

while($row = mysql_fetch_row($call)) 
{
$countcall++;
$cw=$cw+$row[13];

echo 	$row[7]." <a href=orgntform.php?to=8".
	$row[0].">".FormatTelNum($row[0])."</a> пропущен ".
	showDate( strtotime($row[1]) )." назад (".$row[1].") ожидал: ".
	$row[13]." сек <a href=monitor.php?id=".$row[18].">подробно</a>".
	"<br>";
 }
echo "<b>Всего пропущено звонков: ".$countcall." с общим временем ожидания: ".Round($cw/60,1)." мин.
<br>Среднее время ожидания: ".Round($cw/60/$countcall,1)." мин.</b>";

?>

