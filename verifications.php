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
                // acc�der � la base et r�cuperer le montant 
 				$carte->getMontantApartirReference($ref);
                echo "Reference=".$ref. "&Action=".$act."&Reponse=".$carte->montant;

  	  break; 
        case "ERREUR":  
                // acc�der � la base et mettre � jour l��tat de la transaction 
                $carte->UpdateTransaction($ref,$act,$par);
                echo "Reference=".$ref. "&Action=".$act. "&Reponse=OK"; 
     break; 
        case "ACCORD":    
                // acc�der � la base, enregistrer le num�ro d�autorisation (dans param) 
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
                // acc�der � la base et mettre � jour l��tat de la transaction 
                $carte->UpdateTransaction($ref,$act,$par);
                echo "Reference=".$ref. "&Action=".$act. "&Reponse=OK"; 
     break; 
        case "ANNULATION": 
                // acc�der � la base et mettre � jour l��tat de la transaction 
                $carte->UpdateTransaction($ref,$act,$par);
             	echo "Reference=".$ref. "&Action=".$act. "&Reponse=OK"; 
     break; 
} 
?> 