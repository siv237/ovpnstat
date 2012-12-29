<?php
function FormatTelNum($tel)
{
$tel=substr($tel,-10);	
$origtel=$tel;
$base=fopen("rutelcode.csv","r");
while (!feof($base)) 
	{
	$strM=explode(";",fgets($base));
	$lenstr=strlen($strM[0]);
	if ( substr($tel,0,$lenstr) == $strM[0] and $lenstr > 2)
		{
		$FullNum=$tel;
		$SchortNum=substr_replace(substr_replace(substr($FullNum,$lenstr),"-",-2,0),"-",-5,0);
		$CityCode=$strM[0];
		$FullNumFormat="+7 (".$CityCode.") ".$SchortNum;
		$AreaName=$strM[1];
		$CityName=$strM[2];
		}	
	}
if(isset($FullNum))
	{$tel=$FullNumFormat." ".$AreaName." ".$CityName;}
else
	{$tel=$origtel;}
return $tel;
fclose($base);
}

//FormatTelNum(74953587438)
?>
