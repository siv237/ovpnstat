<?php 
// Ищем в файле конфигурации FreePBX логин и пароль к базе
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

// Подключаемся к базе
mysql_pconnect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asteriskcdrdb") or die(mysql_error());

//        max(IN-CNT),
//        max(IN-BS),
//        max(IN-DT),
//        max(OUT-CNT),
//        max(OUT-BS), 
//        max(OUT-DT) 

// Текст запроса к базе


$strdate="BETWEEN STR_TO_DATE('2013-06-14 9:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('2013-06-14 23:59:59', '%Y-%m-%d %H:%i:%s')";

//$curdata=date('Y-m-d');
//$curdata=date('2013-07-13');

//$strdate="BETWEEN STR_TO_DATE('".$curdata." 0:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('".$curdata." 23:59:59', '%Y-%m-%d %H:%i:%s')";


$strSQL = 
("


select 	t1.extension ext,
	LEFT(t1.name,LOCATE('-',t1.name)-1) pref,
	RIGHT(t1.name,LENGTH(t1.name)-LOCATE('-',t1.name)) name,

	t2.ocnt_all ish_vsego,
	t2.ocnt_answ ish_uspesh,
	t2.ocnt_int ish_vnutr,
	t2.ocnt_answ-t2.ocnt_int ish_vnesh,
	SEC_TO_TIME(otime_all) ish_vrem,
	SEC_TO_TIME(owait_all) ish_vozhid, 
	SEC_TO_TIME(obill_all) ish_vrazgv,
	SEC_TO_TIME(obill_int) ish_vraz_vnut,
	SEC_TO_TIME(obill_all-obill_int) ish_vraz_vnesh,
	SEC_TO_TIME((obill_all-obill_int)/(t2.ocnt_answ-t2.ocnt_int)) vsred_obzvon,

	icnt_all vh_vsego,
	icnt_all-icnt_answ vh_prop,
	icnt_answ vh_prnt,
	icnt_int vh_vnut,
	icnt_answ-icnt_int vh_vhesh,
	SEC_TO_TIME(itime_all) vh_vrem,
	SEC_TO_TIME(itime_all-ibill_all) vh_vozhid,
	SEC_TO_TIME(ibill_all) vh_vrazg,
	SEC_TO_TIME(ibill_int) vh_vvnutr,
        SEC_TO_TIME(ibill_all-ibill_int) vh_vvnesh,
	SEC_TO_TIME((ibill_all-ibill_int)/(icnt_answ-icnt_int)) vsred_vh_vnesh
#	t3.* 

from


(select * from asterisk.users) as t1
left join
(select 	src,
		count(src)ocnt_all,
		sum(if(dst in(select extension from asterisk.users) and disposition = 'ANSWERED',1,0))ocnt_int,
		sum(if(disposition = 'ANSWERED',1,0))ocnt_answ,

		sum(duration)otime_all,
		sum(duration)-sum(billsec)owait_all,
		sum(billsec)obill_all,
		sum(if(dst in(select extension from asterisk.users),billsec,0))obill_int

	from cdr 
	where 
	calldate $strdate group by src) as t2
on t1.extension=t2.src
left join
(select         dst,
                count(src)icnt_all,
		sum(if(src in(select extension from asterisk.users) and disposition = 'ANSWERED',1,0))icnt_int,
		sum(if(disposition = 'ANSWERED',1,0)) icnt_answ,

		sum(duration)itime_all,
                sum(duration)-sum(billsec)iwait_all,
                sum(billsec)ibill_all,
		sum(if(src in(select extension from asterisk.users),billsec,0))ibill_int
        from cdr 
        where 
        calldate $strdate group by dst) as t3
on t1.extension=t3.dst

order by pref,ish_vnesh desc ,vh_vhesh desc




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


