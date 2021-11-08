<?php

namespace frontend\models\report;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\customer\Customer;
use yii\db\Query;

/**
 * CustomerSearch represents the model behind the search form about `frontend\models\customer\Customer`.
 */
class ReportSearch extends Model
{

    private $_fromDate;
    private $_toDate;
	private $_caseStatus;
	public $createdBy;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['FromDate','ToDate','CaseStatus','createdBy'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'FromDate' => 'From',
            'ToDate' => 'To',
			'CaseStatus'=>'Case Status',
			'createdBy' =>'createdBy'
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
    public function generateComplete($params)
    {
		
        $query = new Query;
        
        $query->select('*')->distinct()->from('vi_report_complete');

        $this->load($params);
		$len = @count($this->CaseStatus);
//die('len='.$len);
		//$caseStatusList = implode(', ', $this->CaseStatus);
        		//var_dump($caseStatusList);die();

        if(!empty($this->FromDate)) 
            $this->FromDate .= " 00:00:00" ;

        if(!empty($this->ToDate))
            $this->ToDate .= " 23:59:59" ;

        $query->andFilterWhere(['>', 'customer_cases_created_datetime', $this->FromDate])
            ->andFilterWhere(['<', 'customer_cases_created_datetime', $this->ToDate])
		    ->andFilterWhere(['IN', 'case_status_id',$this->CaseStatus])
		    ->andFilterWhere(['IN', 'customer_cases_created_by',$this->createdBy]);

			//echo $query->createCommand()->sql;
        
        $dataProvider = new ActiveDataProvider([
			'db' => Yii::$app->get('db2'),
            'pagination' =>false,// ['pageSize'=>10],
            'query' => $query
        ]);
        //echo "interaction_creation_datetime: ".$this->FromDate."<br>";
        //echo "interaction_creation_datetime".$this->ToDate;
        //var_dump($dataProvider);
        //die(45345);
        
        return $dataProvider;
    }
	
	
	
	
	public function generateCustomer($params)
    {
		
        $query = new Query;
        $query->select('*')->distinct()->from('vi_search');
        $this->load($params);
		$len = @count($this->CaseStatus);

        if(!empty($this->FromDate)) 
            $this->FromDate .= " 00:00:00" ;

        if(!empty($this->ToDate))
            $this->ToDate .= " 23:59:59" ;

        $query->andFilterWhere(['>', 'id', $this->FromDate])
            ->andFilterWhere(['<', 'id', $this->ToDate]);

			//echo $query->createCommand()->sql;
        
        $dataProvider = new ActiveDataProvider([
			'db' => Yii::$app->get('db2'),
            'pagination' =>false,// ['pageSize'=>10],
            'query' => $query
        ]);
        //echo "interaction_creation_datetime: ".$this->FromDate."<br>";
        //echo "interaction_creation_datetime".$this->ToDate;
        //var_dump($dataProvider);
        //die(45345);
        
        return $dataProvider;
    }
	
	
	   public function generateOutbound($params)
    {
        $query = new Query;
        
        $query->select('*')->distinct()->from('vi_outbound');

        $this->load($params);
        
        if(!empty($this->FromDate)) 
            $this->FromDate .= " 00:00:00" ;

        if(!empty($this->ToDate))
            $this->ToDate .= " 23:59:59" ;

        $query->andFilterWhere(['BETWEEN', 'customer_cases_created_datetime', $this->FromDate, $this->ToDate])
				    ->andFilterWhere(['IN', 'case_status_id',$this->CaseStatus])
		    ->andFilterWhere(['IN', 'customer_cases_created_by',$this->createdBy]);
        
        //$rows = $query->all();
        
        //return $rows;
        
        $dataProvider = new ActiveDataProvider([
			'db' => Yii::$app->get('db2'),
            'pagination' =>false,// ['pageSize'=>10],
            'query' => $query
        ]);
        //echo "interaction_creation_datetime: ".$this->FromDate."<br>";
        //echo "interaction_creation_datetime".$this->ToDate;
        //var_dump($dataProvider);
        //die(45345);
        
        return $dataProvider;
    }
	
	
	

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function generateSummaryInbound($params)
    {
        $query = new Query;
        
        $query->select('*')->from('vi_report_summary');

        $this->load($params);
        
        if(!empty($this->FromDate)) 
            $this->FromDate .= " 00:00:00" ;

        if(!empty($this->ToDate))
            $this->ToDate .= " 23:59:59" ;

        $query->andFilterWhere(['>', 'interaction_creation_datetime', $this->FromDate])
            ->andFilterWhere(['<', 'interaction_creation_datetime', $this->ToDate])
            ->andFilterWhere(['IN', 'case_status_id',$this->CaseStatus])
		    ->andFilterWhere(['IN', 'Service_Request_Created_by_ID',$this->createdBy]);
        $dataProvider = new ActiveDataProvider([
			'db' => Yii::$app->get('db2'),
			'pagination' => ['pageSize'=>''],
            'query' => $query
        ]);
        return $dataProvider;
    }

 public function generateSummaryOutbound($params)
    {
        $query = new Query;
        
        $query->select('*')->from('vi_report_summary_outbound');

        $this->load($params);
        
        if(!empty($this->FromDate)) 
            $this->FromDate .= " 00:00:00" ;

        if(!empty($this->ToDate))
            $this->ToDate .= " 23:59:59" ;

        $query->andFilterWhere(['>', 'interaction_creation_datetime', $this->FromDate])
            ->andFilterWhere(['<', 'interaction_creation_datetime', $this->ToDate])
            ->andFilterWhere(['IN', 'case_status_id',$this->CaseStatus])
		    ->andFilterWhere(['IN', 'Service_Request_Created_by_ID',$this->createdBy]);
        $dataProvider = new ActiveDataProvider([
			'db' => Yii::$app->get('db2'),
			'pagination' => ['pageSize'=>''],
            'query' => $query
        ]);
        return $dataProvider;
    }
	
	
	
	  public function generateCsat($params)
    {
        $query = new Query;

         $query->select('*')->from('csat_final');
        // $query= "select first_name,Name ,question_id,";
        // $resultquery= "SELECT distinct question_id FROM csat_survey";
        // $connection = Yii::$app->getDb();
        // $command = $connection->createCommand($resultquery);
        // $result=$command->queryAll();
        // $i=0;
        // foreach( $result as $res){
        //  $i++;   
        // $query.='sum(case when question_id = '.$res['question_id'].' then answer else 0 end) '.'Q'.$i.',';
                  
        //                     }
        //         $query = substr($query, 0, -1); 
        //         $query .= " from csat_survey group by first_name";

        //  $command = $connection->createCommand($query);
        // $result=$command->queryAll();       
        // var_dump($query);
        // die();
        $this->load($params);

        if(!empty($this->FromDate))
            $this->FromDate .= " 00:00:00" ;

        if(!empty($this->ToDate))
            $this->ToDate .= " 23:59:59" ;

        $query->andFilterWhere(['>', 'CreateDate', $this->FromDate])
            ->andFilterWhere(['<', 'CreateDate', $this->ToDate]);

        $dataProvider = new ActiveDataProvider([
			'db' => Yii::$app->get('db2'),
            'pagination' => ['pageSize'=>10],
            'query' => $query
        ]);
        return $dataProvider;
    }


  public function generateActivitylog($params)
    {
        $query = new Query;
		$query->select('*')->from('vi_audit_log');
        $this->load($params);

        if(!empty($this->FromDate))
            $this->FromDate .= " 00:00:00" ;

        if(!empty($this->ToDate))
            $this->ToDate .= " 23:59:59" ;

        $query->andFilterWhere(['between', 'dml_timestamp', $this->FromDate,$this->ToDate])->orderBy('dml_timestamp DESC') ;
            
        $dataProvider = new ActiveDataProvider([
			'db' => Yii::$app->get('db'),
            'pagination' =>['pageSize'=>10],
            'query' => $query
        
        ]);
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
	public function getCaseStatus(){
        return $this->_caseStatus;
    }
    
    public function setCaseStatus($_caseStatus){
        $this->_caseStatus = $_caseStatus;
    }
}
