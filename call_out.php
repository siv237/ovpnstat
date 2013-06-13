
<div class="headmenu"
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Пропущенные звонки</title>
	<meta name="author" content="gaynulin" />
	<!-- Date: 2013-04-16 -->
</head>
<body>
<br>


<?php
include 'formatnum.php'; // FormatTelNum() Форматирование и геостатус номера
include 'funtime.php';  // showDate() Функция - примерное время

include 'page-css.php';    
include 'connect-script/mysql_connect.php';
include 'css.php';
include 'css/button_css.php';

if($_COOKIE['CooKodG']==''){echo "<a href=menu_callfail.php target='search'>Код города не задан!</a>";}else{echo "Код города: ".$_COOKIE['CooKodG'];}
echo "<br>";
if($_COOKIE['CooQStr']==''){echo "<a href=menu_callfail.php target='search'>Номер очереди не задан!</a>";}else{echo "Номер очереди: ".$_COOKIE['CooQStr'];}

mysql_select_db("asteriskcdrdb") or die(mysql_error());

echo "
<form method='POST' action='call_out.php'>";
echo "<input type ='submit' class='button red' value='Обновить список пропущенных звонков' ' />";
echo"</form>";

$curdata=date('Y-m-d');

$strdate="BETWEEN STR_TO_DATE('".$curdata." 08:30:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('".$curdata." 23:59:59', '%Y-%m-%d %H:%i:%s')";
//запрос


if ($str_find==''){$q_str='';}else{$q_str="where s2.dst='".$_COOKIE['CooQStr']."'";}
$str_kodg=$_COOKIE['CooKodG'];



mysql_select_db("asteriskcdrdb") or die(mysql_error());


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
        and LENGTH(RIGHT(concat('".$str_kodg."',dst),10)) = 10
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




$q_str





");
 
$call=mysql_query($zapros);
while($row = mysql_fetch_row($call)) 
{

echo 	$row[7]." <a href=orgntform.php?to=8".
	$row[0].">".FormatTelNum($row[0])."</a> пропущен ".
	showDate(strtotime($row[1])-strtotime(time()))." назад (".$row[1].")<br>";
 }










//echo "</td></table>";
echo '<meta http-equiv="refresh" content="30">';
?>

