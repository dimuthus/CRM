<?php

namespace frontend\models\complaint;

use Yii;

/**
 * This is the model class for table "complaint_product".
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_by
 * @property string $creation_datetime
 * @property string $last_modified_datetime
 * @property integer $deleted
 * @property integer $division_id
 * @property integer $brand_id
 * @property integer $subband_id
 */
class ComplaintProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'complaint_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'deleted', 'division_id', 'brand_id', 'subband_id'], 'integer'],
            [['creation_datetime', 'last_modified_datetime'], 'safe'],
            [['name'], 'string', 'max' => 250]
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
            'creation_datetime' => 'Creation Datetime',
            'last_modified_datetime' => 'Last Modified Datetime',
            'deleted' => 'Deleted',
            'division_id' => 'Division ID',
            'brand_id' => 'Brand ID',
            'subband_id' => 'Subband ID',
        ];
    }
}
