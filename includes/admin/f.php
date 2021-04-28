<?php

///////////////////////////// verrouillage site /////////////////////////////
if(isset($_GET['verrou']) AND $_GET['verrou']=='code de verrouillage du site par url')
	{
	if(file_get_contents('verrou.php')=="<?php define('VERROU_SITE',false);")
		{
		file_put_contents('verrou.php', "<?php define('VERROU_SITE',true);",LOCK_EX);
		}
	else{file_put_contents('verrou.php', "<?php define('VERROU_SITE',false);",LOCK_EX);}
	}
include('verrou.php');
if(VERROU_SITE===true AND !isset($_GET['imtheadmin']))
	{
	if(isset($_GET['page']) AND $_GET['page']=='maintenance')
		{
		header("Status: 503 Service Unavailable", false, 503);
		include('pages/maintenance.php');
		exit;
		}
	else{
		header("Status: 302 Moved Temporarily", false, 302);
		header("Location: /maintenance");
		exit();
		}
	}

// autochargement classe
function chargerClasse($classe)
{
  require 'includes/admin/class.'.strtolower($classe). '.php';
}
function chargerClasseAjax($classe)
{
  require '../includes/admin/class.'.strtolower($classe). '.php';
}
if(isset($ajax)){spl_autoload_register('chargerClasseAjax');}
else{spl_autoload_register('chargerClasse');}
// déclaration de constantes
include('define.php');
include('connect.php');

// déclarations de fonctions
/* if(isset($ajax)){require_once("../ressources/nbbc/nbbc.php");require_once("class.phpmailer.php");}
else{require_once("./ressources/nbbc/nbbc.php");require_once("class.phpmailer.php");}
$bbcode = new BBCode; */
include('fonction.php');
?>