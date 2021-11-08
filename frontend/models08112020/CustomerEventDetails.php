<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer_event_details".
 *
 * @property integer $id
 * @property string $customer_id
 * @property string $event_name
 * @property string $event_location
 * @property string $event_month
 * @property string $event_year
 * @property string $created_datetime
 * @property string $created_by
 */
class CustomerEventDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_event_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['created_datetime'], 'safe'],
            [['customer_id'], 'string', 'max' => 11],
            [['event_name'], 'string', 'max' => 200],
            [['event_location'], 'string', 'max' => 30],
            [['event_month'], 'string', 'max' => 12],
            [['event_year'], 'string', 'max' => 5],
            [['created_by'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'event_name' => 'Event Name',
            'event_location' => 'Event Location',
            'event_month' => 'Event Month',
            'event_year' => 'Event Year',
            'created_datetime' => 'Created Datetime',
            'created_by' => 'Created By',
        ];
    }
}
