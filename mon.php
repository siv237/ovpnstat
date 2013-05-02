<?php
include 'auth.php'; // Извлечение логина и пароля к ARI_ADMIN_USERNAME, ARI_ADMIN_PASSWORD
//echo $ARI_ADMIN_USERNAME.' '.$ARI_ADMIN_PASSWORD;
if(isset($_POST[login]))
	{SetCookie('arilogin',$_POST["login"],time()+30);$_COOKIE['arilogin'] = $_POST['login'];}
if(isset($_POST[password]))
	{SetCookie('aripassword',$_POST["password"],time()+30);$_COOKIE['aripassword'] = $_POST['password'];}

if 	($_COOKIE['arilogin'] != $ARI_ADMIN_USERNAME or $_COOKIE['aripassword'] != $ARI_ADMIN_PASSWORD)
{
echo "
<form method='post' action=''>
<input type='text' name='login'>
<input type='password' name='password'>
<input type='submit' name='submit'>
";
}
else
{
SetCookie('arilogin',$_COOKIE['arilogin'],time()+30);
SetCookie('aripassword',$_COOKIE['aripassword'],time()+30);
$uniqueid= $_GET["uniqueid"];
$recordingfile= $_GET["recordingfile"];

if (isset($recordingfile))
	{$pathFile=exec("/usr/bin/find /var/spool/asterisk/monitor/ -name '$recordingfile'");}
	else
	{ 
	if (isset($uniqueid))
		{$pathFile=exec("/usr/bin/find /var/spool/asterisk/monitor/ -name '$uniqueid'");
		 $pathFile=exec("/usr/bin/find /var/spool/asterisk/monitor/ -name '*$uniqueid*'");
		}
	else 
		{
		echo 'Условие поиска не задано!<br>';
		}
	}
 	if ($pathFile != '')
		{
		 $fileName=end(explode ("/",$pathFile));
		 header("Content-Disposition: attachment; filename=$fileName");
		 readfile("$pathFile"); 
		 exit(); 
		}
	else
	{
	echo "Файл не найден!";
	}

}
?>
