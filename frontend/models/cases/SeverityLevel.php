<?php

namespace frontend\models\cases;

use Yii;

/**
 * This is the model class for table "severity_level".
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_by
 * @property string $creation_datetime
 * @property string $last_modified_datetime
 * @property integer $deleted
 *
 * @property InboundInteraction[] $inboundInteractions
 * @property OutboundInteraction[] $outboundInteractions
 */
class SeverityLevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'severity_level';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'deleted','updated_by'], 'integer'],
            [['creation_datetime', 'last_modified_datetime'], 'safe'],
            [['name'], 'string', 'max' => 10],
			            [['name'], 'required', 'message'=>'']

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
        return $this->hasMany(InboundInteraction::className(), ['severity_level' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOutboundInteractions()
    {
        return $this->hasMany(OutboundInteraction::className(), ['severity_level' => 'id']);
    }
}
