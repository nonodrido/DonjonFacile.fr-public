<?php
$ajax=1;
include('../includes/admin/f.php');
$bdd=connect();
//users
$rep = $bdd->query('SELECT COUNT(*) AS nb FROM users WHERE etat != "delete" AND last_connect > NOW() - INTERVAL 2 MONTH')->fetch();
file_put_contents('compteur_user.cache',$rep['nb'],LOCK_EX);
echo $rep['nb'].'/';
//persos
$rep = $bdd->query('SELECT COUNT(*) AS nb FROM perso WHERE etat != "delete"')->fetch();
file_put_contents('compteur_perso.cache',$rep['nb'],LOCK_EX);
echo $rep['nb'].'/';
//items
$rep = $bdd->query('SELECT COUNT(*) AS nb FROM item WHERE etat != "delete" AND `auteur_id`!=1 AND `type`!="sort" AND `type`!="prodige"')->fetch();
file_put_contents('compteur_item.cache',$rep['nb'],LOCK_EX);
echo $rep['nb'].'/';
//items officiels
$rep = $bdd->query('SELECT COUNT(*) AS nb FROM item WHERE etat != "delete" AND `auteur_id`=1 AND `type`!="sort" AND `type`!="prodige"')->fetch();
file_put_contents('compteur_item_offi.cache',$rep['nb'],LOCK_EX);
echo $rep['nb'].'/';
//group
$rep = $bdd->query('SELECT COUNT(*) AS nb FROM `group` WHERE etat != "delete"')->fetch();
file_put_contents('compteur_group.cache',$rep['nb'],LOCK_EX);
echo $rep['nb'].'/';

include('item_cache.php');
?>