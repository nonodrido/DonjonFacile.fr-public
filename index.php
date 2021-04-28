<?php 
session_start();
$debut = microtime(true); //initialisation compteur vitesse de la page
// setlocale (LC_TIME, 'fr_FR.utf8','fra');
// ini_set('mbstring.internal_encoding', 'UTF-8');
if($_SERVER['HTTP_HOST']!='localhost' AND $_SERVER['HTTP_HOST']!='donjonfacile.fr')
	{
	header("Status: 301 Moved Permanently", false, 301);
	header("Location: http://donjonfacile.fr".$_SERVER['REQUEST_URI']);
	exit();
	}

if(!isset($_SESSION['err'])){$_SESSION['err']='';} 
if(!isset($_SESSION['info'])){$_SESSION['info']='';}
if(!isset($_SESSION['warning'])){$_SESSION['warning']='';}
if(!isset($_SESSION['success'])){$_SESSION['success']='';}

///////////////////////////// inclusion de tout ce qui faut ! /////////////////////////////
include('includes/admin/f.php');
$bdd=connect();

 
///////////////////////////// validation get id /////////////////////////////
if(!empty($_GET['id']) AND !filter_var($_GET['id'], FILTER_VALIDATE_INT)){unset($_GET['id']);}

///////////////////////////// connexion via cookies /////////////////////////////
if(!isuser() AND !empty($_COOKIE['auth_userid']) AND filter_var($_COOKIE['auth_userid'], FILTER_VALIDATE_INT) AND !empty($_COOKIE['auth_mdp'])) 
	{
	$val=$bdd->query('SELECT * FROM users WHERE etat!="delete" AND id='.$_COOKIE['auth_userid'])->fetch();
	if(isset($val['pseudo']) AND $_COOKIE['auth_mdp']===sha1($val['mdp'].'salt'))
		{
		login($val['pseudo'],$val['mdp'],'on');
		}
	else{
		$_SESSION['err'].='Cookies de connection invalides';
		setcookie('auth_userid', false, 1,'/','.donjonfacile.fr',false,true);
		setcookie('auth_mdp', false, 1,'/','.donjonfacile.fr',false,true);
		}
	}

////////////////////////////// update user //////////////////////////////
if(isset($_SESSION['user_id']))
	{
	if(time() > ($_SESSION['update_user_timer'] + 1*60))//refresh des infos toutes les 3 minutes
		{
		$bdd->exec('UPDATE users SET last_connect = LOCALTIME() WHERE id='.$_SESSION['user_id']);
		$req=$bdd->prepare('SELECT * FROM users WHERE id = :id AND etat != "delete"');
		$req->execute(array('id' => $_SESSION['user_id']));
		$user=$req->fetch();
		$_SESSION['user_data']=$user;
		$req->closeCursor();
		if($user['type']=='ban')
			{
			$reponse = $bdd->prepare('SELECT * FROM ban WHERE user_id= ? AND etat != "old"');$reponse->execute(array($_SESSION['user_id']));$rep = $reponse->fetch();$reponse->closeCursor();
			logout();
			session_start();
			if($rep['time']!=0){$_SESSION['warning'].='vous avez été banni de ce site jusque au '.date('d/m/Y à h\hi',$rep['time']).' ('.$rep['motif'].')<br/>';}
			else{$_SESSION['err'].='vous avez été banni de ce site définitivement ! ('.$rep['motif'].')<br/>';}
			}
		$_SESSION['update_user_timer']=time();
		}
	if(time() < $_SESSION['update_perso_timer'])
		{
		$_SESSION['list_perso']=$bdd->query('(SELECT * FROM perso WHERE user_id = '.$_SESSION['user_id'].' AND etat != "delete")
					UNION
				(SELECT p.*
				FROM users_persos as up
				INNER JOIN perso as p
				ON up.perso_id = p.id
				WHERE up.user_id='.$_SESSION['user_id'].' AND p.etat != "delete" AND up.etat != "delete")
				ORDER BY name DESC')->fetchAll();
		$_SESSION['update_perso_timer']=0;
		}
	}
ob_start(); //tamporisation de sortie : init
if(empty($_GET['mode'])){$mode='';}else{$mode=str_replace('.','',$_GET['mode']);}
if(!empty($_GET['page']))
{
if ($_GET['page']=='login')
	{
	if (isset($_POST['Mpseudo']) AND isset($_POST['Mmdp']))
		{
		if (isset($_POST['autoconnect']) AND $_POST['autoconnect']== 'on') {$autoconnect='on';} 
		else{$autoconnect='off';}
		login($_POST['Mpseudo'],sha1(md5($_POST['Mpseudo']).md5($_POST['Mmdp'])),$autoconnect);
		}
    header('Location:'.referer());
	exit();
	}
elseif ($_GET['page']=='logout')
	{
	logout();
	header('Location:'.referer());
	exit();
	}
elseif ($_GET['page']=='fiche'){include ('pages/fiche.php');exit();}
elseif ($_GET['page']=='fiche_advanced'){include ('pages/fiche_advanced.php');exit();}
elseif(!empty($_GET['page']))
	{
	if(is_file('pages/'.str_replace('.','',$_GET['page']).'.php')){include ('pages/'.str_replace('.','',$_GET['page']).'.php');}
	else{include('pages/404.php');}
	}
else
	{
    include ('pages/accueil.php');
	}
}else{include ('pages/accueil.php');}
if($_SERVER['REQUEST_URI']!='/accueil' AND $_SERVER['REQUEST_URI']!='' AND $_SERVER['REQUEST_URI']!='/'){$fil=array('accueil'=>'accueil');}
// }
$contenu = ob_get_clean();
ob_end_flush();//tamporisation de sortie : séparation du contenu et des variables

if(isset($titre)){$descr= ucfirst($titre).' - ';}else{$descr='';}
$keywords='';if(isset($titre)){$meta=explode(" ",$titre);foreach($meta as $cle=>$valeur){$keywords.= ', '.$valeur;}}
if(isset($titre)){$title= ucfirst(secure::html($titre,1)).TITRE_SITE;$_SESSION['titre']=$titre;} else {$title='page sans nom'.TITRE_SITE;$_SESSION['titre']='page sans nom';}
if(!isset($header)){$header='';}
if(!isset($fil)){$fil=array('Accueil'=>'accueil');}$fil=fil($fil);
$notif=notif();
// header( 'content-type: text/html; charset=utf-8' );
cache::design_render($contenu,$header,$title,$fil,$descr,$keywords,$_SERVER['REQUEST_URI'],$notif,$debut);


