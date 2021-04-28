<?php
define('NAME_MIN', 3);
define('NAME_MAX', 75);
define('DESCR_MIN', 3);
define('DESCR_MAX', 1000);
class item
    {
		
        private $mode,$verif=false,$droits=0,$secure=1,
		$_id=0,//INT
		$_create_date= '0000-00-00 00:00:00',//DATETIME
		$_date= '0000-00-00 00:00:00',//DATETIME
		$_auteur_id=0,//INT
		$_orig_auteur_id=0,//INT
		$_auteur_pseudo='',
		$_emplacement='default',
		$_etat='default',//DEFAULT OR DELETE
		$_type='',//ARRAY_DEFINED
		$_subtype='',//ARRAY_DEFINED ???
		$_name='',//LIMIT ???
		$_carac='',//LIMIT ???
		$_prix=0,//INT
		$_descr='',//LIMIT ???
		$_effets='',//LIMIT ???
		$_rupture=0;//INT
		
		//SETTERS	public function set($val){$this->_=$val;}
		public function setId($val){$this->_id = (int) $val;}
		public function setSecure($val){$this->secure = (int) $val;}
		public function setAuteur_id($val){$this->_auteur_id = (int) $val;}
		public function setOrig_auteur_id($val){$this->_orig_auteur_id = (int) $val;}
		public function setPrix($val){if($val>=0){$this->_prix = (float) $val;}else{$this->_prix =0;}}
		public function setRupture($val)
			{
			if($val===NULL){$this->_rupture =NULL;return true;}
			else{
				$val=(int) $val;
				if($val>=0 && $val<=5){$this->_rupture=$val;return true;}
				else{$this->_rupture =0;return false;}
				}
			}
		public function setEtat($val){if(in_array($val,array('default','delete','officiel'))){$this->_etat=$val;return true;}else{return false;}}
		public function setType($val){if(in_array($val,array('arme','protec','comp','sort','prodige','divers'))){$this->_type=$val;return true;}else{return false;}}
		public function setSubtype($val){$this->_subtype=$val;}
		public function setEmplacement($val){$this->_emplacement=$val;}
		public function setName($val)
			{
			if(str_check($val,NAME_MIN,NAME_MAX) AND (($this->mode==='new' AND $this->check_name($val)) OR $this->mode==='old') AND !is_numeric($val))
				{$this->_name=$val;return true;}
			else{return false;}
			}
		public function setCarac($val){$this->_carac=$val;}
		public function setPseudo($val){$this->_auteur_pseudo=$val;}
		public function setDescr($val){if(str_check($val,DESCR_MIN,DESCR_MAX)){$this->_descr=$val;return true;}else{return false;}}
		public function setEffets($val){$this->_effets=$val;}
		public function setDate($val){$this->_date=date_bdd($val,MYSQL_DATETIME_FORMAT);}
		public function setCreate_date($val){$this->_create_date=date_bdd($val,MYSQL_DATETIME_FORMAT);}
		
		//FUNCTION BASE
		public function __construct($id)
        {	
		if($id==='new')
			{
			$this->mode='new';
			}
			else{$this->mode='old';}
		$this->setId($id);
		if($val=$this->get())
			{
			$this->hydrate($val);
			$this->verif=true;
			}
        }
		public function hydrate(array $val)
        {
        foreach ($val as $key => $value)
			{
				$method = 'set'.ucfirst($key);
				if (method_exists($this, $method))
				{
					$this->$method($value);
				}
			}
        }
		private function get()
			{
			if(empty($this->_id)){return false;}
			$bdd=connect();
			$rep = $bdd->query('SELECT item.*,users.pseudo FROM item INNER JOIN users ON item.auteur_id = users.id WHERE item.etat!="delete" AND item.id='.$this->_id);
			$result = $rep->fetch(PDO::FETCH_ASSOC);
			if($rep->rowCount() == 0){return false;}
			else{return $result;}
			}
		public function __get ($nom)
			{
			$arg='_'.$nom;
			if($this->secure==1 AND false)
				{
				if (isset ($this->$nom)){return secure::html($this->$nom,1);}
				elseif(isset($this->$arg)){return secure::html($this->$arg,1);}
				}
			else{
				if (isset ($this->$nom)){return $this->$nom;}
				elseif(isset($this->$arg)){return $this->$arg;}
				}
			}
		public function __call ($nom, $arguments)
        {
			echo 'La méthode <strong>', $nom, '</strong> a été appelée alors qu\'elle n\'existe pas ! Ses arguments étaient les suivants : <strong>', implode ($arguments, '</strong>, <strong>'), '</strong>';
        }
		public function __set ($nom, $val)
			{
			$method='set'.ucfirst($nom);
			if (method_exists($this, $method))
				{
					$this->$method($val);
				}
			}
		public function verif()
			{
			if($this->verif===true){return true;}
			else{return false;}
			}
		//FUNCTION GENERAL
		public function update()
			{
			if(empty($this->_name)){return false;}
			if($this->mode=='new')
				{
				if($this->droits()){$this->create();return $this->_id;}else{return false;}
				}
			else{
				if($this->droits()){$this->edit();}else{return false;}
				}
			}
		private function create()
			{
			$bdd=connect();
			$q=$bdd->prepare('INSERT INTO item SET create_date=NOW(),date=NOW(),auteur_id=:auteur_id,orig_auteur_id=:orig_auteur_id,emplacement=:emplacement,etat=:etat,type=:type,subtype=:subtype,name=:name,carac=:carac,prix=:prix,descr=:descr,effets=:effets,rupture=:rupture');
            // id,create_date,date,auteur_id,etat,type,subtype,name,carac,prix,descr,effets,rupture
            $q->bindValue(':etat', $this->_etat);
			$q->bindValue(':type', $this->_type);
			$q->bindValue(':subtype', $this->_subtype);
			$q->bindValue(':name', $this->_name);
			$q->bindValue(':descr', $this->_descr);
			$q->bindValue(':effets', $this->_effets);
			$q->bindValue(':carac', $this->_carac);
			$q->bindValue(':emplacement', $this->_emplacement);
            $q->bindValue(':auteur_id', $this->_auteur_id, PDO::PARAM_INT);
			$q->bindValue(':orig_auteur_id', $this->_auteur_id, PDO::PARAM_INT);
			$q->bindValue(':prix', $this->_prix, PDO::PARAM_INT);
			$q->bindValue(':rupture', $this->_rupture, PDO::PARAM_INT);
            $q->execute();
			$this->_id=$bdd->lastInsertId();
			$q=$bdd->prepare('INSERT INTO search SET id=:id,date=NOW(),auteur_id=:auteur_id,etat=:etat,type=:type,subtype=:subtype,name=:name,descr=:descr');
            $q->bindValue(':etat', $this->_etat);
			$q->bindValue(':type', $this->_type);
			$q->bindValue(':subtype', $this->_subtype);
			$q->bindValue(':name', $this->_name);
			$q->bindValue(':descr', $this->_descr);
            $q->bindValue(':auteur_id', $this->_auteur_id, PDO::PARAM_INT);
			$q->bindValue(':id', $this->_id, PDO::PARAM_INT);
            $q->execute();
			}
		private function edit()
			{
			$bdd=connect();
			$q=$bdd->prepare('UPDATE item SET date=NOW(),auteur_id=:auteur_id,etat=:etat,emplacement=:emplacement,type=:type,subtype=:subtype,name=:name,carac=:carac,prix=:prix,descr=:descr,effets=:effets,rupture=:rupture WHERE id=:id');
            $q->bindValue(':id', $this->_id, PDO::PARAM_INT);
			$q->bindValue(':etat', $this->_etat);
			$q->bindValue(':type', $this->_type);
			$q->bindValue(':subtype', $this->_subtype);
			$q->bindValue(':name', $this->_name);
			$q->bindValue(':carac', $this->_carac);
			$q->bindValue(':descr', $this->_descr);
			$q->bindValue(':effets', $this->_effets);
			$q->bindValue(':emplacement', $this->_emplacement);
            $q->bindValue(':auteur_id', $this->_auteur_id, PDO::PARAM_INT);
			$q->bindValue(':prix', $this->_prix, PDO::PARAM_INT);
			$q->bindValue(':rupture', $this->_rupture, PDO::PARAM_INT);
            $q->execute();
			$q=$bdd->prepare('UPDATE search SET date=NOW(),auteur_id=:auteur_id,etat=:etat,type=:type,subtype=:subtype,name=:name,descr=:descr WHERE id=:id');
            $q->bindValue(':id', $this->_id, PDO::PARAM_INT);
			$q->bindValue(':etat', $this->_etat);
			$q->bindValue(':type', $this->_type);
			$q->bindValue(':subtype', $this->_subtype);
			$q->bindValue(':name', $this->_name);
			$q->bindValue(':descr', $this->_descr);
            $q->bindValue(':auteur_id', $this->_auteur_id, PDO::PARAM_INT);
            $q->execute();
			}
		public function delete()
			{
			if($this->droits())
				{
				$bdd=connect();
				$bdd->exec('UPDATE item SET etat="delete" WHERE id='.$this->_id);
				$bdd->exec('UPDATE search SET etat="delete" WHERE id='.$this->_id);
				// $bdd->exec('UPDATE rating SET etat="delete" WHERE item_id='.$this->_id);
				}
			else{return false;}
			}
		public function droits()
			{
			if($this->droits===0)
				{
				if(!isuser()){$this->droits=false;return false;}
				elseif(isadmin()){$this->droits=true;return true;}
				elseif(isadmin('validateur') AND $this->_auteur_id==1){$this->droits=true;return true;}
				$bdd=connect();
				// $rep = $bdd->query('SELECT * FROM item WHERE auteur_id='.$_SESSION['user_id'].' AND id='.$this->_id.' AND etat!="delete"');
				if($_SESSION['user_id']==$this->_auteur_id){$this->droits=true;return true;}
				else{$this->droits=false;return false;}
				}
			elseif($this->droits===true){return true;}
			else{return false;}
			}
		public function check_name($altname='')  // id,create_date,date,auteur_id,etat,type,subtype,name,carac,prix,descr,effets,rupture
			{
			$bdd=connect();
			$q=$bdd->prepare('SELECT * FROM item WHERE etat!="delete" AND id!=:id AND name=:name
							AND (auteur_id=1 OR auteur_id='.$this->_auteur_id.')');
			if(!empty($altname)){$q->bindValue(':name', $altname);}
			else{$q->bindValue(':name', $this->_name);}
			$q->bindValue(':id', $this->_id, PDO::PARAM_INT);
            $q->execute();
			if($q->rowCount() == 0){return true;}
			return false;
			}
		//// FONCTIONS D'AFFICHAGE ////	
		
		public function tableau($mode=0,$search=false)
			{
			if(!$this->verif()){return false;}
			$array=array(
				'comp' =>array('Nom'=>'name','Type'=>'subtype','Description'=>'descr','Effets'=>'effets','Auteur'=>'auteur_pseudo'),
				'prodige' =>array('Nom'=>'name','Métier'=>'subtype','Niveau'=>'carac','Description'=>'descr','Effets'=>'effets','Auteur'=>'auteur_pseudo'),
				'sort' =>array('Nom'=>'name','Type de magie'=>'subtype','Niveau'=>'carac','Description'=>'descr','Effets'=>'effets','Auteur'=>'auteur_pseudo'),
				'arme' =>array('Nom'=>'name','Type'=>'subtype','Prix'=>'prix','PI'=>'carac','Effets'=>'effets','Rupture'=>'rupture','Description'=>'descr','Auteur'=>'auteur_pseudo'),
				'protec'=>array('Nom'=>'name','Type'=>'subtype','Prix'=>'prix','PR'=>'carac','Effets'=>'effets','Rupture'=>'rupture','Description'=>'descr','Auteur'=>'auteur_pseudo'),
				'divers'=>array('Nom'=>'name','Type'=>'subtype','Emplacement'=>'emplacement','Prix'=>'prix','Effets'=>'effets','Description'=>'descr','Auteur'=>'auteur_pseudo')
				);
			if(array_key_exists($this->_type, $array))
				{
				$rupture=array('jamais','1','1 à 2','1 à 3','1 à 4','1 à 5');
				echo '<table class="table table-bordered table-hover table-condensed">';
				foreach($array[$this->_type] as $cle=>$val)
					{
					$arg='_'.$val;
					if ((!empty($this->$arg) OR ($this->droits() AND $mode==0)) AND $val=='descr')
						{
						echo '<tr><th>'.$cle.':</th><td style="width:100%;" id="'.$val.'">'.secure::html($this->$arg).'</td></tr>';
						}
					elseif (((!$this->droits() AND $mode==0) OR $search) AND $val=='name')
						{
						if(!$search)
							{
							echo '<tr>
									<th style="width:150px;">'.$cle.':</th>
									<td id="'.$val.'">
										<a style="color:black;" href="/item/'.$this->_id.'/'.to_url($this->_name).'">
										'.secure::html($this->_name,1).'</a>
									</td>
								</tr>';
							}
						else{
							echo '<tr><th style="width:150px;">'.$cle.':</th>
							<td id="'.$val.'">
							<span>
								<a style="color:black;" href="/item/'.$this->_id.'/'.to_url($this->_name).'">
								'.secure::html($this->_name,1).'</a>
							</span>
							<span class="btn-toolbar pull-right" style="margin:0;padding:0;">
								'.rate_button($this->id).'
								<a class="btn add_btn" rel="nofollow" href="/ajax/ajax_item_add_window.php?item_id='.$this->_id.'"  onclick="href_modal(this);return false;"><i class="icon-plus-sign"></i> Ajouter</a>
								<a class="btn" href="/item/'.$this->_id.'/'.to_url($this->_name).'"><i class="icon-search"></i> Voir</a>
							</span>
							</td></tr>';
							}
						}
					elseif ((!empty($this->$arg) OR ($this->droits() AND $mode==0)) AND $val=='auteur_pseudo')
						{
						if($this->_auteur_id===1){echo '<tr><th>'.$cle.':</th><td><img src="/ressources/img/img_html/officiel.png"/></td></tr>';}
						else{echo '<tr><th>'.$cle.':</th><td><a style="color:black;" href="/membre/'.$this->_auteur_id.'/'.to_url($this->$arg).'">'.secure::html($this->$arg).'</a></td></tr>';}
						}
					elseif(!empty($this->$arg) AND $val=='rupture')
						{
						echo '<tr><th>'.$cle.':</th><td id="'.$val.'">'.secure::html($rupture[$this->$arg],1).'</td></tr>';
						}
					elseif(!empty($this->$arg) OR ($this->droits() AND $mode==0))
						{
						echo '<tr><th>'.$cle.':</th><td id="'.$val.'">'.secure::html($this->$arg,1).'</td></tr>';
						}
					}
				echo '</table>';
				}
			else{return false;}
			}
		public function tableau_min()
			{
			// if($this->verif()){return false;}
			$array=array(
				'comp' =>array(/* 'Type'=>'subtype', */'Description'=>'descr'),
				// 'prodige' =>array('Nom'=>'name','Métier'=>'subtype','Niveau'=>'carac','Description'=>'descr','Effets'=>'effets','Auteur'=>'auteur_pseudo'),
				// 'sort' =>array('Nom'=>'name','Type de magie'=>'subtype','Niveau'=>'carac','Description'=>'descr','Effets'=>'effets','Auteur'=>'auteur_pseudo'),
				'arme' =>array('Type'=>'subtype','PI'=>'carac','Rupture'=>'rupture','Prix'=>'prix'),
				'protec'=>array('Type'=>'subtype','PR'=>'carac','Rupture'=>'rupture','Prix'=>'prix'),
				'divers'=>array('Nom'=>'name','Type'=>'subtype','Prix'=>'prix','Effets'=>'effets','Description'=>'descr','Auteur'=>'auteur_pseudo')
				);
			/* return '<tr>
						<td>'.$this->_name.'<br/>
						<span style="font-size:small;">'.$this->_effet.'</span></td>
						<td>'.$this->_subtype.'</td>
						<td>'.$this->_carac.'</td>
						<td>'.$this->_rupture.'</td>
						<td>'.$this->_prix.'</td>
					</tr>'; */
			if(array_key_exists($this->_type, $array))
				{
				$rupture=array('jamais','1','1 à 2','1 à 3','1 à 4','1 à 5');// data-target="#modal_'.$this->_id.'" 
				echo '<tr><td><a target="_blank"  onclick="href_modal(this);return false;" 
							href="/item/'.$this->_id.'/'.to_url($this->_name).'?popin=popin" rel="nofollow">'
				.secure::html($this->_name,1).'</a>';
				if($this->_auteur_id===1){echo ' <img src="/ressources/img/img_html/officiel.png"/>';}
				echo '<br/><small>'.secure::html($this->_effets,1).'</small>';
				echo '</td>';
				foreach($array[$this->_type] as $cle=>$val)
					{
					$arg='_'.$val;if($val==='descr'){$mode=1;}else{$mode=0;}
					if($val==='rupture'){echo '<td id="'.$val.'">'.secure::html($rupture[$this->$arg],$mode).'</td>';}
					else{echo '<td id="'.$val.'">'.secure::html($this->$arg,$mode).'</td>';}
					}
				}
			else{return false;}
			}
    }