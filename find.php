<?php 
// Считываем переданые параметры поиска и если их нет задаем дефолты
$date_to=$_POST["date_to"];
$date_from=$_POST["date_from"];
$str_find=$_POST["str_find"];
if ($_POST["str_limit"] == "" ) 
	{$str_limit="100";}
else	{$str_limit=$_POST["str_limit"];}

// Рисуем форму для поиска
echo "<table border='0'><td>Дата начала<td>Дата окончания<td>Поиск<td>Лимит<td></tr>";
echo "
<form method='post' action=''>

<! -- Добавляем скрипт календаря для удобства задания периода в форме поиска/--!>

 <link rel='stylesheet' type='text/css' href='cal/tcal.css' />
 <script type='text/javascript' src='cal/tcal.js'></script>

<td>	<input type='text' name='date_from' class='tcal' value='".$date_from."'> 
<td>	<input type='text' name='date_to' class='tcal' value='".$date_to."'>
<td>	<input type='text' name='str_find' value='".$str_find."'>
<td>	<input type='text' name='str_limit' value='".$str_limit."' SIZE=4>
<td>	<input type='submit' name='submit' value='Найти'>
</td>
</table>
</form>
";

// Ищем в файле конфигурации FreePBX логин и пароль к базе
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

// Подключаемся к базе
mysql_connect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asteriskcdrdb") or die(mysql_error());

// Извлекаем список полей для глобального поиска (в разных версиях разная структура)
$fields = mysql_list_fields("asteriskcdrdb", "cdr");
$columns = mysql_num_fields($fields);

for ($i = 0; $i < $columns; $i++) {
    $a=$a.",".mysql_field_name($fields, $i);
}
$ListNames=trim($a,",");

// Задаем условия поиска в зависимости от заполнения полей поисковой формы
if ($str_find == "") 
	{ $FindAll="where uniqueid != ''";}
	else
	{
	 $FindAll="where concat_ws('|',$ListNames) like ('%".$str_find."%')";
	}

if 	( $date_to == ""  and $date_from == "") 
		{ echo "Внимание, период не задан!";}
	else 	{ 
		$FindDate="and (calldate BETWEEN STR_TO_DATE('".$date_from." 00:00:00','%m/%d/%Y %H:%i:%s') AND STR_TO_DATE('".$date_to." 23:59:59','%m/%d/%Y %H:%i:%s'))";
		}
// Собираем из кусочков полный текст запроса к базе
$strSQL = 
("
	select 	* 
	from cdr 
	".$FindAll." 
	".$FindDate." 
	order by calldate desc 
	limit ".$str_limit 
);

// Выполняем запрос
$rs = mysql_query($strSQL);

echo "<br>Запрос к базе:<br>".$strSQL."<br>";
echo "<table border='1'>";

// Вытаскиваем имена полей и формируем заголовок таблицы результатов
$field = mysql_num_fields( $rs );

echo "<tr>";
for ( $i = 0; $i < $field; $i++ ) { 
	$rsclmn = mysql_field_name($rs,$i);
	echo "<th>".$rsclmn;
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

?>

