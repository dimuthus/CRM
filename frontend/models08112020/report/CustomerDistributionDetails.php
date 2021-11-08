<?php

namespace frontend\models\report;

use Yii;

/**
 * This is the model class for table "customer_distribution_details".
 *
 * @property integer $id
 * @property string $customer_id
 * @property string $campaign
 * @property string $distributed_to
 * @property string $distributed_by
 * @property string $distributed_date
 */
class CustomerDistributionDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_distribution_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['distributed_date'], 'safe'],
            [['customer_id'], 'string', 'max' => 11],
            [['campaign'], 'string', 'max' => 250],
            [['distributed_to', 'distributed_by'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'campaign' => 'Campaign',
            'distributed_to' => 'Distributed To',
            'distributed_by' => 'Distributed By',
            'distributed_date' => 'Distributed Date',
        ];
    }
}
