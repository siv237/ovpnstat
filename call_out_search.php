
<?php
include 'formatnum.php'; // FormatTelNum() Форматирование и геостатус номера
include 'funtime.php';  // showDate() Функция - примерное время

include 'page-css.php';    
include 'connect-script/mysql_connect.php';
//include 'css.php';
//include 'css/button_css.php';


$dt=getdate();

//if(isset($_GET[date_from])) {$date_from=$_GET[date_from];} else {$date_from=date('m/d/Y',($dt[0]-604800));}
if(isset($_GET[date_from])) {$date_from=$_GET[date_from];} else {$date_from=date('m/d/Y');}
if(isset($_GET[time_from])) {$time_from=$_GET[time_from];} else {$time_from='08:30:00';}
if(isset($_GET[date_to])) {$date_to=$_GET[date_to];} else {$date_to=date('m/d/Y',$dt[0]);}
//if(isset($_GET[time_to])) {$time_to=$_GET[time_to];} else {$time_to=date('H:i:s',$dt[0]);}
if(isset($_GET[time_to])) {$time_to=$_GET[time_to];} else {$time_to='23:59:59';}

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
Искать с:   <input type='text'   name='date_from' class='tcal' value='".$date_from."' SIZE=8>
            <input type='text'   name='time_from'              value='".$time_from."' SIZE=4> 
по:         <input type='text'   name='date_to'   class='tcal' value='".$date_to."'   SIZE=8>
            <input type='text'   name='time_to'                value='".$time_to."'   SIZE=4> 
            <input type='submit'>
</form>
";
//                        and c.dst IN ($str_find)
if ($str_find==''){$q_str='';}else{$q_str="and c.dst IN (".$str_find.")";}

mysql_select_db("asteriskcdrdb") or die(mysql_error());


//$curdata=date('Y-m-d');
//echo $date_from;
$strdate="BETWEEN STR_TO_DATE('".$date_from." ".$time_from."', '%m/%d/%Y %H:%i:%s') AND STR_TO_DATE('".$date_to." ".$time_to."', '%m/%d/%Y %H:%i:%s')";
//запрос
$zapros =("

select 
    concat('8',RIGHT(x.src,10)), x.dt,x.dd
from
    (select 
        c.src, max(c.calldate) as dt,c.dst as dd
    from
        cdr as c
    where
        LENGTH(c.src) >= 10
            and c.dstchannel = ''
            and c.calldate $strdate
	    and lastapp='Queue'
			$q_str
group by c.src) x
        left join 
(select 
        f.dst, f.calldate , f.src , f.disposition
    from
        cdr as f
    where
        LENGTH(concat('".$str_kodg."',f.dst)) >= 10
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
echo $row[2]." <a href=orgntform.php?to=".$row[0].">".FormatTelNum($row[0])."</a> пропущен ".showDate(strtotime($row[1])-strtotime(time()))." назад в ".$row[1]."<br>";
 }

//echo "</td></table>";
//echo '<meta http-equiv="refresh" content="30">';
?>

