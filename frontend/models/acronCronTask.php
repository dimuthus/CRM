<?php
G::LoadClass( 'pmFunctions' );
$qry="SELECT
    `SCI_ACRONYM_REQUEST`.`EFFECTIVE_DATE`
    , `SCI_ACRONYM_REQUEST_ITEMS`.`FINAL_STATUS`
    , `SCI_ACRONYM_REQUEST`.`REQUEST_TYPE`
    ,`SCI_ACRONYM_REQUEST`.`APP_UID` 
	, SCI_ACRONYM_REQUEST_ITEMS.`ITEM_TYPE` 
	, SCI_ACRONYM_REQUEST_ITEMS.`ID` 
	, SCI_ACRONYM_REQUEST_ITEMS.`ITEM_ID`
	,SCI_ACRONYM_REQUEST_ITEMS.`TEMP_NAME`
	,SCI_ACRONYM_REQUEST_ITEMS.`TEMP_ACRONYM`
	,SCI_ACRONYM_REQUEST_ITEMS.`ITEM_ACRONYM`
	,SCI_ACRONYM_REQUEST_ITEMS.ITEM_NAME
	FROM
    `SCI_ACRONYM_REQUEST`
    INNER JOIN `SCI_ACRONYM_REQUEST_ITEMS` 
        ON (`SCI_ACRONYM_REQUEST`.`APP_UID` = `SCI_ACRONYM_REQUEST_ITEMS`.`APP_UID`)
        WHERE    SCI_ACRONYM_REQUEST_ITEMS.`ITEM_ACRONYM` IS NOT NULL AND `SCI_ACRONYM_REQUEST_ITEMS`.`FINAL_STATUS`=1 AND `SCI_ACRONYM_REQUEST`.`EFFECTIVE_DATE` ORDER BY SCI_ACRONYM_REQUEST_ITEMS.`ID` ";
