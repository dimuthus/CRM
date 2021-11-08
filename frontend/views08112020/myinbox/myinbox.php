<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\BaseYii;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

//var_dump($summary);
//$custID = $summary['id'];
//echo($custID);

//die ('dddd=');
//echo password_hash("jalis", PASSWORD_DEFAULT);
//$str ="1";
//echo 'IDDD='.Yii::$app->session->getId();

//var_dump(Yii::$app->session);

?>
<div id="dashboard-list">


    <?php \yii\widgets\Pjax::begin(['id' => 'dashboard-widget','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <?=
    
  
    
     GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => 'dashboard-list-widget',
        'layout'=>"{items}\n{summary}\n{pager}",
        'showFooter'=>TRUE,
        'footerRowOptions'=>['class'=>'dashboard-footer'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [

          'label'=> 'View Details',
          'format' => 'raw',
          'value' => function ($summary) {
              $customerIdAndCampainID=$summary['id']."_".$summary['campaign_id'];
              return '<div class="edit-request-icon" id="'. $customerIdAndCampainID.'" ><a data-id="'. $customerIdAndCampainID.'" data-name="'. $customerIdAndCampainID.'" class="glyphicon glyphicon-menu-hamburger"></a></div>';
          },

          ],

             [
                'attribute'=>'full_name',
                'label'=>'Customer Name',
                //'footer'=>$summary['total'],
            ],

            [
                'attribute'=>'identity',
                'label'=>'NRIC/Passport',
                //'footer'=>$summary['closed'],
            ],
            [
                'attribute'=>'email',
                'label'=>'Email Address',
                //'footer'=>$summary['open'],
            ],
            [
                'attribute'=>'name',
                 'label'=>'Campaign Name',
                //'footer'=>'Total:',
            ],
                     [
                'attribute'=>'campaign_id',
                 'label'=>'campaign_id',
                //'footer'=>'Total:',
            ],
            [
                'attribute'=>'username',
                 'label'=>'Assigned to',
                //'footer'=>'Total:',
            ],
                  

        ],

        'rowOptions'   => function ($summary, $key, $index, $grid) {
           $customerIdAndCampainID=$summary['id']."_".$summary['campaign_id'];
           return ['data-id' =>$customerIdAndCampainID];
       },

    ]); ?>
<?php
    $this->registerJs("

        /*
        listener to handle click event of view interaction item
        */

        $('#dashboard-list-widget tbody tr').click(function (e) {
                                                        if($(this).hasClass('active-request-row'))
                                                        return;
                                                        $(this).siblings().removeClass('active-request-row');
                                                        $(this).addClass('active-request-row');

                                                        e.preventDefault();

                                                      /*  var thisRow = $(this);
                                                        var id = $(this).data('id');

                                                        if(id != undefined) {
                                                        loadCustomerCase('".Url::to(['search/loadcustomercase'])."/'+id);
                                                      }*/
                                                        });

$('#dashboard-list-widget tbody .edit-request-icon a').click(function (e) {

  var thisRow = $(this);
  var id = $(this).data('id');
  var data_name =$(this).data('name');
  var cusIdAndCampId = id.split('_');
  var cusId=cusIdAndCampId[0];
  var campId=cusIdAndCampId[1];
  //alert(data_name);
  if(id != undefined) {
  loadCustomerCase('".Url::to(['search/loadcustomercaseout'])."/'+cusId+'?campId='+campId);
  }
  });


    ");
?>
  <?php \yii\widgets\Pjax::end(); ?>

</div>
