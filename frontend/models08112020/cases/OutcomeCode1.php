<?php

namespace frontend\models\cases;

use Yii;

/**
 * This is the model class for table "outcome_code1".
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
  * @property int $updated_by
 * @property string $creation_datetime
 * @property string $last_modified_datetime
 * @property int $deleted
 *
 * @property InboundInteraction[] $inboundInteractions
 * @property OutboundInteraction[] $outboundInteractions
 */
class OutcomeCode1 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'outcome_code1';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'deleted','inbound','updated_by'], 'integer'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInboundInteractions()
    {
        return $this->hasMany(InboundInteraction::className(), ['outcome_code_1' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOutboundInteractions()
    {
        return $this->hasMany(OutboundInteraction::className(), ['outcome_code_1' => 'id']);
    }
}
