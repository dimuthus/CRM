<?php

namespace frontend\models\cases;

use Yii;
use frontend\modules\tools\models\user\User;
use frontend\models\cases\OutcomeCode1;
use frontend\models\cases\OutcomeCode2;
use frontend\models\cases\OutcomeCode3;
use frontend\models\cases\CaseStatus;
use frontend\models\campaign\Campaign;
use frontend\models\cases\ChannelType;
use frontend\models\cases\SeverityLevel;
use frontend\models\customer\Customer;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * This is the model class for table "customer_cases".
 *
 * @property integer $id
 * @property string $case_id
 * @property integer $channel_type
 * @property integer $campaign
 * @property integer $case_category1
 * @property integer $case_category2
 * @property integer $case_category3
 * @property integer $case_status
 * @property string $case_note
 * @property string $case_field1
 * @property string $case_field2
 * @property string $case_field3
 * @property string $case_field4
 * @property string $case_field5
 * @property integer $created_by
 * @property string $created_datetime
 * @property integer $last_updated_by
 * @property string $last_updated_datetime
 * @property integer $escalated_to
 * @property integer $customer_id
 * @property integer $severity_level
 * @property string $call_back_date
 * @property string $attachment_url
 *
 * @property User $createdBy
 * @property Campaign $campaign0
 * @property OutcomeCode1 $caseCategory1
 * @property OutcomeCode2 $caseCategory2
 * @property OutcomeCode3 $caseCategory3
 * @property ChannelType $channelType
 * @property User $lastUpdatedBy
 * @property CaseStatus $caseCategory30
 * @property InboundInteraction[] $inboundInteractions
 * @property OutboundInteraction[] $outboundInteractions
 */
