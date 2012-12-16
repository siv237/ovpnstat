<?php
include 'extstat.php';

// Ищем в файле конфигурации FreePBX логин и пароль к базе
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

// Подключаемся к базе
mysql_connect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asterisk") or die(mysql_error());

// Собираем из кусочков полный текст запроса к базе
$strSQL = 
("
        select extension,name,sipname 
        from users 
");

// Выполняем запрос
$rs = mysql_query($strSQL);

echo "<table border='1'>";
//echo "<tr><td>Номер<td>Описание<td>Состояние<td><td>";

// Извлекаем значения и формируем таблицу результатов
while($id=mysql_fetch_row($rs))
        { 
        echo 
	"<tr>".
	"<td>".$id[0].
	"<td>".$id[1].
        "<td>".ExtStatus($id[0]).
	"<td><a href=orgntform.php?to=".$id[0].">звонить</a>";
        }
echo "</table>";

?>
