<?php
function fluxrss()
{
// édition du début du fichier XML
$xml = '<?xml version="1.0" encoding="iso-8859-1"?><rss version="2.0">';
$xml .= '<channel>'; 
$xml .= '<title>Donjonfacile.fr - News</title>';
$xml .= '<link>http://donjonfacile.fr</link>';
$xml .= '<description>Flux des news du site</description>
		<language>fr</language>
		<copyright>nonodrido</copyright>
		<webMaster>contact@donjonfacile.fr (nonodrido)</webMaster>';
// <lastBuildDate>Wed, 26 Jun 2013 20:20:00 N</lastBuildDate>
// selection des 15 dernieres news
$res=connect();
$res=$res->query('SELECT * FROM news WHERE etat!="delete" ORDER BY date DESC LIMIT 15');

// extraction des informations et ajout au contenu
while($tab=$res->fetch()){   
	$id=$tab['id'];
	$titre=$tab['titre'];
	$description=$tab['contenu'];
	$date=$tab['date'];
	$date2=date("D, d M Y H:i:s", strtotime($date));

	$xml .= '<item>';
	$xml .= '<title>'.$titre.'</title>';
	$xml .= '<description>'.tronquer_texte($description,300).'</description>';
	$xml .= '<link>http://donjonfacile.fr/news/'.$id.'/'.to_url($titre).'</link>';
	$xml .= '<pubDate>'.$date2.' GMT</pubDate>'; 
	$xml .= '</item>';	
}

// édition de la fin du fichier XML
$xml .= '</channel>';
$xml .= '</rss>';

// écriture dans le fichier
/* $fp = fopen("../flux.xml", 'w+');
fputs($fp, $xml);
fclose($fp);
return 1; */
return $xml;
}
header('Content-Type: application/rss+xml; charset=UTF-8');
echo fluxrss();
exit;