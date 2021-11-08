<?php
G::LoadClass( 'pmFunctions' );
$appUid  =isset($_SESSION['APPLICATION']) ? $_SESSION['APPLICATION']:0;
$qry="SELECT LESS_ADVANCE,TOTAL,TOTAL-LESS_ADVANCE AS GTOTAL FROM FIN_CLM_APPLICATION WHERE  APP_UID='$appUid'";
$amtRes=executeQuery($qry);
$amtString="";
$userId =isset($_SESSION['USER_LOGGED'])? $_SESSION['USER_LOGGED']:0;
  $qryCountry="SELECT USR_COUNTRY FROM vw_fn_requestor_location WHERE APP_UID='$appUid'";
  $res=executeQuery($qryCountry);
  $userCountry=$res[1]['USR_COUNTRY'];
  
  $displayCurrency = "MYR";

switch ($userCountry) {
    case "MY":
          $displayCurrency = "MYR";
        break;
    case "LK":
          $displayCurrency = "LKR";
        break;
   
    default:
          $displayCurrency = "MYR";
}
$myObj=  new stdClass();
//$LESS_ADVANCE=number_format((float)$amtRes[1]['LESS_ADVANCE'], 2, '.', '');
//$TOTAL=number_format((float)$amtRes[1]['TOTAL'], 2, '.', '');
$LESS_ADVANCE=$amtRes[1]['LESS_ADVANCE'];
$TOTAL=$amtRes[1]['TOTAL'];
$GTOTAL=$amtRes[1]['GTOTAL'];

$myObj->LESS_ADVANCE=$LESS_ADVANCE;
$myObj->TOTAL=$TOTAL;
$myObj->GTOTAL=$GTOTAL;
$myObj->displayCurrency=$displayCurrency;

echo  $myJSON = json_encode($myObj);
die();


?>