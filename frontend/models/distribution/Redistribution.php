<?php

namespace frontend\models\distribution;

use Yii;

/**
 * This is the model class for table "redistribution".
 *
 * @property int $id
 * @property int $agent_id
 * @property int $customer_id
 * @property int $distributed_by
 * @property string $distributed_date
 */
class Redistribution extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'redistribution';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agent_id', 'customer_id', 'distributed_by'], 'integer'],
            [['distributed_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'agent_id' => 'Assign to Agent',
            'customer_id' => 'Customer ID',
            'distributed_by' => 'Distributed By',
            'distributed_date' => 'Distributed Date',
        ];
    }
}
