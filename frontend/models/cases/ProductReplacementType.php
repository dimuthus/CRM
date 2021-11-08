<?php

namespace frontend\models\cases;

use Yii;

/**
 * This is the model class for table "product_replacement_type".
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property string $created_by_datetime
 * @property bool $deleted
 *
 * @property CustomerCases[] $customerCases
 */
class ProductReplacementType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_replacement_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by'], 'integer'],
            [['created_by_datetime'], 'safe'],
            [['deleted'], 'boolean'],
            [['name'], 'string', 'max' => 25],
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
            'created_by_datetime' => 'Created By Datetime',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerCases()
    {
        return $this->hasMany(CustomerCases::className(), ['product_replacement_type' => 'id']);
    }
}
