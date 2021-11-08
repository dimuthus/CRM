<?php

namespace frontend\models\campaign;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\campaign\Campaign;

/**
 * CampaignSearch represents the model behind the search form of `frontend\models\campaign\Campaign`.
 */
class CampaignSearch extends Campaign
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'last_updated_by'], 'integer'],
            [['name', 'start_date', 'end_date', 'crieteria', 'created_datetime', 'last_updated_datetime'], 'safe'],
            [['deleted'], 'boolean'],
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
        $query = Campaign::find();

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
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'created_by' => $this->created_by,
            'created_datetime' => $this->created_datetime,
            'last_updated_by' => $this->last_updated_by,
            'last_updated_datetime' => $this->last_updated_datetime,
            'deleted' => $this->deleted,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'crieteria', $this->crieteria]);

        return $dataProvider;
    }
}
