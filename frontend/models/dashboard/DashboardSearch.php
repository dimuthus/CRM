<?php

namespace frontend\models\dashboard;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\customer\Customer;
use yii\db\Query;

/**
 * CustomerSearch represents the model behind the search form about `frontend\models\customer\Customer`.
 */
class DashboardSearch extends Model
{

    private $_fromDate;
    private $_toDate;
    private $_createdBy;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['FromDate','ToDate','CreatedBy'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'FromDate' => 'From',
            'ToDate' => 'To',
            'CreatedBy' => 'UserID',
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
        $select = '
                u.username AS username,
                COUNT(r.id) AS total,
                COUNT(CASE WHEN
                    r.case_status = 2 
                    THEN 1 ELSE NULL END) AS closed,
                COUNT(CASE WHEN
                    r.case_status = 1 or
                    r.case_status = 3 or
                    r.case_status = 4
                    THEN 1 ELSE NULL END) AS `open`,
                COUNT(CASE WHEN
                    r.case_status = 5
                    
                    THEN 1 ELSE NULL END) AS escalated,
               DATEDIFF(NOW(), MIN(CASE WHEN
                r.case_status != 2
                THEN r.created_datetime ELSE NOW() END)) AS aging

            ';
        $query = new Query;

        $query->select($select)
            ->from('user u')
            ->join('LEFT OUTER JOIN', 'customer_cases r','r.created_by =u.id')
            ->where('u.id != 1')
            ->groupby('username');

        // echo $query->createCommand()->getRawSql();

        $sort = [
            'defaultOrder' => ['username'=>SORT_ASC],
            'attributes' => [
                'username',
                'total' => [
                    'asc' => ['total' => SORT_ASC],
                    'desc' => ['total' => SORT_DESC],
                    'default' => SORT_DESC
                ],
                'closed' => [
                    'asc' => ['closed' => SORT_ASC],
                    'desc' => ['closed' => SORT_DESC],
                    'default' => SORT_DESC
                ],
                'open' => [
                    'asc' => ['open' => SORT_ASC],
                    'desc' => ['open' => SORT_DESC],
                    'default' => SORT_DESC
                ],
                'escalated' => [
                    'asc' => ['escalated' => SORT_ASC],
                    'desc' => ['escalated' => SORT_DESC],
                    'default' => SORT_DESC
                ],
                'aging' => [
                    'asc' => ['aging' => SORT_ASC],
                    'desc' => ['aging' => SORT_DESC],
                    'default' => SORT_DESC
                ],
            ]
        ];

        $this->load($params);

        if(!empty($this->FromDate))
            $this->FromDate .= " 00:00:00" ;

        if(!empty($this->ToDate))
            $this->ToDate .= " 23:59:59" ;

        $query->andFilterWhere(['r.created_by'=> $this->CreatedBy])
            ->andFilterWhere(['>', 'r.created_datetime', $this->FromDate])
            ->andFilterWhere(['<', 'r.created_datetime', $this->ToDate]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
        ]);

        $querySummary = new Query;

        $querySummary->select($select)
            ->from('user u')
            ->join('LEFT OUTER JOIN', 'customer_cases r','r.created_by =u.id')
            ->where('u.id != 1');

        $querySummary->andFilterWhere(['r.created_by'=> $this->CreatedBy])
            ->andFilterWhere(['>', 'r.created_datetime', $this->FromDate])
            ->andFilterWhere(['<', 'r.created_datetime', $this->ToDate]);

        $summary = $querySummary->all();

        return array('dataProvider' => $dataProvider,'summary' => $summary[0]);

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

}
