<?php
namespace frontend\models\customer;


use Yii;
use frontend\modules\tools\models\user\User;


/**
 * This is the model class for table "salutation".
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property string $creation_datetime
 * @property string $last_modified_datetime
 * @property bool $deleted
 *
 * @property Customer[] $customers
 * @property User $createdBy
 */
class Salutation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'salutation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by','updated_by'], 'integer'],
            [['creation_datetime', 'last_modified_datetime'], 'safe'],
            [['deleted'], 'boolean'],
            [['name'], 'string', 'max' => 8],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['salutation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
