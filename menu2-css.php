<?php
	

echo " <head><style type='text/css'>

/*------------------------------------*\
	НАВИГАЦИЯ
\*------------------------------------*/
#nav{
	float:left;
	width:80%;
	list-style:none;
	margin-bottom:9px;
	font-size:16;
	border:5;
}
#nav li{
	float:left;
	margin-right:10px;
	position:relative;
	display:block;
}
#nav li a{
	display:block;
	padding:5px;
	color:#e1292e;
	/*background:#CCCCCC;*/
	text-decoration:none;
	
	
	/*text-shadow:1px 1px 1px rgba(0,0,0,0.75);  Тень текста, чтобы приподнять его на немного */
	-moz-border-radius:5px;
	-webkit-border-radius:9px;
	border-radius:3px;
	
	
}
#nav li a:hover{
	color:#FFFFFF;
	background:#FFFFFF;
	background:#FFFFFF; /* Выглядит полупрозрачным   rgba(107,12,54,0.75) */
	text-decoration:underline;
}

/*--- ВЫПАДАЮЩИЕ ПУНКТЫ ---*/
#nav ul{
	list-style:none;
	position:absolute;
	left:-9999px; /* Скрываем за экраном, когда не нужно (данный метод лучше, чем display:none;) */
	opacity:0; /* Устанавливаем начальное состояние прозрачности */
	-webkit-transition:0.30s linear opacity; /* В Webkit выпадающие пункты будут проявляться задержка */
}
#nav ul li{
	padding-top:0px; /* Вводим отступ между li чтобы создать иллюзию разделенных пунктов меню (выпадающие списки) */
	float:none;
	background:url(dot.gif);
}
#nav ul a{
	white-space:nowrap; /* Останавливаем перенос текста и создаем многострочный выпадающий пункт */
	display:block;
}
#nav li:hover ul{ /* Выводим выпадающий пункт при наведении курсора */
	left:-40; /* Приносим его обратно на экран, когда нужно положение */
	opacity:1; /* Делаем непрозрачным */
}
#nav li:hover a{ /* Устанавливаем стили для верхнего уровня, когда выводится выпадающий список */
	background:#FFFFFF;
	background:#;  Выглядит полупрозрачным */
	text-decoration:underline;
	color:#e1292e;
}
#nav li:hover ul a{ /* Изменяем некоторые стили верхнего уровня при выводе выпадающего пункта */
	text-decoration:none;
	-webkit-transition:-webkit-transform 0.075s linear;
}
#nav li:hover ul li a:hover{ /* Устанавливаем стили для выпадающих пунктов, когда курсор наводится на конкретный пункт */
	
	border-color:black;
	background:#CCCCCC;
	
	/* background:#CCCCCC;  Будет полупрозрачным */
	text-decoration:none;
	-moz-transform:scale(1.05);
	-webkit-transform:scale(1.05);
}


</style></head>";
?>

