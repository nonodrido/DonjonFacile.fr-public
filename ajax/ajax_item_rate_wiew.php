<?php
if(isset($_GET['item_id']) AND filter_var($_GET['item_id'],FILTER_VALIDATE_INT))
	{
	$ajax=1;
	session_start();
	include('../includes/admin/f.php');
	$bdd=connect();
	$req=$bdd->prepare('SELECT  (SELECT COUNT(*) as nb_like FROM item_rate WHERE item_id = :id AND rate = 1) as nb_like,
							  (SELECT COUNT(*) as nb_dislike FROM item_rate WHERE item_id = :id AND rate = -1) as nb_dislike
							  FROM item 
							  WHERE item.id= :id
							  AND item.etat!="delete"');
	$req->execute(array('id' => $_GET['item_id']));
	if($req->rowCount() == 0)
		{
		$val=array('nb_like'=>0,'nb_dislike'=>0);
		}
	else{
		$val=$req->fetch();
		}
	$total=$val['nb_like']+$val['nb_dislike'];
	if($total==0)
		{
		echo '<div style="margin:10px;">Ce contenu n\'a pas encore été évalué.</div>';
		}
	else{
		$width=75;
		echo '	<div class="center" style="margin:10px;">
					<i class="icon-thumbs-up" style="margin-top:3px;"></i> '.$val['nb_like'].' 
					<div style="width:'.$width.'px;height:7px;background:red;display:inline-block;margin-bottom:2px;">
						<div style="background:green;height:7px;width:'.($val['nb_like']/$total)*$width.'px;border-right:solid 1px white;"></div>
					</div>
					'.$val['nb_dislike'].' <i class="icon-thumbs-down" style="margin-top:5px;"></i>
				</div>';
		if(isuser())
			{
			$req=$bdd->prepare('SELECT  *
								FROM item_rate 
								WHERE item_id= :id AND user_id='.$_SESSION['user_id']);
			$req->execute(array('id' => $_GET['item_id']));
			if($req->rowCount() == 0)
				{
				echo '<div class="center" style="margin:10px;"><small>Vous n\'avez pas encore voté.</small></div>';
				}
			else{
				$val=$req->fetch();
				if($val['rate']==1){$icon='icon-thumbs-up';}else{$icon='icon-thumbs-down';}
				echo '<div class="center" style="margin:10px;"><small>Vous avez voté <i class="'.$icon.'"></i></small></div>';
				}
			}
		}
	}