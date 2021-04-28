<?php
$ajax=1;
session_start();
include('../includes/admin/f.php');
if(isset($_POST['content_array']))
{
echo format_mail(json_decode($_POST['content_array']));
}
elseif(isset($_GET['content_array']))
{
echo format_mail(json_decode(urldecode($_GET['content_array'])));
}
else{
	$i=1;
	$array=array();
	while(!empty($_GET['titre'.$i]) AND !empty($_GET['contenu'.$i]))
		{
		$array[$_GET['titre'.$i]]=$_GET['contenu'.$i];
		$i++;
		}
	echo format_mail($array);
	}