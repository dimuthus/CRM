<?php header('Content-type: text/html; charset=utf-8'); ?>
<?php
//file location /opt/processmaker/workflow/engine/methods/reports
$path=realpath(__DIR__ . '/../../cases');
require_once($path."/dbcon.php");
$lib=realpath(__DIR__ . '/../');
require_once($lib."/libs.php"); // contans js files and other common files
global $dbo;
//==============================================================================================================
$iconLocation ='http://10.1.27.231:60/skins/neoclassic/images/';
//error_reporting(0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<style>
/* F58020  dep_select */
.containner { width:99%;}
.doc_header{ background-color:#0099FF; height:35px ; width:100%;}
.doc_hdr_text{ display:inline; line-height:35px; }
.doc_leftMenu{width:250px;background-color:#F9F9F9; border:1px solid #CCCCCC; border-radius:3px;float:left; height:100%;}
.style2 {display: inline; line-height: 35px; font-weight: bold; }
.doc_list_cover{width:600px; border:5px solid #999999;display : table; padding:5px; }
.doc_list{width:500px;}
.menu_left { font-family:Geneva, Arial, Helvetica, sans-serif; font-size:12px; padding:3px;}
.menu_left:hover { background-color:#D8F2F5; cursor:pointer;}
.DocumentTable{font-family:Geneva, Arial, Helvetica, sans-serif; font-size:12px; margin-bottom:10px; border-collapse:collapse; border:1px solid #CCCCCC ;}
.DocumentTable th { background-color: #DFDFDF;  border:1px solid #CCCCCC;}
.DocumentTable td {   border:1px solid #CCCCCC;}
.SubmenuList{font-family:Geneva, Arial, Helvetica, sans-serif; font-size:11px; border-collapse :collapse ; cursor:pointer;}
.SubmenuList:hover{ background-color:#FAEBBC;}
.SubMenuLevel4{ width:230px; display:none;}
.div_level3_cover {width:230px; background-color:#F8F7CD; display:none;}
.div_level4_cover {width:230px; /* background-color:#00CC33;display:none;*/}
.div_level3 { padding-left: 5px; cursor:pointer;}
.menu_hdr{font-size: 11px; 
	color:#FFFFFF; 
	font-family:"Open Sans",Tahoma,sans-serif,MiscFixed; 
	height:25px;  
	text-shadow:0px 1px 2px #585858; 
	background-color:#164C88;
	line-height:24px; }
#tree{ border:1px solid #164C88 !important; padding-left:0 !important; overflow:hidden; background-color:FFFFE0  !important; position:fixed;float: left;width:300px; z-index:1; 
box-shadow:0px 6px 15px -5px #666;}
#tree li {
font-family:"Open Sans",Tahoma,sans-serif,MiscFixed ;
 color:#333333 !important; 
font-size:11px; margin-top:5px; background-color:FFFFE0; }
.mainMenu { }
.dep_select{ font-family:"Open Sans",Tahoma,sans-serif,MiscFixed ; font-size:11px; height:20px;vertical-align: middle; padding-left:10px;padding-top:5px; width:600%;
border-bottom:1px solid #D3D3D3; }
.dep_select:hover { background-color:#164C88 ;color:#FFFFFF; cursor:pointer;  }
clsDocType { font-family:"Open Sans",Tahoma,sans-serif,MiscFixed ; font-size:11px; height:20px;vertical-align: middle; padding-left:10px;padding-top:5px; }
.clsDocType:hover { background-color:#164C88 ;color:#FFFFFF; cursor:pointer; }
</style>

<script>
/*
$(document).ready(function(){

$("#tree").tree({
	   collapsible:true,
	   collapseEffect:'blind',
		collapseDuration: 100,
    	expandDuration: 100,
    	dnd: false
    });
	
	});*/

$(document).ready(function(){
    $(".mainMenu").click(function(){
        $("p").toggle(100);
    });
});

function showDocument(doc_role_id,L2,L3,L4,L5){
	$.post("doc_show.php",{'doc_role_id':doc_role_id,'LV2':L2,'LV3':L3,'LV4':L4,'LV5':L5},function (res){
	//document.write ("doc_show.php?doc_role_id="+ doc_role_id + "&L2="+ L2 +"&L3="+ L3 + "&L4="+L4+"&L5="+L5);
	$('#docsLists').html(res);
	$('.docTable').DataTable();
	
	});

}

function avtiveSelect(id)
{
//alert('PUKAAA');
//var x = document.getElementsByClassName("dep_select");
var x = $(".dep_select");
var i;
for (i = 0; i < x.length; i++) {
    x[i].style.backgroundColor = "";
	 //x[i].style.color = "#000";
}

document.getElementById(id).style.backgroundColor ='#F58020';
//document.getElementById(id).style.color ='#FFFFFF';
}


</script>

<body>
<div class="containner">

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="300" align="left" valign="top" scope="row" style="min-width:300px;">
	<!-- / menu / -->
 <div id="tree" style="" >
 
 <?php 
 // this code change has been added on 17/12/2019
 $qryEntity="SELECT  `DOC_LEVEL2`.`name` FROM `DOC_LEVEL2` WHERE level1_id=2  AND `DOC_LEVEL2`.`name` IN ('SCICOM (MSC) BERHAD','SCICOM ACADEMY','SCICOM LANKA') ";
 //$qryEntity="SELECT  `DOC_LEVEL2`.`name` FROM `DOC_LEVEL2` WHERE level1_id=2  AND `DOC_LEVEL2`.`name` IN ('SCICOM (MSC) BERHAD','SCICOM ACADEMY','SCICOM LANKA') ";

 $entityDoc=$dbo->prepare($qryEntity);
 $entityDoc->execute();	
 foreach($entityDoc->fetchAll() as $e){ ?>
	 
	<div class="menu_hdr" align="center"><?php echo  $e['name'] ?></div>

 
 

<?php


	$userId = isset( $_SESSION['USER_LOGGED'] ) ? $_SESSION['USER_LOGGED'] : 0;
	$sql = "SELECT doc_role_id FROM DOC_ROLE_USERS WHERE user_uid =".$userId." ";
//	echo  $sql;
	$policyDoc=$dbo->prepare($sql);
    $policyDoc->execute();	
	$doc_role_id="";
	foreach($policyDoc->fetchAll() as $getData){
	if($doc_role_id!="")
	$doc_role_id=$doc_role_id.", ";
	$doc_role_id = $doc_role_id.$getData['doc_role_id'];
	
	}
	
	if ($e['name']=='SCICOM ACADEMY'){
		 $sql="SELECT `DOC_LV3`.`name` AS doc_level2 FROM DOC_LV3 WHERE `ses_department_id`=3 ";
	}
    else{
	$sql="SELECT
		 DISTINCT `DOC_MASTER`.`doc_level2`
		 FROM
		`DOC_MASTER`
		INNER JOIN `DOC_D_PREMISSION` 
        ON (`DOC_MASTER`.`resource_id` = `DOC_D_PREMISSION`.`resource_id`)
        WHERE   `DOC_MASTER`.is_deleted=0 AND `DOC_D_PREMISSION`.role_id IN ($doc_role_id) AND `DOC_MASTER`.`doc_level2` !='SCICOM ACADEMY' ORDER BY `DOC_MASTER`.`doc_level2`  ";
    }
// echo 	$sql ;
				$displayStrings="";
				$displayDepartmentLabel="";
				$docLevelTwo="";
				$res2=$dbo->prepare($sql);
				$res2->execute();
			    $countChild=$res2->rowCount();
				if ($countChild >0)
				{
				foreach($res2->fetchAll() as $row2){
				$addCollapsed="collapsed";
				$displayDepartmentLabel=$row2['doc_level2'];
				
				//Exception for SCICOM ACADEMY
				if ($e['name']=='SCICOM ACADEMY'){
					$docLevelTwo='SCICOM ACADEMY';
				}
				else
				{
					$docLevelTwo=$row2['doc_level2'];
				}
				//++++check displaylevel4+++start
				$qryForL4="SELECT doc_level4 FROM DOC_MASTER INNER JOIN `DOC_D_PREMISSION`   ON (`DOC_MASTER`.`resource_id` = `DOC_D_PREMISSION`.`resource_id`) WHERE `DOC_D_PREMISSION`.role_id  IN ($doc_role_id)   AND `DOC_MASTER`.is_deleted=0 AND `DOC_MASTER`.`doc_level2`='$docLevelTwo' AND (doc_level4 is not NULL or doc_level4!='') ORDER BY `DOC_MASTER`.`doc_level4`  ";
				$resForL4=$dbo->prepare($qryForL4);
				$resForL4->execute();
			    $countresForL4=$resForL4->rowCount();
				//+++++++++++++++++++++++++++countForL5 start
				if ($countresForL4 >0)
				{
					echo  "
					<label id='lbl_".$row2['doc_level2']."' >
					<div id='select_".$row2['doc_level2']."' class='dep_select' onclick='avtiveSelect(this.id);showDocument(\"".$doc_role_id."\",\"".$docLevelTwo."\",0,0,0)'>
					<span>".$displayDepartmentLabel."</span>
					</div>
					</label><br/>";
										
				
				}
			
				
				}
				}
			//	die(3345);
				
				
			
 ?>
 
  <?php } ?>
 </div>
 	<!-- / menu / -->
	</td>
    <td valign="top">
	
<!-- / document frame /*  height:600px;overflow-y:auto;*// -->
	

 <div id='docsLists' style="float:left; margin-left:10px; min-width:700px; width:100%; ">
 <?php $displayStrings ?>
 </div>
<!-- / document frame / -->	
	</td>
  </tr>
</table>

</div>
</body>
</html>
