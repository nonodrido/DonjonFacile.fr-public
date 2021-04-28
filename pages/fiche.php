<?php
if(isset($_GET['id']) AND filter_var($_GET['id'], FILTER_VALIDATE_INT))
	{
	$fiche= new fiche($_GET['id']);
	$fiche->get_fiche();
	$referer=parse_url($_SERVER['HTTP_REFERER']);
	$bdd=connect();
	$bdd->exec('INSERT INTO fiches_stat 
				VALUES("'.$referer['host'].'",NOW(),1) 
				ON DUPLICATE KEY UPDATE 
				date=NOW(),nbre=nbre+1
				');
	exit;
	}
else{header("HTTP/1.1 404 Not Found");include('pages/404.php');$error=1;}
exit;


///////////// ANCIEN SYSTEME SANS CLASSE /////////////


if(isset($_GET['id']) AND filter_var($_GET['id'], FILTER_VALIDATE_INT))
	{
	$rep = $bdd->prepare('SELECT perso.*, users.pseudo as user_name
					  FROM perso 
					  INNER JOIN users 
					  ON perso.user_id = users.ID 
					  WHERE perso.id= ?
					   AND perso.etat!="delete"');
	$rep->execute(array($_GET['id']));
	if($rep->rowcount() !=0)
		{
		$array = $rep->fetch();
			/*/////////////////////////////////////////////////////////////////////////////////////*/
			/*///////////////////////////////    FICHE PRINCIPALE     /////////////////////////////*/
			/*/////////////////////////////////////////////////////////////////////////////////////*/
			
			
		if(is_file('ressources/fiches/'.to_url('fiche-'.$array['id'].'-'.md5($array['name']).'_'.md5($array['date'])).'.jpg') AND !isset($_GET['reload']))
			{
			if(isset($_GET['action'])){header("Content-disposition: attachment;filename=fiche de ".$array['name'].".jpg");}
			header ("Content-type: image/jpeg");
			readfile('ressources/fiches/'.to_url('fiche-'.$array['id'].'-'.md5($array['name']).'_'.md5($array['date'])).'.jpg');
			}
		else{
			// header("Content-disposition: attachment;filename=fiche de ".$array['name'].".jpg"); 
			// header ("Content-type: image/jpeg");
			$image = imagecreatefromjpeg('.'.RESSOURCES."baseficheV2.jpg");
			
			$black = imagecolorallocate($image, 0, 0, 0);
			
			$font = '.'.RESSOURCES.'DAUPHINN.TTF';//'FREESCPT.TTF';
			$rupture=array('jamais','1','1 à 2','1 à 3','1 à 4','1 à 5');
			
			
			//img 462 x 472
			@$ext= getimagesize ($array['img']);@$ext=$ext[2];
			if($ext == IMAGETYPE_GIF){$source = @imagecreatefromgif($array['img']);}
			elseif($ext == IMAGETYPE_PNG){$source = @imagecreatefrompng($array['img']);}
			elseif($ext == IMAGETYPE_JPEG){$source = @imagecreatefromjpeg($array['img']);}
			if(isset($source))
			{
			@$destination = imagecreatetruecolor(465, 474); // On crée la miniature vide
			// Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
			@$largeur_source = imagesx($source);
			@$hauteur_source = imagesy($source);
			@$largeur_destination = imagesx($destination);
			@$hauteur_destination = imagesy($destination);
			// On crée la miniature
			@imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);
			@imagecopymerge($image, $destination, 31, 298, 0, 0, $largeur_destination, $hauteur_destination, 100);
			}
			//////////// CORPS DE LA PAGE ////////////////////
			
			// Nom 38 chars
			imagettftext($image, 45, 0, 730, 315, $black, $font, trunc($array['name'],38));
			// Sexe
			imagettftext($image, 45, 0, 1760, 315, $black, $font, trunc($array['sexe'],10));
			// Origine
			imagettftext($image, 45, 0, 765, 435, $black, $font, $array['origine']);
			// Métier
			imagettftext($image, 45, 0, 1400, 435, $black, $font, $array['metier']);
			// Level
			imagettftext($image, 120, 0, 328, 935, $black, $font, get_niv($array['xp']));
			// Expérience
			imagettftext($image, 60, 0, 90, 1218, $black, $font, $array['xp']);
			// imagettftext($image, 45, 0, 45, 1310, $black, $font, $array['experienceact']);
			// Point de destin
			imagettftext($image, 45, 0, 60, 1950, $black, $font, $array['PDest']);
			// Pièce d'or
			imagettftext($image, 45, 0, 200, 2190, $black, $font, $array['PO']);
			imagettftext($image, 45, 0, 200, 2190+68, $black, $font, $array['PA']);
			imagettftext($image, 45, 0, 200, 2190+2*68, $black, $font, $array['PC']);
			imagettftext($image, 45, 0, 25, 2500-60, $black, $font, trunc($array['LT'].' lingot(s) de Thritil',25));
			imagettftext($image, 45, 0, 25, 2500+4, $black, $font, trunc($array['LB'].' lingot(s) de Berylium ',25));
			
			// Energie vitale
			imagettftext($image, 45, 0, 1288, 566, $black, $font, $array['evmax']);
			// Energie Astrale
			imagettftext($image, 45, 0, 1288, 744, $black, $font, $array['eamax']);
			
			// Courage
			imagettftext($image, 45, 0, 1130, 990, $black, $font, $array['COU']);
			// imagettftext($image, 50, 0, 1440, 990, $black, $font, $array['courage2']);
			// Intelligence
			imagettftext($image, 45, 0, 1130, 1080, $black, $font, $array['INTL']);
			// imagettftext($image, 50, 0, 1440, 1080, $black, $font, $array['intelligence2']);
			// Charisme
			imagettftext($image, 45, 0, 1130, 1170, $black, $font, $array['CHA']);
			// imagettftext($image, 50, 0, 1440, 1170, $black, $font, $array['charisme2']);
			// Adresse
			imagettftext($image, 45, 0, 1130, 1260, $black, $font, $array['AD']);
			// imagettftext($image, 50, 0, 1440, 1260, $black, $font, $array['adresse2']);
			// Force
			imagettftext($image, 45, 0, 1130, 1350, $black, $font, $array['FO']);
			// imagettftext($image, 50, 0, 1440, 1350, $black, $font, $array['force2']);
			
			// Résistance Magie
			imagettftext($image, 45, 0, 1122, 836, $black, $font, round(($array['INTL']+$array['AD'])/2));
			// Résistance Magie
			imagettftext($image, 45, 0, 1600, 836, $black, $font, round(($array['INTL']+$array['CHA'])/2));
			// Résistance Magie
			imagettftext($image, 45, 0, 2222, 836, $black, $font, round(($array['INTL']+$array['COU']+$array['FO'])/3));
			
			// Attaque
			imagettftext($image, 45, 0, 1130, 1630, $black, $font, $array['AT']);
			// imagettftext($image, 45, 0, 1440, 1630, $black, $font, $array['attaque2']);
			// Parade
			imagettftext($image, 45, 0, 1130, 1720, $black, $font, $array['PRD']);
			// imagettftext($image, 45, 0, 1440, 1720, $black, $font, $array['parade2']);
			
			// Arme +60px par ligne pour un new item 40 chars
			$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND p.type="arme" AND p.etat="equip"
										ORDER BY t.name
										LIMIT 0,4');
			if($rep->rowCount() != 0){
			$i=0;
			while($result = $rep->fetch())
				{
				$item=new item($result['item_id']);
				imagettftext($image, 45, 0, 690, 2625+($i*60), $black, $font,trunc( $item->name,26));//nom de l'arme
				imagettftext($image, 45*2/3, 0, 1300, 2625+($i*60), $black, $font, trunc('  '.$item->effets,17));// effets
				imagettftext($image, 45, 0, 1620, 2625+($i*60), $black, $font, trunc($item->carac,6)); //PI
				imagettftext($image, 45, 0, 1835, 2625+($i*60), $black, $font, $rupture[$item->rupture]); //rupture
				$i++;
				}
			}
			//.' ('.str_replace('_',' ',$item->subtype).')'
			
			// Protection 1 38 chars
			$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND p.type="protec" AND p.etat="equip"
										ORDER BY t.name');
			$totalprotec=0;
			if($rep->rowCount() != 0){
			$i=0;
			while($result = $rep->fetch())
				{
				$item=new item($result['item_id']);
				$totalprotec+=$item->carac;
				if($i<6){
				imagettftext($image, 45, 0, 690, 2100+($i*60), $black, $font, trunc( $item->name,28));
				imagettftext($image, 45*2/3, 0, 1400, 2100+($i*60), $black, $font, trunc('  '.$item->effets,17));
				imagettftext($image, 45, 0, 1680, 2100+($i*60), $black, $font, trunc($item->carac,6));
				imagettftext($image, 45, 0, 1835, 2100+($i*60), $black, $font, $rupture[$item->rupture]);
				}
				$i++;
				}
			}
			// Protection Total
			imagettftext($image, 120, 0, 2140, 2085, $black, $font, $totalprotec);
			
			// Machins Précieux 22 chars
			/*imagettftext($image, 50, 0, 60, 2992, $black, $font, $array['precieu1']);
			imagettftext($image, 50, 0, 60, 3055, $black, $font, $array['precieu2']);
			imagettftext($image, 50, 0, 60, 3118, $black, $font, $array['precieu3']);
			imagettftext($image, 50, 0, 60, 3181, $black, $font, $array['precieu4']);*/
			
			// Equipement, babioles, fringues  72 chars
			$rep = $bdd->query('SELECT p.*, t.*,p.etat AS equip
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE (p.perso_id='.$_GET['id'].' AND p.type!="comp" AND p.type!="sorts" AND p.etat!="delete") AND  (p.etat!="equip" OR (p.etat="equip" AND p.qte!=1))
										ORDER BY t.name');
			if($rep->rowCount() != 0){
			$txt='';
			while($result = $rep->fetch())
				{
				if($result['qte']==1){$qte='';}
				elseif($result['qte']>1 AND $result['equip']=="equip"){if(($result['qte']-1)==1){$qte='';}else{$qte=' (x'.($result['qte']-1).')';}}
				else{$qte=' (x'.$result['qte'].')';}
				$val=new item($result['item_id']);
				$txt.=$val->name.$qte.', ';
				}
			$txt.='et deux trois autres babioles sans grand intérêt.';
			$i=0;
			$val='';
			while(101*$i < mb_strlen($txt) OR $i != 7)
				{
				$txt=str_replace($val,'',$txt);
				$val=tronquer_texte($txt,105,0,2);
				imagettftext($image, 45, 0, 25, 2992+($i*64), $black, $font, $val);
				$i++;
				}
			}
			imagettftext($image, 45/1.5, 0, 1450, 500, $black, $font, 'Etat au '.date('d/m/y'));
			@unlink("ressources/fiches/".to_url('fiche-'.$array['id'].'-'.md5($array['name']).'_'.$array['old_fiche']).'.jpg');
			imagejpeg($image,'ressources/fiches/'.to_url('fiche-'.$array['id'].'-'.md5($array['name']).'_'.md5($array['date'])).'.jpg');
			$bdd->exec('UPDATE perso SET date="'.$array['date'].'", old_fiche="'.md5($array['date']).'" WHERE id='.$array['id']);
			if(isset($_GET['action'])){header("Content-disposition: attachment;filename=fiche de ".$array['name'].".jpg");}
			header ("Content-type: image/jpeg");
			readfile('ressources/fiches/'.to_url('fiche-'.$array['id'].'-'.md5($array['name']).'_'.md5($array['date'])).'.jpg');
			imagedestroy($image);
			
			}
		}
	else{echo 'personnage inexistant !';}
	}
else{/* header('Location:/'); */echo 'error';}
?>
