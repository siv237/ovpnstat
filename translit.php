<?php
function transliterate($str) {
$str=strtr($str,"абвгдезиклмнопрстуфцъыь",
"abvgdeziklmnoprstufс\"y'");
$str=strtr($str,"АБВГДЕЗИКЛМНОПРСТУФЦЪЫЬ",
"ABVGDEZIKLMNOPRSTUFС\"Y'");
$str=strtr($str,
array(
"э"=>"eh", "х"=>"kh", "й"=>"jj", "ё"=>"jo", "ж"=>"zh", "ч"=>"ch", "ш"=>"sh", "щ"=>"shh", "ю"=>"yu", "я"=>"ya", "Э"=>"Eh", "Х"=>"Kh", "Й"=>"Jj", "Ё"=>"Jo", "Ж"=>"ZH", "Ч"=>"CH", "Ш"=>"SH", "Щ"=>"SHH", "Ю"=>"YU", "Я"=>"YA", "ї"=>"i", "Ї"=>"Yi", "є"=>"ie", "Є"=>"Ye"
)
);
return $str;
}

transliterate(Привет);

?>
