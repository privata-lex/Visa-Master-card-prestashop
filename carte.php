<?php
class Carte extends PaymentModule  {
		
		public $conversion_rate;
		public $reference;
		public $montant;  //prix de la commande en dinar tunisien
		public $sid;
		public $affilie;
		public $devise;
		public $cartid;       //numéro de la cartid
		public $total_price; //prix de la commande en devise par defaut
		//variable qui contient le HTML a afficher
		private $_html;
		//variable qui contient la liste des erreurs
		private $_postErrors = array();
		
	
	/**
	 * @return numéro affilie
	 */
	public function getAffilie() {
		return $this->affilie;
	}
	
	/**
	 * @return devise
	 */
	public function getDevise() {
		//$this->devise='TND';
		return $this->devise;
	}
	
	/**
	 * @return montant de la commande
	 */
	public function getMontant() {
		return $this->montant;
	}
	
	/**
	 * @return reference de la commande
	 */
	public function getReference() {
		$REQUETE= "SELECT reference
				   FROM SMT 
				   ";
		$result =mysql_query($REQUETE);
		while ($row = mysql_fetch_array($result))
		{
			$inter=$row[0];
		}
		$this->reference=$inter;
		return $this->reference;
	}
	
	/**
	 * @return la variable de session sid
	 */
	public function getSid() {
		return $this->sid;
	}
	/**
	 * @return numéro cartid
	 */
	public function getCartid() {
		return $this->cartid;
	}
	/**
	 * @return total_price en devise par defaut
	 */
	public function getTotalprice() {
		return $this->total_price;
	}
	
	/**
	 * @param numero affilie
	 */
	public function setAffilie($affilie) {
		$this->affilie = $affilie;
	}
	
	/**
	 * @param le type de devise
	 */
	public function setDevise($devise) {
		$this->devise = $devise;
	}
	
	/**
	 * Cette fonction permet de convertir le prix sous la forme prévue par la SMTS
	 * @param le $montant
	 */
	public function setMontant($montant) {
		//conversion du format si le devise utiliser est le Dinar Tunisien
		if($this->devise=='TND')
		$montant=number_format($montant,3,',','');
		$this->montant = $montant;
	}
	
	/**
	 * @param unknown_type $reference
	 */
	public function setReference($reference) {
		$this->reference = $reference;
	}
	
	/**
	 * @param la variable $sid
	 */
	public function setSid($sid) {
		$this->sid = $sid;
	}
	/**
	 * @param numero cartid
	 */
	public function setCartid($cartid) {
		$this->cartid = $cartid;
	}
	/**
	 * @param numero total_price
	 */
	public function setTotalprice($total_price) {
		$this->total_price = $total_price;
	}
/*
 * constructeur de la classe carte
 */		
	function __construct() 
	
