<?php
$ajax=1;
session_start();
include('../includes/admin/f.php');
$bdd=connect();
$aResponse['error'] = false;
$aResponse['message'] = '';

// ONLY FOR THE DEMO, YOU CAN REMOVE THIS VAR
	$aResponse['server'] = ''; 
// END ONLY FOR DEMO
	
	
if(isset($_POST['action']))
{
	if(htmlentities($_POST['action'], ENT_QUOTES, 'UTF-8') == 'rating')
	{
		/*
		* vars
		*/
		$id = intval($_POST['idBox']);
		$rate = floatval($_POST['rate']);
		$aResponse['item_id']=$_POST['idBox'];
		$aResponse['rate']=$rate;
		
		// YOUR MYSQL REQUEST HERE or other thing :)
		if($rate<=5 AND $rate>=0 AND isset($_SESSION['user_id']))
			{
			$bdd->exec('INSERT INTO rating VALUES("default",NOW(),"'.$id.'",'.$_SESSION['user_id'].','.$rate.')ON DUPLICATE KEY UPDATE rate='.$rate);
			$success = true;
			}
		else{$success = false;}

		// json datas send to the js file
		if($success)
		{
			$aResponse['message'] = 'Votre vote a bien été enregistré, merci d\'avoir donné votre avis :)';
			$aResponse['global_rating']=get_rate($id);
			echo json_encode($aResponse);
		}
		else
		{
			$aResponse['error'] = true;
			$aResponse['message'] = 'Une erreur est survenue, veuillez réessayer.';
			
			echo json_encode($aResponse);
		}
	}
	else
	{
		$aResponse['error'] = true;
		$aResponse['message'] = '"action" post data not equal to \'rating\'';
		
		// ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
			$aResponse['server'] = '<strong>ERROR :</strong> "action" post data not equal to \'rating\'';
		// END ONLY FOR DEMO
			
		
		echo json_encode($aResponse);
	}
}
else
{
	$aResponse['error'] = true;
	$aResponse['message'] = '$_POST[\'action\'] not found';
	
	// ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
		$aResponse['server'] = '<strong>ERROR :</strong> $_POST[\'action\'] not found';
	// END ONLY FOR DEMO
	
	
	echo json_encode($aResponse);
}