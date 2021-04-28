<?php
$debut = microtime(true);
$i=0;
$cache='';
$chemin='../ressources/js/cache.json.js';
///////////  CACHE AUTOCOMPLETE  /////////////////
//////  ITEM  ///////
$time=$bdd->query('	SELECT date FROM item WHERE etat!="delete"
					UNION
					SELECT date FROM item WHERE etat!="delete"
					UNION
					SELECT last_connect as date FROM users WHERE etat!="delete"
					ORDER BY date DESC LIMIT 1
					')->fetch();
if(!file_exists($chemin) OR filemtime($chemin)<strtotime($time['date']))
	{
	$req=$bdd->query('	SELECT name 
					FROM item 
					WHERE `etat`!="delete" AND `etat`!="suppr" AND `type`!="sort" AND `type`!="prodige"
					GROUP BY  `name` 
					ORDER BY  `name` 
					');
	$result=$req->fetchAll(PDO::FETCH_COLUMN,0);
	$cache.='var list_item_name='.json_encode($result).';';
//////  ITEM  ///////
	$req=$bdd->query('	SELECT name 
					FROM item 
					WHERE `etat`!="delete" AND `etat`!="suppr" AND `type`!="sort" AND `type`!="prodige" AND `auteur_id`=1
					GROUP BY  `name` 
					ORDER BY  `name` 
					');
	$result=$req->fetchAll(PDO::FETCH_COLUMN,0);
	$cache.='var list_item_offi_name='.json_encode($result).';';
//////  PSEUDO  ///////
	$req=$bdd->query('	SELECT pseudo 
					FROM users 
					WHERE `etat`!="delete"
					GROUP BY  `pseudo` 
					ORDER BY  `pseudo` 
					');
	$result=$req->fetchAll(PDO::FETCH_COLUMN,0);
	$i++;
	$cache.='var list_pseudo_name='.json_encode($result).';';
	echo '<br>typehead mis à jour !<br>';
	file_put_contents($chemin,$cache,LOCK_EX);
	}
//////////  SUPPRESSION DU VIEUX CACHE  /////////////
directory_delete('../cache' , 'cache', 3600*24*10);// cache items + 10j
directory_delete('../cache' , 'design', 60*5);// cache design + 5min

///////////////////////////////////////////////////////////////////
$a=array('arme','protec','comp','divers');

//////////////////  CACHE GENERAL  /////////////////
foreach($a as $val)
	{
	$time=$bdd->query('SELECT date FROM item WHERE type="'.$val.'" AND etat!="delete" ORDER BY date DESC LIMIT 1')->fetch();
	if(!file_exists('../cache/item_'.$val.'_.cache') OR filemtime('../cache/item_'.$val.'_.cache')<strtotime($time['date']) 
	OR filemtime('../cache/item_'.$val.'_.cache')<$time)
		{
		cache::create_item('../pages/item.php','../cache/item_'.$val.'_.cache',$val);
		$i++;
		}
	}
//////////////////  CACHE OFFICIEL  /////////////////
foreach($a as $val)
	{
	$time=$bdd->query('SELECT date FROM item WHERE type="'.$val.'" AND auteur_id=1 AND etat!="delete" ORDER BY date DESC')->fetch();
	if(!file_exists('../cache/item_'.$val.'_off_.cache') OR filemtime('../cache/item_'.$val.'_off_.cache')<strtotime($time['date']) 
	OR filemtime('../cache/item_'.$val.'_off_.cache')<$time)
		{
		cache::create_item('../pages/item.php','../cache/item_'.$val.'_off_.cache',$val.'_off');
		$i++;
		}
	}
//////////////  CACHE UTILISATEUR  //////////////
$req=$bdd->query('SELECT auteur_id, date FROM item WHERE id!=1 GROUP BY auteur_id ORDER BY date DESC');
while($val=$req->fetch())
	{
	if(!file_exists('../cache/item_main_'.$val['auteur_id'].'.cache') 
			OR filemtime('../cache/item_main_'.$val['auteur_id'].'.cache')<strtotime($val['date']) 
			OR filemtime('../cache/item_main_'.$val['auteur_id'].'.cache')<$time)

		{
		$_SESSION['user_id']=$val['auteur_id'];$_SESSION['user']='user';
		/* $_SESSION['list_perso']=$bdd->query('(	SELECT * FROM perso WHERE user_id = '.$_SESSION['user_id'].' AND etat != "delete")
													UNION
												(SELECT p.*
												FROM users_persos as up
												INNER JOIN perso as p
												ON up.perso_id = p.id
												WHERE up.user_id='.$_SESSION['user_id'].' AND p.etat != "delete" AND up.etat != "delete")
												ORDER BY name DESC')->fetchAll(); */
		cache::create_item('../pages/item.php','../cache/item_main_'.$val['auteur_id'].'.cache','main');
		$i++;
		}	

	}
if(!file_exists('../cache/item_main_.cache') OR filemtime('../cache/item_main_.cache')<$time)
	{
	// session_destroy();
	cache::create_item('../pages/item.php','../cache/item_main_.cache','main');
	$i++;
	}

echo 'Cache généré en '. round((microtime(true) - $debut),5) .' seconde(s). ('.$i.' modifications)';