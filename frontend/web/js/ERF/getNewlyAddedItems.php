<?php
G::LoadClass( 'pmFunctions' );
$appUid  =isset($_SESSION['APPLICATION']) ? $_SESSION['APPLICATION']:0;
$itemType=$_POST['itemType'];
$tblName="";
switch ($itemType){
case 'Entity':
	$tblName="SCI_ACRONYM_ENTITY";
	$itemTypeStr="Entity";
	break;  
	case 'Department':
	$tblName="SCI_ACRONYM_DEPARTMENT";
	$itemTypeStr="Department";
	break;
	case 'Division':
	$tblName="SCI_ACRONYM_DIVISION";
	$itemTypeStr="Division";
	break;
	case 'Project':
	$tblName="SCI_ACRONYM_PROJECT";
	$itemTypeStr="Project";
	break;
}
echo $qry=" SELECT ID,`NAME` FROM  $tblName WHERE IS_DELETED = 0 UNION
		SELECT ID,ITEM_NAME FROM `SCI_ACRONYM_REQUEST_ITEMS` WHERE ITEM_TYPE = '$itemType' AND APP_UID ='$appUid'";
$res=executeQuery($qry);
$optionString="<option value="">--Please select--</option>";
foreach($res as $row){
    $optionString .="<option value=".$row['ID'].">".$row['NAME']."</option>";
}
die($optionString);


?>