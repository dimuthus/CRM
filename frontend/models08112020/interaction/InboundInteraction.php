<?php

namespace frontend\models\interaction;

use Yii;
use frontend\modules\tools\models\user\User;
use frontend\models\cases\CustomerCases;
use frontend\models\cases\ChannelType;
/**
 * This is the model class for table "inbound_interaction".
 *
 * @property integer $id
 * @property string $inbound_interaction_id
 * @property integer $case_tbl_id
 * @property integer $interaction_status
 * @property integer $escalated_to
 * @property string $call_back_date
 * @property string $call_back_time
 * @property string $notes
 * @property integer $created_by
 * @property string $created_datetime
 * @property integer $inbound_interaction_counter
 * @property integer $severity_level
 * @property string $attachment_url
 *
 * @property User $createdBy
 * @property CustomerCases $caseTbl
 * @property InteractionStatus $interactionStatus
 * @property User $escalatedTo
 * @property SeverityLevel $severityLevel
 */
class InboundInteraction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inbound_interaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['case_tbl_id','interaction_status','channel_type'], 'required'],
            [['case_tbl_id', 'interaction_status', 'created_by', 'inbound_interaction_counter'], 'integer'],
            [[ 'created_datetime'], 'safe'],
            [['notes'], 'string'],
            [['inbound_interaction_id'], 'string', 'max' => 20],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['case_tbl_id'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerCases::className(), 'targetAttribute' => ['case_tbl_id' => 'id']],
            [['interaction_status'], 'exist', 'skipOnError' => true, 'targetClass' => InteractionStatus::className(), 'targetAttribute' => ['interaction_status' => 'id']],
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
            'case_tbl_id' => 'Case Tbl ID',
            'interaction_status' => 'Interaction Status',
            'channel_type' => 'Channel Type',
             'notes' => 'Notes',
            'created_by' => 'Created By',
			'creator.username' => 'Created by', 
            'created_datetime' => 'Created Datetime',
            'inbound_interaction_counter' => 'Inbound Interaction Counter',
    
        ];
    }


  public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseTbl()
    {
        return $this->hasOne(CustomerCases::className(), ['id' => 'case_tbl_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInteractionStatus()
    {
        return $this->hasOne(InteractionStatus::className(), ['id' => 'interaction_status']);
    }

  
 /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannelType()
    {
        return $this->hasOne(ChannelType::className(), ['id' => 'channel_type']);
    }
}
