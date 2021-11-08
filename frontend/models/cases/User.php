<?php

namespace frontend\modules\tools\models\user;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status_id
 * @property int $created_by
 * @property string $creation_datetime
 * @property string $last_modified_datetime
 * @property string $first_name
 * @property resource $last_name
 * @property string $role_id
 * @property string $passwordtupdate_datetime
 * @property int $firsttime
 * @property string $status_modified_datetime
 *
 * @property AgeGroup[] $ageGroups
 * @property CaseDescription[] $caseDescriptions
 * @property Country[] $countries
 * @property Customer[] $customers
 * @property Customer[] $customers0
 * @property CustomerCases[] $customerCases
 * @property CustomerCases[] $customerCases0
 * @property CustomerLanguage[] $customerLanguages
 * @property InboundInteraction[] $inboundInteractions
 * @property Race[] $races
 * @property Race[] $races0
 * @property Salutation[] $salutations
 * @property Source[] $sources
 * @property AuthItem $role
 * @property UserStatus $status
 * @property User $createdBy
 * @property User[] $users
 * @property UserStatus[] $userStatuses
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_by', 'first_name', 'last_name', 'role_id'], 'required'],
            [['status_id', 'created_by', 'firsttime'], 'integer'],
            [['creation_datetime', 'last_modified_datetime', 'passwordtupdate_datetime', 'status_modified_datetime'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'first_name', 'last_name'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['role_id'], 'string', 'max' => 85],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['role_id' => 'name']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserStatus::className(), 'targetAttribute' => ['status_id' => 'id']],
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
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status_id' => 'Status ID',
            'created_by' => 'Created By',
            'creation_datetime' => 'Creation Datetime',
            'last_modified_datetime' => 'Last Modified Datetime',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'role_id' => 'Role ID',
            'passwordtupdate_datetime' => 'Passwordtupdate Datetime',
            'firsttime' => 'Firsttime',
            'status_modified_datetime' => 'Status Modified Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgeGroups()
    {
        return $this->hasMany(AgeGroup::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseDescriptions()
    {
        return $this->hasMany(CaseDescription::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(Country::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers0()
    {
        return $this->hasMany(Customer::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerCases()
    {
        return $this->hasMany(CustomerCases::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerCases0()
    {
        return $this->hasMany(CustomerCases::className(), ['last_updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerLanguages()
    {
        return $this->hasMany(CustomerLanguage::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInboundInteractions()
    {
        return $this->hasMany(InboundInteraction::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRaces()
    {
        return $this->hasMany(Race::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRaces0()
    {
        return $this->hasMany(Race::className(), ['last_upadted_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalutations()
    {
        return $this->hasMany(Salutation::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSources()
    {
        return $this->hasMany(Source::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(UserStatus::className(), ['id' => 'status_id']);
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
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserStatuses()
    {
        return $this->hasMany(UserStatus::className(), ['created_by' => 'id']);
    }
}
