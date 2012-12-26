<?php
//SetCookie('CooMyNum','');
$num_to=$_POST["MyNum"];
$clean=$_GET["clean"];
$chan=$_GET["chan"];
if (isset($clean))
	{SetCookie('CooMyNum','');
	 unset($_COOKIE['CooMyNum']);
	}


if (isset($num_to))
	{
	 SetCookie('CooMyNum',$num_to);
	 $_COOKIE['CooMyNum']=$num_to;
	}


if (isset($_COOKIE['CooMyNum']))
	{
	 $CooMyNum=$_COOKIE['CooMyNum'];
         echo "<form method='post' action='redir.php'>";
         echo "<br>Мой номер: <a href='?clean=true'>".$CooMyNum."</a>";
         echo "<input type='hidden' name='num_to' value=".$CooMyNum.">";
         echo "<input type='hidden' name='chan' value=".$chan.">";
         echo "<br><input type='submit' name='submit' value='Ответить'></form>";

//echo "<br><form method='post' action=''><br>";
//echo "У меня другой номер: <input type='text' name='MyNum' value=".$CooMyNum.">";
//echo "<br><input type='submit' name='submit' value='Изменить'></form>";
	}

else
	{
         echo "<form method='post' action=''>";
         echo "Укажите свой номер: <input type='text' name='MyNum' value=".$num_to.">";
         echo "<br><input type='submit' name='submit' value='Запомнить'></form>";
	}
?>

