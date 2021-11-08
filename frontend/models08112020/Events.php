<?php

namespace frontend\models;
use frontend\models\State;

use Yii;

/**
 * This is the model class for table "events".
 *
 * @property string $id
 * @property string $name
 * @property integer $created_by
 * @property string $creation_datetime
 * @property string $last_modified_datetime
 * @property integer $deleted
 */
class Events extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'is_deleted','event_location'], 'integer'],
            //[['creation_datetime', 'last_modified_datetime'], 'required'],
            [['event_name'], 'string', 'max' => 250],
            [['event_type'], 'string', 'max' => 50],
            [['event_year','event_month'], 'string', 'max' => 15],
            [['event_location'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['event_location' => 'id']],
            [['event_name','event_type'], 'required', 'message'=>'']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_name' => 'Event Name',
            'created_by' => 'Created By',
            'created_date' => 'Creation Datetime',
            'event_location' => 'Event Location',
            'eventLocation.name' => 'Event Location',
            'event_type' => 'Event Type',
            'event_year' => 'Event Year',
            'event_month' => 'Event Month',
            'is_deleted' => 'Status'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventLocation()
    {
        return $this->hasOne(State::className(), ['id' => 'event_location']);
    }
}
