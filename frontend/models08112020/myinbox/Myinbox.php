<?php

namespace frontend\models\myinbox;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use frontend\models\customer\Customer;
use yii\db\Query;

/**
 * CustomerSearch represents the model behind the search form about `frontend\models\customer\Customer`.
 */
class Myinbox extends Model
{

    private $_fromDate;
    private $_toDate;
    private $_createdBy;
    private $_campaignID;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['FromDate','ToDate','CreatedBy','campaignID'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'FromDate' => 'From',
            'ToDate' => 'To',
            'CreatedBy' => 'Agent',
            'campaignID' => 'Campaign',
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
     public function search($params)
     {
		 $encryptionKey = Yii::$app->params['encryptionKey'];

       $select = ['AES_DECRYPT(UNHEX(cust.`full_name`), "'.$encryptionKey.'") AS full_name','(CASE  WHEN cust.`new_nic` = ""  THEN cust.passport  ELSE cust.new_nic  END) AS identity',
        'cust.gender','AES_DECRYPT(UNHEX(cust.`email`), "'.$encryptionKey.'") AS email','u.username AS username','cdg.campaign_id','c.name','cust.id'];

       $query = new Query;
       if ((Yii::$app->user->identity->id)!=1) { // for the admin user having all data.
       $query->select($select)
           ->from('customer cust')
           //->join('INNER JOIN', 'customer_campaign cc','cc.customer_id = cust.id')
           ->join('INNER JOIN', 'contact_distribution cdg','cdg.customer_id = cust.id')
           ->join('INNER JOIN', 'campaign c','c.id = cdg.campaign_id')
           ->join('INNER JOIN', 'user u','u.id = cdg.agent_id')
           ->Where(['cdg.agent_id' => Yii::$app->user->identity->id]);
         } else {
           $query->select($select)
               ->from('customer cust')
               //->join('INNER JOIN', 'customer_campaign cc','cc.customer_id = cust.id')
               ->join('INNER JOIN', 'contact_distribution cdg','cdg.customer_id = cust.id')
               ->join('INNER JOIN', 'campaign c','c.id = cdg.campaign_id')
               ->join('INNER JOIN', 'user u','u.id = cdg.agent_id');

         }

       $sort = [
           'defaultOrder' => ['full_name'=>SORT_ASC],
           'attributes' => [
               'full_name',
               'total' => [
                   'asc' => ['total' => SORT_ASC],
                   'desc' => ['total' => SORT_DESC],
                   'default' => SORT_DESC

               ]

           ]
       ];

        $this->load($params);

        if(!empty($this->FromDate))
            $this->FromDate .= " 00:00:00" ;

        if(!empty($this->ToDate))
            $this->ToDate .= " 23:59:59" ;

        $query->andFilterWhere(['u.id'=> $this->CreatedBy])
            ->andFilterWhere(['>', 'cust.created_datetime', $this->FromDate])
            ->andFilterWhere(['<', 'cust.created_datetime', $this->ToDate])
            ->andFilterWhere(['cdg.campaign_id'=> $this->campaignID]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
        ]);

        $querySummary = new Query;

        if ((Yii::$app->user->identity->id)!=1) { // for the admin user having all data.
                $querySummary->select($select)
                  ->from('user u')
                  ->join('LEFT OUTER JOIN', 'contact_distribution cdg','cdg.agent_id =u.id')
                  ->join('INNER JOIN', 'customer cust','cust.id = cdg.customer_id')
                //  ->join('INNER JOIN', 'customer_campaign cc','cc.customer_id = cust.id')
                  ->join('INNER JOIN', 'campaign c','c.id = cdg.campaign_id')

                    ->Where(['cdg.agent_id' => Yii::$app->user->identity->id]);
                  } else {

                  $querySummary->select($select)
                        ->from('user u')
                        ->join('LEFT OUTER JOIN', 'contact_distribution cdg','cdg.agent_id =u.id')
                        ->join('INNER JOIN', 'customer cust','cust.id = cdg.customer_id')
                        //->join('INNER JOIN', 'customer_campaign cc','cc.customer_id = cust.id')
                        ->join('INNER JOIN', 'campaign c','c.id = cdg.campaign_id');

                  }

//echo $querySummary->createCommand()->getRawSql();

        $summary = $querySummary->all();
        if ($summary!=NULL){
          return array('dataProvider' => $dataProvider,'summary' => $summary[0]);
      } else {
            return array('dataProvider' => $dataProvider,'summary' => $summary);
      }
    }

    public function getFromDate(){
         return $this->_fromDate;
    }

    public function setFromDate($_fromDate){
        $this->_fromDate = $_fromDate;
    }

    public function getToDate(){
        return $this->_toDate;
    }

    public function setToDate($_toDate){
        $this->_toDate = $_toDate;
    }

    public function getCreatedBy(){
        return $this->_createdBy;
    }

    public function setCreatedBy($_createdBy){
        $this->_createdBy = $_createdBy;
    }

    public function getCampaignID(){
        return $this->_campaignID;
    }

    public function setCampaignID($_campaignID){
        $this->_campaignID = $_campaignID;
    }

}
