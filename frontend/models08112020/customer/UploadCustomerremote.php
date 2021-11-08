<?php

namespace frontend\models\customer;

use Yii;
use yii\base\Model;

class UploadCustomerremote extends Model{
    public $file;
    public $campaign_id;

    public function rules(){
        return [
            [['file'],'required'],
			      [['file'],'file','extensions'=>'xlsx,xls','maxSize'=>1024 * 1024 * 25],
        ];
    }

    public function attributeLabels(){
        return [
            'file'=>'Select File',
			  ];
    }
}
