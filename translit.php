<?php
function latrus ($string) # Теперь задаём функцию перекодировки транслита в кириллицу.
{
$string = ereg_replace("Ya","Я",$string);
$string = ereg_replace("ya","я",$string);
$string = ereg_replace("yu","ю",$string);
$string = ereg_replace("yo","ё",$string);
$string = ereg_replace("Yo","Ё",$string);
$string = ereg_replace("zh","ж",$string);
$string = ereg_replace("Zh","Ж",$string);
$string = ereg_replace("j","й",$string);
$string = ereg_replace("J","Й",$string);
$string = ereg_replace("x","х",$string);
$string = ereg_replace("X","Х",$string);
$string = ereg_replace("ch","ч",$string);
$string = ereg_replace("Ch","Ч",$string);
$string = ereg_replace("sh","ш",$string);
$string = ereg_replace("Sh","Ш",$string);
$string = ereg_replace("shh","щ",$string);
$string = ereg_replace("Shh","Щ",$string);
$string = ereg_replace("'","ь",$string);
$string = ereg_replace("''","Ь",$string);
$string = ereg_replace("e'","э",$string);
$string = ereg_replace("E'","Э",$string);
$string = ereg_replace("Yu","Ю",$string);
$string = ereg_replace("yа","я",$string);


$string = ereg_replace("a","а",$string);
$string = ereg_replace("A","А",$string);
$string = ereg_replace("b","б",$string);
$string = ereg_replace("B","Б",$string);
$string = ereg_replace("v","в",$string);
$string = ereg_replace("V","В",$string);
$string = ereg_replace("g","г",$string);
$string = ereg_replace("G","Г",$string);
$string = ereg_replace("d","д",$string);
$string = ereg_replace("D","Д",$string);
$string = ereg_replace("e","е",$string);
$string = ereg_replace("E","Е",$string);
$string = ereg_replace("yo","ё",$string);
$string = ereg_replace("Yo","Ё",$string);
$string = ereg_replace("zh","ж",$string);
$string = ereg_replace("Zh","Ж",$string);
$string = ereg_replace("z","з",$string);
$string = ereg_replace("Z","З",$string);
$string = ereg_replace("i","и",$string);
$string = ereg_replace("I","И",$string);
$string = ereg_replace("j","й",$string);
$string = ereg_replace("J","Й",$string);
$string = ereg_replace("k","к",$string);
$string = ereg_replace("K","К",$string);
$string = ereg_replace("l","л",$string);
$string = ereg_replace("L","Л",$string);
$string = ereg_replace("m","м",$string);
$string = ereg_replace("M","М",$string);
$string = ereg_replace("n","н",$string);
$string = ereg_replace("N","Н",$string);
$string = ereg_replace("o","о",$string);
$string = ereg_replace("O","О",$string);
$string = ereg_replace("p","п",$string);
$string = ereg_replace("P","П",$string);
$string = ereg_replace("R","Р",$string);
$string = ereg_replace("r","р",$string);
$string = ereg_replace("s","с",$string);
$string = ereg_replace("S","С",$string);
$string = ereg_replace("t","т",$string);
$string = ereg_replace("T","Т",$string);
$string = ereg_replace("u","у",$string);
$string = ereg_replace("U","У",$string);
$string = ereg_replace("f","ф",$string);
$string = ereg_replace("F","Ф",$string);
$string = ereg_replace("x","х",$string);
$string = ereg_replace("X","Х",$string);
$string = ereg_replace("c","ц",$string);
$string = ereg_replace("C","Ц",$string);
$string = ereg_replace("ch","ч",$string);
$string = ereg_replace("Ch","Ч",$string);
$string = ereg_replace("sh","ш",$string);
$string = ereg_replace("Sh","Ш",$string);
$string = ereg_replace("shh","щ",$string);
$string = ereg_replace("Shh","Щ",$string);
$string = ereg_replace("\"","ъ",$string);
$string = ereg_replace("\"\"","Ъ",$string);
$string = ereg_replace("y","ы",$string);
$string = ereg_replace("Y","Ы",$string);
$string = ereg_replace("'","ь",$string);
$string = ereg_replace("''","Ь",$string);
$string = ereg_replace("eh","э",$string);
$string = ereg_replace("E'","Э",$string);
$string = ereg_replace("Yu","Ю",$string);
$string = ereg_replace("Yа","я",$string);

return $string;
}

//echo latrus ('114 BUH-Beljaeva Ljudmila');
?>
