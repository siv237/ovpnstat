<?php
function LocalName($itel)
{

// Ищем в файле конфигурации FreePBX логин и пароль к базе
$login=exec("grep AMPDBUSER /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|grep -v '^#'|tail -n 1|awk -F '=' '{print $2}'");

mysql_connect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asterisk") or die(mysql_error());


$strSQL = ("select * from users");
mysql_query("SET lc_time_names = 'ru_RU'");


$rs_users = mysql_query($strSQL);
        while ($row = mysql_fetch_assoc($rs_users)) 
		//print_r($row);

                { 
                 if ($row[extension]==$itel)    {$itel="<".$row[extension]."> ".$row[name];}
                }
return $itel;
}
?>
