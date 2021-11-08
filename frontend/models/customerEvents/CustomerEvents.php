<?php

namespace frontend\models\customerEvents;

use Yii;
use frontend\modules\tools\models\user\User;
use frontend\models\Events;
use frontend\models\State;
use yii\db\Query;



/**
 * This is the model class for table "inbound_interaction".
 *
 */


class CustomerEvents extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id','customer_id'], 'required'],
            [['customer_id','event_id','created_by','last_updated_by'], 'integer'],
            [['event_id','customer_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'Event ID',
            'customer_id' => 'Customer ID',
            'created_by' => 'Created By',
            'created_datetime' => 'Created Datetime',
            'last_updated_by' => 'Latest Updated By',
            'last_updated_datetime' => 'latest Updated Datetime',
            'eventId.event_name' => 'Event Name',
            'createdBy.username' => 'Created By',
            'lastUpdatedBy.username' => 'Latest Updated By',
            'eventType.event_type' => 'Event Type',
            'eventMonth.event_month' => 'Event Month',
            'eventYear.event_year' => 'Event Year',

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */

   

    public function getEventId()
    {
        //$this->setEventLocationID('$cap');
        return $this->hasOne(Events::className(), ['id' => 'event_id']);
    }

    public function getEventType()
    {
        return $this->hasOne(Events::className(), ['id' => 'event_id']);
    }

    public function getEventMonth()
    {
        return $this->hasOne(Events::className(), ['id' => 'event_id']);
    }


    public function getEventYear()
    {
        return $this->hasOne(Events::className(), ['id' => 'event_id']);
    }

	public function getLastUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'last_updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getcreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

}
