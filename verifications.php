<?php session_start();
include ('../../config/config.inc.php');
include ('carte.php');
include ('../../classes/Currency.php');


$ref = $_GET['Reference']; 
$act = $_GET['Action']; 
$par = $_GET['Param']; 

$carte = new Carte();

switch ($act) {  
        case "DETAIL": 
                // accéder à la base et récuperer le montant 
 				$carte->getMontantApartirReference($ref);
                echo "Reference=".$ref. "&Action=".$act."&Reponse=".$carte->montant;

  	  break; 
        case "ERREUR":  
                // accéder à la base et mettre à jour l’état de la transaction 
                $carte->UpdateTransaction($ref,$act,$par);
                echo "Reference=".$ref. "&Action=".$act. "&Reponse=OK"; 
     break; 
        case "ACCORD":    
                // accéder à la base, enregistrer le numéro d’autorisation (dans param) 
                $carte->UpdateTransaction($ref,$act,$par);
                $carte->getMontantApartirReference($ref);
                //Validation de l'ordre dans la boutique
                $carte->setReference($ref);
             	//var_dump($_SESSION);
             	
             	$cartid=$carte->getCartidApartirReference($ref);
				$carte->validateCarte($cartid);
                $currency=new Currency();
				$currency_id=$currency->getIdByIsoCode("TND");
				$total_price  = $carte->getTotpriceApartirReference($ref);
		
				$carte->validateOrder($cartid, _PS_OS_PAYMENT_, $total_price, $carte->displayName, NULL, NULL, $currency_id);
			
				$order = new Order($carte->currentOrder);
				//$_SESSION['secure_key']=$order->secure_key;
				echo "Reference=".$ref. "&Action=".$act. "&Reponse=OK"; 
			
		//echo $_SESSION['ok'];
		

     break; 
        case "REFUS":  
                // accéder à la base et mettre à jour l’état de la transaction 
                $carte->UpdateTransaction($ref,$act,$par);
                echo "Reference=".$ref. "&Action=".$act. "&Reponse=OK"; 
     break; 
        case "ANNULATION": 
                // accéder à la base et mettre à jour l’état de la transaction 
                $carte->UpdateTransaction($ref,$act,$par);
             	echo "Reference=".$ref. "&Action=".$act. "&Reponse=OK"; 
     break; 
} 
?> 