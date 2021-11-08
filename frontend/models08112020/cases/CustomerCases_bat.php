<?php

namespace frontend\models\cases;

use Yii;
use frontend\modules\tools\models\user\User;
use frontend\models\City;
use frontend\models\customer\Customer;

/**
 * This is the model class for table "customer_cases".
 *
 * @property int $id
 * @property string $case_id
 * @property int $caller_type
 * @property int $product_replacement_type
 * @property int $product_replacement_status
 * @property int $replacement_delivery_status
 * @property int $case_status
 * @property int $transaction_type
 * @property string $hotline
 * @property string $awb
 * @property int $total_box
 * @property int $product
 * @property string $purchased_at
 * @property int $called_from
 * @property int $created_by
 * @property string $created_datetime
 * @property int $last_updated_by
 * @property string $last_updated_datetime
 * @property int $replacement_delivery_method
 * @property int $first_call_resolution
 * @property int $escalated_to
 * @property bool $complain
 * @property string $callback
 * @property string $other_city
 * @property int $customer_id
 *
 * @property CaseCallback[] $caseCallbacks
 * @property CaseDescription[] $caseDescriptions
 * @property CaseEscalation[] $caseEscalations
 * @property CaseUpdation[] $caseUpdations
 * @property CallerType $callerType
 * @property User $createdBy
 * @property User $lastUpdatedBy
 * @property User $escalatedTo
 * @property ProductReplacementType $productReplacementType
 * @property ProductReplacementStatus $productReplacementStatus
 * @property ReplacementDeliveryStatus $replacementDeliveryStatus
 * @property CaseStatus $caseStatus
 * @property Product $product0
 * @property City $calledFrom
 * @property ReplacementDeliveryMethod $replacementDeliveryMethod
 * @property CaseStatus $firstCallResolution
 * @property InboundInteraction[] $inboundInteractions
 */
