<?php
G::LoadClass( 'pmFunctions' );
$userId =isset($_SESSION['USER_LOGGED'])? $_SESSION['USER_LOGGED']:0;
$appUid  =isset($_SESSION['APPLICATION']) ? $_SESSION['APPLICATION']:0;
$cleanLessAdvance=$_POST['cleanLessAdvance'];
$qryChk="SELECT * FROM  FIN_CLM_APPLICATION  WHERE APP_UID='$appUid'";
$resChk=executeQuery($qryChk);
if (count($resChk)){
$qryUpdate="UPDATE FIN_CLM_APPLICATION SET LESS_ADVANCE='$cleanLessAdvance' WHERE APP_UID='$appUid'";
executeQuery($qryUpdate);
echo "1";
}
else{
$qryInsert="INSERT INTO FIN_CLM_APPLICATION (APP_UID,LESS_ADVANCE) VALUES ('$appUid','$cleanLessAdvance') ";
executeQuery($qryInsert);		
}
die();


?>