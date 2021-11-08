<?php

namespace frontend\models\report;

use Yii;

/**
 * This is the model class for table "customer_case_details".
 *
 * @property integer $id
 * @property string $case_id
 * @property string $customer_id
 * @property string $caller_type
 * @property string $product_replacement_type
 * @property string $product_replacement_status
 * @property string $replacement_delivery_method
 * @property string $replacement_delivery_status
 * @property string $product
 * @property string $purchased_at
 * @property string $called_from
 * @property string $transaction_type
 * @property string $case_status
 * @property string $hotline
 * @property string $awb
 * @property integer $total_box
 * @property integer $first_call_resolution
 * @property string $complain
 * @property string $callback
 * @property string $created_by
 * @property string $escalated_to
 * @property string $last_updated_by
 * @property string $last_updated_datetime
 */
class CustomerCaseDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_case_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'total_box', 'first_call_resolution'], 'integer'],
            [['case_id'], 'required'],
            [['callback', 'last_updated_datetime'], 'safe'],
            [['case_id', 'caller_type', 'replacement_delivery_status'], 'string', 'max' => 20],
            [['customer_id'], 'string', 'max' => 11],
            [['product_replacement_type', 'product_replacement_status', 'replacement_delivery_method', 'transaction_type'], 'string', 'max' => 25],
            [['product'], 'string', 'max' => 150],
            [['purchased_at', 'hotline', 'awb'], 'string', 'max' => 250],
            [['called_from'], 'string', 'max' => 30],
            [['case_status'], 'string', 'max' => 10],
            [['complain'], 'string', 'max' => 3],
            [['created_by', 'escalated_to', 'last_updated_by'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'case_id' => 'Case ID',
            'customer_id' => 'Customer ID',
            'caller_type' => 'Caller Type',
            'product_replacement_type' => 'Product Replacement Type',
            'product_replacement_status' => 'Product Replacement Status',
            'replacement_delivery_method' => 'Replacement Delivery Method',
            'replacement_delivery_status' => 'Replacement Delivery Status',
            'product' => 'Product',
            'purchased_at' => 'Purchased At',
            'called_from' => 'Called From',
            'transaction_type' => 'Transaction Type',
            'case_status' => 'Case Status',
            'hotline' => 'Hotline',
            'awb' => 'Awb',
            'total_box' => 'Total Box',
            'first_call_resolution' => 'First Call Resolution',
            'complain' => 'Complain',
            'callback' => 'Callback',
            'created_by' => 'Created By',
            'created_datetime' => 'Created Datetime',
            'escalated_to' => 'Escalated To',
            'last_updated_by' => 'Last Updated By',
            'last_updated_datetime' => 'Last Updated Datetime',
        ];
    }
}
