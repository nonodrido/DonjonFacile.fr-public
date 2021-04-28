<?php
$ajax=1;
include('../includes/admin/f.php');
$bdd=connect();
$pre_url='http://www.naheulbeuk.com/jdr-docs/';
$data= file_get_contents($pre_url);
preg_match_all("|<a href=[\"'](.*?)[\"']>(.*)</a>[ ]*([0-9]{2}-[A-Za-z]{3}-[0-9]{4} [0-9]{2}:[0-9]{2})|",//  ([0-9]*[OKMG])
	$data,$result,PREG_SET_ORDER);
$i=0;$now=date(MYSQL_DATETIME_FORMAT);
foreach($result as $val)
	{
	$i++;
	$date_mysql=date_bdd($val[3],MYSQL_DATETIME_FORMAT);
	$req='INSERT INTO fichiers VALUES(NULL,"'.$now.'","default","'.$val[1].'","'.$date_mysql.'","","","","") ON DUPLICATE KEY UPDATE etat="default",date="'.$now.'",date_ftp="'.$date_mysql.'"';
	// echo $req;
	$bdd->exec($req);
	}
$bdd->exec('UPDATE fichiers SET etat="delete" WHERE date!="'.$now.'"');
echo $i.' éléments détectés';