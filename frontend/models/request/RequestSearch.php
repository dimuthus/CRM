<?php

namespace frontend\models\request;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\customer\Customer;
use yii\db\Query;

/**
 * CustomerSearch represents the model behind the search form about `frontend\models\customer\Customer`.
 */
class RequestSearch extends Request
{

    private $_fromDate;
    private $_toDate;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['FromDate','ToDate','created_by'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'FromDate' => 'From',
            'ToDate' => 'To',
            'created_by' => 'Agent',
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
        $query = Request::find()
                ->where('user.id != 1 AND service_request.service_request_status not in (1,3,4,12,18)')
                ->joinWith('customer')
                ->joinWith('type')
                ->joinWith('status')
                ->joinWith([
                    'escalated' => function ($q) {
                        $q->from('user es');
                    },
                ])
                ->joinWith('prioritytitle')
                ->joinWith('creator');
        
        $sort = [
            'defaultOrder' => ['creation_datetime'=>SORT_DESC],
            'attributes' => [
                'customer.full_name' => [
                    'asc' => ['customer.full_name' => SORT_ASC],
                    'desc' => ['customer.full_name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'service_request_id',
                'type.name' => [
                    'asc' => ['service_request_type.name' => SORT_ASC],
                    'desc' => ['service_request_type.name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'status.name' => [
                    'asc' => ['service_request_status.name' => SORT_ASC],
                    'desc' => ['service_request_status.name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'escalated.username' => [
                    'asc' => ['es.username' => SORT_ASC],
                    'desc' => ['es.username' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'prioritytitle.name' => [
                    'asc' => ['service_type_priority.name' => SORT_ASC],
                    'desc' => ['service_type_priority.name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'creator.username' => [
                    'asc' => ['user.username' => SORT_ASC],
                    'desc' => ['user.username' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'creation_datetime' => [
                    'asc' => ['service_request.creation_datetime' => SORT_ASC],
                    'desc' => ['service_request.creation_datetime' => SORT_DESC],
                    'default' => SORT_DESC
                ],
            ]
        ];

        $dataProvider = new ActiveDataProvider([
            'pagination' => ['pageSize'=>10],
            'query' => $query,
            'sort' => $sort,
        ]);

        $this->load($params);

        if(!empty($this->FromDate)) 
            $this->FromDate .= " 00:00:00" ;
        if(!empty($this->ToDate)) 
            $this->ToDate .= " 23:59:59" ;

        if(Yii::$app->user->can('View Service Reuqests Created by All Agents'))
            $creator = $this->created_by;
        else
            $creator = Yii::$app->user->identity->id; 
        
        $query->andFilterWhere(['service_request.created_by'=> $creator])
            ->andFilterWhere(['>', 'service_request.creation_datetime', $this->FromDate])
            ->andFilterWhere(['<', 'service_request.creation_datetime', $this->ToDate]);

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
