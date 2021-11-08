<?php

namespace frontend\models\outboundInteraction;

use frontend\models\campaign\Campaign;
use frontend\modules\survey\models\CrmSurveyResponse;
use frontend\modules\tools\models\user\User;
use frontend\models\cases\CustomerCases;
use frontend\models\cases\ChannelType;
use frontend\models\interaction\InteractionStatus;
use yii\db\Query;
use yii\data\ActiveDataProvider;



use Yii;

/**
 * This is the model class for table "outbound_interaction".
 *
 * @property int $id
 * @property string $outbound_interaction_id
 * @property int $campaign_id
 * @property int $call_type
 * @property int $outcome_code_1
 * @property int $outcome_code_2
 * @property int $outcome_code_3
 * @property string $outcome_code_3_desc
 * @property int $created_by
 * @property string $created_datetime
 *
 * @property Campaign $campaign
 * @property OutcomeCode1 $outcomeCode1
 * @property OutcomeCode2 $outcomeCode2
 * @property OutcomeCode3 $outcomeCode3
 * @property int $customer_id
 */
class OutboundInteraction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'outbound_interaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['case_tbl_id','interaction_status','channel_type'], 'integer'],
            [['case_tbl_id','interaction_status','channel_type'], 'required'],
            [['case_tbl_id', 'interaction_status', 'created_by', 'outbound_interaction_counter'], 'integer'],           
            [['created_datetime'], 'safe'],
            [['outbound_interaction_id'], 'string', 'max' => 20],
            //[['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            //[['case_tbl_id'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerCases::className(), 'targetAttribute' => ['case_tbl_id' => 'id']],
            //[['interaction_status'], 'exist', 'skipOnError' => true, 'targetClass' => InteractionStatus::className(), 'targetAttribute' => ['interaction_status' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
       return [
            'id' => 'ID',
            'outbound_interaction_id' => 'Outbound Interaction ID',
            'case_tbl_id' => 'Case Tbl ID',
            'interaction_status' => 'Interaction Status',
            'escalated_to' => 'Escalated To',
            'notes' => 'Notes',
            'created_by' => 'Created By',
            'created_datetime' => 'Created Datetime',
            'outbound_interaction_counter' => 'Outbound Interaction Counter',
          
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaignId()
    {
        
        $campain_name='';
        $result = CrmSurveyResponse::find()->where(['id'=>$this->campaign_id])->all();
        
        
        //print_r($dd->survey_id);
        
        if ($result!== null){
            $campainID = $result[0]['survey_id'];
            
             $result = Campaign::find()->where(['id'=>$campainID])->all();
             $campain_name = $result[0]['name'];
            
        }
        return $campain_name;
        //return $campaignquery->name;
    }

 
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInteractionStatus()
    {
        return $this->hasOne(InteractionStatus::className(), ['id' => 'interaction_status']);
    }
    
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
 /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannelType()
    {
        return $this->hasOne(ChannelType::className(), ['id' => 'channel_type']);
    }
}
