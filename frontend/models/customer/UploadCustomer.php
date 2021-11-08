<?php

namespace frontend\models\customer;

use Yii;
use yii\base\Model;

class UploadCustomer extends Model{
    public $file;
    public $campaign_id;

    public function rules(){
        return [
            [['file','campaign_id'],'required'],
			      [['file'],'file','extensions'=>'csv','maxSize'=>1024 * 1024 * 25],
        ];
    }

    public function attributeLabels(){
        return [
            'file'=>'Select File',
			  ];
    }
}
