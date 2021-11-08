<?php
G::LoadClass( 'pmFunctions' );
$userId =isset($_SESSION['USER_LOGGED'])? $_SESSION['USER_LOGGED']:0;
$appUid  =isset($_SESSION['APPLICATION']) ? $_SESSION['APPLICATION']:0;
$stage=$_POST['stage'];
$qryPart="";
$qryWherePart="";
switch ($stage) {
       case 'PM':
		$lmChecked=$_POST["lmChecked"];
		$lmComment=mysql_real_escape_string($_POST["lmComment"]);	
		$qryPart="LM_UID='$userId',LM_DECISION='$lmChecked',LM_REMARKS='$lmComment',HOD_DECISION='$lmChecked',EF_DECISION='$lmChecked',SF_DECISION='$lmChecked',CFO_DECISION='$lmChecked',CEO_DECISION='$lmChecked',STATUS='$lmChecked'";
		$qryWherePart="LM_DECISION=1";
        break;
    case 'HOD':
        $hodChecked=$_POST["lmChecked"];
		$hodComment=mysql_real_escape_string($_POST["lmComment"]);	
		$qryPart="HOD_UID='$userId',HOD_DECISION='$hodChecked',HOD_REMARKS='$hodComment',EF_DECISION='$hodChecked',SF_DECISION='$hodChecked',CFO_DECISION='$hodChecked',CEO_DECISION='$hodChecked',STATUS='$hodChecked'";
		$qryWherePart="HOD_DECISION=1";
		break;
    case 'EF':
        $efChecked=$_POST["lmChecked"];
		$efComment=mysql_real_escape_string($_POST["lmComment"]);	
		$qryPart="EF_UID='$userId',EF_DECISION='$efChecked',EF_REMARKS='$efComment',SF_DECISION='$efChecked',CFO_DECISION='$efChecked',CEO_DECISION='$efChecked',STATUS='$efChecked'";
		$qryWherePart="EF_DECISION=1";
        break;
	case 'SF':
        $sfChecked=$_POST["lmChecked"];
		$sfComment=mysql_real_escape_string($_POST["lmComment"]);	
		$qryPart="SF_UID='$userId',SF_DECISION='$sfChecked',SF_REMARKS='$sfComment',CFO_DECISION='$sfChecked',CEO_DECISION='$sfChecked',STATUS='$sfChecked'";
		$qryWherePart="SF_DECISION=1";

        break;
	case 'CFO':
        $cfoChecked=$_POST["lmChecked"];
		$cfoComment=mysql_real_escape_string($_POST["lmComment"]);	
		$qryPart="CFO_UID='$userId',CFO_DECISION='$cfoChecked',CFO_REMARKS='$cfoComment',CEO_DECISION='$cfoChecked',STATUS='$cfoChecked'";
		$qryWherePart="CFO_DECISION=1";

        break;
	case 'CEO':
        $ceoChecked=$_POST["lmChecked"];
		$ceoComment=mysql_real_escape_string($_POST["lmComment"]);	
		$qryPart="CEO_UID='$userId',CEO_DECISION='$ceoChecked',CEO_REMARKS='$ceoComment',STATUS='$ceoChecked'";
		$qryWherePart="CEO_DECISION=1";

        break;
    default:
        echo "Your favorite color is neither red, blue, nor green!";
}

	
$qry="SELECT APP_NUMBER FROM APPLICATION WHERE APP_UID='$appUid'";

$res=executeQuery($qry);
if(count($res)>0)	{
		$app_number=$res[1]['APP_NUMBER'];
			
}

$hdClaimId=$_POST['hdClaimId'];
$qryUpdate="UPDATE `FIN_CLMS` SET ".$qryPart." WHERE `CLIM_ID` = '$hdClaimId' AND APP_UID='$appUid' ";
executeQuery($qryUpdate);
$qryChkLD="SELECT LESS_ADVANCE FROM  FIN_CLM_APPLICATION  WHERE APP_UID='$appUid'";
$resChkLD=executeQuery($qryChkLD);
$lD=$resChkLD[1]['LESS_ADVANCE'];
$gTotQry="SELECT COALESCE(SUM(TOTAL_AMOUNT),0) AS GTOT FROM FIN_CLMS WHERE  APP_UID='$appUid' AND ".$qryWherePart."";
$totRes=executeQuery($gTotQry);
$Tot=$totRes[1]['GTOT'];
$qryUpdate2="UPDATE FIN_CLM_APPLICATION SET TOTAL='$Tot' WHERE APP_UID='$appUid'";
executeQuery($qryUpdate2);

$gtot=$totRes[1]['GTOT']-$lD;
$gtot=number_format((float)$gtot, 2, '.', '');
echo $gtot;
die();


?>