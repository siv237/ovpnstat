<?php
//$reflink=$_SERVER['HTTP_REFERER'];
$num_to=$_POST["MyNum"];
$chan=$_GET["chan"];

 	 echo "<form method='post' action='redir.php'>";
         echo "<input type='hidden' name='chan' value=".$chan.">";
	 echo "<input name='num_to' value=".$_COOKIE['CooMyNum'].">";
         echo "<br><input type='submit' name='submit' value='Ответить'></form>";

?>

