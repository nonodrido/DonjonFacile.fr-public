<?php
if(isset($_GET['id']) AND filter_var($_GET['id'], FILTER_VALIDATE_INT))
	{
	$fiche= new fiche($_GET['id']);
	$fiche->get_fiche(2);
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
		/*///////////////////////////////    FICHE SECONDAIRE     /////////////////////////////*/
		/*/////////////////////////////////////////////////////////////////////////////////////*/
			
			
		if(is_file('ressources/fiches/'.to_url('fiche-advanced-'.$array['id'].'-'.$array['name'].'_'.md5($array['date'])).'.jpg') AND !isset($_GET['reload']))
			{
			if(isset($_GET['action'])){header("Content-disposition: attachment;filename=fiche secondaire de ".$array['name'].".jpg");}
			// header ("Content-type: image/jpeg");
			readfile('ressources/fiches/'.to_url('fiche-advanced-'.$array['id'].'-'.$array['name'].'_'.md5($array['date'])).'.jpg');
			}
		else{ ///// GENERATION DE LA FICHE /////
			
			if(in_array($array['metier'],array('Ninja','Assassin','Voleur')))/////// ARCHER ///////
				{
				$image = imagecreatefromjpeg('.'.RESSOURCES.'naheulbeuk-feuille-equipement-archer.jpg');
				$size=45;
				$angle=0;
				$color = imagecolorallocate($image, 0, 0, 0);
				$font = '.'.RESSOURCES.'DAUPHINN.TTF';

				// nom perso
				imagettftext($image, $size, $angle, 150, 315, $color, $font, trunc($array['name'],35));

				//flèches
				$i=0;
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND t.type="arme" AND 
										(
										((t.subtype="Flèches pour arc (jet)" OR t.subtype="flèches" OR t.subtype="Carreaux arbalète (jet)") AND t.emplacement!="page principale") 
										OR
										t.emplacement="flèches"
										)
										AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				while($i<5 AND $result = $rep->fetch())
					{
					imagettftext($image, $size, $angle, 30, 535+$i*60, $color, $font, trunc($result['qte'],7));// nbre
					imagettftext($image, $size, $angle, 340, 535+$i*60, $color, $font, trunc($result['name'],35));// name
					imagettftext($image, $size, $angle, 1370, 535+$i*60, $color, $font, trunc($result['effets'],20));// bonus
					$i++;
					}	
				}
				// nourriture
				$i=0;
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND 
										(
										(t.type="divers" AND t.emplacement="bouffe et boisson")
										OR
										(t.type="bouffe et boisson" AND t.emplacement!="page principale")
										)
										AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				while($i<9 AND $result = $rep->fetch())
					{
					$val=trunc($result['name'],22).' (x'.$result['qte'].')';
					imagettftext($image, $size, $angle, 30, 1260+$i*65, $color, $font, $val);// ligne i+1
					$i++;
					}
				}
				
				//potions
				$i=0;
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND 
										(
										(t.type="divers" AND t.emplacement IN ("potions")) 
										OR
										(t.type IN("potions","Antidotes","potions","remèdes et produits de medecine","Potions – Augmentation des carac.") AND t.emplacement!="page principale")
										)
										AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				while($i<5 AND $result = $rep->fetch())
					{
					imagettftext($image, $size, $angle, 800, 1060+$i*60, $color, $font, trunc($result['qte'],7));// nbre
					imagettftext($image, $size, $angle, 1090, 1060+$i*60, $color, $font, trunc($result['name'],23));// name
					imagettftext($image, $size, $angle, 1830, 1060+$i*60, $color, $font, trunc($result['effets'],20));// effets
					$i++;
					}	
				}
				
				//poison
				$i=0;
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND 
										(
										(t.type="divers" AND t.emplacement IN ("poisons")) 
										OR
										(t.type IN("poisons") AND t.emplacement!="page principale")
										)
										AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				while($i<5 AND $result = $rep->fetch())
					{
					imagettftext($image, $size, $angle, 800, 1530+$i*60, $color, $font, trunc($result['qte'],7));// nbre
					imagettftext($image, $size, $angle, 1090, 1530+$i*60, $color, $font, trunc($result['name'],23));// name
					imagettftext($image, $size, $angle, 1830, 1530+$i*60, $color, $font, trunc($result['effets'],20));// effets
					$i++;
					}
				}
				
				//objets speciaux
				$i=0;
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND 
										(
										(t.type="divers" AND t.emplacement="objets spéciaux")
										OR
										(t.type IN("objets spéciaux","Objets exclusifs","Accessoires – Augmentation des carac.","Matériel à usage magique",
										"Relique - Dlul","Relique - Adathie",
										"Relique - Kornettoh","Relique - Slanoush","Relique - Youclidh","bouffe et boisson")
										AND t.emplacement!="page principale")
										)
										AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				while($i<10 AND $result = $rep->fetch())
					{
					// ajout nbre entre parenthèse
					imagettftext($image, $size, $angle, 160, 2185+$i*62, $color, $font, trunc($result['name'],40));// name
					imagettftext($image, $size, $angle, 1460, 2185+$i*62, $color, $font, trunc($result['effets'],33));// effets
					$i++;
					}
				}
				
				// butin
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND t.type="divers" AND t.emplacement="butin" AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				$txt='';
				while($result = $rep->fetch())
					{
					if($result['qte']==1){$qte='';}
					else{$qte=' (x'.$result['qte'].')';}
					$val=new item($result['item_id']);
					$txt.=$val->name.$qte.', ';
					}
				$txt.='';//MESSAGE DE FIN
				$i=0;
				$val='';
				while(101*$i < mb_strlen($txt) OR $i != 7)
					{
					$txt=str_replace($val,'',$txt);
					$val=tronquer_texte($txt,105,0,2);
					imagettftext($image, $size, $angle, 30, 2990+$i*63, $color, $font, trunc($val,89));// ligne i+1
					$i++;
					}
				}
				}
			elseif(in_array($array['metier'],array('Prêtre','Mage', 'Sorcier')))/////// MAGE/PRETRE ///////
				{
				$image = imagecreatefromjpeg('.'.RESSOURCES.'naheulbeuk-feuille-equipement-mage.jpg');
				$size=45;
				$angle=0;
				$color = imagecolorallocate($image, 0, 0, 0);
				$font = '.'.RESSOURCES.'DAUPHINN.TTF';

				// nom perso
				imagettftext($image, $size, $angle, 150, 315, $color, $font, trunc($array['name'],35));

				//ingredients magiques
				$i=0;
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND 
										(
										(t.type="divers" AND t.emplacement="ingrédients magiques")
										OR
										(t.type IN("Matériel à usage magique","ingrédients magiques") AND t.emplacement!="page principale")
										)
										AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				while($i<10 AND $result = $rep->fetch())
					{
					imagettftext($image, $size, $angle, 30, 535+$i*61, $color, $font, trunc($result['qte'],7));// nbre
					imagettftext($image, $size, $angle, 340, 535+$i*61, $color, $font, trunc($result['name'],35));// name
					imagettftext($image, $size, $angle, 1370, 535+$i*61, $color, $font, trunc($result['effets'],9));// valeur
					$i++;
					}
				}

				// bouquins
				$i=0;
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND 
										(
										(t.type="divers" AND t.emplacement="bouquins")
										OR
										(t.type IN("bouquins","Livres pour mages","Livres pour prêtres/paladins","Livres généraux",
										"Livres généraux – compétences") AND t.emplacement!="page principale")
										)
										AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				while($i<10 AND $result = $rep->fetch())
					{
					imagettftext($image, $size, $angle, 1730, 535+$i*61, $color, $font, trunc($result['name'],23));// livre i
					$i++;
					}
				}
				
				$ecart=300;

				// nourriture
				$i=0;
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND 
										(
										(t.type="divers" AND t.emplacement="bouffe et boisson")
										OR
										(t.type="bouffe et boisson" AND t.emplacement!="page principale")
										)
										AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				while($i<9 AND $result = $rep->fetch())
					{
					$val=trunc($result['name'],22).' (x'.$result['qte'].')';
					imagettftext($image, $size, $angle, 30, 1260+$i*65+$ecart, $color, $font, $val);// bouffe i
					$i++;
					}
				}
				
				// /!\ potions / poison /!\
				$i=0;
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND 
										(
										(t.type="divers" AND t.emplacement IN ("poisons","potions","remèdes et produits de medecine","Potions – Augmentation des carac.","Antidotes")) 
										OR
										(t.type IN("poisons","potions") AND t.emplacement!="page principale")
										)
										AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				while($i<5 AND $result = $rep->fetch())
					{
					imagettftext($image, $size, $angle, 800, 1060+$i*61+$ecart, $color, $font, trunc($result['qte'],7));// nbre
					imagettftext($image, $size, $angle, 1090, 1060+$i*61+$ecart, $color, $font, trunc($result['name'],23));// name
					imagettftext($image, $size, $angle, 1830, 1060+$i*61+$ecart, $color, $font, trunc($result['effets'],20));// effets
					$i++;
					}
				}

				// bagues
				$i=0;
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND 
										(
										(t.type="divers" AND t.emplacement="bagues") 
										OR
										(t.type IN("Bagues de puissance (prêtre)","bagues",
										"Médaillons d\'économie (prêtre)",
										"Bagues de Sûreté (prêtre)",
										"Bagues de puissance (sorcier)",
										"Bagues d\'économie (sorcier)",
										"Bagues de Sûreté (sorcier)") AND t.emplacement!="page principale")
										)
										AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				while($i<5 AND $result = $rep->fetch())
					{
					imagettftext($image, $size, $angle, 800, 1530+$i*61+$ecart, $color, $font, trunc($result['qte'],7));// nbre
					imagettftext($image, $size, $angle, 1130, 1530+$i*61+$ecart, $color, $font, trunc($result['name'],27));// name
					imagettftext($image, $size, $angle, 1980, 1530+$i*61+$ecart, $color, $font, trunc($result['effets'],14));// effets
					$i++;
					}
				}

				$ecart=270;
					
				//objets speciaux
				$i=0;
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND 
										(
										(t.type="divers" AND t.emplacement="objets spéciaux")
										OR
										(t.type IN("objets spéciaux","Objets exclusifs","Accessoires – Augmentation des carac.","Matériel à usage magique",
										"Relique - Dlul","Relique - Adathie",
										"Relique - Kornettoh","Relique - Slanoush","Relique - Youclidh","bouffe et boisson")
										AND t.emplacement!="page principale")
										)
										AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				while($i<7 AND $result = $rep->fetch())
					{
					// ajout nbre entre parenthèse ?
					imagettftext($image, $size, $angle, 160, 2185+$i*62+$ecart, $color, $font, trunc($result['name'],40));// name
					imagettftext($image, $size, $angle, 1460, 2185+$i*62+$ecart, $color, $font, trunc($result['effets'],33));// effets
					$i++;
					}
				}

				// butin
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND t.type="divers" AND t.emplacement="butin" AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				$txt='';
				while($result = $rep->fetch())
					{
					if($result['qte']==1){$qte='';}
					else{$qte=' (x'.$result['qte'].')';}
					$val=new item($result['item_id']);
					$txt.=$val->name.$qte.', ';
					}
				$txt.='';//MESSAGE DE FIN
				$i=0;
				$val='';
				while(101*$i < mb_strlen($txt) OR $i != 7)
					{
					$txt=str_replace($val,'',$txt);
					$val=tronquer_texte($txt,105,0,2);
					imagettftext($image, $size, $angle, 30, 2990+63+$i*63, $color, $font, trunc($val,89));// ligne i+1
					$i++;
					}
				}
				}
			else{/////// GUERRIER/DEFAULT ///////
				$image = imagecreatefromjpeg('.'.RESSOURCES.'naheulbeuk-feuille-equipement-guerrier.jpg');
				$size=45;
				$angle=0;
				$color = imagecolorallocate($image, 0, 0, 0);
				$font = '.'.RESSOURCES.'DAUPHINN.TTF';

				// nom perso
				imagettftext($image, $size, $angle, 150, 315, $color, $font, trunc($array['name'],35));

				$ecart=450;

				// nourriture
				$i=0;
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND 
										(
										(t.type="divers" AND t.emplacement="bouffe et boisson")
										OR
										(t.type="bouffe et boisson" AND t.emplacement!="page principale")
										)
										AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				while($i<9 AND $result = $rep->fetch())
					{
					$val=trunc($result['name'],22).' (x'.$result['qte'].')';
					imagettftext($image, $size, $angle, 30, 1260+$i*65-$ecart, $color, $font, $val);// ligne i+1
					$i++;
					}
				}
				//potions
				$i=0;
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND 
										(
										(t.type="divers" AND t.emplacement IN ("potions")) 
										OR
										(t.type IN("potions","Antidotes","potions","remèdes et produits de medecine","Potions – Augmentation des carac.") AND t.emplacement!="page principale")
										)
										AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				while($i<5 AND $result = $rep->fetch())
					{
					imagettftext($image, $size, $angle, 800, 1060+$i*60-$ecart, $color, $font, trunc($result['qte'],7));// nbre
					imagettftext($image, $size, $angle, 1090, 1060+$i*60-$ecart, $color, $font, trunc($result['name'],23));// name
					imagettftext($image, $size, $angle, 1830, 1060+$i*60-$ecart, $color, $font, trunc($result['effets'],20));// effets
					$i++;
					}	
				}
				
				//poison
				$i=0;
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND 
										(
										(t.type="divers" AND t.emplacement IN ("poisons")) 
										OR
										(t.type IN("poisons") AND t.emplacement!="page principale")
										)
										AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				while($i<5 AND $result = $rep->fetch())
					{
					imagettftext($image, $size, $angle, 800, 1530+$i*60-$ecart, $color, $font, trunc($result['qte'],7));// nbre
					imagettftext($image, $size, $angle, 1090, 1530+$i*60-$ecart, $color, $font, trunc($result['name'],23));// name
					imagettftext($image, $size, $angle, 1830, 1530+$i*60-$ecart, $color, $font, trunc($result['effets'],20));// effets
					$i++;
					}
				}

				//objets speciaux
				$i=0;
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND 
										(
										(t.type="divers" AND t.emplacement="objets spéciaux")
										OR
										(t.type IN("objets spéciaux","Objets exclusifs","Accessoires – Augmentation des carac.","Matériel à usage magique",
										"Relique - Dlul","Relique - Adathie",
										"Relique - Kornettoh","Relique - Slanoush","Relique - Youclidh","bouffe et boisson")
										AND t.emplacement!="page principale")
										)
										AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				while($i<10 AND $result = $rep->fetch())
					{
					// ajout nbre entre parenthèse ?
					imagettftext($image, $size, $angle, 160, 2185+$i*62-$ecart, $color, $font, trunc($result['name'],40));// name
					imagettftext($image, $size, $angle, 1460, 2185+$i*62-$ecart, $color, $font, trunc($result['effets'],33));// effets
					$i++;
					}
				}

				// butin
				$i=0;
				$rep = $bdd->query('SELECT p.*, t.*
										FROM perso_items AS p
										INNER JOIN item AS t
										ON t.id = p.item_id
										WHERE p.perso_id='.$_GET['id'].' AND t.type="divers" AND t.emplacement="butin" AND p.etat!="delete" AND p.qte>0
										ORDER BY t.name');
				if($rep->rowCount() != 0){
				$txt='';
				while($result = $rep->fetch())
					{
					if($result['qte']==1){$qte='';}
					else{$qte=' (x'.$result['qte'].')';}
					$val=new item($result['item_id']);
					$txt.=$val->name.$qte.', ';
					}
				$txt.='';//MESSAGE DE FIN
				$i=0;
				$val='';
				while(101*$i < mb_strlen($txt) OR $i != 15)
					{
					$txt=str_replace($val,'',$txt);
					$val=tronquer_texte($txt,105,0,2);
					imagettftext($image, $size, $angle, 30, 2990+$i*63-$ecart, $color, $font, trunc($val,89));// ligne i+1
					$i++;
					}
				}
				}
			// echo $array['metier'];
			imagettftext($image, $size/1.5, $angle, 1350, 315, $color, $font, 'Etat au '.date('d/m/y'));
			@unlink("ressources/fiches/".to_url('fiche-advanced-'.$array['id'].'-'.md5($array['name']).'_'.$array['old_fiche_advanced']).'.jpg');
			imagejpeg($image,'ressources/fiches/'.to_url('fiche-advanced-'.$array['id'].'-'.md5($array['name']).'_'.md5($array['date'])).'.jpg');
			$bdd->exec('UPDATE perso SET date="'.$array['date'].'", old_fiche_advanced="'.md5($array['date']).'" WHERE id='.$array['id']);
			if(isset($_GET['action'])){header("Content-disposition: attachment;filename=fiche secondaire de ".$array['name'].".jpg");}
			header ("Content-type: image/jpeg");
			readfile('ressources/fiches/'.to_url('fiche-advanced-'.$array['id'].'-'.md5($array['name']).'_'.md5($array['date'])).'.jpg');
			imagedestroy($image);
			
			}
		}
	else{echo 'personnage inexistant !';}
	}
else{/* header('Location:/'); */echo 'error';}
?>
