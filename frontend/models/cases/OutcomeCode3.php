<?php

namespace frontend\models\cases;

use Yii;

/**
 * This is the model class for table "outcome_code3".
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property string $creation_datetime
 * @property string $last_modified_datetime
 * @property int $deleted
 * @property int $outcome_code1_id
 * @property int $outcome_code2_id
 *
 * @property InboundInteraction[] $inboundInteractions
 * @property OutboundInteraction[] $outboundInteractions
 */
class OutcomeCode3 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'outcome_code3';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'deleted', 'outcome_code1_id', 'outcome_code2_id','inbound','updated_by'], 'integer'],
            [['creation_datetime', 'last_modified_datetime'], 'safe'],
            [['name'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_by' => 'Created By',
            'creation_datetime' => 'Creation Datetime',
            'last_modified_datetime' => 'Last Modified Datetime',
            'deleted' => 'Deleted',
            'outcome_code1_id' => 'Outcome Code1 ID',
            'outcome_code2_id' => 'Outcome Code2 ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInboundInteractions()
    {
        return $this->hasMany(InboundInteraction::className(), ['outcome_code_3' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOutboundInteractions()
    {
        return $this->hasMany(OutboundInteraction::className(), ['outcome_code_3' => 'id']);
    }
}
