<?php

use yii\helpers\Html;

?>
<div style="padding:30px;">
<table>
	<tr>
		<td style="width:45%">
		    <?php
		    echo Html::textInput('search_av', '', ['placeholder'=>'Available','class' => 'form-control role-search', 'data-target' => 'avaliable']) . '<br>';
		    echo Html::listBox('roles', '', $avaliable, [
		        'id' => 'avaliable',
		        'multiple' => true,
		        'size' => 20,
		        'style' => 'width:100%']);
		    ?>
		</td>
		<td style="width:10%; text-align:center">
			&nbsp;<br><br>
		    <?php
		    echo Html::a('>>', '#', ['class' => 'btn btn-success', 'data-action' => 'assign']) . '<br>';
		    echo Html::a('<<', '#', ['class' => 'btn btn-success', 'data-action' => 'delete']) . '<br>';
		    ?>
		</td>
		<td style="width:45%">
		    <?php
		    echo Html::textInput('search_asgn', '', ['placeholder'=>'Assigned','class' => 'form-control role-search', 'data-target' => 'assigned']) . '<br>';
		    echo Html::listBox('roles', '', $assigned, [
		        'id' => 'assigned',
		        'multiple' => true,
		        'size' => 20,
		        'style' => 'width:100%']);
		    ?>
		</td>
	</tr>
</table>
</div>
<?php
if(isset($model))
	$this->render('_script',['name'=>$model->name]);
