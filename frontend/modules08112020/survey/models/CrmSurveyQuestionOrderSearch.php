<?php

namespace frontend\modules\survey\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\survey\models\CrmSurveyQuestionOrder;
use yii\db\Query;


/**
 * CrmSurveyQuestionOrderSearch represents the model behind the search form about `frontend\modules\survey\models\CrmSurveyQuestionOrder`.
 */
class CrmSurveyQuestionOrderSearch extends CrmSurveyQuestionOrder
{
    /**
     * @inheritdoc
     */
    
    public $surveyname;
    public function rules()
    {
        return [
            [['id', 'question_id',  'order', 'conditional_order_id', 'is_conditional'], 'integer'],
             [['surveyname'], 'safe'],

           [[ 'servey_id'], 'string'],

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
        $query = CrmSurveyQuestionOrder::find();
          $query->joinWith(['survey']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => false,

        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'question_id' => $this->question_id,
            'servey_id' => $this->servey_id,
            'order' => $this->order,
            'conditional_order_id' => $this->conditional_order_id,
            'is_conditional' => $this->is_conditional,
        ])
        ->andFilterWhere(['like', 'crm_survey.name', $this->surveyname]);

        return $dataProvider;
    }
	 
	 public function campaginsearch(){
		         $query = new Query;

		         $query->select('*')->distinct()->from('vi_campaign_survey');
				  $dataProvider = new ActiveDataProvider([
            'pagination' =>false,// ['pageSize'=>10],
            'query' => $query
        ]);
               
        return $dataProvider;

		// die('CCCCCCCCCCCCCCCCCCCCC');
	 }
	
    
      public function getSurvey()
    {
        return $this->hasOne(CrmSurvey::className(), ['id' => 'servey_id']);
    }
}