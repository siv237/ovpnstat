<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<div class="headmenu">
<ul id="nav">
<li><a href=index.php><img src="img/astgolv.gif"  width="156" height="100"  /></a>
<li>

<li><a href="">Оператор</a>
 <ul>
	<li><a href="menu_QueueStatus.php">Кто ждет в очереди?</a></li>
	<li><a href="menu_astuser.php">Локальные пользователи Asterisk</a></li>
 </ul>
</li>


<li><a href="">Руководитель</a>
 <ul>
	<li><a href="menu_dialstat.php">Журнал звонков</a></li>
        <li><a href="menu_callfail.php">Пропущенные звонки</a></li> 
	<li><a href="menu_opstat.php">Статистика по операторам</a></li> 
 </ul>
</li>


<li><a href="#">Администратор</a>
 <ul>
	<li><a href="menu_find.php">Глобальный поиск в базе</a></li>
	<li><a href="menu_chanstat.php">Загрузка каналов</a></li>
	<li><a href="cstq">Каталог запросов</a></li>
	<li><a href="menu_mysql_select.php">SQL запросы</a></li>
 </ul>
</li>

<li><a href="#">О проекте</a>
 <ul>
        <li><a href="https://github.com/siv237/ovpnstat/commits/master">Обновления Git</a></li>
        <li><a href="http://aststat.tobase.ru/viewforum.php?f=3">Форум</a></li>
        <li><a href="http://ru.man.wikia.com/wiki/AstStat">Справка WiKi</a></li>
        <li><a>Версия от <?php echo date("d M Y H:i:s", filemtime('./.git/index')); ?></a></li>

 </ul>
</li>


</div>

<?
include'menu2-css.php';
include 'css.php';
include 'page-css.php';
include 'scrol-css.php';
?>



