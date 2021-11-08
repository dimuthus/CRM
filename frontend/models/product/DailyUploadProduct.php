<?php

namespace frontend\models\product;

use Yii;
use yii\base\Model;

class DailyUploadProduct extends Model{
    public $file;

    public function rules(){
        return [
            [['file'],'required'],
			[['file'],'file','extensions'=>'csv','maxSize'=>1024 * 1024 * 25],
        ];
    }

    public function attributeLabels(){
        return [
            'file'=>'Select File',
			  ];
    }
}
