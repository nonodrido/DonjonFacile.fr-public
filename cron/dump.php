<?php
$ajax=1;
include('../includes/admin/f.php');
$chemin=realpath('').'/sav/'.date('Y-m-d_H-i-s').'.sql';//C:\wamp\bin\mysql\mysql5.5.24\bin/mysqldump
$dump_req='mysqldump --opt -Q -C -u '.DB_USER.' -h '.DB_HOST.' --password="'.DB_PASSWORD.'" -B '.DB_NAME.' > '.$chemin;
// echo $dump_req;
exec($dump_req,$output);
$bdd=connect();
$bdd->exec('DELETE FROM  `message` WHERE `auteur_id` = `destinataire_id`');
$bdd->exec('DELETE FROM validation WHERE `date` < NOW() - INTERVAL 5 DAY');
echo 'Supprimer les antislashs du site : ';
$count=0;

$anti_array=array(	'item'=>array('name','descr','effets','carac','subtype'),
					'group'=>array('name','descr'),
					'perso'=>array('name','descr'),
					'search'=>array('name','descr'),
					'users'=>array('pseudo','descr','localisation'),
					'news_commentaires'=>array('txt'),
					'livreor'=>array('txt'),
					'news'=>array('titre','contenu')
					);
foreach($anti_array as $table=>$champ_list)
	{
	foreach($champ_list as $champ)
		{
		// echo "<br>UPDATE `".$table."` SET ".$champ." = REPLACE(".$champ.", '\\\\', '')";
		$count+= $bdd->exec("UPDATE `".$table."` SET ".$champ." = REPLACE(".$champ.", '\\\\', '')");
		}
	}
echo $count.' champ(s) nettoyés !<br>';

/////vidage cache des fiches graphiques + antibrute
directory_delete('../ressources/fiches','jpg',3600*24*3);
directory_delete('../antibrute','tmp',3600*3);

$bdd=connect();
//////////////////// RESET TABLE SEARCH //////////////////////
$bdd->exec('TRUNCATE TABLE `search`');
$bdd->exec('INSERT INTO `search` SELECT id,date,etat,type,subtype,auteur_id,name,descr FROM `nonodrid_site`.`item`');

///////////////////////////DELETE VIEUX MESSAGES /////////////////////////////
$bdd->exec('UPDATE message SET etat="delete" WHERE date < NOW() - INTERVAL 4 MONTH');

///////////////////  reset du perso de test ///////////////////

$bdd->exec('DELETE FROM users WHERE id=28');
$bdd->exec("INSERT INTO `users` (`id`, `etat`, `pseudo`, `mdp`, `orig_mail`, `mail`, `date`, `last_connect`, `last_ip`, `last_nav`, `last_geoloc`, `current_perso`, `type`, `option_mail`, `option_online`, `sexe`, `age`, `localisation`, `status`, `descr`, `avatar`) VALUES
(28, 'default', 'test', 'c1b781240604abd5691bbed33389b26b2d6839f8', 'contact@donjonfacile.fr', 'contact@donjonfacile.fr', '2012-07-30 13:41:01', '2013-03-02 13:34:16', '86.74.199.215', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.97 Safari/537.22', 'FR,FRANCE,ILE-DE-FRANCE,VERSAILLES', 20, 'user', 1, 1, 'non renseigné', 'non renseigné', 'non renseignée', 'membre standard', '', '/ressources/img/avatar/default.png')");
$bdd->exec("INSERT INTO `perso` (`id`, `create_date`, `date`, `user_id`, `etat`, `name`, `xp`, `origine`, `metier`, `sexe`, 
`evmax`, `eamax`, `PDest`, `COU`, `INTL`, `CHA`, `AD`, `FO`, `AT`, `PRD`, `PO`, `PA`, `PC`, `LT`, `LB`, `descr`, `img`, `old_fiche`, 
`old_fiche_advanced`) VALUES ('20', '2012-08-23 19:45:19', '2013-03-07 09:48:22', '28', 'default', 'perso de test', '4', 'Humain', 'Mage', 
'masculin', '20', '0', '2', '9', '13', '13', '11', '10', '8', '12', '23', '0', '0', '0', '0', 'Ce personnage est remis a 0 tous les jours !', 
'http://donjonfacile.fr/ressources/img/avatar/default.png', '2013-01-13 10:26:05', '2013-01-13 10:26:05')");
?>