 <?php 
session_start();
include('includes/admin/f.php');
if(isuser())
{
require_once "/chat/src/phpfreechat.class.php";
// $params = array();
$params["nick"] = $_SESSION['pseudo'];  // setup the intitial nickname
$params["language"] = "fr_FR";
$params['firstisadmin'] = false;
$params['frozen_nick'] = true;
$params["shownotice"]= 7;
$params["max_privmsg"]= 25;
$params["max_nick_len"]= 50;
$params["timeout"]= 20000;
$params["isadmin"]= isadmin();
$params['max_msg']= 150;
$params["admins"]= array('nonodrido');
$params["display_pfc_logo"]= false;
$params['timeout'] = 3*60*1000;
$params['connect_at_startup'] = true;
$params['showsmileys'] = true;
$params['display_ping'] = true;
$params["nickmeta"] = array(	'profil'=>'<a target="_blank" href="/membre/'.$_SESSION['user_id'].'/'.to_url($_SESSION['pseudo']).'">'.secure::html($_SESSION['pseudo'],1).'</a>'
								// 'avatar' => $_SESSION['user_data']['avatar']
							);
// $params['dyn_params']=array('nick','isadmin');
//  $params[''] = ; 
if(empty($_GET['chan']) OR empty($_GET['chan_id']) OR $_GET['chan']=='main')
	{
	$params["serverid"] = "main";
	$params['channels'] = array('Auberge');
	$params["title"] = 'Chat de DonjonFacile.fr';
	$params['focus_on_connect'] = false;
	}
else{
	if(isset($_GET['admin']) AND $_GET['admin']==md5($_GET['chan_id'].'admin')){$params["isadmin"]=true;}
	/* echo md5($_GET['chan_id'].'admin').'//'.$_GET['chan_id'].'admin';
	var_dump($params["isadmin"]); */
	$params['focus_on_connect'] = false;
	$params["serverid"] = urlencode($_GET['chan']).$_GET['chan_id'];
	$params['channels'] = array('Accueil');
	$params["title"] = 'Chan privé : '.secure::html(ucfirst(urldecode($_GET['chan'])),1);
	if(isset($_GET['mode']) AND $_GET['mode']=='group')
		{
		$params["title"] = 'Chat de la compagnie "'.secure::html(ucfirst(urldecode($_GET['chan'])),1).'"';
		$params["serverid"]='group'.$params["serverid"];
		}
	}
// setup urls
// $params["data_public_url"]   = "/chat/data/public";
// $params["server_script_url"] = "./demo21_with_hardcoded_urls.php";
$params["theme_default_url"] = "/chat/themes";

// setup paths
// $params["container_type"]         = "File";
// $params["container_cfg_chat_dir"] = "/chat/data/private/chat";

$params["debug"] = false;

@$chat = new phpFreeChat( $params );
 
 
 
 @$chat->printChat();
 echo'<p class="info-box" style="font-size:large;">Pour lancer un dé, faites la commande "/roll xdyyy+z" <br>
		Par exemple tapez "/roll 1d6" pour lancer un dé à 6 faces
		</p>
		<p>
		Voici la liste des commandes (/commande option):<br>
		ban, 
		banlist, 
		clear, 
		connect, 
		debug, 
		deop, 
		help, 
		invite, 
		join, 
		kick, 
		leave, 
		me, 
		op, 
		privmsg, 
		quit, 
		roll, 
		send, 
		unban, 
		version, 
		whois.</p>';
/* echo '	<style>
			#pfc_container {
				background: #eee;
				}
		</style>
		'; */
 }