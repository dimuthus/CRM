<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/*use frontend\models\customer\Customer;
use frontend\models\customer\CustomerContactType;
use frontend\models\customer\CustomerLanguage;
use frontend\models\customer\CustomerTitle;
use frontend\models\State;
use frontend\models\City;
/* @var $this yii\web\View */
/* @var $model frontend\models\Customer */

?>
<div class="customer-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
	    
             [ 'attribute'=>'cif', 'label'=>'CIF' ],
             [ 'attribute'=>'salutation.name', 'label'=>'Salutation' ],
            'full_name',
            'preferred_name',
            'new_nic',
            'old_nic',
            [                                                  // the owner name of the model
            'label' => 't-pin',
            'value' => '**********',            
            'contentOptions' => ['class' => 'bg-red'],     // HTML attributes to customize value tag
            'captionOptions' => ['tooltip' => 'Tooltip'],  // HTML attributes to customize label tag
        ],
            'marital_status',
            
           [ 'attribute'=>'gender0.gender', 'label'=>'Gender' ],

            'spouse_name',
            'passport',
            'driving_license',
            'business_name',
            'business_reg_number',
            'business_registered_date',
            'mobile_number',
            'staff_pf',
            'alternative_number',
            'email',
            'address1',
            'address2',
            'address3',
            'town',
            'district',
            'postal_code',
            'alternate_address1',
			'alternate_address2',
            'alternate_address3',
            'alternate_town',
            'alternate_district',
            'alternate_postal_code',
            [ 'attribute'=>'preferredLanguage.name', 'label'=>'Preferred Language' ],
            'customer_since',
            'citizenship',
            'profession',
            'employer',
            'dob',
            'branch',
            'relationship_manager',
        
              [ 'attribute'=>'customerStatus.name', 'label'=>'Status' ],
              [ 'attribute'=> 'customerType.name', 'label'=>'Type' ],
            'vip_flag',
			'group_code',
			'group_description',
            'creator.username',
            [
                'attribute'=>'created_datetime',
                'format'=>['date', 'php:d M y @ h:i a']
            ],
			'updator.username',
            [
                'attribute'=>'last_updated_datetime',
                'format'=>['date', 'php:d M y @ h:i a']
            ],
        ],
    ]) ?>

</div>