class CustomerCases extends \yii\db\ActiveRecord
{
   
    
    /**
     * @var UploadedFile
     */
    public $myfile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_cases';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['channel_type', 'case_category1', 'case_category2', 'case_category3','case_status'], 'required'],
            [['channel_type', 'campaign', 'case_category1', 'case_category2', 'case_category3', 'case_status','escalated_to', 'created_by', 'last_updated_by', 'customer_id', 'case_counter','severity_level','product_id','inbound_csat','outbound_csat'], 'integer'],
            [['created_datetime', 'last_updated_datetime','call_back_date','email_received_datetime','email_replied_datetime'], 'safe'],
           // [['case_id'], 'string', 'max' => 20],
            [['case_note','attachment_url','attachment_name'], 'string', 'max' => 250],
            [['case_field1', 'case_field2', 'case_field3', 'case_field4', 'case_field5','location_of_atm',
				'debit_card_number',
				'branch_department',
				'credit_card_number',
				'charge_disputed__note',
				'reference_number_of_application',
				'application_date',
				'new_credit_limit_requested',
				'current_credit_limit',
				'reason_for_change_in_credit_limit',
				'mobile_number_requested_on',
				'current_debit_limit',
				'new_debit_limit_requested',
				'reason_for_change_in_debit_limit',
				'charge_disputed',
				'product_interest_in',
				'responsible_officer',
				'TAT',
				'merchant_number',
				'amount',
				'mobile_device',
				'app_version',], 'string', 'max' => 200],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
           // [['campaign'], 'exist', 'skipOnError' => true, 'targetClass' => Campaign::className(), 'targetAttribute' => ['campaign' => 'id']],
            [['case_category1'], 'exist', 'skipOnError' => true, 'targetClass' => OutcomeCode1::className(), 'targetAttribute' => ['case_category1' => 'id']],
            [['case_category2'], 'exist', 'skipOnError' => true, 'targetClass' => OutcomeCode2::className(), 'targetAttribute' => ['case_category2' => 'id']],
            [['case_category3'], 'exist', 'skipOnError' => true, 'targetClass' => OutcomeCode3::className(), 'targetAttribute' => ['case_category3' => 'id']],
            [['channel_type'], 'exist', 'skipOnError' => true, 'targetClass' => ChannelType::className(), 'targetAttribute' => ['channel_type' => 'id']],
            [['last_updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['last_updated_by' => 'id']],
            [['case_status'], 'exist', 'skipOnError' => true, 'targetClass' => CaseStatus::className(), 'targetAttribute' => ['case_status' => 'id']],
            [['escalated_to'], 'escalatedRequired', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['call_back_date'], 'callBackRequired', 'skipOnEmpty' => false, 'skipOnError' => false],
	    [['call_back_date'], 'checkCallBackDateValidity', 'skipOnEmpty' => false, 'skipOnError' => false],
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
            'channel_type' => 'Channel Type',
            'campaign' => 'Campaign',
            'case_category1' => 'Case Category1',
            'case_category2' => 'Case Category2',
            'case_category3' => 'Case Category3',
            'case_status' => 'Case Status',
            'case_note' => 'Case Note',
            'case_field1' => 'Case Field1',
            'case_field2' => 'Case Field2',
            'case_field3' => 'Case Field3',
            'case_field4' => 'Case Field4',
            'case_field5' => 'Case Field5',
            'created_by' => 'Created By',
            'created_datetime' => 'Created Datetime',
            'last_updated_by' => 'Last Updated By',
            'last_updated_datetime' => 'Last Updated Datetime',
            'escalated_to' => 'Escalated To',
            'customer_id' => 'Customer ID',
            'case_counter' => 'Case Counter',
            'myfile'=>'Attachment',
            'attachment_name' => 'Attachment',
            'attachment_url' => 'Attachment Url',
        ];
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
    public function getCampaign0()
    {
        return $this->hasOne(Campaign::className(), ['id' => 'campaign']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseCategory1()
    {
        return $this->hasOne(OutcomeCode1::className(), ['id' => 'case_category1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseCategory2()
    {
        return $this->hasOne(OutcomeCode2::className(), ['id' => 'case_category2']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseCategory3()
    {
        return $this->hasOne(OutcomeCode3::className(), ['id' => 'case_category3']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannelType()
    {
        return $this->hasOne(ChannelType::className(), ['id' => 'channel_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'last_updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInboundInteractions()
    {
        return $this->hasMany(InboundInteraction::className(), ['case_tbl_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOutboundInteractions()
    {
        return $this->hasMany(OutboundInteraction::className(), ['case_tbl_id' => 'id']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseStatus()
    {
        return $this->hasOne(CaseStatus::className(), ['id' => 'case_status']);
    }
    
    	public function getCustomer()
	{
		return $this->hasOne(Customer::className(), ['id'=>'customer_id']);
	}
        
         /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeverityLevel()
    {
        return $this->hasOne(SeverityLevel::className(), ['id' => 'severity_level']);
    }
    
       public function getDocument($model)
    {

            $link = '';
                    $names = explode("#LRL#", $model->attachment_name);
                    $urls = explode("#LRL#", $model->attachment_url);
                    //print_r($urls);
                    $count=0;
                    if(!empty($names)){
                        foreach($names as $name)
                        {
                            $link .= Html::a($name, [ $urls[$count]],['target' => '_blank']). '<br />';
                        //echo $urls[$count];
                            $count=$count+1;
                        //echo $urls[$count];

                        }
                    }
                    //die(233);
                        return $link;
    }
    
       /**
     * @return \yii\db\ActiveQuery
     */
    public function getEscalatedTo()
    {
        return $this->hasOne(User::className(), ['id' => 'escalated_to']);
    }
    
    
        public function escalatedRequired($attribute_name, $params)
    {  
        
        // if (in_array($this->service_request_status, $this->escalation_statuses) && empty($attribute_name)) {
            // $this->addError($attribute_name, Yii::t('user', ""));
            // return false;
        // }
        if (($this->case_status==5) && empty($this->escalated_to)) {
            $this->addError($attribute_name, Yii::t('user', ""));
            return false;
        }
        return true;
    }
	
	public function callBackRequired($attribute_name, $params)
    {  
	
	   //die('nn'.$this->onsite_appointment_datetime);
        
        // if (in_array($this->service_request_status, $this->escalation_statuses) && empty($attribute_name)) {
            // $this->addError($attribute_name, Yii::t('user', ""));
            // return false;
        // }
	if (($this->case_status==3) && empty($this->call_back_date)) {
			
		$this->addError($attribute_name, Yii::t('user', ""));
          return false;
        }
        return true;
    }
	
	
	public function checkCallBackDateValidity($attribute_name, $params)
    {  
	
		if (($this->case_status==3) &&($this->call_back_date < date('Y-m-d h:i:s') )){
		    $this->addError($attribute_name, Yii::t('user', "Call back date can not be less than current data and time"));
            return false;
			
		}
		
    }
}
