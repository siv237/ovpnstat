<?php
function FormatTelNum($tel)
{
$tel=substr($tel,-10);	
$origtel=$tel;

// Если телефон начинается с 9, значит обрабатываем как сотовый
if ($tel[0] == 9)
{
$origtel=$tel;
$base=fopen("rusotdef.csv","r");
while (!feof($base)) 
        {
        $strm=explode(";",fgets($base));
        if ($tel > $strm[0] and $tel < $strm[1])
                {
                $intrv=$strm[1]-$strm[0];
                $tel=substr_replace($tel,"-",-2,0);
                $tel=substr_replace($tel,"-",-5,0);
                $tel=substr_replace($tel,") ",-9,0);
                $tel=substr_replace($tel,"+7 (",-14,0);
                echo "Сот. ".$tel." ".$strm[2].", ".$strm[3];
                }       
        }
fclose($base);
}

// Если телефон не сотовый то обрабатываем как городской
else
{

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
	{$tel=$FullNumFormat." ".$AreaName.", ".$CityName;}
else
	{$tel=$origtel;}
return $tel;
fclose($base);
}
}
?>
