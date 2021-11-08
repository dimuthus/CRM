<?php
//use this url to read ->>>>>http://10.1.27.108:88/sysworkflow/en/classic/cases/leaveBalance.php
//file location /opt/processmaker/workflow/engine/methods/cases
require_once ("dbcon.php");

global $dbo;

$query="SELECT SCI_USERTABLE.USR_UID,SCI_USERTABLE.full_name ,SCI_USERTABLE.icno,SCI_USERTABLE.nt_userlogin,RBAC_USERS.`USR_EMAIL` AS email_add FROM SCI_USERTABLE 
INNER JOIN RBAC_USERS ON
SCI_USERTABLE.`USR_UID`=RBAC_USERS.`USR_UID` ";
//echo ($query.$userId);
$sth = $dbo->prepare($query);
$sth->bindParam(':application_id', $application_id, PDO::PARAM_STR);
$sth->execute();
//$rows = $sth->fetchAll();
echo "<table border='1' style='width:100%'  class='display' id='tblUsers'><thead style='background-color:#1546b0; color: #ffffff;'><th>Seq .No</th><th>Full Name</th><th>NRIC/PASSPORT</th><th>User Name:</th><th>Email</th><th></th><th></th></thead><tbody>";
$BoxColor="";
$i=0;
foreach ($sth->fetchAll() as $rows){
	
		$i++;
		$email=$rows['email_add'];
		$full_name=$rows['full_name'];
		$full_name = str_replace("'", '', $full_name);
        echo "<tr>";
		echo "<td style='background-color:#fef8f2; '>".$i."</td>";
        echo '<td style="background-color:#eff3fe; ">'.$rows['full_name'].'</td>';
		echo "<td style='background-color:#fef8f2; '>".$rows['icno']."</td>";
		echo "<td style='background-color:#fef8f2; '>".$rows['nt_userlogin']."</td>";
		echo "<td style='background-color:#fef8f2; ' id='td_".$i."'>".$email."</td>";
	    echo "<td style='background-color:#fef8f2; '><input type='button' value='Update Email' id='update_email' onclick=\"updateEmail(".$i.","."'".$rows['USR_UID']."'".","."'".$email."'".")\" /></td>";
		echo "<td style='background-color:#fef8f2; '><input type='button' value='Reset Password' id='reset_password' onclick=\"resetPassword("."'".$rows['USR_UID']."'".","."'".$full_name."'".")\" /></td>";
		echo "</tr>";
		}
echo "</tbody></table>";

?>
