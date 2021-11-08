<?php

namespace frontend\models\customer;

use Yii;

/**
 * This is the model class for table "gender".
 *
 * @property int $id
 * @property string $gender
 *
 * @property Customer[] $customers
 */
class Gender extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gender';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gender'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gender' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['gender' => 'id']);
    }*/
}
