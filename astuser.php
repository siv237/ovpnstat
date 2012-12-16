<?php

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

echo "<br>Запрос к базе:<br>".$strSQL."<br>";
echo "<table border='1'>";

// Вытаскиваем имена полей и формируем заголовок таблицы результатов
$field = mysql_num_fields( $rs );

echo "<tr>";
for ( $i = 0; $i < $field; $i++ ) { 
        $rsclmn = mysql_field_name($rs,$i);
        echo "<td>".$rsclmn;
}

// Извлекаем значения и формируем таблицу результатов
while($id=mysql_fetch_row($rs))
        { 
        echo "<tr>";
        for ($x=0; $x<=count($id)-1; $x++) 
                {
                echo "<td>".$id[$x];
                }
        }
echo "</table>";

?>
