<?php
include 'auth.php'; // Извлечение логина и пароля к ARI_ADMIN_USERNAME, ARI_ADMIN_PASSWORD
$id= $_GET["id"];
// Подключаемся к базе
mysql_connect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asteriskcdrdb") or die(mysql_error());

// Получаем информацию о звонке из базы
$strSQL = 
("
	select 	* 
	from cdr
	where uniqueid='$id'
");

// Выполняем запрос
$rs = mysql_query($strSQL);

// Переводим результат запроса в массив с заголовком
// Вытаскиваем имена полей и формируем заголовок массива
$field = mysql_num_fields( $rs );
for ( $i = 0; $i < $field; $i++ ) 
	{ 
	$rsclmn = mysql_field_name($rs,$i);
	$m_rname[$i]=$rsclmn;
	}
// Извлекаем значения и формируем таблицу результатов
while($id=mysql_fetch_row($rs))
	{ 
	for ($x=0; $x<=count($id)-1; $x++) 
		{$m_date[$m_rname[$x]]=$id[$x];}
	}


// Строим форму вывода
echo "

<table border=1>
<th>Дата совершения вызова</th>	<td>$m_date[calldate]</tr>
<th>Совершил вызов</th>		<td>$m_date[src]</tr>
<th>Получатель</th>		<td>$m_date[dst]</tr>
<th>Уникальный номер ID</th>	<td><a href='mon.php?uniqueid=$m_date[uniqueid]' target 'mon'>Найти запись<a></tr>

</table>
<br>
";

echo "<form method='get' action='mon.php'>";
echo "Запись разговора на номер: <input type='text' name='num_to' value='".$_COOKIE['CooMyNum']."'>";
echo "<input type='hidden' name='recordingfile' value=".$m_date[recordingfile].">";
echo "<input type='submit'></form>";


print_r($m_date);

?>
