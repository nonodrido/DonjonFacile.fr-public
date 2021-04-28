<?php
include("geoipcity.inc");
include("geoipregionvars.php");

$gi = geoip_open(realpath("GeoLiteCity.dat"),GEOIP_STANDARD);

$record = geoip_record_by_addr($gi,$_SERVER['REMOTE_ADDR']);

$la = $record->latitude;
$lo = $record->longitude;

$url = "http://maps.google.com/maps/geo?output=json&q=".$la.",".$lo;
if($json = file_get_contents($url))
{
$informations = json_decode($json, true);
   if($informations['Status']['code']!=200)
   {
      die("Erreur");
   }
   else
   {
      print_r($informations);
	  $geoloc=$informations['Placemark'][0]['address'];
   }
}
else
{
   echo "Erreur";
}


geoip_close($gi);

/*Array ( [name] => 48.8667,1.9667 
[Status] => Array ( [code] => 200 [request] => geocode ) 
[Placemark] => Array ( [0] => Array ( [id] => p1 
	[address] => Route Départementale 307, 78810 Feucherolles, France 
	[AddressDetails] => Array ( 
		[Accuracy] => 6 
		[Country] => Array ( 
			[AdministrativeArea] => Array ( 
				[AdministrativeAreaName] => Île-de-France 
				[SubAdministrativeArea] => Array ( 
					[Locality] => Array ( 
						[LocalityName] => Feucherolles 
						[PostalCode] => Array ( [PostalCodeNumber] => 78810 ) 
						[Thoroughfare] => Array ( [ThoroughfareName] => Route Départementale 307 ) ) 
					[SubAdministrativeAreaName] => Yvelines ) ) 
			[CountryName] => France 
			[CountryNameCode] => FR ) ) [ExtendedData] => Array ( [LatLonBox] => Array ( [north] => 48.8686159 [south] => 48.8659179 [east] => 1.96806 [west] => 1.965362 ) ) [Point] => Array ( [coordinates] => Array ( [0] => 1.9666942 [1] => 48.8672456 [2] => 0 ) ) ) )
 ) 
*/


?>
