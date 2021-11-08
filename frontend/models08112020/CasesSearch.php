<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\cases\CustomerCases;

/**
 * CasesSearch represents the model behind the search form about `frontend\models\cases\Cases`.
 */
class CasesSearch extends CustomerCases
{
    /**
     * @inheritdoc
     */
    
   public $_fromDate;
    public $_toDate;
    public function rules()
    {
        return [
            [['id', 'customer_id', 'case_category1', 'case_category2', 'case_category3',  'product_id',  'case_status', 'escalated_to',  'created_by', 'last_updated_by', 'case_counter'], 'integer'],
            [['case_id', 'attachment_name', 'created_datetime', 'last_updated_datetime', 'FromDate','ToDate'], 'safe'],
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
        $query = CustomerCases::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'customer_id' => $this->customer_id,
           // 'case_type' => $this->case_type,
            'case_category1' => $this->case_category1,
            'case_category2' => $this->case_category2,
            'case_category3' => $this->case_category3,
           // 'division_id' => $this->division_id,
           // 'brand_id' => $this->brand_id,
           // 'subbrand_id' => $this->subbrand_id,
            'product_id' => $this->product_id,
           // 'packsize_id' => $this->packsize_id,
           // 'followup_datetime' => $this->followup_datetime,
            'case_status' => $this->case_status,
            'escalated_to' => $this->escalated_to,
           // 'priority_id' => $this->priority_id,
           // 'country_id' => $this->country_id,
            'created_by' => $this->created_by,
            'last_updated_by' => $this->last_updated_by,
            'created_datetime' => $this->created_datetime,
            'last_updated_datetime' => $this->last_updated_datetime,
            //'last_modified_datetime' => $this->last_modified_datetime,
            'case_counter' => $this->case_counter,
        ]);

        $query->andFilterWhere(['like', 'case_id', $this->case_id])
					->andWhere(' customer_cases.case_status NOT IN (2)')

            ->andFilterWhere(['like', 'attachment_name', $this->attachment_name]);
        if(!empty($this->FromDate)) 
            $this->FromDate .= " 00:00:00" ;

        if(!empty($this->ToDate))
            $this->ToDate .= " 23:59:59" ;

        $query->andFilterWhere(['between', 'created_datetime', $this->FromDate, $this->ToDate]);
		  if(!Yii::$app->user->can('View Service Reuqests Created by All Agents')){
                            $query->andFilterWhere(['or',['customer_cases.created_by'=> Yii::$app->user->identity->id],['customer_cases.escalated_to'=> Yii::$app->user->identity->id]]);

			}
            //->andFilterWhere(['<', 'created_datetime', $this->ToDate]);
		//echo $query->createCommand()->getRawSql();
		//die('XXX');
        return $dataProvider;
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

}
