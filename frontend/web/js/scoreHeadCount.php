<?php
$lib=realpath(__DIR__ . '/../');
require_once($lib."/libs.php");
?>
<div class='loader' alt='Loading...'></div>
<div id='DBdiv'></div>
<script type="text/javascript">
   $.post("scoreHeadCountExecution.php",{},function (res){
	   $('.loader').hide();
		$('#DBdiv').html(res);
   } );
</script>