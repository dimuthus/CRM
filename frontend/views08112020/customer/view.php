<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model frontend\models\Customer */
$outboud = 0;
$campId=0;
if(isset($_GET['outbnd'])) {
  $outboud = $_GET['outbnd'];
}
if(isset($_GET['campId'])) {
  $campId = $_GET['campId'];
}
?>
<div class="customer-view">
    <table class="layout-container">
        <tr>
            <td>
                 <?= $this->render('details', [
                    'model' => $model,
                ]) ?>
            </td>
	<?php if ($outboud==100){ ?>
             <td>
                   <?= $this->render(Url::to('../cases/indexout'), [
                    'cases' => $cases,
                    'model' => $model,
                    'case_id'=>'0',
                    'case_to_load' => $case_to_load,
                    'customer_id'=>$customer_id,
                    'campId'=> $campId
                ]) ?>
            </td>
           <?php  } else { ?>
             <td>
                <?= $this->render(Url::to('../cases/index'), [
                    'cases' => $cases,
                    'model' => $model,
                    'case_to_load' => $case_to_load,
                    'customer_id'=>$customer_id,
                    'campId'=> $campId
                ]) ?>
            </td>
           <?php } ?>
		</tr>
        <tr>
            <td>
             <?= $this->render(Url::to('../product/index'), [
                    'products' => $products,
                    'model' => $model,
                    'case_to_load' => $case_to_load,
                ]) ?> 
            </td>
          
       
   <?php if ($outboud==100){ ?>
            <td>
                <?=
                $this->render(Url::to('../outbound-interaction/index'), [
                    'interactions' => $outboundinteractions,
                    'model' => $model,
                    'customer_id'=>$customer_id,
                   
            //  'survey_response_id'=>$survey_response_id,
                ])?>
            </td>
          <?php } else { ?>
              <td>
                  <?=
                  $this->render(Url::to('../inbound-interaction/index'), [
                      'interactions' => $interactions,
                      'model' => $model,
                  ])?>
              </td>
          <?php  } ?>
        </tr>
    </table>

</div>
