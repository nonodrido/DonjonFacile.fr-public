<?php 
function verif_uri($uri)
	{
	$req=explode('?',$_SERVER['REQUEST_URI'],2);
	if($req[0]!=$uri)
		{
		$get=(empty($req[1]))? '':'?'.$req[1];
		header("Status: 301 Moved Permanently", false, 301);
		header('Location: '.$uri.$get);//.explode('&',$_SERVER['argv'])
		$_SESSION['info'].='L\'url demandée était inexacte. Elle a été corrigée automatiquement. Si cette page se trouve dans vos favoris, vous devriez modifier son adresse.';
		exit();
		return false;
		}
	return true;
	}
function scan_stat($txt)
	{
	if(preg_match_all('#(AD|CHA|INT|COU|FO|PRD|AT|AT/PRD)[ ]?([+-][0-9]+\*{0,4})#',$txt,$result,PREG_SET_ORDER))
		{
		$modif=array();
		foreach($result as $val)
			{
			if(!isset($modif[$val[1]]))
				{
				if($val[1]=='INT'){$val[1]='INTL';}
				$modif[$val[1]]=$val[2];
				}
			}
		return $modif;
		}
	return array();
	}
function gravatar($email,$s=80,$d='mm',$r='pg')
	{
	return 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($email))).'?s='.$s.'&d='.$d.'&r='.$r;
	}
