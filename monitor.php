<?php
include 'auth.php'; // Извлечение логина и пароля к ARI_ADMIN_USERNAME, ARI_ADMIN_PASSWORD
//echo $ARI_ADMIN_USERNAME.' '.$ARI_ADMIN_PASSWORD;
if(isset($_POST[login])){SetCookie('arilogin',$_POST["login"],time()+30);$_COOKIE['arilogin'] = $_POST[login];}
if(isset($_POST[password])){SetCookie('aripassword',$_POST["password"],time()+30);$_COOKIE['aripassword'] = $_POST[password];}

if 	($_COOKIE['arilogin'] != $ARI_ADMIN_USERNAME and $_COOKIE['aripassword'] != $ARI_ADMIN_PASSWORD)
   
{ 
//	SetCookie('arilogin',$_POST["login"],time()+30);
//	SetCookie('aripassword',$_POST["password"],time()+30);

echo "
<form method='post' action=''>
<input type='text' name='login' value='admin'>
<input type='password' name='password' value=''>
<input type='submit' name='submit'>
";
}
else
{
	SetCookie('arilogin',$_COOKIE['arilogin'],time()+30);
	SetCookie('aripassword',$_COOKIE['aripassword'],time()+30);


// Подключаемся к базе
mysql_connect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asteriskcdrdb") or die(mysql_error());

// Собираем из кусочков полный текст запроса к базе
$strSQL = 
("
	select 	* 
	from cdr
	where uniqueid='1367213365.9549'
");

// Выполняем запрос
$rs = mysql_query($strSQL);


// Вытаскиваем имена полей и формируем заголовок таблицы результатов
$field = mysql_num_fields( $rs );
//echo "<tr>";
for ( $i = 0; $i < $field; $i++ ) { 
	$rsclmn = mysql_field_name($rs,$i);
//	echo "<th>".$rsclmn;
	$m_rname[$i]=$rsclmn;
}
//echo "</td>";
// Извлекаем значения и формируем таблицу результатов
while($id=mysql_fetch_row($rs))
	{ 
//	echo "<tr>";
	for ($x=0; $x<=count($id)-1; $x++) 
		{
//		echo "<td>".$id[$x];
		$m_date[$m_rname[$x]]=$id[$x];
		}
//	echo "</td>";
	}
//echo "</td></table>";


print_r($m_date);
// Строим форму вывода
echo "

<table border=1>
<th>Дата совершения вызова</th>	<td>$m_date[calldate]</tr>
<th>Совершил вызов</th>		<td>$m_date[src]</tr>
<th>Получатель</th>		<td>$m_date[dst]</tr>

</table>
";
}
?>
