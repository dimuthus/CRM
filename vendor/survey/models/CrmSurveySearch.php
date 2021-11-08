<?php

namespace frontend\modules\survey\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\survey\models\CrmSurvey;

/**
 * CrmSurveySearch represents the model behind the search form about `frontend\modules\survey\models\CrmSurvey`.
 */
class CrmSurveySearch extends CrmSurvey
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'group_id'], 'integer'],
            [['name', 'description', 'updated', 'opening_date', 'closing_date'], 'safe'],
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
        $query = CrmSurvey::find();

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
            'updated' => $this->updated,
            'opening_date' => $this->opening_date,
            'closing_date' => $this->closing_date,
            'group_id' => $this->group_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
