<?php 
include 'ncal.php'; // Поле календаря class='datepickerTimeField'
include 'formatnum.php'; // FormatTelNum() Форматирование и геостатус номера
include 'localname.php';
include 'translit.php'; # Функция latrus()


// Считываем переданые параметры поиска и если их нет задаем дефолты
$date_to=$_GET["date_to"];
$date_from=$_GET["date_from"];
$str_find=$_GET["str_find"];
if ($_GET["str_limit"] == "" ) 
	{$str_limit="100";}
else	{$str_limit=$_GET["str_limit"];}

if(!isset($date_from)){$date_from=date("d.m.Y 00:00:00");}
if(!isset($date_to)){$date_to=date("d.m.Y 23:59:59");}

// Рисуем форму для поиска
echo "<table border='0'>";
echo "
<form method='get' action=''>

<td>   Дата начала:    <input type='text' name='date_from' value='".$date_from."' class='datepickerTimeField' size=14>
<td>   Дата окончания: <input type='text' name='date_to'   value='".$date_to."'   class='datepickerTimeField' size=14>
<td>	Поиск: <input type='text' name='str_find' value='".$str_find."'>
<td>	<input type='submit' value='Найти'>
</td>
</table>
</form>
<br>
";


// Ищем в файле конфигурации FreePBX логин и пароль к базе
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

mysql_connect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asteriskcdrdb") or die(mysql_error());


// Текст запроса к базе


$strdate="BETWEEN STR_TO_DATE('".$date_from."', '%d.%m.%Y %H:%i:%s') AND STR_TO_DATE('".$date_to."', '%d.%m.%Y %H:%i:%s')";

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
	SEC_TO_TIME((ibill_all-ibill_int)/(icnt_answ-icnt_int)) vsred_vh_vnesh,

	SEC_TO_TIME(if(isnull(itime_all),0,itime_all)+if(isnull(otime_all),0,otime_all)) all_time,
	SEC_TO_TIME(if(isnull(ibill_all),0,ibill_all)+if(isnull(obill_all),0,obill_all)) all_time_ozh,	
	SEC_TO_TIME(if(isnull(ibill_all-ibill_int),0,ibill_all-ibill_int)+if(isnull(obill_all-obill_int),0,obill_all-obill_int)) all_time_vnesh	
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
        calldate $strdate
	group by dst) as t3
on t1.extension=t3.dst
where not (isnull(t2.ocnt_all) and isnull(icnt_all)) and
CONCAT_WS('|',t1.extension,LEFT(t1.name,LOCATE('-',t1.name)-1),RIGHT(t1.name,LENGTH(t1.name)-LOCATE('-',t1.name))) like ('%".$str_find."%')
order by all_time_vnesh desc


");
//echo $strSQL;

// Выполняем запрос
$rs = mysql_query($strSQL);
//	<th><div id='rotateText'>Номер</div>
//	<th><div id='rotateText'>Префикс</div>

echo "<table border='1'>";
echo "
<tr align='center'>
        <th>Номер
        <th>Префикс
	<th>Оператор
	<th>Исходящие<br>Внешние
	<th>Входящие<br>Внешние
	<th>Не дозвонился /<br>пропущено
        <th>Среднее время внешних<br>(исходящих / входящих)
	<th>Общее время<br>разговоров
	<th>Время внешних<br>разговоров

</td>";


// Извлекаем значения и формируем таблицу результатов
while($id=mysql_fetch_row($rs))
	{ 
echo "<tr>" .
               "<td><a href='menu_dialstat.php?str_find=%7C".$id[0]."%7C'>" . $id[0] ."</a>". 
               "<td>" . $id[1] . 
               "<td title=\"".$id[2]."\">" . latrus($id[2]) . 
               "<td align='center' title='Всего исходящих звонков ".$id[3]." общей продолжительностью ".$id[9].
			" из них внутренних: ".$id[5]." продолжительностью ".$id[10]."'>
			".$id[6]." - ".$id[11]. 

               "<td align='center' title='Всего входящих звонков ".$id[13]." общей продолжительностью ".$id[18].
                        " из них внутренних: ".$id[16]." продолжительностью ".$id[21]."'>
                        ".$id[17]." - ".$id[22].
               "<td align='center'>" . ($id[3]-$id[4]) . "/" .$id[14].
               "<td align='center'>" . $id[12]." / ". $id[23] . 
               "<td align='center' title='Общая занятость линии с учетом ожидания и неответов на звонки: ".$id[24]."'>" . $id[25] . 
		"<td align='center'>" .  $id[26].
               "</td>";

$sum_incoming =$sum_incoming+$id[17]-$id[22];//Суммируем (количество принятых)


	}
echo "</td></table>";
echo "ИТОГО ПРИНЯТЫХ: ".$sum_incoming //Вывод строчки вместе со значением переменной



?>



