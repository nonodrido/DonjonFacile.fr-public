<?php
class cache
	{
	
	public function create_item($elem,$cache='cache',$mode,$type='')
		{
		ob_start();
		///// DEBUT GENERATION CONTENU /////
		$bdd=connect();
		$debut='';
		include($elem);
		///// FIN GENERATION CONTENU /////
		$tampon = ob_get_contents();
		ob_end_clean();
		file_put_contents($cache, $tampon,LOCK_EX) ;		
		}
	public function design_render($content,$header,$titre,$fil,$descr,$keywords,$REQUEST_URI,$notif,$debut)
		{
		if(!isset($_SESSION['user_id']))
			{
			if(file_exists('cache/design.design') AND filemtime('cache/design.design')>filemtime("includes/design.php"))
				{
				$page=file_get_contents('cache/design.design');
				}
			else{
				ob_start();
				$design=1;include("includes/design.php");
					echo '{{ $content 88fbb3ba455fadcca770fe01cf1386a7 }}';
				$design=2;include("includes/design.php");
				$tampon = ob_get_clean();
				file_put_contents('cache/design.design', $tampon,LOCK_EX) ;
				$page=$tampon;
				}
			}
		else{
			$chemin='cache/design_'.$_SESSION['user_id'].'.design';
			if(file_exists($chemin)){$cache_time=filemtime($chemin);}
			/* $test=$bdd->query('SELECT date FROM message WHERE destinataire_id='.$_SESSION['user_id'].' ORDER BY date DESC')->fetch();
			if(!empty($test['date']))
				{
				if(strtotime($test['date'])>$cache_time){$new_mess=1;}
				} */
			
			if(isset($cache_time) AND $cache_time>filemtime("includes/design.php") 
			AND isset($_SESSION['cache_time']) AND $cache_time>$_SESSION['cache_time'] /* AND !isset($new_mess) AND $cache_time < ($_SESSION['cache_time']+90) */)
				{
				$page=file_get_contents($chemin);
				}
			else{
				$bdd=connect();
				ob_start();
				$design=1;include("includes/design.php");
					echo '{{ $content 88fbb3ba455fadcca770fe01cf1386a7 }}';
				$design=2;include("includes/design.php");
				$tampon = ob_get_clean();
				file_put_contents($chemin, $tampon,LOCK_EX);
				$_SESSION['cache_time']=time()-30;//30 pour être sûr de pas refaire le cache
				$page=$tampon;
				}
			}
		$page=str_replace('{{ $header 88fbb3ba455fadcca770fe01cf1386a7 }}',$header,$page);
		$page=str_replace('{{ $titre 88fbb3ba455fadcca770fe01cf1386a7 }}',$titre,$page);
		$page=str_replace('{{ $fil 88fbb3ba455fadcca770fe01cf1386a7 }}',$fil,$page);
		$page=str_replace('{{ $descr 88fbb3ba455fadcca770fe01cf1386a7 }}',$descr,$page);
		$page=str_replace('{{ $keywords 88fbb3ba455fadcca770fe01cf1386a7 }}',$keywords,$page);
		$page=str_replace('{{ $REQUEST_URI 88fbb3ba455fadcca770fe01cf1386a7 }}',$REQUEST_URI,$page);
		$page=str_replace('{{ $notif 88fbb3ba455fadcca770fe01cf1386a7 }}',$notif,$page);
		if(isuser()){$bdd=connect();$test=$bdd->query('SELECT count(*) as nb FROM message WHERE lu=0 AND destinataire_id='.$_SESSION['user_id'])->fetch();}
		else{$test=0;}
		if($test['nb']!=0)
		{
		$new_mess= '<ul class="nav pull-right">
		<li><a href="/messagerie" class="clignote">'.$test['nb'].' <i style="margin-top:3px;" class="icon-white icon-envelope"></i></a></li>
		</ul>';
		}else{$new_mess='';}
		$page=str_replace('{{ $new_mess 88fbb3ba455fadcca770fe01cf1386a7 }}',$new_mess,$page);
		require_once "chat/src/pfcinfo.class.php";
		$info  = new pfcInfo('main');
		// NULL is used to get all the connected users, but you can specify
		// a channel name to get only the connected user on a specific channel
		$users = $info->getOnlineNick('Auberge');
		$nb_users = count($users);
		if ($nb_users ==0){$info = '';}
		else{$info = ' ('.$nb_users.')';}
		$page=str_replace('{{ $chat_number 88fbb3ba455fadcca770fe01cf1386a7 }}',$info,$page);
		$page=str_replace('{{ $content 88fbb3ba455fadcca770fe01cf1386a7 }}',$content,$page);
		$dev="<br/><em> Délai serveur : ". round((microtime(true) - $debut),2) ." seconde(s).</em><em class='pull-right'>Délai client : ". round((microtime(true) - $_SERVER['REQUEST_TIME']),2) ." seconde(s).</em>";
		$page=str_replace('{{ $dev 88fbb3ba455fadcca770fe01cf1386a7 }}',$dev,$page);
		echo $page;
		}
	
	
	
	}