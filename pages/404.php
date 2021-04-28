<?php 
// var_dump($_SERVER['REQUEST_URI']);echo "Location: http://donjonfacile.fr".str_replace('.html','',$_SERVER['REQUEST_URI']);exit;
if(mb_substr($_SERVER['REQUEST_URI'],-5)=='.html')
	{
	header("Status: 301 Moved Permanently", false, 301);
	header("Location: http://donjonfacile.fr".str_replace('-','/',str_replace('.html','',$_SERVER['REQUEST_URI'])));
	exit();
	}
$titre='erreur !'; 
header ("HTTP/1.1 404 Not Found");?>
<img src="/ressources/img/img_html/elfe02-menu.gif" style="float:left;"/>
<h3>Oups, la page n'existe plus (ou n'a jamais existée). Vous êtes sur la page d'erreur de ce site.</h3>
<p>Vous pouvez hurler de rage et de dépit ou cliquer sur l'un des menus judiscieusement disposé sur cette page.<br/>
<p>Page demandée : <?php echo $_SERVER['REQUEST_URI'];?></p>
<i>Pendant ce temps, un Nain compte ses pièces d'or.</i></p>