class CustomerCases extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_cases';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['case_id'], 'string', 'max' => 50],
            [['caller_type', 'product_replacement_type', 'product_replacement_status', 'replacement_delivery_status', 'case_status', 'transaction_type', 'total_box', 'product', 'called_from', 'created_by', 'last_updated_by', 'replacement_delivery_method', 'first_call_resolution', 'escalated_to', 'customer_id'], 'integer'],
            [['created_datetime', 'last_updated_datetime', 'callback'], 'safe'],
            [['complain'], 'boolean'],
            [['case_id'], 'string', 'max' => 20],
            [['hotline', 'awb', 'purchased_at'], 'string', 'max' => 250],
            [['other_city'], 'string', 'max' => 25],
            [['caller_type'], 'exist', 'skipOnError' => true, 'targetClass' => CallerType::className(), 'targetAttribute' => ['caller_type' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['last_updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['last_updated_by' => 'id']],
            [['escalated_to'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['escalated_to' => 'id']],
            [['product_replacement_type'], 'exist', 'skipOnError' => true, 'targetClass' => ProductReplacementType::className(), 'targetAttribute' => ['product_replacement_type' => 'id']],
            [['transaction_type'], 'exist', 'skipOnError' => true, 'targetClass' => TransactionType::className(), 'targetAttribute' => ['transaction_type' => 'id']],
            [['product_replacement_status'], 'exist', 'skipOnError' => true, 'targetClass' => ProductReplacementStatus::className(), 'targetAttribute' => ['product_replacement_status' => 'id']],
            [['replacement_delivery_status'], 'exist', 'skipOnError' => true, 'targetClass' => ReplacementDeliveryStatus::className(), 'targetAttribute' => ['replacement_delivery_status' => 'id']],
            [['case_status'], 'exist', 'skipOnError' => true, 'targetClass' => CaseStatus::className(), 'targetAttribute' => ['case_status' => 'id']],
            [['product'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product' => 'id']],
            [['called_from'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['called_from' => 'id']],
            [['replacement_delivery_method'], 'exist', 'skipOnError' => true, 'targetClass' => ReplacementDeliveryMethod::className(), 'targetAttribute' => ['replacement_delivery_method' => 'id']],
            [['first_call_resolution'], 'exist', 'skipOnError' => true, 'targetClass' => CaseStatus::className(), 'targetAttribute' => ['first_call_resolution' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    //Product Replacement Type was asked to changed on 15th Oct 2018
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'case_id' => 'Case ID',
            'caller_type' => 'Caller Type',
            'product_replacement_type' => 'Outcomecode',
            'product_replacement_status' => 'Product Replacement Status',
            'replacement_delivery_status' => 'Replacement Delivery Status',
            'case_status' => 'Case Status',
            'transaction_type' => 'Transaction Type',
            'hotline' => 'Hotline',
            'awb' => 'Awb',
            'total_box' => 'Total Box',
            'product' => 'Product',
            'purchased_at' => 'Purchased At',
            'called_from' => 'Called From',
            'created_by' => 'Created By',
            'created_datetime' => 'Created Datetime',
            'last_updated_by' => 'Last Updated By',
            'last_updated_datetime' => 'Last Updated Datetime',
            'replacement_delivery_method' => 'Replacement Delivery Method',
            'first_call_resolution' => 'First Call Resolution',
            'escalated_to' => 'Escalated To',
            'complain' => 'Is it a Complain?',
            'callback' => 'Callback',
            'other_city' => 'Other City',
            'customer_id' => 'Customer ID',
			'caseStatus.name' => 'Case Status',
			'callerType.name' => 'Caller Type',
            'productReplacementType.name' => 'Outcomecode',
            'productReplacementStatus.name' => 'Product Replacement Status',
            'replacementDeliveryStatus.name' => 'Product Replacement Delivery Status',
            'transactionType0.name' => 'Transaction Type',
			'product0.name' => 'Product',
			'calledFrom.name' => 'Called From',
            'createdBy.username' => 'Created By',
			'lastUpdatedBy.username' => 'Last Updated By',
			'replacementDeliveryMethod.name' => 'Product Replacement Delivery Method',
            'firstCallResolution.name' => 'First Call Resolution',
            'escalatedTo.username' => 'Escalated To',
			'customer.name'=> 'Customer Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseCallbacks()
    {
        return $this->hasMany(CaseCallback::className(), ['case_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseDescriptions()
    {
        return $this->hasMany(CaseDescription::className(), ['case_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseEscalations()
    {
        return $this->hasMany(CaseEscalation::className(), ['case_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseUpdations()
    {
        return $this->hasMany(CaseUpdation::className(), ['case_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallerType()
    {
        return $this->hasOne(CallerType::className(), ['id' => 'caller_type']);
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
    public function getLastUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'last_updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEscalatedTo()
    {
        return $this->hasOne(User::className(), ['id' => 'escalated_to']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductReplacementType()
    {
        return $this->hasOne(ProductReplacementType::className(), ['id' => 'product_replacement_type']);
    }

	public function getTransactionType0()
    {
        return $this->hasOne(TransactionType::className(), ['id'=>'transaction_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductReplacementStatus()
    {
        return $this->hasOne(ProductReplacementStatus::className(), ['id' => 'product_replacement_status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReplacementDeliveryStatus()
    {
        return $this->hasOne(ReplacementDeliveryStatus::className(), ['id' => 'replacement_delivery_status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseStatus()
    {
        return $this->hasOne(CaseStatus::className(), ['id' => 'case_status']);
    }

	public function getCustomer()
	{
		return $this->hasOne(Customer::className(), ['id'=>'customer_id']);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct0()
    {
        return $this->hasOne(Product::className(), ['id' => 'product']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalledFrom()
    {
        return $this->hasOne(City::className(), ['id' => 'called_from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReplacementDeliveryMethod()
    {
        return $this->hasOne(ReplacementDeliveryMethod::className(), ['id' => 'replacement_delivery_method']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFirstCallResolution()
    {
        return $this->hasOne(CaseStatus::className(), ['id' => 'case_status']);
    }

    /**
     * @return \yii\db\ActiveQuery

     */

    public function getInboundInteractions()
    {
        return $this->hasMany(InboundInteraction::className(), ['case_id' => 'id']);
    }

	public function getOutcomeCode1()
    {
        return $this->hasOne(OutcomeCode1::className(), ['id' => 'outcome_code1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */


    public function getOutcomeCode2()
    {
        return $this->hasOne(OutcomeCode2::className(), ['id' => 'outcome_code2']);
    }

    /**
     * @return \yii\db\ActiveQuery

     */

    public function getOutcomeCode3()
    {
        return $this->hasOne(OutcomeCode3::className(), ['id' => 'outcome_code3']);
    }

}
