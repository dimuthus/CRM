<?php
if (! class_exists( 'pmFunctions' )) {
    G::LoadClass( 'pmFunctions' );
}
$status='ACTIVE';
if(isset($_POST['status'])) $status=$_POST['status'];
global $dbo;
$appUid  =isset($_SESSION['APPLICATION']) ? $_SESSION['APPLICATION']:0;
$userId=$_SESSION['USER_LOGGED'];


$query="SELECT
    `SPK_PWDS`.`pwd`,
    `SPK_PWDS`.`dept_id`,
	`SPK_PWDS`.username
FROM
   `SPK_PWDS`
 WHERE   `SPK_PWDS`.`dept_id` IN 
 (SELECT `SCI_USERTABLE`.`department_id` FROM  SCI_USERTABLE WHERE `SCI_USERTABLE`.`USR_UID`='$userId' AND  SCI_USERTABLE.`USR_STATUS`='ACTIVE' ) 
 AND `SPK_PWDS`.`is_used`=0 ORDER BY RAND() LIMIT 1 ";
//echo ($query.$userId);

$res=executeQuery($query);
if ($res){
$pwd=$res[1]['pwd'];
$dept_id=$res[1]['dept_id'];
$username=$res[1]['pwd'];
executeQuery("UPDATE SPK_PWDS SET is_used=1 WHERE dept_id=$dept_id AND pwd='$pwd' AND username='$username' ");
executeQuery("INSERT INTO SPK_PWDS_TRACK (user_uid,is_used) VALUES ('$userId',1)");
die("Your generated password is:".$pwd);

}
else{
	die('All password tokens has been issued for your department. You may contact SPEAKE admin');
}



