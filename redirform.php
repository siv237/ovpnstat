<?php
$num_to=$_POST["MyNum"];
$clean=$_GET["clean"];
$chan=$_GET["chan"];
if (isset($clean))
	{SetCookie('CooMyNum','');
	 unset($_COOKIE['CooMyNum']);
	}

if (isset($num_to))
	{
	 SetCookie('CooMyNum',$num_to,0x6FFFFFFF);
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
	}

else
	{
         echo "<form method='post' action=''>";
         echo "Укажите свой номер: <input type='text' name='MyNum' value=".$num_to.">";
         echo "<br><input type='submit' name='submit' value='Запомнить'></form>";
	}
?>

