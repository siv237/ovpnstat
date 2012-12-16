<?php
$to= $_GET["to"];

echo "<br>Хочу, чтобы система соединила меня.<br>";
echo "<form method='post' action='orgn.php'>";
echo "Мой номер: <input type='text' name='v1' value=''>";
echo "<input type='hidden' name='v2' value=".$to.">";
echo "<br><input type='submit' name='submit' value='Соединить с абонентом ".$to."'></form>";

?>