function rate_button($id)
	{
	if(isuser())
		{
		return '<div class="btn-group">
				  <button class="btn btn-mini" onclick="rate(1,'.$id.');" title="j\'aime"><i class="icon-thumbs-up"></i></button>
				  <button class="btn btn-mini" onclick="rate(-1,'.$id.');"title="je n\'aime pas"><i class="icon-thumbs-down"></i></button>
				  <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown" onclick="$(\'.rate_dropdown_'.$id.'\').load(\'/ajax/ajax_item_rate_wiew.php?item_id='.$id.'\');">
					<i class="icon-tasks"></i> <span class="caret"></span>
				  </button>
				  <div class="dropdown-menu rate_dropdown_'.$id.'">
					<div class="center"><img src="'.DIR_IMG_HTML.'ajax-loader.gif"/></div>
				  </div>
				</div>';
		}
	}
function isadmin($type='admin')
	{
	if(isset($_SESSION['user']) AND ($_SESSION['user']=='admin' OR $_SESSION['user']==$type)){return true;}
	return false;
	}
function isuser()
	{
	if(isset($_SESSION['user'])){return true;}
	return false;
	}
class ajax
	{
	function send($msg=false,$type='success',$title=false,$action=false,$option=false)
		{
		return json_encode(array('msg'=>$msg,'type'=>$type,'title'=>$title,'action'=>$action,'option'=>$option));
		}
	}
function clean_text($text)
	{
	if(is_string($text)){return str_replace('\\','',$text);}
	elseif(is_array($text))
		{
		foreach($text as $cle=>$val)
			{
			$text[$cle]=clean_text($val);
			}
		return $text;
		}
	else{return $text;}
	}
function add_item_filter($txt)
	{
	if(preg_match_all('#<span class="hide">{{ item ([0-9]+) }}</span>#', $txt, $matches))
		{
		foreach ($matches[1] as $val) {
			$txt=str_replace('<span class="hide">{{ item '.$val.' }}</span>',
							add_list($val,0,'',true)
							,$txt);
		}
		return $txt;
		}
	}
function help_button($titre,$txt,$place='right')
	{
	echo ' <a class="btn btn-info" rel="popover" 
	data-content="'.str_replace('"',"'",$txt).'" 
	data-original-title="'.str_replace('"',"'",$titre).'"
	data-placement="'.$place.'"
	><i class="icon-flag icon-white"></i> Aide</a> ';
	}
function jsEscape($str) { 
    return addcslashes($str,"\\'\"&\n\r<>"); 
} 
function notif()
	{
	$notif='<script>$(document).ready(function(){';
	if(!empty($_SESSION['err'])){if(mb_substr(trim($_SESSION['err']),mb_strlen(trim($_SESSION['err']))-5)=='<br/>'){$_SESSION['err']=mb_substr(trim($_SESSION['err']),0,mb_strlen(trim($_SESSION['err']))-5);}$notif.="$.pnotify({text: '".str_replace("'","\\'",$_SESSION['err'])."',type:'error',});";unset($_SESSION['err']);}
	if(!empty($_SESSION['warning'])){if(mb_substr(trim($_SESSION['warning']),mb_strlen(trim($_SESSION['warning']))-5)=='<br/>'){$_SESSION['warning']=mb_substr(trim($_SESSION['warning']),0,mb_strlen(trim($_SESSION['warning']))-5);}$notif.="$.pnotify({text: '".str_replace("'","\\'",$_SESSION['warning'])."',type:'warning',});";unset($_SESSION['warning']);}
	if(!empty($_SESSION['info'])){if(mb_substr(trim($_SESSION['info']),mb_strlen(trim($_SESSION['info']))-5)=='<br/>'){$_SESSION['info']=mb_substr(trim($_SESSION['info']),0,mb_strlen(trim($_SESSION['info']))-5);}$notif.="$.pnotify({text: '".str_replace("'","\\'",$_SESSION['info'])."',type:'info',});";unset($_SESSION['info']);}
	if(!empty($_SESSION['success'])){if(mb_substr(trim($_SESSION['success']),mb_strlen(trim($_SESSION['success']))-5)=='<br/>'){$_SESSION['success']=mb_substr(trim($_SESSION['success']),0,mb_strlen(trim($_SESSION['success']))-5);}$notif.="$.pnotify({text: '".str_replace("'","\\'",$_SESSION['success'])."',type:'success',});";unset($_SESSION['success']);}
	return $notif.'})</script>';
	}
function group_button($perso_id)
	{
	if(isset($_SESSION['user_id']) AND get_droits_perso($perso_id,$_SESSION['user_id'],'wiew'))
		{
		$bdd=connect();
		$req=$bdd->query('	SELECT *
							FROM `group`
							WHERE etat!="delete" AND user_id='.$_SESSION['user_id'].'
							AND id NOT IN (SELECT group_id FROM group_perso WHERE perso_id='.$perso_id.')');
		if($req->rowCount()!=0)
			{
			echo '	<div class="btn-group" style="display:inline-block;">
					  <a class="btn dropdown-toggle" data-toggle="dropdown"  style="color:black;">
						<i class="icon-th"></i> Recruter dans la compagnie 
						<span class="caret"></span>
					  </a>
					  <ul class="dropdown-menu">';
			while($p=$req->fetch())
				{
				echo '<li><a style="color:black;"  onclick="group_perso('.$perso_id.','.$p['id'].');reload_content(\''.$_SERVER['REQUEST_URI'].'?ajax=true\');">'.secure::html(ucfirst($p['name']),1).'</a></li>';
				}
			echo	 '</ul>=</div>';
			}
		}
	}
function fav_button($id,$type)
	{
	if(isset($_SESSION['user_id']))
		{
		$bdd=connect();
		$req=$bdd->query('SELECT * FROM fav WHERE etat!="delete" AND user_id='.$_SESSION['user_id'].' AND fav_id='.$id.' AND type="'.$type.'"');
		if($req->rowCount()==0){$active='';}else{$active=' active ';}
		echo '	<a id="btn_fav_'.$type.'_'.$id.'" class="btn '.$active.' btn_fav" onclick="fav('.$id.',\''.$type.'\');" 
				title="Les favoris sont plus facilement accessible via la barre supérieure du site.">
					<i class="icon-heart"></i>Favoris
				</a>';
		}
	}
function add_list($item_id,$option=0,$item_name='',$mode=false)
	{
	if(isset($_SESSION['user_id']) AND !empty($_SESSION['list_perso']))
		{
		if($mode)
			{
			$return='';
			$return.= '	<div class="btn-group dropup" style="display:inline-block;">
					  <a class="btn dropdown-toggle" data-toggle="dropdown"  style="color:black;">
						<i class="icon-plus-sign"></i> Ajouter à
						<span class="caret"></span>
					  </a>
					  <ul class="dropdown-menu">';
			foreach($_SESSION['list_perso'] as $p)
				{
				$return.= '<li><a style="color:black;"  onclick="add_item('.$item_id.','.$p['id'].');">'.secure::html($p['name'],1).'</a></li>';
				}
			$return.=	 '</ul>';
			if($option==1)
				{
				$return.= '<a href="/item/'.$item_id.'/'.to_url($item_name).'" class="btn" title="modifier/éditer"><i class="icon-edit"></i></a>
					<a class="btn btn-danger" href="/item/delete/'.$item_id.'/'.to_url($item_name).'" title="Supprimer"><i class="icon-trash"></i></a>';
				}
			$return.= '	</div>';
			return $return;
			}
		echo '	<div class="btn-group dropup" style="display:inline-block;">
				  <a class="btn dropdown-toggle" data-toggle="dropdown"  style="color:black;">
					<i class="icon-plus-sign"></i> Ajouter à
					<span class="caret"></span>
				  </a>
				  <ul class="dropdown-menu">';
		foreach($_SESSION['list_perso'] as $p)
			{
			echo '<li><a style="color:black;"  onclick="add_item('.$item_id.','.$p['id'].');">'.secure::html($p['name'],1).'</a></li>';
			}
		echo	 '</ul>';
		if($option==1)
			{
			echo '<a href="/item/'.$item_id.'/'.to_url($item_name).'" class="btn" title="modifier/éditer"><i class="icon-edit"></i></a>
				<a class="btn" href="/item/delete/'.$item_id.'/'.to_url($item_name).'" title="Supprimer"><i class="icon-trash"></i></a>';
			}
		echo '	</div>';
		}
	}
function add_button($id)
	{
	/* if($perso_id==='undefined' AND isset($_SESSION['current_perso'])){echo '<a class="button icon add" style="color:black;" onclick="add_item('.$id.','.$_SESSION['current_perso'].');">Ajouter</a>';}
	else{echo '<a class="button icon add" style="color:black;" onclick="add_item('.$id.','.$perso_id.');">Ajouter</a>';} */
	// return true;
	echo
	'<div>
		<div class="button-group">
			<button class="rerun button">Personnage actuel</button>
			<button class="button"></button>
		</div>
		<ul style="position:absolute;">';
		foreach($_SESSION['list_perso'] as $key=>$val)
			{
			echo '<li><a onclick="add_item('.$id.''.$val['id'].');return false;">'.secure::html($val['name'],1).'</a></li>';
			}
			echo'<!--<li class="dropdown-submenu">
					<a tabindex="-1" >More options</a>
					<ul class="dropdown-menu">
						<li></li>
					</ul>-->
				</li>';
	echo'</ul>
	</div>';
	}
function directory_delete($dossier_traite , $extension_choisie, $age_requis=0)
	{
	// On ouvre le dossier.
	$repertoire = opendir($dossier_traite);
	 
	// On lance notre boucle qui lira les fichiers un par un.
		while(false !== ($fichier = readdir($repertoire)))
			{
			// On met le chemin du fichier dans une variable simple
			$chemin = $dossier_traite."/".$fichier;
					
			// Les variables qui contiennent toutes les infos nécessaires.
			$infos = pathinfo($chemin);
			$extension = $infos['extension'];

			$age_fichier = time() - filemtime($chemin);
					
			// On n'oublie pas LA condition sous peine d'avoir quelques surprises. :p
			if($fichier!="." AND $fichier!=".." AND !is_dir($fichier) AND $extension == $extension_choisie AND $age_fichier > $age_requis)
				{
				unlink($chemin);
				}
			}
	closedir($repertoire); // On ferme !
	}
function str_check($str,$min,$max)
	{
	$strlen=mb_strlen($str);
	if($strlen>=$min AND $strlen<=$max){return true;}
	else{return false;}
	}
function envoi_mail($subject,$body,$address,$from_name="DonjonFacile.fr",$from='contact@donjonfacile.fr')
	{
	/*  $headers  = 'MIME-Version: 1.0' . "\r\n";
     $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

     // En-têtes additionnels
     $headers .= 'To: '.implode(',',array_keys($address)).'' . "\r\n";
     $headers .= 'From: DonjonFacile <contact@donjonfacile.fr>' . "\r\n";
	return mail (implode(',',array_keys($address)),$subject,$body,$headers); */
	$mail = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host       = 'hostname'; // SMTP server 'smtp.donjonfacile.fr'
	// $mail->SMTPSecure = "ssl";
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Port       = 450;                    // set the SMTP port for the GMAIL server
	$mail->Username   = 'contact@donjonfacile.fr'; // SMTP account username
	$mail->Password   = 'PASSWORD';        // SMTP account password
	$mail->CharSet = 'UTF-8';
	$mail->SetLanguage('fr');
	$mail->IsHTML(true);
	// $mail->SMTPDebug = true;
	// De qui vient le message, e-mail puis nom
	$mail->SetFrom = $from;
	$mail->From = $from;
	$mail->FromName = $from_name;
	// $mail->AddReplyTo('contact@donjonfacile.fr');
	// Définition du sujet/objet
	$mail->Subject = $subject;
	// On définit le contenu de cette page comme message
	$mail->MsgHTML($body);
	$mail->AltBody = "Ce message est au format HTML, votre messagerie n'accepte pas ce format.";
	// Il reste encore à ajouter au moins un destinataire
	foreach($address as $cle=>$val)//mail=>pseudo
		{
		$mail->AddBCC($cle,$val);
		// $mail->AddAddress($cle,$val);
		}
	// Pour finir, on envoi l'e-mail
	if($mail->Send()){return true;}else{echo '<br>Le message n\'a pas pu être envoyé.<br>';return false;}
	}

function check_mdp($pseudo,$mdp,$id=0)
	{
	$bdd=connect();
	$pass=sha1(md5('salt').md5($mdp));
	$rep=$bdd->query('SELECT * FROM users WHERE id='.$id.' AND mdp="'.$pass.'"');
	if($rep->rowCount() != 0){return true;}else{return false;}
	}
function get_pseudo($id)
	{
	$bdd=connect();
	$req=$bdd->query('SELECT pseudo FROM users WHERE id='.$id);
	if($req->rowCount() == 0){return '';}
	else{return secure::html($req->fetch(PDO::FETCH_OBJ)->pseudo,1);}
	}
function resize_img($img_url,$max,$final_url='')
	{
	$img = imagecreatefromjpeg($img_url);
	$x = imagesx($img);
	$y = imagesy($img);
	if($x>$max or $y>$max)
	{
        if($x>$y)
        {
                $nx = $max;
        $ny = $y/($x/$max);
        }
        else
        {
                $nx = $x/($y/$max);
        $ny = $max;
        }
	}
	$nimg = imagecreatetruecolor($nx,$ny);
	imagecopyresampled($nimg,$img,0,0,0,0,$nx,$ny,$x,$y);
	if(empty($final_url)){imagejpeg($nimg);}
	else{imagejpeg($nimg,$final_url);}
	}
function get_rate($item_id,$user_id=false)
	{
	$bdd=connect();
	if($user_id)
		{
		$result=$bdd->query('SELECT rate FROM rating WHERE etat="default" AND user_id='.$user_id.' AND item_id='.$item_id)->fetch();
		if($result){return $result['rate'];}
		}
	else{
		$result=$bdd->query('SELECT  AVG(rate) AS rate FROM rating WHERE etat="default" AND item_id='.$item_id)->fetch();
		if($result){return round($result['rate'],1);}
		}
	return false;
	}
function fil($array=array())
	{
	// echo '<div id="fil">';
	// $img=base64_encode_image('ressources/img/img_html/nav_fleche_droite_off.png','png',2);
	$result='';
	foreach($array as $cle => $val)
		{
		if($val='accueil'){$result.= '  <li><a href="/">'.secure::html(ucfirst($cle),1).'</a></li> ';}
		else{$result.= '  <span class="divider">></span><li> <a href="/'.$val.'">'.secure::html(ucfirst($cle),1).'</a></li> ';}
		}
	if(!isset($_SESSION['titre']) OR $_SESSION['titre']=='accueil'){$_SESSION['titre']='';}
	else{$result.= '<span class="divider">></span>';}
	$result.= ' <li calss="active" > <h1 style="
					background-color: transparent;
					border-bottom-left-radius: 0px;
					border-bottom-right-radius: 0px;
					border-top-left-radius: 0px;
					border-top-right-radius: 0px;
					color: #666;
					font-weight:normal;
					display: inline-block;
					font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;
					font-size: 14px;
					height: 20px;
					line-height: 20px;
					list-style-image: none;
					list-style-position: outside;
					list-style-type: none;
					margin-bottom: 0px;
					margin-left: 0px;
					margin-right: 0px;
					margin-top: 0px;
					min-width: 0px;
					padding-bottom: 0px;
					padding-left: 0px;
					padding-right: 0px;
					padding-top: 0px;
					text-align: left;
					text-shadow: white 0px 1px 0px;">'.secure::html(ucfirst($_SESSION['titre']),1).'</h1></li>';
	
	return $result;
	}
function update_perso($id)
	{
	$bdd=connect();
	$bdd->exec('UPDATE perso SET date=NOW() WHERE id='.$id);
	}
function tronquer_texte($texte, $longeur_max,$init=0,$option=0)
	{
	if($option==1){$longeur_max=$longeur_max-3;$end='...';}
	elseif($option==2){$longeur_max=$longeur_max;$end='';}
	else{$end='...';}
	if (mb_strlen($texte) > $longeur_max) 
    {
	$texte = mb_substr($texte, $init, $longeur_max); 
    $dernier_espace = mb_strrpos($texte, " "); 
    if($dernier_espace!=0){$texte = mb_substr($texte, $init, $dernier_espace).$end;}
    } 
    return trim($texte);
	}
function trunc($texte, $longeur_max)
	{
	if (mb_strlen($texte) > $longeur_max) 
		{
		$texte = mb_substr($texte, 0, $longeur_max-3).'...'; 
		}
	return $texte;
	}
function add_item($item_id,$perso_id,$mode='qte')
	{
	$bdd=connect();
	$rep=$bdd->query('SELECT * FROM item WHERE etat!="delete" AND id='.$item_id);
	if($rep->rowCount() != 0)
		{
		$result=$rep->fetch();
		$bdd->exec('INSERT INTO perso_items SET perso_id='.$perso_id.',item_id='.$item_id.',`'.$mode.'`=1,type="'.$result['type'].'" 
		ON DUPLICATE KEY UPDATE etat="default", date=NOW(),  `'.$mode.'`=`'.$mode.'`+1');
		update_perso($perso_id);
		return true;
		}
	return false;
	}
function delete_item($item_id,$perso_id,$mode='qte')
	{
	$item_id=(int) $item_id;$perso_id=(int) $perso_id;
	$bdd=connect();
	$bdd->exec('UPDATE perso_items SET '.$mode.'=0 WHERE perso_id='.$perso_id.' AND item_id='.$item_id);
	/* if($mode==1){$bdd->exec('UPDATE perso_items SET qte=1 WHERE perso_id='.$perso_id.' AND item_id='.$item_id);return true;}
	else{
		// $val=$bdd->query('SELECT etat FROM perso_items WHERE perso_id='.$perso_id.' AND item_id='.$item_id);
		// $val=$val->fetch();
		// if($val['equip']=='equip' AND in_array($val['type'],array('armement','protec'))){$equip=$val['equip'];$qte=1;}else{$equip='delete';$qte=0;}
		$bdd->exec('UPDATE perso_items SET equip='.$equip.', qte='.$qte.' WHERE perso_id='.$perso_id.' AND item_id='.$item_id);
		return true;
		} */
	update_perso($perso_id);return true;
	}
function timeInWords( $datetime )
	{
	$now = new DateTime();
	$datetime = new DateTime($datetime,new DateTimeZone('Europe/Paris'));
	$distance= $now->diff($datetime)->format('%i');
	$days = array('Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi');
	$months = array('Janvier','Fevrier','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');
	if( $distance <= 1 )
	{
		$return = ($distance == 0) ? 'il y a moins d\'1 minute' : 'il y a 1 minute';
	} elseif( $distance < 60 )
	{
		$return = 'il y a '.$distance . ' minutes';
	} elseif( $distance < 119 )
	{
		$return = 'il y a 1 heure';
	} elseif( $distance < 1440 )
	{
		$return = 'il y a '.round(floatval($distance) / 60.0) . ' heures';
	} elseif( $distance < 2880 )
	{
		$return = 'hier à ' . $datetime->format('H:i');
	} elseif( $distance < 14568 )
	{
		$return = $days[$timestamp->format('w')] . ' à ' . $datetime->format('H:i');
	} else {
		$return = $datetime->format('d').' '.$months[$datetime->format('n')-1] . ( ( date('Y') != $datetime->format('Y') ) ? ' ' . $datetime->format('Y') : '' . ' à ' . $datetime->format('H:i') );
	}

	return '<abbr class="timee" title="' . $datetime->getTimestamp() . '">' . $return .'</abbr>';
	}
function get_droits_perso($perso_id,$user_id,$type='full')
	{
	if(isset($_SESSION['user']) AND $_SESSION['user'] == 'admin'){return true;}
	elseif($type=='wiew')
		{
		$bdd=connect();
		$rep = $bdd->query('SELECT etat FROM users_persos WHERE user_id='.$user_id.' AND perso_id='.$perso_id.' AND etat!="delete"
							UNION
							SELECT etat FROM perso WHERE user_id='.$user_id.' AND id='.$perso_id.' AND etat!="delete"');
		if($rep->rowCount() == 0){return false;}
		else{return true;}
		}
	elseif($type=='group')
		{
		$bdd=connect();
		$rep = $bdd->query('SELECT etat FROM users_persos WHERE user_id='.$user_id.' AND perso_id='.$perso_id.' AND etat!="delete"
								 AND (type="full" OR type="group")
							UNION
							SELECT etat FROM perso WHERE user_id='.$user_id.' AND id='.$perso_id.' AND etat!="delete"');
		if($rep->rowCount() == 0){return false;}
		else{return true;}
		}
	elseif($type=='full'){
		$bdd=connect();
		$rep = $bdd->query('SELECT etat FROM users_persos WHERE user_id='.$user_id.' AND perso_id='.$perso_id.' AND etat!="delete" AND type="full"
							UNION
							SELECT etat FROM perso WHERE user_id='.$user_id.' AND id='.$perso_id.' AND etat!="delete"');
		if($rep->rowCount() == 0){return false;}
		else{return true;}
		}
	return false;
	}
function to_url($chaine)
	{
	if(is_numeric($chaine)){return 'defaut';}
	return str_replace('+','_',urlencode(filter($chaine)));
	}
function filter($chaine)
	{
	$chaine=utf8_decode($chaine);
    //  les accents
    $chaine=trim($chaine);
    $chaine= strtr($chaine,utf8_decode("ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ"),"aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn");
    //  les caracètres spéciaux (aures que lettres et chiffres en fait)
    $chaine = preg_replace('/([^.a-z0-9]+)/i', '_', $chaine);
    $chaine = strtolower($chaine);
	$chaine=str_replace('.','_',$chaine);
    return utf8_encode($chaine);
	}
function get_ip(){ 
if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){ 
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];} 
elseif(isset($_SERVER['HTTP_CLIENT_IP'])){ 
$ip = $_SERVER['HTTP_CLIENT_IP'];} 
else{ $ip = $_SERVER['REMOTE_ADDR'];} 
return $ip;}

function encode_array($args)
	{
	  if(!is_array($args)) return false;
	  $c = 0;
	  $out = '';
	  foreach($args as $name => $value)
	  {
		if($c++ != 0) $out .= '&';
		$out .= urlencode($name).'=';
		if(is_array($value))
		{
		  $out .= urlencode(serialize($value));
		}else{
		  $out .= urlencode($value);
		}
	  }
	  return $out . "\n";
	}

/* function notif($message='notification',$type='info',$user_id=0)
	{
	$bdd= connect();
	if($user_id==0 AND isset($_SESSION['user_id'])){$user_id=$_SESSION['user_id'];}
	$bdd->prepare('INSERT INTO notif(message,type,user_id) VALUES(:mess,:type,:user_id)')
	->execute(array('user_id' => $user_id,'mess'=> $message, 'type'=>$type));
	} */
function genpwd($length = 8)
{
    return mb_substr(md5(uniqid(mt_rand(), true)), 0, $length);
}

function array_key_delete($array,$val)
	{
	$key=array_search($val, $array);
	if($key != false OR $key ==0){unset($array[$key]);}
	return $array;
	}


/*function reduireUrl($matches)
{
	if(mb_strlen($matches[0]) > 25)
		return mb_substr($matches[0],0,20).'[...]'.mb_substr($matches[0],-10);
	else
		return $matches[0];
}

// et mon beau parser
function parser($message)
{
	$message = htmlspecialchars($message, ENT_NOQUOTES);
	// Parsage des smileys
	$smiliesName = array(':\)', ';\)', ':euh:', ':pleure:', ':D', ':P', ':\(', ':unsure2:');
	$smiliesUrl  = array('sourire.png', 'clin-oeil.png', 'euh.png', 'pleurs.png', 'sourired.png', 'tongue.png', 'triste.png', 'unsure2.gif');
	$smiliesPath = "http://remontees.free.fr/images/smileys/";
	
	for ($i = 0, $c = count($smiliesName); $i < $c; $i++) {
		$message = preg_replace('`' . $smiliesName[$i] . '`isU', '<img src="' . $smiliesPath . $smiliesUrl[$i] . '" alt="smiley" />', $message);
	}
	$message = preg_replace('#(?<!\[(?:img|url)\])http://[a-z0-9._/-\?]+#i', '<a href="$0">$0</a>', $message);
	$message = preg_replace('#\[i\](.+)\[/i\]#isU', '<em>$1</em>', $message);
	$message = preg_replace('#\[b\](.+)\[/b\]#isU', '<strong>$1</strong>', $message);
	$message = preg_replace('#\[u\](.+)\[/u\]#isU', '<span class="souligne">$1</span>', $message);
	$message = preg_replace('#\[img\](.+)\[/img\]#isU', '<img class="imagecenter" src="$1" alt="Image utilisateur" />', $message);
	$message = preg_replace_callback('#(?<!\[(?:img|url)\])http://[a-z0-9._/-\?]+#i', reduireUrl, $message);
	$message = preg_replace('#\[url=(.+)\](.+)\[/url\]#isU', '<a href="$1" title="$2">$2</a>', $message);
	$message = preg_replace('#\[url\](.+)\[/url\]#isU', '<a href="$1">$1</a>', $message);
	$message = preg_replace('#\[legende\](.+)\[/legende\]#isU', '<p class="legendes">$1</p>', $message);
	$message = preg_replace('#\[size=(.+)\](.+)\[/size\]#isU', '<span class="$1">$2</p>', $message);
	$message = preg_replace('#\[titre1\](.+)\[/titre1\]#isU', '<h2>$1</h2>', $message);
	$message = preg_replace('#\[titre2\](.+)\[/titre2\]#isU', '<h3>$1</h3>', $message);
	$message = preg_replace('#\[left\](.+)\[/left\]#isU', '<p class="left">$1</p>', $message);
	$message = preg_replace('#\[center\](.+)\[/center\]#isU', '<p class="center">$1</p>', $message);
	$message = preg_replace('#\[right\](.+)\[/right\]#isU', '<p class="right">$1</p>', $message);
	$message = preg_replace('#\[abbr=(.+)\](.+)\[/abbr\]#isU', '<abbr title="$1">$2</abbr>', $message);
	$message = preg_replace('#\[sup\](.+)\[/sup\]#isU', '<sup>$1</sup>', $message);
	$message = preg_replace('#\[sub\](.+)\[/sub\]#isU', '<sub>$1</sub>', $message);
	$message = recursiveReplaceElements($message, '\[list(?:=(1))?]', '\[/list]', 'liste');
	$message = nl2br($message);
	return $message;
}*/
function date_bdd($date,$format=DATETIME_FORMAT)
	{
	$date=new DateTime($date);
	return $date->format($format);
	}
function get_money($money)
	{
	$money=$money['LB']*500+$money['LT']*100+$money['PO']+$money['PA']/10+$money['PC']/100;
	return $money;
	}
function get_niv($exp)//valable jusqu'au lvl 20
	{
	if($exp==0){return '1';}
	else{
		$lvl=0;
		$lvl_xp=0;
		while($exp > $lvl_xp)
			{
			$lvl=$lvl+1;
			$lvl_xp=$lvl_xp+($lvl-1)*100;
			}
		$lvl=$lvl-1;
		return $lvl;
		}
	}

/*****************************
* sizethis
* ----------------------------
* Retourne le poids d'un dossier
* ----------------------------
* Prend en paramètre le dossier source
* @param string $src : l'adresse du dossier source
* @return int : taille en octets
*****************************/
function sizethis($src){
    $size = 0;
    $h = @opendir($src);
    while (($o = @readdir($h)) !== FALSE){
        if (($o != '.') and ($o != '..')){
            if (is_dir($src.'/'.$o))
                $size = $size + sizethis($src.'/'.$o);
            else
                $size = $size+filesize($src.'/'.$o);
        }
    }
    @closedir($h);
    return ($size);
}

function remove_accents($str, $charset='utf-8')
{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    
    return $str;
}

function f_js($f,$opt=false)
	{
	if($opt){return '<script type="text/javascript">$(document).ready(function() {'.$f.'});</script>';}
	echo '<script type="text/javascript">$(document).ready(function() {'.$f.'});</script>';
	}

function connect()
{
	try{
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$pdo_options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES UTF8';
	@$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD, $pdo_options);
	return $bdd;
	}
	catch(Exception $e){
		header("Status: 503 Service Unavailable", false, 503);
		envoi_mail('erreur mysql - '.date('H:i').' - donjonfacile.fr','Une erreur mysql est survenue sur donjonfacile.fr',array('nonodrido@gmail.com'=>'nonodrido','contact@donjonfacile.fr'=>'nonodrido'));
		exit('<img src="/ressources/img/img_html/elfe02-menu.gif" style="float:left;"/><h2>Erreur : La base de donnée du site est temporairement indisponible</h2> 
		Veuillez réessayer de vous connecter plus tard, le problème sera certainement corrigé.<br><a href="mailto:contact@donjonfacile.fr">contact</a>');
		}
}

class Secure
	{
	public static function bdd($string)
		{
		if(ctype_digit($string)){$string = intval($string);}
		else{$string = $string = addcslashes($string, '%_');}return $string;
		}
	public static function html($string,$opt=0)
		{
		if($opt==0){$bbcode = new BBCode;return stripslashes(str_replace('&#039;',"'",str_replace('\\','',$bbcode->Parse(stripslashes($string)))));}
		else{return stripslashes(htmlspecialchars(str_replace('&#039;',"'",str_replace('\\','',stripcslashes($string))),ENT_QUOTES));}
		}
	}
function age($annee_naissance, $mois_naissance, $jour_naissance, $timestamp = '') {
 
	//Si on veut vérifier à la date actuelle ( par défaut )
	if(empty($timestamp))
		$timestamp = time();
 
	//On evalue l'age, à un an par exces
	$age = date('Y',$timestamp) - $annee_naissance;
 
	//On retire un an si l'anniversaire n'est pas encore passé
	if($mois_naissance > date('n', $timestamp) || ( $mois_naissance== date('n', $timestamp) && $jour_naissance > date('j', $timestamp)))
		$age--;
 
	return $age;
}

function referer()
    {
	if(isset($_SERVER['HTTP_REFERER']) AND $_SERVER['HTTP_REFERER']!='http://donjonfacile.fr/inscription' AND $_SERVER['HTTP_REFERER']!='http://localhost/inscription')
	{$source=$_SERVER['HTTP_REFERER'];}
	else {$source = "/";}
	return $source;
	}

// fonction de connection //

function login($pseudo,$mdp,$autoconnect='off')
    {
	$existence_ft = '';
	if(file_exists('antibrute/'.$pseudo.'.tmp'))
		{
		$fichier_tentatives = fopen('antibrute/'.$pseudo.'.tmp', 'r+');$contenu_tentatives = fgets($fichier_tentatives);$infos_tentatives = explode(';', $contenu_tentatives);
		if($infos_tentatives[0] == date('d/m/Y')){$tentatives = $infos_tentatives[1];}else{$existence_ft = 2;$tentatives = 0;}
		}
	else{$existence_ft = 1;$tentatives = 0;}

	if($tentatives < 15)
		{
		try 
			{
			$bdd= connect();
			$reponse = $bdd->prepare('SELECT * FROM users WHERE pseudo= ? AND etat != "delete"');
			$reponse->execute(array($pseudo));
			$donnees = $reponse->fetch();
			$reponse->closeCursor();
			if ($mdp == $donnees['mdp'] AND $donnees['type'] != 'ban') {
				$_SESSION['user']= $donnees['type'];
				$_SESSION['pseudo']= $pseudo;
				$_SESSION['mail']= $donnees['mail'];
				$_SESSION['user_id']= $donnees['id'];
				$_SESSION['user_avatar']= $donnees['avatar'];
				$_SESSION['update_perso_timer']=time()+100000*60;
				$_SESSION['update_user_timer']=	0;
				/* création des cookies */ 
				if ($autoconnect== 'on')
					{
					setcookie('auth_userid', $donnees['id'], time() + 14*24*3600,'/','.donjonfacile.fr',false,true);
					setcookie('auth_mdp', sha1($mdp.'salt'), time() + 14*24*3600,'/','.donjonfacile.fr',false,true);
					}
				$bdd= connect();
				$req = $bdd->prepare('UPDATE users SET last_connect = :time, last_ip = :ip, last_nav = :nav, last_geoloc = :geoloc WHERE pseudo = :pseudo ');
				include("includes/geoloc.php");
				$req->execute(array(
					'time' => date('Y-m-d h-i-s'),
					'ip' => get_ip(),
					'nav' => $_SERVER["HTTP_USER_AGENT"],//serialize(get_browser(null, true))
					'geoloc' => $geoloc,
					'pseudo' => $pseudo,
					));
				$_SESSION['success'].='Vous vous êtes correctement connecté(e) ou reconnecté(e) !';
				// if($geoloc!='erreur'){setcookie('geoloc', $geoloc, time() + 365*24*3600);}
				}
			else{
				if ($mdp != $donnees['mdp']){$_SESSION['err'].='mauvais indentifiant ou mot de passe<br/>';}
				else if($donnees['type'] == 'ban')
					{
					$reponse = $bdd->prepare('SELECT * FROM ban WHERE user_id= ? AND etat != "old"');$reponse->execute(array($donnees['id']));$rep = $reponse->fetch();$reponse->closeCursor();
					if($rep['time']!=0)
						{
						if(time()>$rep['time'])
							{
							$req = $bdd->prepare('UPDATE users SET type = :opt WHERE ID = :id');
							$req->execute(array('id' => $donnees['id'],'opt' => 'user'));
							$req = $bdd->prepare('UPDATE ban SET etat = :opt WHERE user_id = :id');
							$req->execute(array('id' => $donnees['id'],'opt' => 'old'));
							$_SESSION['success'].='votre bannissement a pris fin, nous espérons que cela vous dissuadera d\'enfreindre à nouveau le règlement du site<br/>';
							login($pseudo,$mdp);
							}
						$_SESSION['warning'].='vous avez été banni de ce site jusque au '.date('d/m/Y à h\hi',$rep['time']).' ('.$rep['motif'].')<br/>';
						}
					else{$_SESSION['err'].='vous avez été banni de ce site définitivement ! ('.$rep['motif'].')<br/>';}
					}
				if($existence_ft == 1){$creation_fichier = fopen('antibrute/'.$pseudo.'.tmp', 'a+');fputs($creation_fichier, date('d/m/Y').';1');fclose($creation_fichier);}
				elseif($existence_ft == 2){fseek($fichier_tentatives, 0);fputs($fichier_tentatives, date('d/m/Y').';1');}
				else{fseek($fichier_tentatives, 11);fputs($fichier_tentatives, $tentatives + 1);}
				setcookie('auth_userid', false, 1,'/','.donjonfacile.fr',false,true);
				setcookie('auth_mdp', false, 1,'/','.donjonfacile.fr',false,true);
				}
			}
		catch(Exception $e){die('Erreur : '.$e->getMessage());}
		}else{$_SESSION['warning'].='trop d\'essais de mots de passe erronés sur ce compte. il est bloqué jusqu\'à demain. Pour toute réclamations, veuillez contactez un administrateur<br/>';}
	}


// fonction de deconnection
function logout()
    {
	if(isset($_COOKIE['auth_userid']) OR isset($_COOKIE['auth_mdp']))
		{
		setcookie('auth_userid', false, 1,'/','.donjonfacile.fr',false,true);
		setcookie('auth_mdp', false, 1,'/','.donjonfacile.fr',false,true);
		}
	$_SESSION = array();session_destroy();
	if (isset($_COOKIE[session_name()])){setcookie(session_name(),false,1,'/','donjonfacile.fr',false,true);}
}

function tinyMCEconfig()
    {
	return '<script language="javascript" type="text/javascript" src="/ressources/js/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript">
		tinyMCE.init({
        // General options
        mode : "textareas",
        theme : "advanced",
        plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        // Theme options
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage,forecolor,backcolor",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,

        // Skin options
        skin : "o2k7",
        skin_variant : "silver",

        // Example content CSS (should be your site CSS)
        //content_css : "css/css.php", //à n\'activer que dans le cas d\'un apercu

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "js/template_list.js",
        external_link_list_url : "js/link_list.js",
        external_image_list_url : "js/image_list.js",
        media_external_list_url : "js/media_list.js",

        // Replace values for the template plugin
        template_replace_values : {
                username : "Some User",
                staffid : "991234"
        }
		});
	</script>';
	}

function calendar($date='current')//timestamp
    {
	if($date=='current'){$date=time();}
	if(filter_var($date, FILTER_VALIDATE_INT) === false){$date=strtotime($date);}
	$day=date("N",$date);$lune=$decade=date("z",$date);
	$list= array('day'=>array("Obouloserin","Crômerin","Menestrin","Ilshidrin","Lafounerin","Caddyrin","Dlulerin"),'decadename'=> array("Décade de Sbroz","Décade de Th’ungi","Décade d’Uhien le Velu","Fête d’Honslépêle (jour du Sameule)","Décade de Lakkan","Décade de Sblortz","Décade maudite de Gzor","Décade des champignons","journée de la fête de Dékhon(dis aussi «jour du con d’or»)",'Décade de Kzaranagax l’Archi-Mage ou "de l’équité"',"Décade des géraniums en pôt","Décade de Phytgar Ranald","Fête des giboulées","Décade des géraniums gelés","Fête des grenouilles","Décade des pieds humides","Décade de Zaralbak","Petite Décade de Ravsgalat","Décade de Swimaf","Décade de Vontorz","Décade de Lasinjan","Décade des premières moissons","jour de la fête de la bière de printemps","Décade de la truite","Décade du grand Khan Ikul","Fête de Dlul, jour des Feignasses, jour du sommeil, jour des couettes, jour du Cursed Pillow of Slumber","Décade des Grand Départs","Décade des Pèlerins de Dlul","Décade des Grands Retours","Fête de Oboulos","Décade des moissons tardives","Fête de la bière d’automne","Décade des vendanges","Décade des bonnes résolutions","Décade des Trolls","Fête du vin nouveau frelaté","Jour chômé du Mal de Tête","Décade de Kazarmon","Fête des pommes(incidemment, Jour de Deuil chez les Elfes)","Décade des liches","Décade des barbares Drombards","Décade des barbares Moriacs","Décade des barbares Syldériens","Fête de la Baston, jour de Crom, jour pour pourrir ses voisins","Décade des cochons","Fête du gras jambon","Décade des boules de neige","Décade de Saint Taklauss","Jour Sacré, fête de la Grande Binouze, Vénération hivernale de Dlul"),'decade'=> array(10,20,30,31,41,51,61,71,74,84,94,104,105,115,116,126,136,145,155,165,175,185,188,198,208,209,210,220,230,231,241,242,252,262,272,273,274,284,285,295,305,315,325,326,336,337,347,357,367),'lune'=> array("Keleurithil, la lune des plants sous terre","Dif’isil, la lune des perce-neige","Yrfoulmûn, la lune des nez meurtris","Walkithil, la lune des bourgeons","Bakhoulë, la lune des douces brises","Sailormûn, la lune des navigateurs","Fuhalië, la lune des jours sans fin","Teikitisil, la lune des pèlerins","Peri’më, la lune du déclin des jours","Petipanië, la lune des bourrasques","Taratasta, la lune des citrouilles","Malgarë, la lune des premiers gels","Gudnaïthmûn, la lune maudite du Chaos","Gudnaïthmûn, la lune maudite du Chaos"));
	/* jour */		$day=$list['day'][$day-1];
	/* décade */	$i=0;$ok=false;while($ok != true){if($decade<=$list['decade'][$i]){$decade=$list['decadename'][$i];$ok=true;}$i++;}
	/* lune */		$i=0;$ok=false;while($ok != true){if($lune<=28*($i+1)){$lune=$list['lune'][$i];$ok=true;}$i++;}
	return $day.", ".$decade." (".$lune.")";
	}

// fonction pour formater le nom de page pour les stats
Function xtTraiter($nompage) 
	{
     $nompage = strtolower($nompage);
     $nompage = strtr($nompage,"àâäáîïíôöóùûüéèêëçñ","aaaaiiiooouuueeeecn");
     $nompage = preg_replace("#[^a-z0-9_:~\\\/\-]#i","_",$nompage);
     return($nompage);
	}
	
function base64_encode_image ($filename=string,$filetype=string,$mode=1) {
    if ($filename) {
       if($mode==1){ echo 'data:image/' . $filetype . ';base64,' . base64_encode(file_get_contents($filename));}
	   elseif($mode==2){ return 'data:image/' . $filetype . ';base64,' . base64_encode(file_get_contents($filename));}
    }
}

// redimensionnement d'image
function redimage($img_src,$img_dest,$dst_w,$dst_h) 
	{
   // Lit les dimensions de l'image
   $size = GetImageSize($img_src);  
   $src_w = $size[0]; $src_h = $size[1];  
   // Teste les dimensions tenant dans la zone
   $test_h = round(($dst_w / $src_w) * $src_h);
   $test_w = round(($dst_h / $src_h) * $src_w);
   // Si Height final non précisé (0)
   if(!$dst_h) $dst_h = $test_h;
   // Sinon si Width final non précisé (0)
   elseif(!$dst_w) $dst_w = $test_w;
   // Sinon teste quel redimensionnement tient dans la zone
   elseif($test_h>$dst_h) $dst_w = $test_w;
   else $dst_h = $test_h;

   // La vignette existe ?
   $test = (file_exists($img_dest));
   // L'original a été modifié ?
   if($test)
      $test = (filemtime($img_dest)>filemtime($img_src));
   // Les dimensions de la vignette sont correctes ?
   if($test) {
      $size2 = GetImageSize($img_dest);
      $test = ($size2[0]==$dst_w);
      $test = ($size2[1]==$dst_h);
   }

   // Créer la vignette ?
   if(!$test) {
      // Crée une image vierge aux bonnes dimensions
      $dst_im = ImageCreate($dst_w,$dst_h);
      // Copie dedans l'image initiale redimensionnée
      $src_im = ImageCreateFromJpeg($img_src);
      ImageCopyResized($dst_im,$src_im,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);
      // Sauve la nouvelle image
      ImageJpeg($dst_im,$img_dest);
      // Détruis les tampons
      ImageDestroy($dst_im);  
      ImageDestroy($src_im);
   }

   // Affiche le descritif de la vignette
   echo "SRC='".$img_dest."' WIDTH=".$dst_w." HEIGHT=".$dst_h;
	}
function format_mail($txt_array)
	{
	$return='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
</head>
<body style="padding:0; margin:0; background:#edf9ff">
	
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#edf9ff">
	<tr>
		<td align="center" valign="top">
			<table width="614" border="0" cellspacing="0" cellpadding="5" bgcolor="#d6edf9">
				<tr>
					<td>
			
						<table width="614" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
							<tr>
								<td>
				<table width="590" border="0" cellspacing="17" cellpadding="0">
					<tr>
						<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<!--<tr>
									<td style="font-family: Arial; font-size:10px; line-height:12px; text-align:center; color:#888888;">
										
									</td>
									
								</tr>-->
								<tr>
									<td bgcolor="#7bbcde">
										<table width="100%" border="0" cellspacing="0" cellpadding="10">
											<tr>
												<td style="color:#fff; font-size:11px; font-weight:bold; font-family:Arial; text-transform:uppercase; font-style:italic; 	">
													<a target="_blank" style="color:white;text-decoration:none;" href="http://www.facebook.com/DonjonFacile">
													<img src="http://donjonfacile.fr/ressources/img/img_html/like-glyph.png" border="0" width="8" height="14">
													 Facebook</a> 
													<a target="_blank" style="color:white;text-decoration:none;" href="https://twitter.com/DonjonFacile">
													<img src="http://donjonfacile.fr/ressources/img/img_html/tweet-glyph.png" border="0" width="17" height="13">
													 Twitter</a>
												</td>
												<td style="color:#fff; font-size:11px; font-weight:bold; font-family:Arial; text-transform:uppercase; font-style:italic; 	" align="right">
													'.date(DATETIME_FORMAT).'
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td style="font-size:0; line-height:0;"><a target="_blank" href="http://donjonfacile.fr"><img alt="DonjonFacile.fr" src="http://donjonfacile.fr/ressources/img/img_css/baniere_center.png" border="0" alt="" /></a></td>
								</tr>
								
								<tr>
									<td>
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td valign="top">
													<table width="100%" border="0" cellspacing="0" cellpadding="0">';
													
					foreach($txt_array as $titre=>$contenu)
						{
						$return.='	<tr>
										<td style="font-family: Arial; font-size:14px; line-height:19px; text-align:left; color:#666;padding-bottom:7px;border-bottom: solid 1px #d6edf9;">
											<br>
											<div style="font-family: Georgia; font-size:26px; line-height:30px; color:#3399cc; font-weight:normal; text-align:left; ">
												'.ucfirst($titre).'
											</div><br>
											'.$contenu.'
											<br><br>
										</td>
									</tr>';
						}								
					$return.= '							
													</table><br><br>
														<small><a target="_blank" style="color:grey;" href="http://donjonfacile.fr/membre/param">
															Se désinscrire de la newsletter
														</a></small>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								
							</table>
						</td>
					</tr>
					
				</table>
			</td>
		</tr>
		
	</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="font-family: Arial; font-size:10px; line-height:12px; text-align:center; color:#888888;"><br />	
		Ceci est un e-mail automatique envoyé via DonjonFacile.fr, merci de ne pas y répondre.
		Néanmoins, vous pouvez me contacter directement via mon site web ou sur le mail 
		<a href="mailto:contact@donjonfacile.fr" style="color:#888888; text-decoration:underline;">contact@donjonfacile.fr</a>.
		<br><br>
		Si cet e-mail a été placé dans votre courrier indésirable, marquez-le comme acceptable.<br>
		<a href="http://donjonfacile.fr/ajax/ajax_newsletter.php?content_array='.urlencode(json_encode($txt_array)).'">Lire en ligne</a>
		</td>
	</tr>
</table>
	
</body>
</html>
';
	return $return;
	}