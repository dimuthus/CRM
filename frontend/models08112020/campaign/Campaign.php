<?php
    
    namespace frontend\models\campaign;
    
    use Yii;
    use frontend\modules\tools\models\user\User;
    
    use frontend\models\customer\Source;
    use frontend\models\customer\AgeGroup;
    use frontend\models\customer\Race;
    
    use frontend\models\Country;
    use frontend\models\City;
    use frontend\models\State;
    use frontend\models\Events;
    use frontend\models\product\Product;
    
    
    /**
     * This is the model class for table "campaign".
     *
     * @property int $id
     * @property string $name
     * @property string $start_date
     * @property string $end_date
     * @property string $crieteria
     * @property int $created_by
     * @property string $created_datetime
     * @property int $last_updated_by
     * @property string $last_updated_datetime
     * @property bool $deleted
     *
     * @property ContactDistribution[] $contactDistributions
     * @property CustomerCampaign[] $customerCampaigns
     * @property OutboundInteraction[] $outboundInteractions
     */
    class Campaign extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public $criteria_label;
        public static function tableName()
        {
            return 'campaign';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
            [['start_date', 'end_date', 'created_datetime', 'last_updated_datetime'], 'safe'],
            [['created_by', 'last_updated_by'], 'integer'],
            [['deleted'], 'boolean'],
            [['name'], 'string', 'max' => 250],
            [['crieteria'], 'string', 'max' => 1000],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
            'id' => 'ID',
            'name' => 'Name',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'crieteria' => 'Crieteria',
            'created_by' => 'Created By',
            'createdBy.username'=>'Created By',
            'created_datetime' => 'Created Datetime',
            'last_updated_by' => 'Last Updated By',
            'lastUpdatedBy.username'=>'Last Updated By',
            'last_updated_datetime' => 'Last Updated Datetime',
            'deleted' => 'Deleted',
            'CriteriaLabel' => 'Crieteria',
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getContactDistributions()
        {
            return $this->hasMany(ContactDistribution::className(), ['campaign_id' => 'id']);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getCustomerCampaigns()
        {
            return $this->hasMany(CustomerCampaign::className(), ['campaign_id' => 'id']);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getOutboundInteractions()
        {
            return $this->hasMany(OutboundInteraction::className(), ['campaign_id' => 'id']);
        }
        
        public function getCreatedBy()
        {
            return $this->hasOne(User::className(), ['id' => 'created_by']);
        }
        public function getlastUpdatedBy()
        {
            return $this->hasOne(User::className(), ['id' => 'last_updated_by']);
        }
        
        public function setCriteriaLabel(){
            
            $age_group = array(); $ag = 0;
            $city = array(); $c=0;
            $race = array(); $r=0;
            $gender = array(); $g=0;
            $nationality = array(); $n = 0;
            $source = array(); $s=0;
            $state = array(); $st=0;
            $smoker = array(); $sm = 0;
            $product = array(); $p = 0;
            $event = array(); $ev = 0;
            $crieteriajson = json_decode($this->crieteria, true);
            $wherecondition='[';
            
            // foreach($crieteriajson as $cj){
                // switch($cj['name']){
                    // case 'age_group_id[]':  $age_group[++$ag]=$cj['value'];
                        // break;
                    // case 'race[]' :    $race[++$r] =  $cj['value'];
                        // break;
                    // case 'gender[]' : $gender[++$g] = $cj['value'];
                        // break;
                    // case 'nationality[]' : $nationality[++$n]=$cj['value'];
                        // break;
                    // case 'source[]' : $source[++$s]=$cj['value'];
                        // break;
                    // case 'city[]' : $city[++$c]=$cj['value'];
                        // break;
                    // case 'state[]': $state[++$st]=$cj['value'];
                        // break;
                    // case 'smoker[]': $smoker[++$sm]=$cj['value'];
                        // break;
                    // case 'product[]': $product[++$p]=$cj['value'];
                        // break;
                    // case 'event[]': $event[++$ev]=$cj['value'];
                        // break;
                // }
                
            // }
            $model =null;
            if ($age_group!=NULL){
                $wherecondition .= "Age_group=";
                foreach($age_group as $ag){
                    $model = AgeGroup::findOne($ag);
                    $wherecondition .= $model->name." OR ";
                }
                $wherecondition = substr($wherecondition, 0, -3);
                $wherecondition .= "] ";
            }
            if ($race!=NULL){
                $wherecondition .= ",";
                $wherecondition .= "Race=";
                foreach($race as $r){
                    $model = Race::findOne($r);
                    $wherecondition .= $model->name." OR ";
                }
                $wherecondition = substr($wherecondition, 0, -3);
                $wherecondition .= "] ";
            }
            if ($gender!=NULL){
                $wherecondition .= ",";
                $wherecondition .= "Gender=";
                foreach($gender as $g){
                    $g==1? $genderStr='Male':$genderStr='Female';
                    $wherecondition .= $genderStr." OR ";
                }
                $wherecondition = substr($wherecondition, 0, -3);
                $wherecondition .= "] ";
            }
            if ($nationality!=NULL){
                $wherecondition .= ",";
                $wherecondition .= "Nationality=";
                foreach($nationality as $n){
                    $model = Country::findOne($n);
                    $wherecondition .= $model->name." OR ";
                }
                $wherecondition = substr($wherecondition, 0, -3);
                $wherecondition .= "] ";
            }
            if ($source!=NULL){
                $wherecondition .= ",";
                $wherecondition .= "Source=";
                foreach($source as $sur){
                    $model = Source::findOne($sur);
                    $wherecondition .= $model->name." OR ";
                }
                $wherecondition = substr($wherecondition, 0, -3);
                $wherecondition .= "] ";
            }
            if ($city!=NULL){
                $wherecondition .= ",";
                $wherecondition .= "City=";
                foreach($city as $c){
                    $model = City::findOne($c);
                    $wherecondition .= $model->name." OR ";
                }
                $wherecondition = substr($wherecondition, 0, -3);
                $wherecondition .= "] ";
            }
            if ($state!=NULL){
                $wherecondition .= ",";
                $wherecondition .= "State=";
                foreach($state as $st){
                    $model = State::findOne($st);
                    $wherecondition .= $model->name." OR ";
                }
                $wherecondition = substr($wherecondition, 0, -3);
                $wherecondition .= "] ";
            }
            if ($smoker!=NULL){
                $wherecondition .= ",";
                $wherecondition .= "Smoker=";
                foreach($smoker as $s){
                    $s==1?$smokerStr='Yes':$smokerStr='No';
                    $wherecondition .= $smokerStr." OR ";
                }
                $wherecondition = substr($wherecondition, 0, -3);
                $wherecondition .= "] ";
            }
            if ($product!=NULL){
                $wherecondition .= ",";
                $wherecondition .= "Product=";
                foreach($product as $p){
                    $model = Product::findOne($p);
                    $wherecondition .= $model->name." OR ";
                }
                $wherecondition = substr($wherecondition, 0, -3);
                $wherecondition .= " ] ";
            }
            
            if ($event!=NULL){
                $wherecondition .= ",";
                $wherecondition .= "Event=";
                foreach($event as $ev){
                    $model = Events::findOne($ev);
                    $wherecondition .= $model->event_name." OR ";
                }
                $wherecondition = substr($wherecondition, 0, -3);
                $wherecondition .= " ] ";
            }
            
            $this->criteria_label = $wherecondition;
        }
        
        public function getCriteriaLabel(){
            $this->setCriteriaLabel();
            return $this->criteria_label;
        }
        
    }

