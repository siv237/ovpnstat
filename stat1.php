<?php 

$login=exec("grep AMPDBUSER /etc/amportal.conf|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|awk -F '=' '{print $2}'");

mysql_connect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asteriskcdrdb") or die(mysql_error());

$strSQL = 
("
	select 	* 
	from cdr 
	order by calldate desc 
	limit 100 
");

$rs = mysql_query($strSQL);

echo "<table border='1'>";

while($id=mysql_fetch_row($rs))
	{ 
	echo "<tr>";
	for ($x=0; $x<=count($id); $x++) 
		{
		echo "<td>".$id[$x];
		}
	echo "</td>";
	}
echo "</td></table>";

?>

