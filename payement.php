<?php session_start();
include ('../../config/config.inc.php');
include('carte.php');
include(dirname(__FILE__).'/../../header.php');
//recupération des valeurs des variables de la commande

$currency_code     = $_POST['currency_code']; //la devise utiliser par le client
$total_price       = $_POST['total_price'];   //total de la commande en devise par defaut
$cartid            = $_POST['custom'];   //numéro du cartid

//format prix 3 chiffre aprés la virgule
$carte = new Carte();
$carte->setCartid($cartid);
$carte->setTotalprice($total_price);
$carte->setDevise($currency_code);
$carte->setMontant($total_price);
$carte->ConvertDinar();


//verification du de devise utiliser qui dois étre obligatoirelment en Dinar Tunisien
//if($carte->VerificationDevise())
//{
$carte->setSid($sid=session_id( ));
$carte->setAffilie(Configuration::get('AFFILIE'));
$carte->insertReference();
$carte->getReference();

//récupération des variable pour l'envoie au serveur de la SMPT
$reference = $carte->reference;
$affilie   = $carte->affilie;
$montant   = $carte->montant;
$sid       = $carte->sid;
$devise    = $carte->devise;
$URL_CARTE=Configuration::get('URL_CARTE');
echo "<html>";
echo"<head>";
echo '<meta http-equiv= refresh  content="0; URL='.$URL_CARTE.'?reference='.$reference.'&affilie='.$affilie.'&montant='.$montant.'&sid='.$sid.'&devise='.$devise.'">' ;
echo "</head>";
echo "<body>";
echo '<center>Connexion au serveur de paiement <img src="../loader.gif"></center>';
/*?>
<form name="form" method="post" action="https://196.203.10.190/paiement/index.asp"> 
   <br><center>Page de test</center><br> 
   <p align="center"> Commande </p>    
   <table width="34%" border="0" align="center" cellpadding="0" cellspacing="1"> 
     <tr><td width="48%">Réference </td> 
      <td width="52%">   
      <input name="Reference" type="text" value="<?php echo $reference ?>">      
      </td></tr>       
      <tr> <td>montant : </td><td>      
      <input name="Montant" type="text" value="<?php echo $montant ?>"> 
</td></tr> 
      <tr><td>devise : </td><td> 
      <input name="Devise" type="text" value="<?php echo $devise ?>"> 
      </td></tr><tr> 
      <td height="52" colspan="2"><br> 
   <input type="hidden" name="sid" value="<?php echo $sid ?>"> 
      <input type="hidden" name="affilie" value="<?php echo $affilie ?>"> 
      <input type="submit" name="Submit" value="passer au paiement"> 
      </td> 
      </tr> 
    </table> 
  </form> 
<?php
*/
echo "</body></html>";
//}
//else 
//echo"<font color=red> Ce module ne peut pas fonctionner correctement <br> Vous devez selection le Dinar Tunisien comme devise par d&#233;faut<br></font>";

?>