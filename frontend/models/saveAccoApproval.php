<?php
G::LoadClass( 'pmFunctions' );
$userId =isset($_SESSION['USER_LOGGED'])? $_SESSION['USER_LOGGED']:0;
$appUid  =isset($_SESSION['APPLICATION']) ? $_SESSION['APPLICATION']:0;
$option =isset($_POST['option']) ? $_POST['option']:0;
$temp =isset($_POST['temp']) ? $_POST['temp']:0;
$updateStr="";
$decision=1;
$remarks="";
$acronUpdate="";
$acronTempUpdate="";
/*

decision: 1
remarks: 
itemId: 86
itemType: Department
acron: AAAA

*/
if (isset($_POST["decision"])){ $decision=mysql_real_escape_string($_POST["decision"]);}
if (isset($_POST["itemId"])){ $itemId=$_POST["itemId"];}
if (isset($_POST["remarks"])){ $remarks=mysql_real_escape_string($_POST["remarks"]);}
if (isset($_POST["acron"])){ $acron=mysql_real_escape_string($_POST["acron"]); $acron=strtoupper($acron);}
if ($option=='IRC'){
	$qry="SELECT `ACRONYM` FROM `SCI_ACRONYM_QUALITY_DOCS` WHERE IS_DELETED=0 AND ACRONYM='$acron'
UNION 
SELECT `ACRONYM` FROM `SCI_ACRONYM_ENTITY` WHERE IS_DELETED=0 AND ACRONYM='$acron'
UNION 
SELECT `ACRONYM` FROM `SCI_ACRONYM_DEPARTMENT` WHERE IS_DELETED=0 AND ACRONYM='$acron'
UNION 
SELECT `ACRONYM` FROM `SCI_ACRONYM_DIVISION` WHERE IS_DELETED=0 AND ACRONYM='$acron'
UNION 
SELECT `ACRONYM` FROM `SCI_ACRONYM_PROJECT` WHERE IS_DELETED=0 AND ACRONYM='$acron'
UNION 
SELECT `ITEM_ACRONYM` FROM `SCI_ACRONYM_REQUEST_ITEMS` WHERE IS_DELETED=0 AND ITEM_ACRONYM='$acron'";
$res=executeQuery($qry);
if (count($res)>0)
	die ('0');
else
	$acronUpdate=", ITEM_ACRONYM='$acron'";
}


$updateStr=$option."_UID='$userId',".$option."_REMARKS='$remarks',".$option."_DECISION='$decision' ,STATUS='$decision',FINAL_STATUS='$decision' $acronUpdate";
$qryUpdate="UPDATE `SCI_ACRONYM_REQUEST_ITEMS`	SET ".$updateStr." WHERE ID='$itemId' ";
executeQuery($qryUpdate);

//Get and Update the IS_DELETED flag in main table either entity, department, division or project based on the decision
$qry="SELECT ITEM_ID,ITEM_TYPE FROM SCI_ACRONYM_REQUEST_ITEMS WHERE ID='$itemId' " ;
$r=executeQuery($qry);
$itemTypeEDDP=$r[1]['ITEM_TYPE'];
$itemIdEDDP=$r[1]['ITEM_ID'];
switch ($itemTypeEDDP){
case 'Entity':
	$tblName="SCI_ACRONYM_ENTITY";
	break;  
	case 'Department':
	$tblName="SCI_ACRONYM_DEPARTMENT";
	break;
	case 'Division':
	$tblName="SCI_ACRONYM_DIVISION";
	break;
	case 'Project':
	$tblName="SCI_ACRONYM_PROJECT";
	break;
}
if ($decision==0){
	$delQry="UPDATE $tblName SET IS_DELETED=1 WHERE ID=$itemIdEDDP ";
	executeQuery($delQry);

}

$qryUpdateDate="UPDATE `SCI_ACRONYM_REQUEST_ITEMS`	SET ".$option."_ACTION_DATE=NOW() WHERE APP_UID='$appUid' ";
$resultExute=executeQuery($qryUpdateDate);

//GET APPROVAL COUNT
 $chkRc="SELECT COUNT(*) AS approveCount FROM SCI_ACRONYM_REQUEST_ITEMS WHERE ".$option."_DECISION=1 AND APP_UID='$appUid'";
$resRc=executeQuery($chkRc);
if ($resRc[1]['approveCount'] ==0){
 $qryUpdateRequestRejection="UPDATE `SCI_ACRONYM_REQUEST_ITEMS`	SET STATUS=0 WHERE APP_UID='$appUid' ";
executeQuery($qryUpdateRequestRejection);
}
die('100');


?>