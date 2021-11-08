<?php

namespace frontend\models\campaign;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\campaign\CustomerCampaign;

/**
 * CustomerCampaignSearch represents the model behind the search form of `frontend\models\campaign\CustomerCampaign`.
 */
class CustomerCampaignSearch extends CustomerCampaign
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campaign_id', 'customer_id', 'created_by', 'last_updated_by'], 'integer'],
            [['created_datetime', 'last_updated_datetime'], 'safe'],
            [['campaign_successfull'], 'boolean'],
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
        $query = CustomerCampaign::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'campaign_id' => $this->campaign_id,
            'customer_id' => $this->customer_id,
            'created_by' => $this->created_by,
            'created_datetime' => $this->created_datetime,
            'last_updated_by' => $this->last_updated_by,
            'last_updated_datetime' => $this->last_updated_datetime,
            'campaign_successfull' => $this->campaign_successfull,
        ]);

        return $dataProvider;
    }
}
