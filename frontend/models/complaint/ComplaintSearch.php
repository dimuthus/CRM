<?php

namespace frontend\models\complaint;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\complaint\Complaint;

/**
 * ComplaintSearch represents the model behind the search form about `frontend\models\complaint\Complaint`.
 */
class ComplaintSearch extends Complaint
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'brand_id', 'customer_id', 'created_by', 'subbrand_id', 'product_id', 'packsize_id', 'color_id', 'user_type_id', 'proof_of_purchase_id'], 'integer'],
            [['creation_datetime', 'last_modified_datetime', 'batch_no', 'purchase_date', 'description'], 'safe'],
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
        $query = Complaint::find();

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
            'brand_id' => $this->brand_id,
            'customer_id' => $this->customer_id,
            'created_by' => $this->created_by,
            'creation_datetime' => $this->creation_datetime,
            'last_modified_datetime' => $this->last_modified_datetime,
            'subbrand_id' => $this->subbrand_id,
            'product_id' => $this->product_id,
            'packsize_id' => $this->packsize_id,
            'color_id' => $this->color_id,
            'user_type_id' => $this->user_type_id,
            'purchase_date' => $this->purchase_date,
            'proof_of_purchase_id' => $this->proof_of_purchase_id,
        ]);

        $query->andFilterWhere(['like', 'batch_no', $this->batch_no])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
