<?php

namespace frontend\models\report;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\report\UserClicks;

/**
 * DailyLoginsSearch represents the model behind the search form about `frontend\models\report\DailyLogins`.
 */
class UserClicksSearch extends UserClicks
{
    /**
     * @inheritdoc
     */
    
    public $user;
    public function rules()
    {
        return [
            [['user_id', 'tab_id'], 'integer'],
            [['click_date','user'], 'safe'],
            [['url'], 'string', 'max' => 255]
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
        $query = UserClicks::find();
        $query->joinWith(['user']);
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
            'user_id' => $this->user_id,
        ]);
        $query->andFilterWhere(['like', 'username', $this->user]);
        $query->andFilterWhere(['like', 'url', $this->url]);
        $query->andFilterWhere(['like', 'click_date', $this->click_date]);

        return $dataProvider;
    }
}
