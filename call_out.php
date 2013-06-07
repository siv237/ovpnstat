
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


mysql_select_db("asteriskcdrdb") or die(mysql_error());

echo "
<form method='POST' action='call_out.php'>";
echo "<input type ='submit' class='button red' value='Обновить список пропущенных звонков' ' />";
echo"</form>";

$curdata=date('Y-m-d');

$strdate="BETWEEN STR_TO_DATE('".$curdata." 08:30:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('".$curdata." 23:59:59', '%Y-%m-%d %H:%i:%s')";
//запрос
$zapros =("

select 
    concat('8',RIGHT(x.src,10)), x.dt
from
    (select 
        c.src, max(c.calldate) as dt
    from
        cdr as c
    where
        LENGTH(c.src) >= 10
            and c.dstchannel = ''
            and c.calldate $strdate
	    and lastapp='Queue'
			and c.dst IN (010)
group by c.src) x
        left join 
(select 
        f.dst, f.calldate , f.src , f.disposition
    from
        cdr as f
    where
        LENGTH(f.dst) >= 10
            and f.disposition = 'ANSWERED'
            and f.calldate  $strdate)

as f ON RIGHT(f.dst, 10) = RIGHT(x.src, 10)
        and f.calldate $strdate
        and f.calldate > x.dt
        and f.disposition = 'ANSWERED'
  
where
    f.src is NULL order  by  x.dt desc

");
 
$call=mysql_query($zapros);
//echo "<table>";

//echo "<br>";
while($row = mysql_fetch_row($call)) 
{

//echo "<tr>";
//  for($i=0 ; $i<=count($row); $i++) 
//{echo"<td> $row[$i]" ; }
//
//}
echo "<a href=orgntform.php?to=".$row[0].">".FormatTelNum($row[0])."</a> пропущен ".showDate(strtotime($row[1])-strtotime(time()))." назад<br>";
 }

//echo "</td></table>";
echo '<meta http-equiv="refresh" content="30">';
?>

