<?php 
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
$chan='redcom';
$grdate='%d.%m.%Y %W';
$wh="where (channel like '%".$chan."%' and dst !='s') or (dstchannel like '%".$chan."%')";

//	max(if(@a+incr<0,@a:=0,@a:=@a+incr)) as maxchannel,
//	avg(if(@a+incr<0,@a:=0,@a:=@a+incr)) as avgchannel


$strSQL = 
("
select 	FROM_UNIXTIME(tevent),
	@a0:=if(dst != 's' and channel like '%".$chan."%',if(@a+incr<0,0,@a:=@a+incr),'') as c_in,
	@b0:=if(dstchannel like '%".$chan."%',if(@b+incr<0,0,@b:=@b+incr),'') as c_out,
	if(tevent-@c>14400,@a:=0 and @b:=0,0),
	@c:=tevent

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


