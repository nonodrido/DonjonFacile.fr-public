<?php
function ScanDirectory($Directory){

  $MyDirectory = opendir($Directory) or die('Erreur');
	while($Entry = @readdir($MyDirectory)) 
		{
		if($Entry!='.' AND $Entry!='..' AND $Entry!='index.html')
			{
			// echo'<li>'.$Entry.'</li>';
			echo '<img width="100" height="100"
			style="cursor:pointer;width:100px;height:100px;" 
			src="'.$Directory.$Entry.'" 
			onMouseOver="$(this).style(\'border\',\'black solid 1px\';" 
			onclick="
			$(\'#img\').val(\'http://donjonfacile.fr/ressources/img/avatar/'.$Entry.'\');
			$(\'#avatar\').val(\'http://donjonfacile.fr/ressources/img/avatar/'.$Entry.'\');
			$(\'#img_preview\').attr(\'src\',\'http://donjonfacile.fr/ressources/img/avatar/'.$Entry.'\');
			$(\'.modal\').modal(\'hide\')"/>';
			}
		}
	closedir($MyDirectory);
}
ScanDirectory('../ressources/img/avatar/');
?>