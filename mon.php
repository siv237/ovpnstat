<?php
 $fileName= $_GET["fname"];
 $pathFile=exec("/usr/bin/find /var/spool/asterisk/monitor/ -name '$fileName'");
 $tmpFile="/var/www/tmp/$fileName";
// echo $tmpFile;
 exec("sox --norm -v 10 $pathFile $tmpFile");
 header("Content-Disposition: attachment; filename=$fileName");
 readfile("$tmpFile"); 
 exit(); 
?>
