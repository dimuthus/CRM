<?php

namespace frontend\models\campaign;

use Yii;

/**
 * This is the model class for table "customer_campaign".
 *
 * @property int $id
 * @property int $campaign_id
 * @property int $customer_id
 * @property int $created_by
 * @property string $created_datetime
 * @property int $last_updated_by
 * @property string $last_updated_datetime
 * @property bool $campaign_successfull
 *
 * @property Campaign $campaign
 */
class CustomerCampaign extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_campaign';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campaign_id', 'customer_id', 'created_by', 'last_updated_by'], 'integer'],
            [['created_datetime', 'last_updated_datetime'], 'safe'],
            [['campaign_successfull'], 'boolean'],
            [['campaign_id','customer_id'], 'required'],
            [['campaign_id','customer_id'], 'unique', 'message' => 'Customer already exists in campaign.', 'targetAttribute' => ['campaign_id','customer_id']],
            //[['campaign_id'], 'unique']

           // [['campaign_id'], 'exist', 'skipOnError' => true, 'targetClass' => Campaign::className(), 'targetAttribute' => ['campaign_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'campaign_id' => 'Campaign ID',
            'customer_id' => 'Customer ID',
            'created_by' => 'Created By',
            'created_datetime' => 'Created Datetime',
            'last_updated_by' => 'Last Updated By',
            'last_updated_datetime' => 'Last Updated Datetime',
            'campaign_successfull' => 'Campaign Successfull',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaign()
    {
        return $this->hasOne(Campaign::className(), ['id' => 'campaign_id']);
    }
}
