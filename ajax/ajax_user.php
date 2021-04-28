<?php
if(isset($_GET['user_id']) AND filter_var($_GET['user_id'], FILTER_VALIDATE_INT))
	{
	$ajax=1;
	session_start();
	include('../includes/admin/f.php');
	$bdd=connect();
	$rep = $bdd->prepare('SELECT users.*,
						  (SELECT COUNT(*) as nb_perso FROM perso WHERE user_id = :id AND etat != "delete") as nb_perso,
						  (SELECT COUNT(*) as nb_item FROM item WHERE auteur_id = :id AND etat != "delete") as nb_item,
						  (SELECT COUNT(*) as nb_group FROM `group` WHERE user_id = :id AND etat != "delete") as nb_group
						  FROM users 
						  WHERE users.id= :id
						  AND users.etat!="delete"
						  ');
	$rep->execute(array('id'=>$_GET['user_id']));
	if($rep->rowCount() != 0)
		{
		$donnees = $rep->fetch();
		echo '<div class="center" style="padding:5px;min-width:250px;">
				<img class="pull-left" src="'. secure::html($donnees['avatar'],1).'" style="margin-right:5px;margin-bottom:45px;width:64px;height:64px;"/>
				<h4><a class="black" href="/membre/'.secure::html($donnees['id'],1).'/'.to_url($donnees['pseudo']).'">
					'.secure::html($donnees['pseudo'],1).'
				</a></h4>
				<ul class="unstyled">
					<li>Statut : <i>'.secure::html($donnees['status'],1).'</i></li>
					<li>Personnages : '.secure::html($donnees['nb_perso'],1).'</li>
					<li>Objets : '.secure::html($donnees['nb_item'],1).'</li>
					<li>Compagnies : '.secure::html($donnees['nb_group'],1).'</li>
				</ul>
				<hr style="margin:10px;">
				Inscription <time class="ajax" data-date="'.$donnees['date'].'" title="'.date_bdd($donnees['date']).'">le '.secure::html(date_bdd($donnees['date'])).'</time>.<br>
				Actif ';
			if(isadmin() OR $donnees['option_online']==1){echo '<time class="ajax" data-date="'.$donnees['last_connect'].'" title="'.date_bdd($donnees['last_connect']).'">le '.secure::html(date_bdd($donnees['last_connect'])).'</time>.';}
			else{echo 'il y a un certain temps.';}
				echo '<br><br><a class="btn" href="/messagerie?username='.$donnees['pseudo'].'"><i class="icon-envelope"></i> Envoyer un message</a>';
		echo ' </div>
				<script>
				$(\'time.ajax\').each(function(){
					var val_bdd = $(this).data(\'date\');
					var val_final = moment(val_bdd,"YYYY-MM-DD HH:mm:ss").fromNow();
					$(this).html(val_final);
					});
				</script>';
		}
	else{echo 'Cet utilisateur n\'existe pas !';}
	}
else{echo 'Cet utilisateur n\'existe pas !';}