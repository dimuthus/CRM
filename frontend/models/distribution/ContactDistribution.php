<?php

namespace frontend\models\distribution;

use Yii;
use frontend\models\campaign\Campaign;
/**
 * This is the model class for table "contact_distribution".
 *
 * @property int $id
 * @property int $agent_id
 * @property int $customer_id
 * @property int $distributed_by
 * @property string $distributed_date
 * @property int $campaign_id
 *
 * @property Campaign $campaign
 */



class ContactDistribution extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $agent_ids;

    public static function tableName()
    {
        return 'contact_distribution';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agent_id', 'customer_id', 'distributed_by', 'campaign_id', 'no_of_customers'], 'integer'],
            [['distributed_date'], 'safe'],
			[['no_of_customers'], 'required'],
            [['campaign_id'], 'exist', 'skipOnError' => true, 'targetClass' => Campaign::className(), 'targetAttribute' => ['campaign_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'agent_id' => 'Agent ID',
            'customer_id' => 'Customer ID',
            'distributed_by' => 'Distributed By',
            'distributed_date' => 'Distributed Date',
            'campaign_id' => 'Campaign ID',
			'no_of_customers' => 'No of customers to be assigned',
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
