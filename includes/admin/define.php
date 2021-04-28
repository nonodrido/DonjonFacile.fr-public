<?php
ini_set('display_errors', 0);
define('TITRE_SITE', ' &bull; DonjonFacile.fr');
define('RESSOURCES','/ressources/');
define('DIR_IMG', RESSOURCES.'img/');
define('DIR_IMG_CSS', ''.DIR_IMG.'img_css/');
define('DIR_IMG_CSS_FROM_CSS',''.DIR_IMG_CSS);
define('DIR_IMG_HTML', ''.DIR_IMG.'img_html/');
define('DIR_IMG_FICHES', 'ressources/fiches/');
define('DIR_AVATAR', DIR_IMG.'avatar/');
define('DIR_GEO', RESSOURCES.'geoloc/');
define('PUBLICKEY', 'PUBLICKEY GOOGLE CAPTCHA');
define('PRIVATEKEY', 'PRIVATEKEY GOOGLE CAPTCHA');
define('DATETIME_FORMAT','d/m/Y à H\hi');
define('MYSQL_DATETIME_FORMAT','Y-m-d H:i:s');
define('MYSQL_DATE_FORMAT','Y-m-d');
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"); 
define('ACCES_REFUSE_INVITE',"<br/>Cette section du site n'est accessible qu'aux membres. Connectez-vous ou n'hésitez pas à vous <a href='/inscription'>créer un compte gratuit</a>  
pour pouvoir accéder à l'ensemble des fonctionnalités du site.<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>");
define('ACCES_REFUSE_ADMIN','<br/>Zone réservée aux administrateurs du site. <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>');
define('ACCES_REFUSE','Accés à cette page refusée. Vous n\'avez pas les droits nécessaires.<br/>');
$array_divers=array( //////////DIVERS
'pierre/gemme',
'objets maudits',
'ingrédients magiques',
'Instruments à corde',
'Instruments à vent',
'Instruments de percussion',
'Instruments de combinaison',
'remèdes et produits de medecine',
'Objets exclusifs',
'Bagues de puissance (prêtre)',
'Médaillons d\'économie (prêtre)',
'Bagues de Sûreté (prêtre)',
'Bagues de puissance (sorcier)',
'Bagues d\'économie (sorcier)',
'Bagues de Sûreté (sorcier)',
'Livres pour mages',
'Livres pour prêtres/paladins',
'Livres généraux',
'Livres généraux – compétences',
'Sacs et transport',
'Équipement d\'aventure',
'Bivouac et camping',
'Produits pour les elfes',
'Meubles',
'Immobilier',
'Animaux de monte, de compagnie',
'Poisons',
'Antidotes',
'Accessoires – Augmentation des carac.',
'Potions – Augmentation des carac.',
'Soin et récupération',
'Divers objets permettant de survivre',
'Matériel à usage magique',
'Relique - Dlul',
'Relique - Adathie',
'Relique - Kornettoh',
'Relique - Slanoush',
'Relique - Youclidh',
'Relique - Malgar',
'bouffe et boisson',
'potions',
'poisons',
'objets spéciaux',
'flèches',
'butin',
'ingrédients magiques',
'bouquins',
'bagues');
$array_emplacements=array(//////////EMPLACEMENTS
'bouffe et boisson',
'potions',
'poisons',
'objets spéciaux',
'flèches',
'butin',
'ingrédients magiques',
'bouquins',
'bagues',
'page principale'
);
$array_arme=array( ///////ARMEMENT
'Récupération',
'Lames courtes',
'Lames 1 main',
'Lames 2 mains',
'Haches 1 main',
'Haches de jet',
'Haches 2 mains',
'Marteaux et masses 1 main',
'Marteaux 2 mains',
'Lances et piques (2 mains; armes d\'hast)',
'Javelots (jet)',
'Arcs (jet)',
'Flèches pour arc (jet)',
'Arbalètes (jet)',
'Carreaux arbalète (jet)',
'Armes bizarres',
'Armement standard (combat) (prêtre)',
'Armement béni (dégâts mystiques) (prêtre)',
'Bâtons standards (combat) (sorcier)',
'Bâtons ensorcelés (dégâts magiques) (sorcier)');
$array_protec=array(  //////PROTECTION
'Vestes, cottes matelassées (torse, bras)',
'Plastrons cuir (torse)',
'Plastrons métal (torse)',
'Accessoires métal (bras ou jambes)',
'Cottes de maille (torse, bras)',
'Casques et heaumes (tête)',
'Gantelets/Bracelets (mains, avant-bras)',
'Bottes, chaussures (pieds)',
'Armures complètes (toutes local.)',
'Boucliers',
'Protections pour semi-homme',
'Protections pour gnôme',
'Protections pour ogre',
'Chapeaux pour mages et prêtres, simples',
'Chapeaux pour mages et prêtres, enchantés',
'Chapeaux et couvre-chefs légers, simples (pour tous)',
'Chapeaux et couvre-chefs légers, enchantés (origines compatibles uniquement)',
'Chapeaux et couvre-chefs légers, enchantés (ménestrels)',
'Robes standard (prêtre)',
'Robes bénies (prêtre)',
'Robes standard (sorcier)',
'Robes enchantées',
'Vêtements');
$array_comp=array("ALL","COU","INT","CHA","AD","FO","PI","PR",'Coups spéciaux');//////COMP
$array_metiers=array(////////METIER
'Glandeur',
'Guerrier',
'Gladiateur',
'Paladin',
'Ninja',
'Assassin',
'Voleur',
'Prêtre',
'Mage', 
'Sorcier',
'Ranger',
'Ménestrel',
'Pirate',
'Marchand',
'Ingénieur',
'Bourgeois',
'Noble',
'Nécromancien',
'PNJ');
$array_origines=array( ////////ORIGINE
'Humain',
'Barbare',
'Nain',
'Haut Elfe',
'Demi-Elfe',
'Elfe Sylvain',
'Elfe Noir',
'Orque',
'Demi-Orque',
'Gobelin',
'Ogre',
'Semi-homme',
'Gnôme');
sort($array_origines);
sort($array_emplacements);
sort($array_metiers);
sort($array_divers);
sort($array_arme);
sort($array_protec);
sort($array_comp);

/* On distingue, en gros, en Terre de Fangh, 4 types d'équipement que nous allons détailler plus bas :
➢ Les armures et protections
➢ Les armes
➢ Les objets magiques
➢ Le reste (vêtements, bouffe, sacs, mobilier, petit matériel) */