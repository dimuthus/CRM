<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Inbound_details".
 *
 * @property integer $id
 * @property string $inbound_interaction_id
 * @property string $customer_id
 * @property string $case_id
 * @property string $call_type
 * @property string $outcome_code_1
 * @property string $outcome_code_2
 * @property string $outcome_code_3
 * @property string $outcome_code_3_desc
 * @property string $created_by
 * @property string $created_datetime
 */
class InboundDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Inbound_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['inbound_interaction_id'], 'required'],
            [['outcome_code_3_desc'], 'string'],
            [['created_datetime'], 'safe'],
            [['inbound_interaction_id', 'customer_id', 'call_type'], 'string', 'max' => 11],
            [['case_id'], 'string', 'max' => 20],
            [['outcome_code_1', 'outcome_code_2', 'outcome_code_3'], 'string', 'max' => 250],
            [['created_by'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inbound_interaction_id' => 'Inbound Interaction ID',
            'customer_id' => 'Customer ID',
            'case_id' => 'Case ID',
            'call_type' => 'Call Type',
            'outcome_code_1' => 'Outcome Code 1',
            'outcome_code_2' => 'Outcome Code 2',
            'outcome_code_3' => 'Outcome Code 3',
            'outcome_code_3_desc' => 'Outcome Code 3 Desc',
            'created_by' => 'Created By',
            'created_datetime' => 'Created Datetime',
        ];
    }
}
