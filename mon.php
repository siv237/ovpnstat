<?php
 $fileName= $_GET["fname"];
 $pathFile=exec("/usr/bin/find /var/spool/asterisk/monitor/ -name '$fileName'");
 $tmpFile="/tmp/$fileName".".wav";
// echo $tmpFile;
 exec("ffmpeg -i $pathFile $tmpFile");

 exec("sox --norm -v 10 $tmpFile /tmp/dfgdfg.wav");
 header("Content-Disposition: attachment; filename=$fileName");
 readfile("/tmp/dfgdfg.wav"); 
 exit(); 
?>
