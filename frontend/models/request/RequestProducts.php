<?php

namespace frontend\models\request;

use Yii;

/**
 * This is the model class for table "service_request_products".
 *
 * @property integer $id
 * @property integer $request_id
 * @property integer $product_id
 */
class RequestProducts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_request_products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','request_id', 'product_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'request_id' => 'Request ID',
            'product_id' => 'Product ID',
        ];
    }
}
