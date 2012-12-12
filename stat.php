<html>
	<head>
	 <title>Статистика</title>
	</head>
<body>
<p align="center">История звонков</p>

<?php 

$login=exec("grep AMPDBUSER /etc/amportal.conf|awk -F '=' '{print $2}'");
$password=exec("grep AMPDBPASS /etc/amportal.conf|awk -F '=' '{print $2}'");

mysql_connect("127.0.0.1", $login, $password) or die(mysql_error());
mysql_select_db("asteriskcdrdb") or die(mysql_error());


$strSQL = ("


	select 	DATE_FORMAT(calldate,'%d %b %Y %H:%i %a'), 
		src, 
                dst, 
		lastapp, 
		duration, 
		sec_to_time(billsec), 
		recordingfile, 
		clid 

	from 	cdr 

	where 	calldate > (NOW() - INTERVAL 3 month) 
	and 	(channel like  '%Local%' 
	and	src != '' 
        or	recordingfile != ''
	and	lastapp='Dial') 
	order by calldate desc");

 mysql_query("SET lc_time_names = 'ru_RU'");
 $rs = mysql_query($strSQL);



	
echo "<table border='1'>";
echo "<tr><th ALIGN=left>Дата звонка</th><th>Кто</th><th>Кому</th><th>Время</th><th>R.</th><th>WiKi</th><th>Описание звонившего</th></tr>";
	while($row = mysql_fetch_array($rs)) {

if ($row[1] == '') { $row[1] = $row[7];};
if ($row[6] != '') { $LinkRec = "<a href=mon.php?fname=".$row[6]."><img src='img/download_manager.png' width='20' height='20'></a>";} else {$LinkRec = "";}

if ($row[1] == $row[7]) { 
 $from   = $row[2];
 $row[2] = $row[1];
 $row[1] = $from; 

};

	   // Записать значение столбца FirstName (который является теперь массивом $row)
          $Comment=exec("grep ".$row[1]." list.csv |awk -F ';' '{print $2}'");
          $wiki_link=exec("grep ".$row[1]." list.csv |awk -F ';' '{print $3}'");


	  echo "<tr>" .
               "<td>" . $row[0] . 
               "<td ALIGN=CENTER><a href=form.php?to=".$row[1].">" . $row[1] ."</a>".
               "<td>" . $row[2] .
               "<td>" . $row[5] .
               "<td>" . $LinkRec .
               "<td><a href=wiki/index.php/".$wiki_link.">$wiki_link</a>" .
               "<td>".$Comment.
               "</td>";
	  }
echo "</tr></table>";

	// Закрыть соединение с БД
	mysql_close();




	?>

 </body>
</html>


