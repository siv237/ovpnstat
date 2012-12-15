<?php 

$date_to=$_POST["date_to"];
$date_from=$_POST["date_from"];
$str_find=$_POST["str_find"];
if ($_POST["str_limit"] == "" ) 
	{$str_limit="100";}
else	{$str_limit=$_POST["str_limit"];}

echo "<table border='0'><td>Дата начала<td>Дата окончания<td>Поиск<td>Лимит</tr>";
echo "
<form method='post' action=''>
        <link rel='stylesheet' type='text/css' href='cal/tcal.css' />
        <script type='text/javascript' src='cal/tcal.js'></script>

<td>                <input type='text' name='date_from' class='tcal' value='".$date_from."'> 
<td>                <input type='text' name='date_to' class='tcal' value='".$date_to."'>
<td>		<input type='text' name='str_find' value='".$str_find."'>
<td>                <input type='text' name='str_limit' value='".$str_limit."' SIZE=4>
</td>                
</table>
		<input type='submit' name='submit' value='Найти'>
</form>
<br>
";



$login=exec("grep AMPDBUSER /etc/amportal.conf|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|awk -F '=' '{print $2}'");

mysql_connect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asteriskcdrdb") or die(mysql_error());


$FindAll="where concat_ws('|',clid,src,dst,dcontext,channel,dstchannel,lastapp,lastdata,duration,billsec,disposition,amaflags,accountcode,uniqueid,userfield,did,recordingfile) like ('%".$str_find."%')";

if 	( $date_to == ""  and $date_from == "") 
		{ echo "Период не задан";}
	else 	{ 
		$FindDate="and (calldate BETWEEN STR_TO_DATE('".$date_from."','%m/%d/%Y') AND STR_TO_DATE('".$date_to."','%m/%d/%Y'))";
		}

$strSQL = 
("
	select 	* 
	from cdr 
	".$FindAll." 
	".$FindDate." 
	order by calldate desc 
	limit ".$str_limit 
);

echo "<br>Запрос к базе:<br>".$strSQL."<br>";
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

