<?php
define('CSTE', 3);

class fiche
    {
		private $perso_id=0,
		$create_date,
		$date,
		$user_id,
		$etat,
		$name,
		$xp,
		$pnj=0,
		$origine,
		$metier,
		$specialisation,
		$sexe,
		$evmax,
		$eamax,
		$PDest,
		$COU,
		$INTL,
		$CHA,
		$AD,
		$FO,
		$AT,
		$PRD,
		$PO,
		$PA,
		$PC,
		$LT,
		$LB,
		$descr,
		$img,
		$adv_opt,
		$old_fiche,
		$old_fiche_advanced,
		$user_name,
		$items=array();
	
	// SETTERS
		public function setPerso_id($val){$this->perso_id = (int) $val;}
		public function setCreate_date($val){$this->create_date = $val;}
		public function setName($val){$this->name = str_replace('\\','',$val);}
		public function setDate($val){$this->date = $val;}
		public function setUser_id($val){$this->user_id = (int) $val;}
		public function setEtat($val){$this->etat = $val;}
		public function setXp($val){$this->xp = (int) $val;}
		public function setOrigine($val){$this->origine = $val;}
		public function setMetier($val){$this->metier = $val;}
		public function setSpecialisation($val){$this->specialisation = $val;}
		public function setSexe($val){$this->sexe = $val;}
		public function setEvmax($val){$this->evmax = (int) $val;}
		public function setEamax($val){$this->eamax = (int) $val;}
		public function setPDest($val){$this->PDest = (int) $val;}
		public function setCOU($val){$this->COU = (int) $val;}
		public function setINTL($val){$this->INTL = (int) $val;}
		public function setCHA($val){$this->CHA = (int) $val;}
		public function setAD($val){$this->AD = (int) $val;}
		public function setFO($val){$this->FO = (int) $val;}
		public function setAT($val){$this->AT = (int) $val;}
		public function setPRD($val){$this->PRD = (int) $val;}
		public function setPO($val){$this->PO = (int) $val;}
		public function setPA($val){$this->PA = (int) $val;}
		public function setPC($val){$this->PC = (int) $val;}
		public function setLT($val){$this->LT = (int) $val;}
		public function setLB($val){$this->LB = (int) $val;}
		public function setPnj($val){$this->pnj = (int) $val;}
		public function setDescr($val){$this->descr = str_replace('\\','',$val);}
		public function setImg($val){$this->img = $val;}
		public function setAdv_opt($val){$this->adv_opt = $val;}
		public function setOld_fiche($val){$this->old_fiche = $val;}
		public function setOld_fiche_advanced($val){$this->old_fiche_advanced = $val;}
		public function setUser_name($val){$this->user_name = $val;}
		public function setItems($val){$this->items = (array) $val;}
	
	//FUNCTION BASE
		public function __construct($id)
        {	
		$this->setPerso_id($id);
		if(!$this->get_value())
			{
			echo 'Ce personnage n\'existe pas !';
			exit;
			}
		$this->get_items();
		$this->clean_var();
        }
		private function hydrate(array $val)
        {
        foreach ($val as $key => $value)
			{
				$method = 'set'.ucfirst($key);
				if (method_exists($this, $method))
				{
					$this->$method($value);
				}
			}
        }
		public function __get ($nom)
			{
			if (isset ($this->$nom)){return $this->$nom;}
			return false;
			}
		public function __call ($nom, $arguments)
        {
			echo 'La méthode <strong>', $nom, '</strong> a été appelée alors qu\'elle n\'existe pas ! Ses arguments étaient les suivants : <strong>', implode ($arguments, '</strong>, <strong>'), '</strong>';
        }
		public function __set ($nom, $val)
			{
			$method='set'.ucfirst($nom);
			if (method_exists($this, $method))
				{
					$this->$method($val);
				}
			}
	// GET METHOD
		private function get_value()
			{
			$bdd=connect();
			$rep = $bdd->prepare('	SELECT perso.*, users.pseudo as user_name
									FROM perso 
									INNER JOIN users 
									ON perso.user_id = users.ID 
									WHERE perso.id= ?
									AND perso.etat!="delete"');								
			$rep->execute(array($this->perso_id));
			
			if($rep->rowcount() !=0){$this->hydrate($rep->fetch());return true;}
			else{return false;}
			}
		private function get_items()
			{
			$bdd=connect();
			$rep = $bdd->prepare('	SELECT p.*, t.*
									FROM perso_items AS p
									INNER JOIN item AS t
									ON t.id = p.item_id
									WHERE p.perso_id=? AND p.etat!="delete" AND t.etat!="delete" 
									AND (p.qte>0 OR p.waste>0 OR (p.equip>0 AND (p.type="arme" OR p.type="protec")))
									ORDER BY t.name
									');
			$rep->execute(array($this->perso_id));
			$this->items=$rep->fetchAll();
			if($rep->rowcount() !=0){return true;}
			else{return false;}
			}
		private function clean_var()
			{
			$list=get_object_vars($this);
			foreach($list as $cle=>$val)
				{
				$this->$cle=clean_text($val);
				}
			}
		// GENERAL METHOD
		public function get_fiche($type=1)// 1:default 2:advanced
			{
			if(strtotime($this->date)<filemtime(__FILE__)){$reload=true;}
			if($type==2){$link='ressources/fiches/'.to_url('fiche-advanced-'.$this->perso_id.'-'.$this->name.'_'.$this->date).'.jpg';}
			else{$link='ressources/fiches/'.to_url('fiche-'.$this->perso_id.'-'.$this->name.'_'.$this->date).'.jpg';}
			if(!is_file($link) OR isset($_GET['reload']) OR isset($reload))
				{
				$this->delete_old();
				if($type==2){$this->create_fiche_advanced();}
				else{$this->create_fiche_main();}
				/* $this->create_fiche_main();
				$this->create_fiche_advanced(); */
				}
			header('Status: 303 See Other', true, 303);  
			header('Location: '.$link);
			// exit; //=> commenté pour permettrel'enregistrement de l'utilisation des fiches par des fiches externes
			}
		public function delete_old()
			{
			if($this->old_fiche!=$this->date){@unlink("ressources/fiches/".to_url('fiche-'.$this->perso_id.'-'.$this->name.'_'.$this->old_fiche).'.jpg');}
			if($this->old_fiche_advanced!=$this->date){@unlink("ressources/fiches/".to_url('fiche-advanced-'.$this->perso_id.'-'.$this->name.'_'.$this->old_fiche_advanced).'.jpg');}
			}
		
		
	////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////    FICHE PRINCIPALE    //////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////
		public function create_fiche_main()
			{
			$debut = microtime(true);
			$image = imagecreatefromjpeg('.'.RESSOURCES."baseficheV9.jpg");
			
			$black = imagecolorallocate($image, 0, 0, 0);
			
			$font = '.'.RESSOURCES.'DAUPHINN.TTF';//'FREESCPT.TTF';
			$rupture=array('jamais','1','1 à 2','1 à 3','1 à 4','1 à 5');
			
			
			//img 462 x 472
			@$ext= getimagesize ($this->img);@$ext=$ext[2];
			if($ext == IMAGETYPE_GIF){$source = @imagecreatefromgif($this->img);}
			elseif($ext == IMAGETYPE_PNG){$source = @imagecreatefrompng($this->img);}
			elseif($ext == IMAGETYPE_JPEG){$source = @imagecreatefromjpeg($this->img);}
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
			
			if($this->pnj>0)
				{
				$points=array(	197,57,
								199,55,
								227,81,
								258,55,
								260,57,
								231,84,
								260,112,
								258,110,
								230,87,
								199,110,
								197,112,
								225,85
								);
				imagefilledpolygon ($image, $points, 12, $black);
				}
			
			// Nom 38 chars
			imagettftext($image, 45, 0, 730, 315, $black, $font, trunc($this->name,38));
			// Sexe
			imagettftext($image, 45, 0, 1760, 315, $black, $font, trunc($this->sexe,10));
			// Origine
			imagettftext($image, 45, 0, 765, 435, $black, $font, $this->origine);
			// Métier
			if(in_array($this->metier,array('Prêtre','Paladin','Mage','Sorcier')) AND $this->specialisation!="Aucune spécialisation")
				{
				imagettftext($image, 45, 0, 1400, 435, $black, $font, $this->metier.' ('.$this->specialisation.')');
				}
			else{
				imagettftext($image, 45, 0, 1400, 435, $black, $font, $this->metier);
				}
			// Level
			imagettftext($image, 120, 0, 328, 935, $black, $font, get_niv($this->xp));
			// Expérience
			imagettftext($image, 60, 0, 90, 1218-3, $black, $font, $this->xp);
			
			$col_h=-580;
			
			// Point de destin
			imagettftext($image, 45, 0, 60, 1950+$col_h, $black, $font, $this->PDest);
			// Pièce d'or
			imagettftext($image, 45, 0, 200, 2190+$col_h, $black, $font, $this->PO);
			imagettftext($image, 45, 0, 200, 2190+65+$col_h, $black, $font, $this->PA);
			imagettftext($image, 45, 0, 200, 2190+2*67+$col_h, $black, $font, $this->PC);
			imagettftext($image, 45, 0, 25, 2500-60+$col_h, $black, $font, trunc($this->LT.' lingot(s) de Thritil',25));
			imagettftext($image, 45, 0, 25, 2500+4+$col_h, $black, $font, trunc($this->LB.' lingot(s) de Berylium ',25));
			
			$main=-142;
			// Energie vitale
			imagettftext($image, 45, 0, 1145, 570, $black, $font, $this->evmax);
			// Energie Astrale
			imagettftext($image, 45, 0, 2050, 570, $black, $font, $this->eamax);
			
			// Courage
			imagettftext($image, 45, 0, 1130, 990+$main, $black, $font, $this->COU);
			
			// Intelligence
			imagettftext($image, 45, 0, 1130, 1080+$main, $black, $font, $this->INTL);
			
			// Charisme
			imagettftext($image, 45, 0, 1130, 1170+$main, $black, $font, $this->CHA);
			
			// Adresse
			imagettftext($image, 45, 0, 1130, 1260+$main, $black, $font, $this->AD);
			
			// Force
			imagettftext($image, 45, 0, 1130, 1350+$main, $black, $font, $this->FO);
			
			if($this->eamax>0)
			{
			// Magie Phys
			imagettftext($image, 45, 0, 1122, 836+$main-3, $black, $font, round(($this->INTL+$this->AD)/2));
			// Magie Psy
			imagettftext($image, 45, 0, 1600, 836+$main-3, $black, $font, round(($this->INTL+$this->CHA)/2));
			}
			
			// Résistance Magie
			imagettftext($image, 45, 0, 2222, 836+$main-3, $black, $font, round(($this->INTL+$this->COU+$this->FO)/3));
			
			// Attaque
			imagettftext($image, 45, 0, 1130, 1630+$main, $black, $font, $this->AT);
			// imagettftext($image, 45, 0, 1440, 1630, $black, $font, $this->attaque2);
			// Parade
			imagettftext($image, 45, 0, 1130, 1720+$main, $black, $font, $this->PRD);
			// imagettftext($image, 45, 0, 1440, 1720, $black, $font, $this->parade2);
			
			// Compétences
			$i=0;
			foreach($this->items as $item)
				{
				if($item['type']=="comp" AND $i<12)
					{
					imagettftext($image, 45, 0, 25, 2626+$i*63+$col_h, $black, $font, trunc($item['name'],23));// nom de la comp
					$i++;
					}
				}
			
			// imagettftext($image, 45, 0, 25, 2500+4, $black, $font, trunc($this->LB.' lingot(s) de Berylium ',25));
			
			// Arme +60px par ligne pour un new item 40 chars
			$i=0;
			$base=985;
			$major=91;
			$modif_ref=array('COU'=>$base,'INTL'=>$base+$major,'CHA'=>$base+2*$major,'AD'=>$base+3*$major,'FO'=>$base+4*$major,
								'AT'=>$base+640,'PRD'=>$base+640+$major,'AT/PRD'=>$base+640+$major+45);
			$masque='#(AD|CHA|INT|COU|FO|PRD|AT|AT/PRD)[ ]?([+-][0-9]+\*{0,4})#';
			foreach($this->items as $item)
				{
				if($item['type']=="arme" AND $item['equip']>0 AND !($item['subtype']=="Flèches pour arc (jet)" OR $item['subtype']=="flèches" 
					OR $item['subtype']=="Carreaux arbalète (jet)") AND $i<5)
					{
					$l=29;
					$effet=trim(str_replace(',','',preg_replace($masque,'',$item['effets'])));
					if(empty($effet)){$l=43;}
					imagettftext($image, 45, 0, 690, 2625+($i*60)+$main, $black, $font,trunc( $item['name'],$l));//nom de l'arme
					imagettftext($image, 45*2/3, 0, 1300-25, 2625+($i*60)+$main, $black, $font, trunc(' '.str_replace(',','',preg_replace($masque,'',$item['effets'])),20));// effets
					imagettftext($image, 45, 0, 1620, 2625+($i*60)+$main, $black, $font, trunc($item['carac'],6)); //PI
					imagettftext($image, 45, 0, 1835, 2625+($i*60)+$main, $black, $font, $rupture[$item['rupture']]); //rupture
					
					//////  GESTION DES MODIF CARACS  /////
					$array_modif=scan_stat($item['effets']);//'AD+1|CHA+2|INT+3|COU+4|FO+5|PRD+6|AT+7|AT/PRD+2'
					imagettftext($image, 45*2/5, 0, 1425+($i*157), $base-$major/2-5+$main, $black, $font,trunc( $item['name'],15));//nom réduit
					imagettftext($image, 45*2/5, 0, 1425+($i*157), $base+640-$major/2-5+$main, $black, $font,trunc( $item['name'],15));//nom réduit
					foreach($array_modif as $cle=>$val)
						{
						if($val!=0 AND $cle!='AT/PRD'){imagettftext($image, 45, 0, 1450+($i*157), $modif_ref[$cle]+$main-3, $black, $font, $val);}
						elseif($cle=='AT/PRD'){imagettftext($image, 45/2, 0, 1450+($i*157)-35, $modif_ref[$cle]+$main-3, $black, $font, $cle.' : '.$val);}
						}
					
					
					$i++;
					}
				}
			
			// Protection 38 chars
			$i=0;$totalprotec=0;$modif_total=array();
			foreach($this->items as $item)
				{
				if($item['type']=="protec" AND $item['equip']>0)
					{
					if($i<6)
						{
						$l=30;
						$effet=trim(str_replace(',','',preg_replace($masque,'',$item['effets'])));
						if(empty($effet)){$l=44;}
						imagettftext($image, 45, 0, 690, 2100+($i*60)+$main, $black, $font, trunc( $item['name'],$l));
						imagettftext($image, 45*2/3, 0, 1400-25, 2100+($i*60)+$main, $black, $font, trunc(' '.str_replace(',','',preg_replace($masque,'',$item['effets'])),20));
						imagettftext($image, 45, 0, 1680, 2100+($i*60)+$main, $black, $font, trunc($item['carac'],6));
						imagettftext($image, 45, 0, 1835, 2100+($i*60)+$main, $black, $font, $rupture[$item['rupture']]);
						}
					$array_modif=scan_stat($item['effets']);//'AD+1|CHA+2|INT+3|COU+4|FO+5|PRD+6|AT+7|AT/PRD+2'
					foreach($array_modif as $cle=>$val)
						{
						if(array_key_exists($cle,$modif_total)){$modif_total[$cle]=$modif_total[$cle]+$val;}
						else{$modif_total[$cle]=$val;}
						}
					$totalprotec+=$item['carac'];
					$i++;
					}
				}
			imagettftext($image, 45*2/5, 0, 1425+(5*157), $base-$major/2-5+$main, $black, $font,'Protections');
			imagettftext($image, 45*2/5, 0, 1425+(5*157), $base+640-$major/2-5+$main, $black, $font,'Protections');
			foreach($modif_total as $cle=>$val)
				{
				if(is_int($val) AND $val>0){$val='+'.$val;}
				if($val!=0 AND $cle!='AT/PRD'){imagettftext($image, 45, 0, 1450+(5*157), $modif_ref[$cle]+$main-3, $black, $font, $val);}
				elseif($cle=='AT/PRD'){imagettftext($image, 45/2, 0, 1450+(5*157)-35, $modif_ref[$cle]+$main-3, $black, $font, $cle.' : '.$val);}
				}
			// Protection Total
			$bdd=connect();
			$comp_protec_add=$bdd->query('SELECT p.*
							FROM perso_items AS p
							WHERE p.perso_id='.$this->perso_id.' AND p.item_id=117 AND p.etat!="delete" AND p.qte>0
							LIMIT 1');// comp "truc de mauviette"
			if($comp_protec_add->rowCount() != 0){
				$totalprotec++;
				}
			imagettftext($image, 120, 0, 2130, 2085+$main, $black, $font, $totalprotec);
			
			// Equipement, babioles, fringues  72 chars
			
			//génération du texte complet
			$txt='';
			foreach($this->items as $item)
				{
				if(($item['type']!="comp" AND $item['type']!="sorts" AND $item['etat']!="delete") AND  
				(($item['equip']!="equip" OR ($item['equip']=="equip" AND $item['qte']>1)) OR ($item['type']!="arme" AND $item['type']!="protec"))
				AND (in_array($item['emplacement'],array('page principale','default'))
				AND !($item['subtype']=="Flèches pour arc (jet)" OR $item['subtype']=="flèches" OR $item['subtype']=="Carreaux arbalète (jet)")
				AND $item['subtype']!="bouffe et boisson" 
				AND !in_array($item['subtype'],array("potions","Antidotes","potions","remèdes et produits de medecine","Potions – Augmentation des carac."))
				AND !in_array($item['subtype'],array("poisons"))
				AND !in_array($item['subtype'],array("objets spéciaux","Objets exclusifs","Accessoires – Augmentation des carac.","Matériel à usage magique",
									"Relique - Dlul","Relique - Adathie",
									"Relique - Kornettoh","Relique - Slanoush","Relique - Youclidh","bouffe et boisson"))
				AND !in_array($item['subtype'],array("bouquins","Livres pour mages","Livres pour prêtres/paladins","Livres généraux",
										"Livres généraux – compétences"))
				AND !in_array($item['subtype'],array("Bagues de puissance (prêtre)","bagues",
									"Médaillons d\'économie (prêtre)",
									"Bagues de Sûreté (prêtre)",
									"Bagues de puissance (sorcier)",
									"Bagues d\'économie (sorcier)",
									"Bagues de Sûreté (sorcier)"))
				AND !in_array($item['subtype'],array("objets spéciaux","Objets exclusifs","Accessoires – Augmentation des carac.","Matériel à usage magique",
									"Relique - Dlul","Relique - Adathie",
									"Relique - Kornettoh","Relique - Slanoush","Relique - Youclidh","bouffe et boisson"))
				
				
				))
					{
					if($item['qte']>1){$qte=' (x'.($item['qte']).')';}
					else{$qte='';}
					$val=new item($item['item_id']);
					$txt.=$item['name'].$qte.', ';
					}
				}
			if($txt!=''){$txt.='et deux trois autres babioles sans grand intérêt.';}
			
			//affichage texte généré
			$i=0;
			$val='';$txt_len=mb_strlen($txt);
			while(108*$i < $txt_len OR $i != 8)
				{
				$txt=str_replace($val,'',$txt);
				$val=tronquer_texte($txt,108,0,2);
				imagettftext($image, 45, 0, 25, 2992+(($i-1)*64)-2, $black, $font, $val);
				$i++;
				}
			
			// Date de création + copyleft
			imagettftext($image, 45/1.5, 0, 1920, 140, $black, $font, 'Etat au '.date('d/m/y').' ('.round((microtime(true) - $debut),1).'sec)');
			imagettftext($image, 45/1.5, 0, 1980, 90, $black, $font, 'DonjonFacile.fr');
			imagejpeg($image,'ressources/fiches/'.to_url('fiche-'.$this->perso_id.'-'.$this->name.'_'.$this->date).'.jpg');
			$bdd=connect();
			$bdd->exec('UPDATE perso SET date="'.$this->date.'", old_fiche="'.$this->date.'" WHERE id='.$this->perso_id);
			imagedestroy($image);
			}
	////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////    FICHE SECONDAIRE    //////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////
		public function create_fiche_advanced()
			{
			$debut = microtime(true);
			if((in_array($this->metier,array('Ninja','Assassin','Voleur','Ranger')) AND $this->adv_opt=='Automatique') OR $this->adv_opt=='Archer')/////// ARCHER ///////
				{
				$image = imagecreatefromjpeg('.'.RESSOURCES.'naheulbeuk-feuille-equipement-archer.jpg');
				$size=45;
				$angle=0;
				$color = imagecolorallocate($image, 0, 0, 0);
				$font = '.'.RESSOURCES.'DAUPHINN.TTF';

				// nom perso
				imagettftext($image, $size, $angle, 150, 315, $color, $font, trunc($this->name,35));

				//flèches
				$i=0;
				foreach($this->items as $item)
					{
					if($i<5 AND $item['type']=="arme" AND $item['qte']>0 AND((
					($item['subtype']=="Flèches pour arc (jet)" OR $item['subtype']=="flèches" OR $item['subtype']=="Carreaux arbalète (jet)") 
					AND $item['emplacement']!="page principale") OR $item['emplacement']=="flèches"))
						{
						imagettftext($image, $size, $angle, 30, 535+$i*60, $color, $font, trunc($item['qte'],7));// nbre
						imagettftext($image, $size, $angle, 340, 535+$i*60, $color, $font, trunc($item['name'],35));// name
						imagettftext($image, $size, $angle, 1370, 535+$i*60, $color, $font, trunc($item['effets'],20));// bonus
						$i++;
						}
					}
				
				// nourriture
				$i=0;
				foreach($this->items as $item)
					{
					if($i<9 AND $item['qte']>0 AND (($item['type']=="divers" AND $item['emplacement']=="bouffe et boisson")
					OR ($item['subtype']=="bouffe et boisson" AND $item['emplacement']!="page principale")))
						{
						$val=trunc($item['name'],22).' (x'.$item['qte'].')';
						imagettftext($image, $size, $angle, 30, 1260+$i*65, $color, $font, $val);// ligne i+1
						$i++;
						}
					}
				
				//potions
				$i=0;
				foreach($this->items as $item)
					{
					if($i<5 AND $item['qte']>0 AND (($item['type']=="divers" AND  in_array($item['emplacement'],array("potions"))) 
							OR (in_array($item['subtype'],array("potions","Antidotes","potions","remèdes et produits de medecine","Potions – Augmentation des carac."))
							AND $item['emplacement']!="page principale" AND $item['emplacement']!="poisons")))
						{
						imagettftext($image, $size, $angle, 800, 1060+$i*60, $color, $font, trunc($item['qte'],7));// nbre
						imagettftext($image, $size, $angle, 1090, 1060+$i*60, $color, $font, trunc($item['name'],23));// name
						imagettftext($image, $size, $angle, 1830, 1060+$i*60, $color, $font, trunc($item['effets'],20));// effets
						$i++;
						}
					}
				
				//poison
				$i=0;
				foreach($this->items as $item)
					{
					if($i<5 AND $item['qte']>0 AND (($item['type']=="divers" AND  in_array($item['emplacement'],array("poisons"))) 
										OR ( in_array($item['subtype'],array("poisons")) AND $item['emplacement']!="page principale"
											AND $item['emplacement']!="potions") ))
						{
						imagettftext($image, $size, $angle, 800, 1530+$i*60, $color, $font, trunc($item['qte'],7));// nbre
						imagettftext($image, $size, $angle, 1090, 1530+$i*60, $color, $font, trunc($item['name'],23));// name
						imagettftext($image, $size, $angle, 1830, 1530+$i*60, $color, $font, trunc($item['effets'],20));// effets
						$i++;
						}
					}
				
				//objets speciaux
				$i=0;
				foreach($this->items as $item)
					{
					if($i<10 AND $item['qte']>0 AND (($item['type']=="divers" AND $item['emplacement']=="objets spéciaux")
									OR
									(in_array($item['subtype'],array("objets spéciaux","Objets exclusifs","Accessoires – Augmentation des carac.","Matériel à usage magique",
									"Relique - Dlul","Relique - Adathie",
									"Relique - Kornettoh","Relique - Slanoush","Relique - Youclidh"))
									AND $item['emplacement']!="page principale")))
						{
						imagettftext($image, $size, $angle, 160, 2185+$i*62, $color, $font, trunc($item['name'],40));// name
						imagettftext($image, $size, $angle, 1460, 2185+$i*62, $color, $font, trunc($item['effets'],33));// effets
						$i++;
						}
					}
				
				// butin
				$txt='';
				foreach($this->items as $item)
					{
					if($item['qte']>0 AND $item['type']=="divers" AND ($item['emplacement']=="butin" OR ($item['subtype']=='pierre/gemme' AND $item['emplacement']!="page principale")))
						{
						if($item['qte']==1){$qte='';}
						else{$qte=' (x'.$item['qte'].')';}
						$txt.=$item['name'].$qte.', ';
						}
					elseif($item['waste']>0)
						{
						if($item['waste']==1){$qte='';}
						else{$qte=' (x'.$item['waste'].')';}
						$txt.=$item['name'].$qte.', ';
						}
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
			elseif((in_array($this->metier,array('Prêtre','Mage', 'Sorcier')) AND $this->adv_opt=='Automatique') OR $this->adv_opt=='Mage')/////// MAGE/PRETRE ///////
				{
				$image = imagecreatefromjpeg('.'.RESSOURCES.'naheulbeuk-feuille-equipement-mage.jpg');
				$size=45;
				$angle=0;
				$color = imagecolorallocate($image, 0, 0, 0);
				$font = '.'.RESSOURCES.'DAUPHINN.TTF';

				// nom perso
				imagettftext($image, $size, $angle, 150, 315, $color, $font, trunc($this->name,35));

				//ingredients magiques
				$i=0;
				foreach($this->items as $item)
					{
					if($i<10 AND $item['qte']>0 AND (($item['type']=="divers" AND $item['emplacement']=="ingrédients magiques")
							OR
							(in_array($item['type'],array("Matériel à usage magique","ingrédients magiques")) AND $item['emplacement']!="page principale")))
						{
						imagettftext($image, $size, $angle, 30, 535+$i*61, $color, $font, trunc($item['qte'],7));// nbre
						imagettftext($image, $size, $angle, 340, 535+$i*61, $color, $font, trunc($item['name'],35));// name
						imagettftext($image, $size, $angle, 1370, 535+$i*61, $color, $font, trunc($item['effets'],9));// valeur
						$i++;
						}
					}

				// bouquins
				$i=0;
				foreach($this->items as $item)
					{
					if($i<10 AND $item['qte']>0 AND (($item['type']=="divers" AND $item['emplacement']=="bouquins")
										OR
										(in_array($item['subtype'],array("bouquins","Livres pour mages","Livres pour prêtres/paladins","Livres généraux",
										"Livres généraux – compétences")) AND $item['emplacement']!="page principale")))
						{
						imagettftext($image, $size, $angle, 1730, 535+$i*61, $color, $font, trunc($item['name'],23));// livre i
						$i++;
						}
					}
				$ecart=300;

				// nourriture
				$i=0;
				foreach($this->items as $item)
					{
					if($i<9 AND $item['qte']>0 AND (($item['type']=="divers" AND $item['emplacement']=="bouffe et boisson")
								OR ($item['subtype']=="bouffe et boisson" AND $item['emplacement']!="page principale")))
						{
						$val=trunc($item['name'],22).' (x'.$item['qte'].')';
						imagettftext($image, $size, $angle, 30, 1260+$i*65+$ecart, $color, $font, $val);// bouffe i
						$i++;
						}
					}
				
				// /!\ potions / poison /!\
				$i=0;
				foreach($this->items as $item)
					{
					if($i<5 AND $item['qte']>0 AND (($item['type']=="divers" AND in_array($item['subtype'],array("poisons","potions","remèdes et produits de medecine","Potions – Augmentation des carac.","Antidotes")) 
									AND $item['emplacement']!="page principale")
									OR (in_array($item['emplacement'],array("poisons","potions")))))
						{
						imagettftext($image, $size, $angle, 800, 1060+$i*61+$ecart, $color, $font, trunc($item['qte'],7));// nbre
						imagettftext($image, $size, $angle, 1090, 1060+$i*61+$ecart, $color, $font, trunc($item['name'],23));// name
						imagettftext($image, $size, $angle, 1830, 1060+$i*61+$ecart, $color, $font, trunc($item['effets'],20));// effets
						$i++;
						}
					}
				
				// bagues
				$i=0;
				foreach($this->items as $item)
					{
					if($i<5 AND $item['qte']>0 AND (($item['type']=="divers" AND $item['emplacement']=="bagues") 
									OR
									(in_array($item['subtype'],array("Bagues de puissance (prêtre)","bagues",
									"Médaillons d\'économie (prêtre)",
									"Bagues de Sûreté (prêtre)",
									"Bagues de puissance (sorcier)",
									"Bagues d\'économie (sorcier)",
									"Bagues de Sûreté (sorcier)")) AND $item['emplacement']!="page principale")
									))
						{
						imagettftext($image, $size, $angle, 800, 1530+$i*61+$ecart, $color, $font, trunc($item['qte'],7));// nbre
						imagettftext($image, $size, $angle, 1130, 1530+$i*61+$ecart, $color, $font, trunc($item['name'],27));// name
						imagettftext($image, $size, $angle, 1980, 1530+$i*61+$ecart, $color, $font, trunc($item['effets'],14));// effets
						$i++;
						}
					}
				$ecart=270;
					
				//objets speciaux
				$i=0;
				foreach($this->items as $item)
					{
					if($i<7 AND $item['qte']>0 AND (($item['type']=="divers" AND $item['emplacement']=="objets spéciaux")
									OR (in_array($item['subtype'],array("objets spéciaux","Objets exclusifs","Accessoires – Augmentation des carac.","Matériel à usage magique",
									"Relique - Dlul","Relique - Adathie",
									"Relique - Kornettoh","Relique - Slanoush","Relique - Youclidh"))
									AND $item['emplacement']!="page principale")))
						{
						imagettftext($image, $size, $angle, 160, 2185+$i*62+$ecart, $color, $font, trunc($item['name'],40));// name
						imagettftext($image, $size, $angle, 1460, 2185+$i*62+$ecart, $color, $font, trunc($item['effets'],33));// effets
						$i++;
						}
					}

				// butin
				$txt='';
				foreach($this->items as $item)
					{
					if($item['type']=="divers" AND $item['emplacement']=="butin" AND $item['qte']>0)
						{
						if($item['qte']==1){$qte='';}
						else{$qte=' (x'.$item['qte'].')';}
						$txt.=$item['name'].$qte.', ';
						}
					elseif($item['waste']>0)
						{
						if($item['waste']==1){$qte='';}
						else{$qte=' (x'.$item['waste'].')';}
						$txt.=$item['name'].$qte.', ';
						}
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
			else{/////// GUERRIER/DEFAULT ///////
				$image = imagecreatefromjpeg('.'.RESSOURCES.'naheulbeuk-feuille-equipement-guerrier.jpg');
				$size=45;
				$angle=0;
				$color = imagecolorallocate($image, 0, 0, 0);
				$font = '.'.RESSOURCES.'DAUPHINN.TTF';

				// nom perso
				imagettftext($image, $size, $angle, 150, 315, $color, $font, trunc($this->name,35));

				$ecart=450;

				// nourriture
				$i=0;
				foreach($this->items as $item)
					{
					if($i<9 AND $item['qte']>0 AND (($item['type']=="divers" AND $item['emplacement']=="bouffe et boisson")
									OR ($item['subtype']=="bouffe et boisson" AND $item['emplacement']!="page principale")))
						{
						$val=trunc($item['name'],22).' (x'.$item['qte'].')';
						imagettftext($image, $size, $angle, 30, 1260+$i*65-$ecart, $color, $font, $val);// ligne i+1
						$i++;
						}
					}
				
				//potions
				$i=0;
				foreach($this->items as $item)
					{
					if($i<9 AND $item['qte']>0 AND (($item['type']="divers" AND in_array($item['emplacement'],array("potions"))) 
								OR (in_array($item['subtype'],array("potions","Antidotes","potions","remèdes et produits de medecine",
								"Potions – Augmentation des carac.")) 
								AND $item['emplacement']!="page principale")))
						{
						imagettftext($image, $size, $angle, 800, 1060+$i*60-$ecart, $color, $font, trunc($item['qte'],7));// nbre
						imagettftext($image, $size, $angle, 1090, 1060+$i*60-$ecart, $color, $font, trunc($item['name'],23));// name
						imagettftext($image, $size, $angle, 1830, 1060+$i*60-$ecart, $color, $font, trunc($item['effets'],20));// effets
						$i++;
						}
					}
				
				//poison
				$i=0;
				foreach($this->items as $item)
					{
					if($i<5 AND $item['qte']>0 AND (($item['type']=="divers" AND in_array($item['emplacement'],array("poisons"))) 
									OR (in_array($item['subtype'],array("poisons")) AND $item['emplacement']!="page principale")))
						{
						imagettftext($image, $size, $angle, 800, 1530+$i*60-$ecart, $color, $font, trunc($item['qte'],7));// nbre
						imagettftext($image, $size, $angle, 1090, 1530+$i*60-$ecart, $color, $font, trunc($item['name'],23));// name
						imagettftext($image, $size, $angle, 1830, 1530+$i*60-$ecart, $color, $font, trunc($item['effets'],20));// effets
						$i++;
						}
					}

				//objets speciaux
				$i=0;
				foreach($this->items as $item)
					{
					if($i<10 AND $item['qte']>0 AND (($item['type']=="divers" AND $item['emplacement']=="objets spéciaux")
									OR (in_array($item['subtype'],array("objets spéciaux","Objets exclusifs","Accessoires – Augmentation des carac.",
									"Matériel à usage magique",
									"Relique - Dlul","Relique - Adathie",
									"Relique - Kornettoh","Relique - Slanoush","Relique - Youclidh"))
									AND $item['emplacement']!="page principale")))
						{
						imagettftext($image, $size, $angle, 160, 2185+$i*62-$ecart, $color, $font, trunc($item['name'],40));// name
						imagettftext($image, $size, $angle, 1460, 2185+$i*62-$ecart, $color, $font, trunc($item['effets'],33));// effets
						$i++;
						}
					}

				// butin
				$txt='';
				foreach($this->items as $item)
					{
					if($item['type']=="divers" AND $item['emplacement']=="butin" AND $item['qte']>0)
						{
						if($item['qte']==1){$qte='';}
						else{$qte=' (x'.$item['qte'].')';}
						$txt.=$item['name'].$qte.', ';
						}
					elseif($item['waste']>0)
						{
						if($item['waste']==1){$qte='';}
						else{$qte=' (x'.$item['waste'].')';}
						$txt.=$item['name'].$qte.', ';
						}
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
			imagettftext($image, $size/1.5, $angle, 1350, 340, $color, $font, 'Etat au '.date('d/m/y').' ('.round((microtime(true) - $debut),1).'sec)');
			imagettftext($image, $size/1.5, 0, 1390, 300, $black, $font, 'DonjonFacile.fr');
			imagejpeg($image,'ressources/fiches/'.to_url('fiche-advanced-'.$this->perso_id.'-'.$this->name.'_'.$this->date).'.jpg');
			$bdd=connect();
			$bdd->exec('UPDATE perso SET date="'.$this->date.'", old_fiche_advanced="'.$this->date.'" WHERE id='.$this->perso_id);
			imagedestroy($image);
			}
	}