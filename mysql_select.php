<?php



include 'mysql_connect.php';
include 'css.php';


//Выбор БД

echo"Выберите Базу Данных из списка:";
$db = mysql_query('SHOW DATABASES');
$databd=$_REQUEST['sel'];

echo "<form action='' metod='POST'>";
echo "<br><select id='sel' name='sel' value=''>";
echo " <br><option value='$databd'> $databd </option value>"; 
while ($co = mysql_fetch_row($db)) 
{
echo "<option value=$co[0]>  $co[0]  </option value>"; 

}
echo"<input type ='submit' value='ok' />";

//$tb = mysql_list_tables($databd);
//while ($tabl = mysql_fetch_row($tb)) 
//{
//echo "<ul>" . $tabl[0]; 

//}




//форма ввода запроса
echo 
"
<br><input type = 'text'  name='lim' value ='10' />
<br><textarea id='inputsql' name='input' cols='100' rows='3 value='Введите ваш запрос'' > 

</textarea>
<br><input type ='submit' value='Выполнить запрос' />
<option value='$databd'> $databd </option value>
<input type = 'reset'  value='Сброс'/><br>
";

echo"</select>";
echo"</form>";
//выбранная БД

mysql_select_db($databd)
or die ("<br><p> Ошибка выбора БД: " . mysql_error() . "</p><br> ");
echo  " БД: "  . $databd;




$limits=$_REQUEST['lim'];


//Отправка запроса
echo "<br>";
$inputsql=$_REQUEST['input'];
$result = mysql_query($inputsql );
 if(!$result)
 { die ("<p> Ошибка при выполнения запроса  " . $inputsql . ": " . mysql_error() . "</p>");
 }
//вывод результата
echo "<table border=1>";
echo "<tr>";

//выводим заголовки полей таблиц
$rcolum = mysql_query("SHOW COLUMNS FROM cdr") ;

while ($col = mysql_fetch_row($rcolum)) {
 
echo "<th>" . $col[0];
	
}
//результат запроса
while($row = mysql_fetch_row($result)) {
echo "<tr>";
  for($i=0 ; $i<=15; $i++) 
{

echo"<td> $row[$i]"; 
echo"</td>";
}

}
echo "</td></table>";
?>