<?php
include("includes/ip2locationlite.class.php");

//Load the class
$ipLite = new ip2location_lite;
$ipLite->setKey('CLE API');
 
//Get errors and locations
$locations = $ipLite->getCity($_SERVER['REMOTE_ADDR']);
$errors = $ipLite->getError();
 
//Getting the result
if (empty($errors)) {
if (!empty($locations)) {
  $geoloc=$locations['countryCode'].','.$locations['countryName'].','.$locations['regionName'].','.$locations['cityName'];
  }else{$geoloc='erreur';}
}else{$geoloc='erreur';}


?>
