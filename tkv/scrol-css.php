<?php
echo "
<head><style type='text/css'>
/*общая ширина крола */
::-webkit-scrollbar{
    width:13px;
}


/*полоса прокрутки */
::-webkit-scrollbar-thumb{
    border-width:1px 1px 1px 2px;
    border-color: #FF0000;
    background-color: #aaa;
}
/* при наведениии курсора */
::-webkit-scrollbar-thumb:hover{
    border-width: 1px 1px 1px 2px;
    border-color: #e1292e;
	background-color: #e1292e;
}
/* не активное состояние */
::-webkit-scrollbar-track{
    border-width:0;
}
/*наведение на ползунок*/
::-webkit-scrollbar-track:hover{
    border-left: solid 1px #aaa;
    background-color: #eeee;
}

</style></head>
";
?>