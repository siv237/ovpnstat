<?php
$logindb=$_POST[logindb];
$passworddb=$_POST[passworddb];

//include 'mysql_connect.php';
include 'css.php';

define("DATABASE_HOST", "127.0.0.1");
define("DATABASE_USERNAME", "$logindb");
define("DATABASE_PASSWORD", "$passworddb");
define("DATABASE_NAME","asteriskcdrdb");

mysql_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD )
or die ("<p>Соединение с Сервером MySQL: ERROR  " . mysql_error() . "</p>"  );
echo "Соединение c cервером MySQL:  ok <br> <br> ";


//Выбор БД

echo"Выберите Базу Данных из списка:";
$db = mysql_query('SHOW DATABASES');
$databd=$_REQUEST['sel'];

echo "<form method='post' action=''>
login:<input type = 'text'  name='logindb' value ='".$logindb."' />
password:<input type = 'password'  name='passworddb' value ='".$passworddb."' />

";

echo "<br><select id='sel' name='sel' value=''>";

echo " <br><option value='$databd'> $databd </option value>"; 
while ($co = mysql_fetch_row($db)) 
{
echo "<option value=$co[0]>  $co[0]  </option value>"; 

}
echo"<input type ='submit' value='ok' />";


//форма ввода запроса
echo 
"
<br><textarea id='inputsql' name='input' cols='100' rows='10'
>select * from cdr limit 10;
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
