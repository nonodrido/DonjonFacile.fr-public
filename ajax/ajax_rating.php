<?php
session_start();
if(isset($_POST['id']) AND isset($_SESSION['user_id']) AND isset($_POST['name']) AND ($_POST['name']=='up' OR $_POST['name']=='down'))
{
$ajax=1;
include('../includes/admin/f.php');
$bdd=connect();

$id=secure::bdd($_POST['id']);
$name=secure::bdd($_POST['name']);
$result=$bdd->query("select * from vote where id='".$id."'");
$data=$result->fetch();
$user_list=unserialize($data['user_list']);
if(!in_array($_SESSION['user_id'],$user_list)){
	$user_list[]=$_SESSION['user_id'];
	$bdd->exec("update vote set ".$name."=".($data[$name]+1).",user_list='".str_replace('"','',str_replace('s:1:','i:',serialize($user_list)))."' where item_id='".$id."'");
	}

$result=$bdd->query("select up,down from vote where item_id='".$id."'");// requete useless
$row=$result->fetch();
$up_value=$row['up'];
$down_value=$row['down'];
$total=$up_value+$down_value;
if($total==0){$total=1;}
$up_per=($up_value*100)/$total;
$down_per=($down_value*100)/$total;
?>
<div class="watch-sparkbars">
<div class="watch-sparkbar-likes" style="width:<?php echo $up_per; ?>%"></div>
<div class="watch-sparkbar-dislikes" style="width:<?php echo $down_per; ?>%"></div>
</div>
<span class="watch-likes-dislikes">
<span class="likes"><?php echo $up_value; ?></span>
 aiment,
<span class="dislikes"><?php echo $down_value; ?></span>
 n'aiment pas
</span>


<?php

}else
	{
	if(!isset($_SESSION['user_id'])){echo 'erreur 1<br/>';}
	if(!isset($_POST['id'])){echo 'erreur 2<br/>';}
	print_r($_POST);
	}
?>