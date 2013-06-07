
<div class="headmenu"
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Упущенные звонки</title>
	<meta name="author" content="gaynulin" />
	<!-- Date: 2013-04-16 -->
</head>
<body>
<br>



<?php

include 'page-css.php';    
include 'connect-script/mysql_connect.php';
include 'css.php';
include 'css/button_css.php';


mysql_select_db("asteriskcdrdb") or die(mysql_error());

echo "
<form method='POST' action='call_out.php'>";
echo "<input type ='submit' class='button red' value='Обновить список упущенных звонков' ' />";
echo"</form>";

//запрос
$zapros =("select 
    x.src, x.dt
from
    (select 
        c.src, max(c.calldate) as dt
    from
        cdr as c
    where
        LENGTH(c.src) >= 10
            and c.dstchannel = ''
            and c.calldate BETWEEN STR_TO_DATE('2013-06-07 09:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('2013-06-07 23:59:59', '%Y-%m-%d %H:%i:%s')
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
            and f.calldate  BETWEEN STR_TO_DATE('2013-06-07 09:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('2013-06-07 23:59:59', '%Y-%m-%d %H:%i:%s'))

as f ON RIGHT(f.dst, 10) = RIGHT(x.src, 10)
        and f.calldate BETWEEN STR_TO_DATE('2013-06-07 09:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('2013-06-07 23:59:59', '%Y-%m-%d %H:%i:%s')
        and f.calldate > x.dt
        and f.disposition = 'ANSWERED'
  
where
    f.src is NULL order  by  x.dt");
 
/*$zapros=("SELECT t2.mt,src FROM cdr,(select max(calldate) as mt from cdr 
where calldate BETWEEN STR_TO_DATE('2013-06-03 14:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('2013-06-03 23:59:59', '%Y-%m-%d %H:%i:%s') 

group by src) as t2 where cdr.calldate=t2.mt 
 and 	LENGTH(cdr.src) >= 10
 and 	(cdr.dstchannel='' 
	or not (
		cdr.disposition REGEXP '[ANSWERED]'
		and cdr.dstchannel !=''
		)
	)

order by cdr.calldate");
*/
$call=mysql_query($zapros);
echo "<table>";

echo "<tr><th>Номер телефона<th>Дата звонка от клиента<th></th>";
while($row = mysql_fetch_row($call)) 
{

echo "<tr>";
  for($i=0 ; $i<=count($row); $i++) 
{echo"<td> $row[$i]" ; }

}
echo "</td></table>";

?>

