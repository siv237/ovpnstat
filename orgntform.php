<?php
$to= $_GET["to"];

//if (isset($_POST['submit'])) 
system ("grep ".$to." list.csv |awk -F ';' '{print $2}'");
echo "<br>Хочу, чтобы система соединила меня.<br>";
echo "<form method='post' action='orgn.php'>";
echo "Мой : <input type='text' name='v1' value='101'><br>";
echo "Кому: <input type='text' name='v2' value=".$to.">";
echo "<br><input type='submit' name='submit' value='Соединить'></form>";

?>
