<?php
function FormatTelNum($tel)
{
$origtel=$tel;
//Отрежем 10 цифр с конца номера
$tel=substr($tel,-10);	
// Выполняем обработку только тогда, когда длинна получившегося номера больше или равна 10 цифрам
if (strlen($tel) > 9) 
	{
		$origtel=$tel;
                
                //$temp = unserialize( file_get_contents( './def_php/'.$tel[0].$tel[1].$tel[2].'.data' ) );
                $temp = file( './def_php/'.$tel[0].$tel[1].$tel[2].'.data' );
		foreach($temp as $strm)
        		{
                        $strm = explode(';', $strm);
        		if ($tel >= $strm[0] and $tel <= $strm[1])
                		{
                		$intrv=$strm[1]-$strm[0];
                		$tel=substr_replace($tel,"-",-2,0);
                		$tel=substr_replace($tel,"-",-5,0);
                		$tel=substr_replace($tel,") ",-9,0);
                		$tel=substr_replace($tel,"+7 (",-14,0);
                                if ( $origtel[0]==9 )
					$tel .= '<img src="./img/logotip/sot.png"> ';
                                if ( $origtel[0]!=9 )
                                        $tel .= '<img src="./img/logotip/tel.ico"> ';
//                                        $tel .= ' (Моб.)';
                                $tel .= ' ';
                                if ( $strm[2]=='МегаФон') $tel .= '<img src="./img/logotip/megafon.ru.png" title="'.$strm[2].'"> ';
                                elseif ( $strm[2]=='Мобильные ТелеСистемы') $tel .= '<img src="./img/logotip/mts.ru.png" title="'.$strm[2].'"> ';
                                elseif ( $strm[2]=='Ростелеком') $tel .= '<img src="./img/logotip/moscow.rt.ru.png" title="'.$strm[2].'"> ';
                                elseif ( $strm[2]=='Вымпел-Коммуникации') $tel .= '<img src="./img/logotip/beeline.ru.png" title="'.$strm[2].'"> ';
				elseif ( $strm[2]=='Гран При Телеком') $tel .= '<img src="./img/logotip/gptel.ru.png" title="'.$strm[2].'"> ';
                                elseif ( $strm[2]=='Санкт-Петербург Телеком') $tel .= '<img src="./img/logotip/spb.tele2.ru.png" title="'.$strm[2].'"> ';
                                elseif ( $strm[2]=='МГТС') $tel .= '<img src="./img/logotip/mgts.ru.png"> ';

                                else $tel .= $strm[2];
	               		$tel .= ', <b>'.$strm[3].'</b>';
                                return $tel;
                		}       
        		}
		

		// Если телефон не сотовый то обрабатываем как городской

	}
	else
	{$tel=$origtel;}
return $tel;
}
?>