	{
		$this->name = 'carte';
		$this->tab = 'payments_gateways';
		$this->version = '1.0';
		$this->author = 'privata & lex';
		$this->module_key = 'cc2ee1ca3815a21c02817dfb6fccb935';
		
		//$this->currencies = true;
		//$this->currencies_mode = 'radio';
		parent::__construct();
		
		/* The parent construct is required for translations */
		$this->page = basename(__FILE__, '.php');
        $this->displayName = $this->l('Visa Master Card - SPS Tunisie');
        $this->description = $this->l('Accepte le paiement par carte VISA MASTER CARD');
		$this->confirmUninstall = $this->l('Etes vous sur de supprimer vos d&#233;tails ?');
		//variable module carte
		$this->referance = '';
		$this->montant = '';
		$this->sid = '';
		$this->affilie = '';
		$this->devise='TND';
	}
	
	
	/**
	 * Fonction d'instalation du module Carte
	 */
	public function install()
		{
			
			$REQUETE = "						
							
					CREATE TABLE IF NOT EXISTS SMT (
					  reference int(150) NOT NULL AUTO_INCREMENT,
					  sid text NOT NULL,
					  montant varchar(500) NOT NULL,
					  param varchar(50) NOT NULL,
					  etat varchar(20) NOT NULL,
					  id_order bigint(20) NOT NULL,
					  cartid bigint(20) NOT NULL COMMENT 'le numéro de la cart',
					  total_price float NOT NULL COMMENT 'prix total de la commande en devise par defaut',
					  UNIQUE KEY reference (reference)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1214 ;
						" ;
			if( ! Db::getInstance()->Execute($REQUETE) )
		{
			return false ;
		}
	    if (!parent::install() OR !Configuration::updateValue('AFFILIE', '99999999') OR !Configuration::updateValue('URL_CARTE', 'https://196.203.10.190/paiement/index.asp') OR !$this->registerHook('payment'))
				return false;
		return true;
		}

	public function uninstall()
	{
		if (!Configuration::deleteByName('AFFILIE') OR !Configuration::deleteByName('URL_CARTE')
			OR !parent::uninstall())
			return false;
		return true;
	}
	


	/*
	 * insertion referance dans la base de donnée
	 */
	public function insertReference()
	{
	 	 $REQUETE= "INSERT 
				   INTO SMT 
				   VALUES('','".$this->sid."','".$this->montant."','','','','".$this->cartid."','".$this->total_price."')
				   ";
		 
		mysql_query($REQUETE) or die("erreur dans le fichier ".__FILE__." Ligne ".__LINE__);
		
	}
	/**
	 * Retourner le montant de la commande apartir du numéro de la reference
	 * @param : numero de reference
	 * @return le montant de la commande à partir de la référence
	 */
	public function getMontantApartirReference($ref)
	{
				$REQUETE= "SELECT montant
				   FROM SMT 
				   WHERE 
				   reference=".$ref."
				   ";
		$result =mysql_query($REQUETE);
		while ($row = mysql_fetch_array($result))
		{
			$inter=$row[0];
		}
		$this->montant=$inter;
		return $this->montant;
	}
	/**
	 * Retourner le cartid de la commande apartir du numéro de la reference
	 * @param : numero de reference
	 * @return le cartid de la commande
	 */
	public function getCartidApartirReference($ref)
	{
				$REQUETE= "SELECT cartid
				   FROM SMT 
				   WHERE 
				   reference=".$ref."
				   ";
		$result =mysql_query($REQUETE);
		while ($row = mysql_fetch_array($result))
		{
			$inter=$row[0];
		}
		$this->cartid=$inter;
		return $this->cartid;
	}	

	/**
	 * Retourner le total_price de la commande apartir du numéro de la reference
	 * @param : numero de reference
	 * @return le total_price de la commande en devise par defaut
	 */
	public function getTotpriceApartirReference($ref)
	{
				$REQUETE= "SELECT total_price
				   FROM SMT 
				   WHERE 
				   reference=".$ref."
				   ";
		$result =mysql_query($REQUETE);
		while ($row = mysql_fetch_array($result))
		{
			$inter=$row[0];
		}
		$this->total_price=$inter;
		return $this->total_price;
	}	
	/*
	 * mise à jour de la transaction en fonction de l'état 
	 * @param : action,reference
	 * @return : resultat de la transaction
	 */
	public function UpdateTransaction($ref,$act,$par)
	{
		switch ($act)
		
		{  
        		case "ERREUR": 
        			
		        		$REQUETE= "UPDATE 
						   			 SMT 
						   		   SET
						   		  	 etat='ERREUR' 
						   		   WHERE 
						   		  	 reference=".$ref." 
						   		  ";
		        			
							mysql_query($REQUETE);
							return "OK";
        		break;
        		case "ACCORD": 
  
        			        $REQUETE= "UPDATE 
							   			 SMT 
							   		   SET
							   		  	 etat='ACCORD',param='".$par."'
							   		   WHERE 
							   		   	 reference=".$ref." 
							   		   ";
        			
							mysql_query($REQUETE);
							return "OK";
        		break;
        		case "REFUS": 
        			
		        		$REQUETE= "UPDATE 
						   			 SMT 
						   		   SET
						   		  	 etat='REFUS' 
						   		   WHERE 
						   		  	 reference=".$ref." 
						   		  ";
		        			
							mysql_query($REQUETE);
							return "OK";
							        			
        		break;
        		case "ANNULATION": 
        			
 		        		$REQUETE= "UPDATE 
						   			 SMT 
						   		   SET
						   		  	 etat='ANNULATION' 
						   		   WHERE 
						   		  	 reference=".$ref." 
						   		  ";
		        			
							mysql_query($REQUETE);
						
							return "OK";       			
        		break;
		}
	}
	

	/*
	 * Fonction d'affichage de l'espace de configuration du module et de mise à jours des variables (AFFILIE et URL_CARTE)
	 */	
	public function getContent()
		{
			$_html = '<h2> Visa Master Card </h2>';
			
			if (isset($_POST['submitCarte']))
			{
				if (empty($_POST['AFFILIE']))
					$this->_postErrors[] = $this->l('Le num&eacute;ro d&#146;affiliation est obligatoire');
				if (empty($_POST['URL_CARTE']))
					$this->_postErrors[] = $this->l('L adresse du serveur est obligatoire');
				if (!sizeof($this->_postErrors))
				{
					Configuration::updateValue('AFFILIE', intval($_POST['AFFILIE']));
					Configuration::updateValue('URL_CARTE', $_POST['URL_CARTE']);
					return $_html->displayForm();
				}
				else
				
					$this->displayErrors();
			}
			$this->displayCarte();
			
			//Recupération de l'ancienne reference et de l'URL du serveur de payement
			$AFFILIE   = Configuration::get('AFFILIE');
			$URL_CARTE = Configuration::get('URL_CARTE');
			$_html.="<form action=".$_SERVER['REQUEST_URI']." method=post>";
			$_html.='<fieldset>
				<legend><img src="../img/admin/contact.gif" />'.$this->l('R&eacute;glage').'</legend>
				<label>'.$this->l('Num&#233;ro affiliation (SMT)').'</label>
				<div class="margin-form"><input type="text" size="33" name="AFFILIE" value='.$AFFILIE.' ></div>
				<label>'.$this->l('URL du serveur (SMT)').'</label>
				<div class="margin-form"><input type="text" size="33" name="URL_CARTE" value='.$URL_CARTE.' ></div>
				<br /><center><input type="submit" name="submitCarte" value="'.$this->l('M&egrave;tre &agrave; jour').'" class="button" /></center>
			</fieldset>';
			$_html.='</form>';
			
			echo $_html;		
	
			
		}

	
	/*
	 * Fonction d'affichage de confirmation
	 */
	public function displayForm()
	{
		$_html .= '
		<div class="conf confirm">
			<img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />
			'.$this->l('Vos param&egrave;tres sont mis &agrave; jours').'
		</div>';
		
		return $_html;
	}	
	/*
	 * Fonction de gestion des erreurs
	 */
	public function displayErrors()
	{
		$nbErrors = sizeof($this->_postErrors);
		$this->_html .= '
		<div class="alert error">
			<h3>'.($nbErrors > 1 ? $this->l('Il y a ') : $this->l('Il y a')).' '.$nbErrors.' '.($nbErrors > 1 ? $this->l('erreurs') : $this->l('erreur')).'</h3>
			<ol>';
		foreach ($this->_postErrors AS $error)
			$this->_html .= '<li>'.$error.'</li>';
		$this->_html .= '
			</ol>
		</div>';
	}
	/*
	 * Fonction qui perment d'afficher le commentaire pour le module utiliser
	 */
	public function displayCarte()
	{
		$this->_html .= '
		<img src="../modules/carte/carte.gif" style="float:left; margin-right:15px;" />
		<b>'.$this->l('Ce module accepte les payements par les cartes de cr&eacute;dit.').'</b><br /><br />
		'.$this->l('Si le client choisie ce mode de payement votre compte bancaire soit automatiquement cr&eacute;dit&eacute;.').'<br />
		'.$this->l('Vous devez configurer ce module avant de l&#146;utiliser.').'
		<br /><br /><br />';

	}




/*
 * cette Fonction permet de convertir le montant en Dinar Tunisie si le money par defaut n'est pas le TND
 */
	public function ConvertDinar()
	{
		if($this->devise!='TND')
		
		{
	 		 $REQUETE= 
				 	  "
					   SELECT conversion_rate
					   FROM "._DB_PREFIX_."currency
					   WHERE 
					   iso_code='TND'
					   ";
			$result =mysql_query($REQUETE);
			while ($row = mysql_fetch_array($result))
			{
				$conversion_rate=$row[0];
			}
			 $this->devise='TND';
			 $this->setMontant($this->montant*2);
		}
	}

	/*
	 * Cette fonction permet d'enregister le numéro de l'ordre de la cart dans la table de la SMT
	 * @param : id_order 
	 */
	public function validateCarte($id_order)
	{
		$REQUETE= " 
					UPDATE SMT 
				    SET
				    id_order=".$id_order." 
					WHERE 
					reference=".$this->reference." 
				  ";
			        			
								mysql_query($REQUETE);
	}

public function hookPayment()
	{
		return $this->display(__FILE__, 'carte.tpl');
    }	
	
	
}

?>