//die($qry);
$res=executeQuery($qry);
$tblName="";
$commonFields="`NAME`,`ACRONYM`,`LOCATION_ID`,`HOD`, `CREATED_BY`,`CREATED_DATE`,`IS_DELETED`, `IS_ACTIVE`";
$addtionalFields="";
$commonSelectFields="`ITEM_NAME`,`ITEM_ACRONYM`,`ITEM_LOCATION`,`ITEM_HOD_UID`,`IRC_HOD_UID`,NOW(),0,1";
$addtionalSelectFields="";
$msgTxt="";
$success=1;
if (count($res)== 0){
	$msgTxt="No records found by the cron job";
}
foreach($res as $records){
	$rT=$records['REQUEST_TYPE'];
	$appId=$records['APP_UID'];
	$itemType=$records['ITEM_TYPE'];
	$rowId=$records['ID'];
	$acronTblId=$records['ITEM_ID'];
	$nName=$records['TEMP_NAME'];
	$nAcron=$records['TEMP_ACRONYM'];
	$iniName=$records['ITEM_NAME'];
	$iniAcron=$records['ITEM_ACRONYM'];
	//echo $rT.$itemType.$rowId."<br/>";
	
	//$tblName="";
	if ($rT=='NAR'){
		switch ($itemType){
				case 'Entity':
					$updateQry="UPDATE SCI_ACRONYM_ENTITY SET `IS_ACTIVE`=1, ACRONYM='$iniAcron' WHERE ID=$acronTblId";
					executeQuery($updateQry);
					//die("ccccc");
					$r=checkExist('name',$iniName,'DOC_LEVEL2');
					//echo $r;
					
					if ($r==0){
					$insDocL2="INSERT INTO `DOC_LEVEL2` (`name`,`code`,`level1_id`,`is_deleted`,`ac_entity_id`)  (SELECT UPPER(`NAME`),`ACRONYM`,'2','0',ID FROM SCI_ACRONYM_ENTITY WHERE ID=$acronTblId )";
					executeQuery($insDocL2);
					$msgTxt .="$itemType". ">>>"." $iniName  sucessfully created";
					$success=2;
					}
					else{
					$msgTxt .="Save failure, " ."$itemType". ">>>"." $iniName  already exsis in DMS(DOC_LEVEL2)";
					$success=1;
					}

				break;  
				case 'Department':
				    $updateQry="UPDATE SCI_ACRONYM_DEPARTMENT SET `IS_ACTIVE`=1, ACRONYM='$iniAcron' WHERE ID=$acronTblId";
					executeQuery($updateQry);
					//insert into SCI_DEPARTMENT
					$r=checkExist('dept_name',$iniName,'SCI_DEPARTMENT');
					if ($r==0){
					$insSciDep="INSERT INTO `SCI_DEPARTMENT` (`dept_name`,department_code,`isDeleted`,`ac_dep_uid`)  (SELECT UPPER(`NAME`),`ACRONYM`,'0',ID FROM SCI_ACRONYM_DEPARTMENT WHERE ID=$acronTblId )";
					executeQuery($insSciDep);
					$msgTxt .="$itemType". ">>>"." $iniName  sucessfully created";
					$success=2;
					}
					else{
						$msgTxt .="Save failure, " ."$itemType". ">>>"." $iniName  already exsis in SCI_DEPARTMENT";
					    $success=1;

					}
					$resultSDI = executeQuery("select LAST_INSERT_ID() L");
					$ses_department_id=$resultSDI[1]['L'];
					//insert into DOC_LV3
						$r=checkExist('name',$iniName,'DOC_LV3');
					if ($r==0){
					$insDocL3="INSERT INTO `DOC_LV3` (`name`,is_department,ses_department_id,hod,`ac_department_id`)  (SELECT UPPER(`NAME`),1,'$ses_department_id',HOD,ID FROM SCI_ACRONYM_DEPARTMENT WHERE ID=$acronTblId )";
					die($insDocL3);
					executeQuery($insDocL3);
					$msgTxt .="$itemType". ">>>"." $iniName  sucessfully created";
					$success=2;
					}
					else{
						$msgTxt .="Save failure, " ."$itemType". ">>>"." $iniName  already exsis in DMS(DOC_LV3)";
					    $success=1;

					}
				break;
				case 'Division':
					$updateQry="UPDATE SCI_ACRONYM_DIVISION SET `IS_ACTIVE`=1,ACRONYM='$iniAcron' WHERE ID=$acronTblId";
					executeQuery($updateQry);
				break;
				case 'Project':
					$updateQry="UPDATE SCI_ACRONYM_PROJECT SET `IS_ACTIVE`=1,ACRONYM='$iniAcron' WHERE ID=$acronTblId";
					executeQuery($updateQry);
				break;
		}
	}
	else if ($rT=='ACR'){
		switch ($itemType){
				case 'Entity':
					$updateQry="UPDATE SCI_ACRONYM_ENTITY SET `NAME`='$nName',`ACRONYM`='$nAcron',`LAST_UPDATE`=NOW() WHERE ID=$acronTblId";
					executeQuery($updateQry);
					$updateDocL2="UPDATE  `DOC_LEVEL2` SET `name`='$nName',`code`='$nAcron' WHERE ac_entity_id=$acronTblId";
					executeQuery($updateDocL2);
					$msgTxt .="$itemType". ">>>"." $iniName  sucessfully updated";

				break;  
				case 'Department':
				 
					echo $updateQry="UPDATE SCI_ACRONYM_DEPARTMENT SET `NAME`='$nName',`ACRONYM`='$nAcron',`LAST_UPDATE`=NOW() WHERE ID=$acronTblId";
					executeQuery($updateQry);
										
					
					
					$r=checkExist('dept_name',$iniName,'SCI_DEPARTMENT');
					if ($r==0){
					$insSciDep="INSERT INTO `SCI_DEPARTMENT` (`dept_name`,department_code,`isDeleted`,`ac_dep_uid`)  (SELECT UPPER(`NAME`),`ACRONYM`,'0',ID FROM SCI_ACRONYM_DEPARTMENT WHERE ID=$acronTblId )";
					executeQuery($insSciDep);
					$msgTxt .="$itemType". ">>>"." $iniName  sucessfully created";
					$success=2;
					}
					else{
						//update SCI_DEPARTMENT
						$upSciDep="UPDATE `SCI_DEPARTMENT` SET `dept_name`='$nName',department_code='$nAcron' WHERE ac_dep_uid=$acronTblId ";
						executeQuery($upSciDep);
						$msgTxt .="Update, " ."$itemType". ">>>"." $iniName  already exsis in SCI_DEPARTMENT";
					    $success=2;

					}
					$resultSDI = executeQuery("select LAST_INSERT_ID() L");
					$ses_department_id=$resultSDI[1]['L'];
					//insert into DOC_LV3
						$r=checkExist('name',$iniName,'DOC_LV3');
					if ($r==0){
					$insDocL3="INSERT INTO `DOC_LV3` (`name`,is_department,ses_department_id,hod,`ac_department_id`)  (SELECT UPPER(`NAME`),1,'$ses_department_id',HOD,ID FROM SCI_ACRONYM_DEPARTMENT WHERE ID=$acronTblId )";
					//die($insDocL3);
					executeQuery($insDocL3);
					$msgTxt .="$itemType". ">>>"." $iniName  sucessfully created";
					$success=2;
					}
					else{
						//update DOC_LV3
						echo $insDocL3="UPDATE `DOC_LV3` SET `name`='$nName' WHERE ac_department_id=$acronTblId";
						executeQuery($insDocL3);
						$msgTxt .="Update , " ."$itemType". ">>>"." $iniName  already exsis in DMS(DOC_LV3)";
					    $success=2;

					}
					
					
					
					
					
					
					
				break;
				case 'Division':
					$updateQry="UPDATE SCI_ACRONYM_DIVISION SET `NAME`='$nName',`ACRONYM`='$nAcron',`LAST_UPDATE`=NOW() WHERE ID=$acronTblId";
					executeQuery($updateQry);
					$msgTxt .="$itemType". ">>>"." $iniName  sucessfully updated";

				break;
				case 'Project':
					$updateQry="UPDATE SCI_ACRONYM_PROJECT SET `NAME`='$nName',`ACRONYM`='$nAcron',`LAST_UPDATE`=NOW() WHERE ID=$acronTblId";
					executeQuery($updateQry);
					$msgTxt .="$itemType". ">>>"." $iniName  sucessfully updated";

				break;
		}
	}
	else if ($rT=='ADR'){
		switch ($itemType){
				case 'Entity':
					$updateQry="UPDATE SCI_ACRONYM_ENTITY SET `IS_DELETED`=1,`LAST_UPDATE`=NOW() WHERE ID=$acronTblId";
					executeQuery($updateQry);
					$updateDocL2="UPDATE  `DOC_LEVEL2` SET `is_deleted`=1  WHERE ac_entity_id=$acronTblId";
					executeQuery($updateDocL2);
					//update deletion all department under this entity
					$updateQry="UPDATE SCI_ACRONYM_DEPARTMENT SET `IS_DELETED`=1,`LAST_UPDATE`=NOW() WHERE ENTITY_ID=$acronTblId";
					executeQuery($updateQry);
					//update deletion all division under this entity
					$updateQry="UPDATE SCI_ACRONYM_DIVISION SET `IS_DELETED`=1,`LAST_UPDATE`=NOW() WHERE ENTITY_ID=$acronTblId";
					executeQuery($updateQry);
					//update deletion all project under this entity
					$updateQry="UPDATE SCI_ACRONYM_PROJECT SET `IS_DELETED`=1,`LAST_UPDATE`=NOW() WHERE ENTITY_ID=$acronTblId";
					executeQuery($updateQry);
					
					$msgTxt .="$itemType". ">>>"." $iniName  sucessfully removed";

				break;  
				case 'Department':
					$updateQry="UPDATE SCI_ACRONYM_DEPARTMENT SET `IS_DELETED`=1,`LAST_UPDATE`=NOW() WHERE ID=$acronTblId";
					executeQuery($updateQry);
					//update SCI_DEPARTMENT
					$upSciDep="UPDATE `SCI_DEPARTMENT` SET `isDeleted`=1  WHERE ac_dep_uid=$acronTblId ";
					executeQuery($upSciDep);
					
					//update DOC_LV3
					$insDocL3="UPDATE `DOC_LV3` SET `name`='$nName' WHERE ac_department_id=$acronTblId";
					executeQuery($insDocL3);
					//update deletion all division under this department
					$updateQry="UPDATE SCI_ACRONYM_DIVISION SET `IS_DELETED`=1,`LAST_UPDATE`=NOW() WHERE DEPARTMENT_ID=$acronTblId";
					executeQuery($updateQry);
					//update deletion all project under this department
					$updateQry="UPDATE SCI_ACRONYM_PROJECT SET `IS_DELETED`=1,`LAST_UPDATE`=NOW() WHERE DEPARTMENT_ID=$acronTblId";
					executeQuery($updateQry);
					$msgTxt .="$itemType". ">>>"." $iniName  sucessfully removed";

					
				break;
				case 'Division':
					$updateQry="UPDATE SCI_ACRONYM_DIVISION SET `IS_DELETED`=1,`LAST_UPDATE`=NOW() WHERE ID=$acronTblId";
					executeQuery($updateQry);
					//update deletion all project under this project
					$updateQry="UPDATE SCI_ACRONYM_PROJECT SET `IS_DELETED`=1,`LAST_UPDATE`=NOW() WHERE DIVISION_ID=$acronTblId";
					executeQuery($updateQry);
					$msgTxt .="$itemType". ">>>"." $iniName  sucessfully removed";

				break;
				case 'Project':
					$updateQry="UPDATE SCI_ACRONYM_PROJECT SET `IS_DELETED`=1,`LAST_UPDATE`=NOW() WHERE ID=$acronTblId";
					executeQuery($updateQry);
					$msgTxt .="$itemType". ">>>"." $iniName  sucessfully removed";

				break;
		}
		
	}
	
	$qryUpdateDate="UPDATE `SCI_ACRONYM_REQUEST_ITEMS`	SET FINAL_STATUS=$success,DEPLOYED_DATE=NOW() WHERE APP_UID='$appId' AND ID='$rowId' ";
	executeQuery($qryUpdateDate);
}

function checkExist($nameField,$valField,$tbl){
	$qry="SELECT count(*) AS c FROM $tbl WHERE `$nameField`='$valField' ";
	$res=executeQuery($qry);
	$conut=$res[1]['c'];
	return $conut;
}
die($msgTxt);

