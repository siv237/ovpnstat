<?php
function FormatTelNum($tel)
{
$origtel=$tel;
//Отрежем 10 цифр с конца номера
$tel=substr($tel,-10);	
// Выполняем обработку только тогда, когда длинна получившегося номера больше или равна 10 цифрам
if (strlen($tel) > 9) 
	{
	// Если телефон начинается с 9, значит обрабатываем как сотовый
	if ($tel[0] == 9)
		{
		$origtel=$tel;
		$base=fopen("rusotdef.csv","r");
		while (!feof($base)) 
        		{
        		$strm=explode(";",fgets($base));
        		if ($tel >= $strm[0] and $tel <= $strm[1])
                		{
                		$intrv=$strm[1]-$strm[0];
                		$tel=substr_replace($tel,"-",-2,0);
                		$tel=substr_replace($tel,"-",-5,0);
                		$tel=substr_replace($tel,") ",-9,0);
                		$tel=substr_replace($tel,"+7 (",-14,0);
                		$tel=$tel." (Моб.) ".$strm[2].", ".$strm[3];
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
		fclose($base);
		}
	}
	else
	{$tel=$origtel;}
return $tel;
}
?>
