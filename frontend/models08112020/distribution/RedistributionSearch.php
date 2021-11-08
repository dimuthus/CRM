<?php

namespace frontend\models\distribution;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\distribution\Redistribution;

/**
 * RedistributionSearch represents the model behind the search form of `frontend\models\distribution\Redistribution`.
 */
class RedistributionSearch extends Redistribution
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'agent_id', 'customer_id', 'distributed_by'], 'integer'],
            [['distributed_date'], 'safe'],
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
        $query = Redistribution::find();

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
            'agent_id' => $this->agent_id,
            'customer_id' => $this->customer_id,
            'distributed_by' => $this->distributed_by,
            'distributed_date' => $this->distributed_date,
        ]);

        return $dataProvider;
    }
}
