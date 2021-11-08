<?php

namespace frontend\modules\tools\controllers;

use Yii;

use frontend\modules\tools\models\user\User;
use frontend\models\customer\CustomerContactType;
use frontend\models\customer\CustomerTitle;
use frontend\models\customer\CustomerLanguage;
use frontend\models\customer\Region;
use frontend\models\customer\Salutation;
use frontend\models\customer\Source;
use frontend\models\customer\AgeGroup;
use frontend\models\customer\Race;

use frontend\models\interaction\InteractionChannelType;
use frontend\models\cases\ChannelType;
use frontend\models\cases\SeverityLevel;

use frontend\models\interaction\InteractionCurrentOutcome;
use frontend\models\interaction\EscalationLevel;
use frontend\models\interaction\EscalatedTo;
use frontend\models\Country;
use frontend\models\City;
use frontend\models\State;
use frontend\models\Events;
use frontend\models\Logadm;


use frontend\models\Marquee;


use frontend\models\cases\CasePriority;
use frontend\models\cases\ComplaintDivision;
use frontend\models\cases\CaseStatus;
use frontend\models\cases\CaseType;
use frontend\models\cases\CallerType;


use frontend\models\cases\ProductReplacementType;
use frontend\models\cases\ProductReplacementStatus;
use frontend\models\cases\ReplacementDeliveryStatus;
use frontend\models\cases\ReplacementDeliveryMethod;
use frontend\models\cases\Product;
use frontend\models\cases\Brand;

use frontend\models\cases\OutcomeCode1;
use frontend\models\cases\OutcomeCode2;
use frontend\models\cases\OutcomeCode3;


use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class DropdownController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['contact-type','contact-title', 'contact-language',
                            'product-category', 'product-type', 'request-priority',
                            'request-status', 'request-center', 'request-type',
                            'request-detail', 'request-additional', 'request-supplemental',
                            'interaction-channel', 'interaction-outcome', 'country','complaint-division_id',],
                'rules' => [
                    [
                       'actions' => ['contact-type','contact-title', 'contact-language',
                                        'product-category', 'product-type', 'request-priority',
                                        'request-status', 'request-center', 'request-type',
                                        'request-detail', 'request-additional', 'request-supplemental',
                                        'interaction-channel', 'interaction-outcome', 'country','complaint-division_id',],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('Dropdown Management Module'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                            return true;
                        }
                    ]
               ],
            ],
        ];
    }

	public function actionCallertype()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new CallerType();
            $model->load(Yii::$app->request->post());
             $data=Yii::$app->request->post();
            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                 $logmodel= new Logadm();
            $logmodel->createInsertLog($data,$model);
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
			$id = Yii::$app->request->post('editableKey');

            $model = CallerType::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['CallerType']);
            $post['CallerType'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = CallerType::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => Callertype::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new CallerType();


        return $this->render('callertype', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
	public function actionCity()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new City();
            $model->load(Yii::$app->request->post());
             $data=Yii::$app->request->post();
            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
            $insert_id = Yii::$app->db->getLastInsertID();
            $logmodel= new Logadm();
            $logmodel->createInsertLogwM($data,$insert_id,"INSERT");
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
			$id = Yii::$app->request->post('editableKey');
             $oldName="";
            $newName="";
            $model = City::findOne($id);
             $oldName=$model->name;
                $newName="";
            $message = '';

            $post = [];
            $posted = current($_POST['City']);
            $post['City'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            $newName=$model->name;
            $logmodel= new Logadm();              
        $logmodel->createUpdateLogRule("City",$oldName,$newName);
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
        $data=Yii::$app->request->post();
            if(isset($state) && isset($id)) {
                $model = City::findOne($id);
                 $oldName=$model->name;
                $newName=Yii::$app->request->post('name');
                $model->deleted = ($state == 'true')?0:1;
                if($model->save(false)){
                     $oldData="true";
                        if($state=="true"){
                            $oldData="false";
                        }
                $insert_id = Yii::$app->db->getLastInsertID();
                $logmodel= new Logadm();
                $logmodel->createUpdateLogRule("Role: ".$model->name,$oldData,$state );
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => City::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new City();


        return $this->render('city', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

	public function actionProductReplacementType()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new ProductReplacementType();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
			$id = Yii::$app->request->post('editableKey');

            $model = ProductReplacementType::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['ProductReplacementType']);
            $post['ProductReplacementType'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = ProductReplacementType::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => ProductReplacementType::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new ProductReplacementType();


        return $this->render('product_replacement_type', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

//========================================================================================================================================================================
//========================================================================================================================================================================
    public function actionContactType()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new CustomerContactType();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = CustomerContactType::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['CustomerContactType']);
            $post['CustomerContactType'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = CustomerContactType::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => CustomerContactType::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new CustomerContactType();


        return $this->render('customer_type', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
//Added By Ahsan 15/09/2015
    public function actionDivision()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new ComplaintDivision();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = ComplaintDivision::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['ComplaintDivision']);
            $post['ComplaintDivision'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = ComplaintDivision::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => ComplaintDivision::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new ComplaintDivision();


        return $this->render('complaint_division', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
//Added by Ahsan 15/09/2015
     public function actionComplaintBrand()
    {

        if (Yii::$app->request->post('hasEditable')) {
            //echo "This action is here :hasEditable";
            //die(121234);
            $id = Yii::$app->request->post('editableKey');
            $model = ComplaintBrand::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['ComplaintBrand']);
            $post['ComplaintBrand'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            //echo "This action is here :hasEditable";
            // die(121234);
            $division_id = Yii::$app->request->get('division_id');
            //echo "This action is here :hasEditable".$division_id;
            // die(121234);
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => ComplaintBrand::find()->where(['division_id'=>$division_id])->orderby('name ASC'),
                'sort' => false
            ]);

            return $this->renderAjax('complaint_brand_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {

            $model = new ComplaintBrand();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                 //echo "This action is here :hasEditable";
                 //var_dump($model->division_id);
             //die(121234);
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => ComplaintBrand::find()->where(['division_id'=>$model->division_id])->orderby('name ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('complaint_brand_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            //echo "This action is here :hasEditable";
            // die(121234);
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = ComplaintBrand::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => ComplaintBrand::find()->orderby('name ASC'),//->where(['country_id'=>-1]),
            'sort' => false
        ]);

        $model = new ComplaintBrand();


        return $this->render('complaint_brand', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
    //Added by Ahsan 18/09/2015
public function actionComplaintSubBrand()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = ComplaintSubBrand::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['ComplaintSubBrand']);
            $post['ComplaintSubBrand'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            $brand_id = Yii::$app->request->get('brand_id');
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => ComplaintSubBrand::find()->where(['brand_id'=>$brand_id])->orderby('name ASC'),
                'sort' => false
            ]);
            //var_dump($brand_id);
            //die(1432);
            return $this->renderAjax('complaint_sub_brand_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {
            $model = new ComplaintSubBrand();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => ComplaintSubBrand::find()->where(['brand_id'=>$model->brand_id])->orderby('name ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('complaint_sub_brand_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = ComplaintSubBrand::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => ComplaintSubBrand::find()->orderby('name ASC'),//->where(['detail_type_id'=>-1]),
            'sort' => false
        ]);

        $model = new ComplaintSubBrand();


        return $this->render('complaint_sub_brand', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    //Added by Ahsan 18/09/2015
public function actionComplaintProduct()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = ComplaintProduct::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['ComplaintProduct']);
            $post['ComplaintProduct'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            $subbrand_id = Yii::$app->request->get('subbrand_id');
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => ComplaintProduct::find()->where(['subbrand_id'=>$subbrand_id])->orderby('name ASC'),
                'sort' => false
            ]);
            //var_dump($brand_id);
            //die(1432);
            return $this->renderAjax('complaint_product_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {
            $model = new ComplaintProduct();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => ComplaintProduct::find()->where(['subbrand_id'=>$model->subband_id])->orderby('name ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('complaint_product_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = ComplaintProduct::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => ComplaintProduct::find()->orderby('name ASC'),//->where(['detail_type_id'=>-1]),
            'sort' => false
        ]);

        $model = new ComplaintProduct();


        return $this->render('complaint_product', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    //Added by Ahsan 18/09/2015
public function actionComplaintColor()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = ComplaintColor::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['ComplaintColor']);
            $post['ComplaintColor'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            $product_id = Yii::$app->request->get('product_id');
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => ComplaintColor::find()->where(['product_id'=>$product_id])->orderby('name ASC'),
                'sort' => false
            ]);
            //var_dump($brand_id);
            //die(1432);
            return $this->renderAjax('complaint_color_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {
            $model = new ComplaintColor();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => ComplaintColor::find()->where(['product_id'=>$model->product_id])->orderby('name ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('complaint_color_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = ComplaintColor::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => ComplaintColor::find()->orderby('name ASC'),//->where(['detail_type_id'=>-1]),
            'sort' => false
        ]);

        $model = new ComplaintColor();


        return $this->render('complaint_color', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    //Added by Ahsan 18/09/2015
public function actionComplaintPacksize()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = ComplaintPacksize::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['ComplaintPacksize']);
            $post['ComplaintPacksize'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            $product_id = Yii::$app->request->get('product_id');
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => ComplaintPacksize::find()->where(['product_id'=>$product_id])->orderby('name ASC'),
                'sort' => false
            ]);
            //var_dump($brand_id);
            //die(1432);
            return $this->renderAjax('complaint_packsize_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {
            $model = new ComplaintPacksize();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => ComplaintPacksize::find()->where(['product_id'=>$model->product_id])->orderby('name ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('complaint_packsize_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = ComplaintPacksize::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => ComplaintPacksize::find()->orderby('name ASC'),//->where(['detail_type_id'=>-1]),
            'sort' => false
        ]);

        $model = new ComplaintPacksize();


        return $this->render('complaint_packsize', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
    public function actionContactTitle()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new CustomerTitle();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = CustomerTitle::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['CustomerTitle']);
            $post['CustomerTitle'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = CustomerTitle::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => CustomerTitle::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new CustomerTitle();


        return $this->render('customer_title', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    
    
    public function actionContactLanguage()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new CustomerLanguage();
            $model->load(Yii::$app->request->post());
             $data=Yii::$app->request->post();
            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                 $insert_id = Yii::$app->db->getLastInsertID();
                   $logmodel= new Logadm();
            $logmodel->createInsertLogwM($data,$insert_id,"INSERT");
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            
            $id = Yii::$app->request->post('editableKey');
            $oldName="";
            $newName="";
            $model = CustomerLanguage::findOne($id);
             $oldName=$model->name;
            
              
            $message = '';

            $post = [];
            $posted = current($_POST['CustomerLanguage']);
            $post['CustomerLanguage'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            if($model->save(false)){
                $newName=$model->name;
                 $logmodel= new Logadm();              
        $logmodel->createUpdateLogRule("Language",$oldName,$newName);

            }
           
        
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
              $data=Yii::$app->request->post();
            if(isset($state) && isset($id)) {
                $model = CustomerLanguage::findOne($id);
                $oldName=$model->name;
                $newName=Yii::$app->request->post('name');
                $model->deleted = ($state == 'true')?0:1;
                if($model->save(false)){
                     $oldData="true";
                        if($state=="true"){

                            $oldData="false";
                        }
                $insert_id = Yii::$app->db->getLastInsertID();
                        $logmodel= new Logadm();
                    $logmodel->createUpdateLogRule("Language: ".$model->name,$oldData,$state );
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => CustomerLanguage::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new CustomerLanguage();


        return $this->render('contact_language', [

            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionProductCategory()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new ProductCategory();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = ProductCategory::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['ProductCategory']);
            $post['ProductCategory'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = ProductCategory::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => ProductCategory::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new ProductCategory();


        return $this->render('product_category', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionProductType()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new ProductType();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = ProductType::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['ProductType']);
            $post['ProductType'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = ProductType::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => ProductType::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new ProductType();


        return $this->render('product_type', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
    public function actionPlaceOfPurchase()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new PlaceOfPurchase();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = PlaceOfPurchase::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['PlaceOfPurchase']);
            $post['PlaceOfPurchase'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = PlaceOfPurchase::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => PlaceOfPurchase::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new PlaceOfPurchase();


        return $this->render('place_of_purchase', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionRequestStatus()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new RequestStatus();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = RequestStatus::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['RequestStatus']);
            $post['RequestStatus'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = RequestStatus::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => RequestStatus::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new RequestStatus();


        return $this->render('request_status', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionRequestPriority()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new RequestPriority();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = RequestPriority::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['RequestPriority']);
            $post['RequestPriority'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = RequestPriority::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => RequestPriority::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new RequestPriority();


        return $this->render('request_priority', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
//Added By Ahsan 21/09/2015
    public function actionCasePriority()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new CasePriority();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = CasePriority::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['CasePriority']);
            $post['CasePriority'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = CasePriority::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => CasePriority::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new CasePriority();


        return $this->render('case_priority', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    //Added By Ahsan 21/09/2015
    public function actionCaseStatus()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new CaseStatus();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {

            $id = Yii::$app->request->post('editableKey');
            $model = CaseStatus::findOne($id);
			$model->updated_by = Yii::$app->user->identity->id;

            $message = '';

            $post = [];
            $posted = current($_POST['CaseStatus']);
            $post['CaseStatus'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = CaseStatus::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => CaseStatus::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new CaseStatus();


        return $this->render('case_status', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
    //Added By Ahsan 21/09/2015
    public function actionCaseType()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new CaseType();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = CaseType::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['CaseType']);
            $post['CaseType'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = CaseType::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => CaseType::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new CaseType();


        return $this->render('case_type', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    //Added By Ahsan 21/09/2015
    public function actionOutcomeCode1()
    {
            $model = new OutcomeCode1();

        if (Yii::$app->request->post('hasNew')) {
            $model->load(Yii::$app->request->post());
             $data=Yii::$app->request->post();
            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                 $insert_id = Yii::$app->db->getLastInsertID();

                $logmodel= new Logadm();
            $logmodel->createInsertLogwM($data,$insert_id,"INSERT");
            $hasError = false;
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $message = '';
            $model = OutcomeCode1::findOne($id);
             $oldName=$model->name;
             
                $newName=Yii::$app->request->post('OutcomeCode1')[0]['name'];
            $post = [];
            $posted = current($_POST['OutcomeCode1']);
            $post['OutcomeCode1'] = $posted;
            if ($model->load($post)) {
				$model->updated_by = Yii::$app->user->identity->id;
                //die(	$model->updated_by);
                if(!$model->save())
                    $message = ' ';
            }

             if($model->save(false)){
                
                 $logmodel= new Logadm();              
        $logmodel->createUpdateLogRule("OutcomeCode1",$oldName,$newName);

            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            $data=Yii::$app->request->post();
            if(isset($state) && isset($id)) {
                $model = OutcomeCode1::findOne($id);
                $oldName=$model->name;
                $newName=Yii::$app->request->post('name');
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $oldData="true";
                        if($state=="true"){

                            $oldData="false";
                        }
                $insert_id = Yii::$app->db->getLastInsertID();
                        $logmodel= new Logadm();
             $logmodel->createUpdateLogRule("Salutation: ".$model->name,$oldData,$state );
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => OutcomeCode1::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new OutcomeCode1();


        return $this->render('outcome_code1', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
//Added by Ahsan 21/09/2015
     public function actionOutcomeCode2()
    {

        if (Yii::$app->request->post('hasEditable')) {
            //echo "This action is here :hasEditable";
            //die(121234);
            $id = Yii::$app->request->post('editableKey');
            $model = OutcomeCode2::findOne($id);
            // $oldName=$model->name;
            // var_dump(Yii::$app->request->post('OutcomeCode2'));
            // die();
                // $newName=Yii::$app->request->post('OutcomeCode2')[2]['name'];
							$model->updated_by = Yii::$app->user->identity->id;

            $message = '';

            $post = [];
            $posted = current($_POST['OutcomeCode2']);
            $post['OutcomeCode2'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            if($model->save(false)){
                
        //          $logmodel= new Logadm();              
        // $logmodel->createUpdateLogRule("OutcomeCode2",$oldName,$newName);

            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            //echo "This action is here :hasEditable";
            // die(121234);
            $outcome_code1_id = Yii::$app->request->get('outcome_code1_id');
            //echo "This action is here :hasEditable".$division_id;
            // die(121234);
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => OutcomeCode2::find()->where(['outcome_code1_id'=>$outcome_code1_id])->orderby('name ASC'),
                'sort' => false
            ]);

            return $this->renderAjax('outcome_code2_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {

            $model = new OutcomeCode2();
            $model->load(Yii::$app->request->post());
            $data=Yii::$app->request->post();
            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                 $insert_id = Yii::$app->db->getLastInsertID();

                $logmodel= new Logadm();
            $logmodel->createInsertLogwM($data,$insert_id,"INSERT");
                $hasError = false;
                 //echo "This action is here :hasEditable";
                 //var_dump($model->division_id);
             //die(121234);
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => OutcomeCode2::find()->where(['outcome_code1_id'=>$model->outcome_code1_id])->orderby('name ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('outcome_code2_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
							$model->updated_by = Yii::$app->user->identity->id;

            //echo "This action is here :hasEditable";
            // die(121234);
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            $data=Yii::$app->request->post();
            if(isset($state) && isset($id)) {
                $model = OutcomeCode2::findOne($id);

                $oldName=$model->name;
                $newName=Yii::$app->request->post('name');
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $oldData="true";
                        if($state=="true"){

                            $oldData="false";
                        }
                $insert_id = Yii::$app->db->getLastInsertID();
                        $logmodel= new Logadm();
             $logmodel->createUpdateLogRule("Salutation: ".$model->name,$oldData,$state );
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => OutcomeCode2::find()->orderby('name ASC'),//->where(['country_id'=>-1]),
            'sort' => false
        ]);

        $model = new OutcomeCode2();


        return $this->render('outcome_code2', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
    //Added by Ahsan 21/09/2015
public function actionOutcomeCode3()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');

            $model = OutcomeCode3::findOne($id);
            $message = '';
			 $model->updated_by = Yii::$app->user->identity->id;

            $post = [];
            $posted = current($_POST['OutcomeCode3']);
            $post['OutcomeCode3'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            $outcome_code2_id = Yii::$app->request->get('outcome_code2_id');
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => OutcomeCode3::find()->where(['outcome_code2_id'=>$outcome_code2_id])->orderby('name ASC'),
                'sort' => false
            ]);
            //var_dump($brand_id);
            //die(1432);
            return $this->renderAjax('outcome_code3_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {
            $model = new OutcomeCode3();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => OutcomeCode3::find()->where(['outcome_code2_id'=>$model->outcome_code2_id])->orderby('name ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('outcome_code3_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = OutcomeCode3::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => OutcomeCode3::find()->orderby('name ASC'),//->where(['detail_type_id'=>-1]),
            'sort' => false
        ]);

        $model = new OutcomeCode3();


        return $this->render('outcome_code3', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    
    public function actionOutcomeCode4()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = OutcomeCode4::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['OutcomeCode4']);
            $post['OutcomeCode4'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            $outcome_code3_id = Yii::$app->request->get('outcome_code3_id');
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => OutcomeCode4::find()->where(['outcome_code3_id'=>$outcome_code3_id])->orderby('name ASC'),
                'sort' => false
            ]);
            //var_dump($brand_id);
            //die(1432);
            return $this->renderAjax('outcome_code4_list', [
                'dataProvider' => $dataProvider,
            ]);
           
        }


        if (Yii::$app->request->post('hasNew')) {
            $model = new OutcomeCode4();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => OutcomeCode4::find()->where(['outcome_code3_id'=>$model->outcome_code3_id])->orderby('name ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('outcome_code4_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = OutcomeCode4::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => OutcomeCode4::find()->orderby('name ASC'),//->where(['detail_type_id'=>-1]),
            'sort' => false
        ]);

        $model = new OutcomeCode4();


        return $this->render('outcome_code4', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
    
    
    
    public function actionOutcomeCode5()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = OutcomeCode5::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['OutcomeCode5']);
            $post['OutcomeCode5'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            $outcome_code4_id = Yii::$app->request->get('outcome_code4_id');
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => OutcomeCode5::find()->where(['outcome_code4_id'=>$outcome_code4_id])->orderby('name ASC'),
                'sort' => false
            ]);
            //var_dump($brand_id);
            //die(1432);
            return $this->renderAjax('outcome_code5_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {
            $model = new OutcomeCode5();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => OutcomeCode5::find()->where(['outcome_code4_id'=>$model->outcome_code4_id])->orderby('name ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('outcome_code5_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = OutcomeCode5::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => OutcomeCode5::find()->orderby('name ASC'),//->where(['detail_type_id'=>-1]),
            'sort' => false
        ]);

        $model = new OutcomeCode5();


        return $this->render('outcome_code5', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
    //Added By Ahsan 21/09/2015
     public function actionComplaintUserType()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new ComplaintUserType();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = ComplaintUserType::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['ComplaintUserType']);
            $post['ComplaintUserType'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = ComplaintUserType::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => ComplaintUserType::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new ComplaintUserType();


        return $this->render('complaint_user_type', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionRequestCenter()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = RequestServiceCenter::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['RequestServiceCenter']);
            $post['RequestServiceCenter'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            $country_id = Yii::$app->request->get('country-id');
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => RequestServiceCenter::find()->where(['country_id'=>$country_id])->orderby('name ASC'),
                'sort' => false
            ]);

            return $this->renderAjax('request_center_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {
            $model = new RequestServiceCenter();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => RequestServiceCenter::find()->where(['country_id'=>$model->country_id])->orderby('name ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('request_center_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = RequestServiceCenter::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => RequestServiceCenter::find()->orderby('name ASC'),//->where(['country_id'=>-1]),
            'sort' => false
        ]);

        $model = new RequestServiceCenter();


        return $this->render('request_center', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }



    public function actionRequestDevice()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = RequestDevice::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['RequestDevice']);
            $post['RequestDevice'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            $type = Yii::$app->request->get('type');
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => RequestDevice::find()->where(['type'=>$type])->orderby('model ASC'),
                'sort' => false
            ]);

            return $this->renderAjax('request_device_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {
            $model = new RequestDevice();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                echo "This action is here :hasEditable";
                 var_dump($model->type);
                 die(234);
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => RequestDevice::find()->where(['type'=>$model->type])->orderby('model ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('request_device_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = RequestDevice::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => RequestDevice::find()->orderby('model ASC'),//->where(['country_id'=>-1]),
            'sort' => false
        ]);

        $model = new RequestDevice();


        return $this->render('request_device', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionRequestDeviceType()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new RequestDeviceType();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = RequestDeviceType::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['RequestDeviceType']);
            $post['RequestDeviceType'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = RequestDeviceType::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => RequestDeviceType::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new RequestDeviceType();


        return $this->render('request_device_type', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionRequestEnhancement()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = RequestEnhancement::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['RequestEnhancement']);
            $post['RequestEnhancement'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            $category = Yii::$app->request->get('category');
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => RequestEnhancement::find()->where(['category'=>$category])->orderby('material ASC'),
                'sort' => false
            ]);

            return $this->renderAjax('request_enhancement_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {
            $model = new RequestEnhancement();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => RequestEnhancement::find()->where(['category'=>$model->category])->orderby('material ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('request_enhancement_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = RequestEnhancement::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => RequestEnhancement::find()->orderby('material ASC'),//->where(['country_id'=>-1]),
            'sort' => false
        ]);

        $model = new RequestEnhancement();

        return $this->render('request_enhancement', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionRequestEnhancementCategory()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new RequestEnhancementCategory();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = RequestEnhancementCategory::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['RequestEnhancementCategory']);
            $post['RequestEnhancementCategory'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = RequestEnhancementCategory::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => RequestEnhancementCategory::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new RequestEnhancementCategory();


        return $this->render('request_enhancement_category', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionRequestSoftware()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = RequestSoftware::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['RequestSoftware']);
            $post['RequestSoftware'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            $category = Yii::$app->request->get('category');
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => RequestSoftware::find()->where(['category'=>$category])->orderby('group ASC'),
                'sort' => false
            ]);

            return $this->renderAjax('request_software_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {
            $model = new RequestSoftware();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => RequestSoftware::find()->where(['category'=>$model->category])->orderby('group ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('request_software_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = RequestSoftware::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => RequestSoftware::find()->orderby('group ASC'),//->where(['country_id'=>-1]),
            'sort' => false
        ]);

        $model = new RequestSoftware();

        return $this->render('request_software', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionRequestSoftwareCategory()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new RequestSoftwareCategory();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = RequestSoftwareCategory::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['RequestSoftwareCategory']);
            $post['RequestSoftwareCategory'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = RequestSoftwareCategory::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => RequestSoftwareCategory::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new RequestSoftwareCategory();


        return $this->render('request_software_category', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionRequestType()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new RequestType();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = RequestType::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['RequestType']);
            $post['RequestType'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = RequestType::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => RequestType::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new RequestType();


        return $this->render('request_type', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionRequestDetail()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = RequestDetailType::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['RequestDetailType']);
            $post['RequestDetailType'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            $type_id = Yii::$app->request->get('type-id');
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => RequestDetailType::find()->where(['type_id'=>$type_id])->orderby('name ASC'),
                'sort' => false
            ]);

            return $this->renderAjax('request_detail_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {
            $model = new RequestDetailType();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => RequestDetailType::find()->where(['type_id'=>$model->type_id])->orderby('name ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('request_detail_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = RequestDetailType::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => RequestDetailType::find()->orderby('name ASC'),//->where(['type_id'=>-1]),
            'sort' => false
        ]);

        $model = new RequestDetailType();


        return $this->render('request_detail', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionRequestAdditional()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = RequestAdditionalType::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['RequestAdditionalType']);
            $post['RequestAdditionalType'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            $detail_type_id = Yii::$app->request->get('type-id');
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => RequestAdditionalType::find()->where(['detail_type_id'=>$detail_type_id])->orderby('name ASC'),
                'sort' => false
            ]);

            return $this->renderAjax('request_additional_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {
            $model = new RequestAdditionalType();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => RequestAdditionalType::find()->where(['detail_type_id'=>$model->detail_type_id])->orderby('name ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('request_additional_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = RequestAdditionalType::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => RequestAdditionalType::find()->orderby('name ASC'),//->where(['detail_type_id'=>-1]),
            'sort' => false
        ]);

        $model = new RequestAdditionalType();


        return $this->render('request_additional', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionRequestSupplemental()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = RequestSupplementalType::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['RequestSupplementalType']);
            $post['RequestSupplementalType'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            $additional_type_id = Yii::$app->request->get('type-id');
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => RequestSupplementalType::find()->where(['additional_type_id'=>$additional_type_id])->orderby('name ASC'),
                'sort' => false
            ]);

            return $this->renderAjax('request_supplemental_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {
            $model = new RequestSupplementalType();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => RequestSupplementalType::find()->where(['additional_type_id'=>$model->additional_type_id])->orderby('name ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('request_supplemental_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = RequestSupplementalType::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => RequestSupplementalType::find()->orderby('name ASC'),//->where(['additional_type_id'=>-1]),
            'sort' => false
        ]);

        $model = new RequestSupplementalType();


        return $this->render('request_supplemental', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionInteractionChannel()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new ChannelType();
            $model->load(Yii::$app->request->post());
            $data=Yii::$app->request->post();
            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $insert_id = Yii::$app->db->getLastInsertID();

                $logmodel= new Logadm();
            $logmodel->createInsertLogwM($data,$insert_id,"INSERT");
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = ChannelType::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['ChannelType']);
            $post['ChannelType'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = ChannelType::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => ChannelType::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new ChannelType();


        return $this->render('interaction_channel', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionInteractionOutcome()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new InteractionCurrentOutcome();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = InteractionCurrentOutcome::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['InteractionCurrentOutcome']);
            $post['InteractionCurrentOutcome'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = InteractionCurrentOutcome::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => InteractionCurrentOutcome::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new InteractionCurrentOutcome();


        return $this->render('interaction_outcome', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionCountry()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new Country();
            $model->load(Yii::$app->request->post());
            $data=Yii::$app->request->post();

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $insert_id = Yii::$app->db->getLastInsertID();
            $logmodel= new Logadm();
            $logmodel->createInsertLogwM($data,$insert_id,"INSERT");
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
             $oldName="";
            $newName="";
            $model = Country::findOne($id);
             $oldName=$model->name;            
            $newName=Yii::$app->request->post('name');
            $message = '';
            $post = [];
            $posted = current($_POST['Country']);
            $post['Country'] = $posted;
            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
              
            if($model->save(false)){
                
                 $logmodel= new Logadm();              
        $logmodel->createUpdateLogRule("Country",$oldName,$newName);

            }         
            // $logmodel->createUpdateLogRule("Country",$oldName,$newName);
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('id');
         
            $id = Yii::$app->request->get('id');
            $saved = false;
            $data=Yii::$app->request->post();
            if(isset($state) && isset($id)) {
                $model = Country::findOne($id);
                $oldName=$model->name;
                $newName=Yii::$app->request->post('name');
                $model->deleted = ($state == 'true')?0:1;
               
                if($model->save()){
                     $oldData="true";
                        if($state=="true"){
                            $oldData="false";
                        }
                $insert_id = Yii::$app->db->getLastInsertID();
                $logmodel= new Logadm();
                $logmodel->createUpdateLogRule("Language: ".$model->name,$oldData,$state );
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => Country::find()->orderby('name ASC'),
            'sort' => false
        ]);

        $model = new Country();


        return $this->render('country', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionMarquee() {


        if (Yii::$app->request->post('hasUpdate')) {
            $model = Marquee::findOne(1);
            $message = '';

            $hasError = true;
            if ($model->load(Yii::$app->request->post())) {
                if($model->save()) {
                    $hasError = false;
                }
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }


        $model = Marquee::findOne(1);

        return $this->render('marquee', [
            'model' => $model
        ]);
    }



	public function actionRegion()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new Region();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            if($model->save()) {
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = Region::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['Region']);
            $post['Region'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = Region::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => Region::find()->orderby('region_name ASC'),
            'sort' => false
        ]);

        $model = new Region();


        return $this->render('region', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

	public function actionEscalationLevel()
    {

        if (Yii::$app->request->post('hasEditable')) {
            //echo "This action is here :hasEditable";
            //die(121234);
            $id = Yii::$app->request->post('editableKey');
            $model = EscalationLevel::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['EscalationLevel']);
            $post['EscalationLevel'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            //echo "This action is here :hasEditable";
            // die(121234);
            $interaction_outcome_id = Yii::$app->request->get('interaction_outcome_id');
            //echo "This action is here :hasEditable".$division_id;
            // die(121234);
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => EscalationLevel::find()->where(['interaction_outcome_id'=>$interaction_outcome_id])->orderby('name ASC'),
                'sort' => false
            ]);

            return $this->renderAjax('escalation_level_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {

            $model = new EscalationLevel();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                 //echo "This action is here :hasEditable";
                 //var_dump($model->division_id);
             //die(121234);
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => EscalationLevel::find()->where(['interaction_outcome_id'=>$model->interaction_outcome_id])->orderby('name ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('escalation_level_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            //echo "This action is here :hasEditable";
            // die(121234);
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = EscalationLevel::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => EscalationLevel::find()->orderby('name ASC'),//->where(['country_id'=>-1]),
            'sort' => false
        ]);

        $model = new EscalationLevel();


        return $this->render('escalation_level', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
    //Added by Ahsan 21/09/2015
public function actionEscalatedTo()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = EscalatedTo::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['EscalatedTo']);
            $post['EscalatedTo'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('refresh-widget')) {
            $escalation_level_id = Yii::$app->request->get('escalation_level_id');
          //  $interaction_outcome_id = Yii::$app->request->get('interaction_outcome_id');
            $dataProvider = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                'query' => EscalatedTo::find()->where(['escalation_level_id'=>$escalation_level_id])->orderby('name ASC'),
                'sort' => false
            ]);
            //var_dump($brand_id);
            //die(1432);
            return $this->renderAjax('escalated_to_list', [
                'dataProvider' => $dataProvider,
            ]);
        }


        if (Yii::$app->request->post('hasNew')) {
            $model = new EscalatedTo();
            $model->load(Yii::$app->request->post());

            $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
				//$escalation_level_id = Yii::$app->request->get('escalation_level_id');
				//$interaction_outcome_id = Yii::$app->request->get('interaction_outcome_id');
                $hasError = false;
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    'query' => EscalatedTo::find()->where(['escalation_level_id'=>$model->escalation_level_id])->orderby('name ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('escalated_to_list', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
                $model = EscalatedTo::findOne($id);
                $model->deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 5),
            'query' => EscalatedTo::find()->orderby('name ASC'),//->where(['detail_type_id'=>-1]),
            'sort' => false
        ]);

        $model = new EscalatedTo();


        return $this->render('escalated_to', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

//jalis added this on 2/8/2018...

public function actionProductReplacementStatus()
{

    if (Yii::$app->request->post('hasNew')) {
        $model = new ProductReplacementStatus();
        $model->load(Yii::$app->request->post());

        $model->created_by = Yii::$app->user->identity->id;
        $hasError = true;
        if($model->save()) {
            $hasError = false;
        }
        echo Json::encode(['hasError'=>$hasError]);
        return;
    }

    if (Yii::$app->request->post('hasEditable')) {
        $id = Yii::$app->request->post('editableKey');
        $model = ProductReplacementStatus::findOne($id);
        $message = '';

        $post = [];
        $posted = current($_POST['ProductReplacementStatus']);
        $post['ProductReplacementStatus'] = $posted;

        if ($model->load($post)) {
            if(!$model->save())
                $message = ' ';
        }
        echo Json::encode(['output'=>'', 'message'=>$message]);
        return;
    }

    if (Yii::$app->request->get('hasToggle')) {
        $state = Yii::$app->request->get('state');
        $id = Yii::$app->request->get('id');
        $saved = false;
        if(isset($state) && isset($id)) {
            $model = ProductReplacementStatus::findOne($id);
            $model->deleted = ($state == 'true')?0:1;
            if($model->save()){
                $saved = true;
            }
        }
        echo Json::encode(['saved'=>$saved]);
        return;
    }

    $dataProvider = new ActiveDataProvider([
        'pagination' => array('pageSize' => 5),
        'query' => ProductReplacementStatus::find()->orderby('name ASC'),
        'sort' => false
    ]);

    $model = new ProductReplacementStatus();
    return $this->render('product_replacement_status', [
        'dataProvider' => $dataProvider,
        'model' => $model
    ]);
}

public function actionReplacementDeliveryStatus()
{

    if (Yii::$app->request->post('hasNew')) {
        $model = new ReplacementDeliveryStatus();
        $model->load(Yii::$app->request->post());

        $model->created_by = Yii::$app->user->identity->id;
        $hasError = true;
        if($model->save()) {
            $hasError = false;
        }
        echo Json::encode(['hasError'=>$hasError]);
        return;
    }

    if (Yii::$app->request->post('hasEditable')) {
        $id = Yii::$app->request->post('editableKey');
        $model = ReplacementDeliveryStatus::findOne($id);
        $message = '';

        $post = [];
        $posted = current($_POST['ReplacementDeliveryStatus']);
        $post['ReplacementDeliveryStatus'] = $posted;

        if ($model->load($post)) {
            if(!$model->save())
                $message = ' ';
        }
        echo Json::encode(['output'=>'', 'message'=>$message]);
        return;
    }

    if (Yii::$app->request->get('hasToggle')) {
        $state = Yii::$app->request->get('state');
        $id = Yii::$app->request->get('id');
        $saved = false;
        if(isset($state) && isset($id)) {
            $model = ReplacementDeliveryStatus::findOne($id);
            $model->deleted = ($state == 'true')?0:1;
            if($model->save()){
                $saved = true;
            }
        }
        echo Json::encode(['saved'=>$saved]);
        return;
    }

    $dataProvider = new ActiveDataProvider([
        'pagination' => array('pageSize' => 5),
        'query' => ReplacementDeliveryStatus::find()->orderby('name ASC'),
        'sort' => false
    ]);

    $model = new ReplacementDeliveryStatus();
    return $this->render('replacement_delivery_status', [
        'dataProvider' => $dataProvider,
        'model' => $model
    ]);
}

public function actionReplacementDeliveryMethod()
{

    if (Yii::$app->request->post('hasNew')) {
        $model = new ReplacementDeliveryMethod();
        $model->load(Yii::$app->request->post());

        $model->created_by = Yii::$app->user->identity->id;
        $hasError = true;
        if($model->save()) {
            $hasError = false;
        }
        echo Json::encode(['hasError'=>$hasError]);
        return;
    }

    if (Yii::$app->request->post('hasEditable')) {
        $id = Yii::$app->request->post('editableKey');
        $model = ReplacementDeliveryMethod::findOne($id);
        $message = '';

        $post = [];
        $posted = current($_POST['ReplacementDeliveryMethod']);
        $post['ReplacementDeliveryMethod'] = $posted;

        if ($model->load($post)) {
            if(!$model->save())
                $message = '';
        }
        echo Json::encode(['output'=>'', 'message'=>$message]);
        return;
    }

    if (Yii::$app->request->get('hasToggle')) {
        $state = Yii::$app->request->get('state');
        $id = Yii::$app->request->get('id');
        $saved = false;
        if(isset($state) && isset($id)) {
            $model = ReplacementDeliveryMethod::findOne($id);
            $model->deleted = ($state == 'true')?0:1;
            if($model->save()){
                $saved = true;
            }
        }
        echo Json::encode(['saved'=>$saved]);
        return;
    }

    $dataProvider = new ActiveDataProvider([
        'pagination' => array('pageSize' => 5),
        'query' => ReplacementDeliveryMethod::find()->orderby('name ASC'),
        'sort' => false
    ]);

    $model = new ReplacementDeliveryMethod();
    return $this->render('replacement_delivery_method', [
        'dataProvider' => $dataProvider,
        'model' => $model
    ]);
}

public function actionProduct()
{
    if (Yii::$app->request->post('hasNew')) {
        $model = new Product();
        $model->load(Yii::$app->request->post());

        $model->created_by = Yii::$app->user->identity->id;
        $hasError = true;
        if($model->save()) {
            $hasError = false;
        }
        echo Json::encode(['hasError'=>$hasError]);
        return;
    }

    if (Yii::$app->request->post('hasEditable')) {
        $id = Yii::$app->request->post('editableKey');
        $model = Product::findOne($id);
        $message = '';

        $post = [];
        $posted = current($_POST['Product']);
        $post['Product'] = $posted;

        if ($model->load($post)) {
            if(!$model->save())
                $message = ' ';
        }
        echo Json::encode(['output'=>'', 'message'=>$message]);
        return;
    }

    if (Yii::$app->request->get('hasToggle')) {
        $state = Yii::$app->request->get('state');
        $id = Yii::$app->request->get('id');
        $saved = false;
        if(isset($state) && isset($id)) {
            $model = Product::findOne($id);
            $model->deleted = ($state == 'true')?0:1;
            if($model->save()){
                $saved = true;
            }
        }
        echo Json::encode(['saved'=>$saved]);
        return;
    }

    $dataProvider = new ActiveDataProvider([
        'pagination' => array('pageSize' => 5),
        'query' => Product::find()->orderby('name ASC'),
        'sort' => false
    ]);

    $model = new Product();


    return $this->render('product', [
        'dataProvider' => $dataProvider,
        'model' => $model
    ]);
}

public function actionSalutation()
{
    if (Yii::$app->request->post('hasNew')) {
        $model = new Salutation();
        $model->load(Yii::$app->request->post());
        $data=Yii::$app->request->post();
        $model->created_by = Yii::$app->user->identity->id;
        $hasError = true;
        if($model->save()) {
            $insert_id = Yii::$app->db->getLastInsertID();

                $logmodel= new Logadm();
            $logmodel->createInsertLogwM($data,$insert_id,"INSERT");
            $hasError = false;
        }
        echo Json::encode(['hasError'=>$hasError]);
        return;
    }

    if (Yii::$app->request->post('hasEditable')) {
        $id = Yii::$app->request->post('editableKey');
         $oldName="";
            $newName="";
        $model = Salutation::findOne($id);
		        $model->updated_by = Yii::$app->user->identity->id;

                $oldName=$model->name;
                $newName=Yii::$app->request->post('Salutation')[0]['name'];;
        $message = '';

        $post = [];
        $posted = current($_POST['Salutation']);
        $post['Salutation'] = $posted;

        if ($model->load($post)) {
            if(!$model->save())
                $message = ' ';
        }
        if($model->save(false)){
                
                 $logmodel= new Logadm();              
        $logmodel->createUpdateLogRule("Salutation",$oldName,$newName);

            }
        echo Json::encode(['output'=>'', 'message'=>$message]);
        return;
    }

    if (Yii::$app->request->get('hasToggle')) {
        
        $state = Yii::$app->request->get('state');
        $id = Yii::$app->request->get('id');
        $saved = false;
        $data=Yii::$app->request->post();
        if(isset($state) && isset($id)) {
            $model = Salutation::findOne($id);

                $oldName=$model->name;
                $newName=Yii::$app->request->post('name');
					        $model->updated_by = Yii::$app->user->identity->id;

            $model->deleted = ($state == 'true')?0:1;
            if($model->save()){
                 $oldData="true";
                        if($state=="true"){

                            $oldData="false";
                        }
                $insert_id = Yii::$app->db->getLastInsertID();
                        $logmodel= new Logadm();
             $logmodel->createUpdateLogRule("Salutation: ".$model->name,$oldData,$state );
                $saved = true;
            }
        }
        echo Json::encode(['saved'=>$saved]);
        return;
    }

    $dataProvider = new ActiveDataProvider([
        'pagination' => array('pageSize' => 5),
        'query' => Salutation::find()->orderby('name ASC'),
        'sort' => false
    ]);

    $model = new Salutation();


    return $this->render('salutation', [
        'dataProvider' => $dataProvider,
        'model' => $model
    ]);
}

public function actionDataSource()
{
    if (Yii::$app->request->post('hasNew')) {
        $model = new Source();
        $model->load(Yii::$app->request->post());

        $model->created_by = Yii::$app->user->identity->id;
        $hasError = true;
        if($model->save()) {
            $hasError = false;
        }
        echo Json::encode(['hasError'=>$hasError]);
        return;
    }

    if (Yii::$app->request->post('hasEditable')) {
        $id = Yii::$app->request->post('editableKey');
        $model = Source::findOne($id);
        $message = '';

        $post = [];
        $posted = current($_POST['Source']);
        $post['Source'] = $posted;

        if ($model->load($post)) {
            if(!$model->save())
                $message = ' ';
        }
        echo Json::encode(['output'=>'', 'message'=>$message]);
        return;
    }

    if (Yii::$app->request->get('hasToggle')) {
        $state = Yii::$app->request->get('state');
        $id = Yii::$app->request->get('id');
        $saved = false;
        if(isset($state) && isset($id)) {
            $model = Source::findOne($id);
            $model->deleted = ($state == 'true')?0:1;
            if($model->save()){
                $saved = true;
            }
        }
        echo Json::encode(['saved'=>$saved]);
        return;
    }

    $dataProvider = new ActiveDataProvider([
        'pagination' => array('pageSize' => 5),
        'query' => Source::find()->orderby('name ASC'),
        'sort' => false
    ]);

    $model = new Source();


    return $this->render('data_source', [
        'dataProvider' => $dataProvider,
        'model' => $model
    ]);
}
public function actionAgeGroup()
{
    if (Yii::$app->request->post('hasNew')) {
        $model = new AgeGroup();
        $model->load(Yii::$app->request->post());

        $model->created_by = Yii::$app->user->identity->id;
        $hasError = true;
        if($model->save()) {
            $hasError = false;
        }
        echo Json::encode(['hasError'=>$hasError]);
        return;
    }

    if (Yii::$app->request->post('hasEditable')) {
        $id = Yii::$app->request->post('editableKey');
        $model = AgeGroup::findOne($id);
        $message = '';

        $post = [];
        $posted = current($_POST['AgeGroup']);
        $post['AgeGroup'] = $posted;

        if ($model->load($post)) {
            if(!$model->save())
                $message = '';
        }
        echo Json::encode(['output'=>'', 'message'=>$message]);
        return;
    }

    if (Yii::$app->request->get('hasToggle')) {
        $state = Yii::$app->request->get('state');
        $id = Yii::$app->request->get('id');
        $saved = false;
        if(isset($state) && isset($id)) {
            $model = AgeGroup::findOne($id);
            $model->deleted = ($state == 'true')?0:1;
            if($model->save()){
                $saved = true;
            }
        }
        echo Json::encode(['saved'=>$saved]);
        return;
    }

    $dataProvider = new ActiveDataProvider([
        'pagination' => array('pageSize' => 5),
        'query' => AgeGroup::find()->orderby('name ASC'),
        'sort' => false
    ]);

    $model = new AgeGroup();


    return $this->render('age_group', [
        'dataProvider' => $dataProvider,
        'model' => $model
    ]);
}

public function actionRace()
{
    if (Yii::$app->request->post('hasNew')) {
        $model = new Race();
        $model->load(Yii::$app->request->post());

        $model->created_by = Yii::$app->user->identity->id;
        $hasError = true;
        if($model->save()) {
            $hasError = false;
        }
        echo Json::encode(['hasError'=>$hasError]);
        return;
    }

    if (Yii::$app->request->post('hasEditable')) {
        $id = Yii::$app->request->post('editableKey');
        $model = Race::findOne($id);
        $message = '';

        $post = [];
        $posted = current($_POST['Race']);
        $post['Race'] = $posted;

        if ($model->load($post)) {
            if(!$model->save())
                $message = '';
        }
        echo Json::encode(['output'=>'', 'message'=>$message]);
        return;
    }

    if (Yii::$app->request->get('hasToggle')) {
        $state = Yii::$app->request->get('state');
        $id = Yii::$app->request->get('id');
        $saved = false;
        if(isset($state) && isset($id)) {
            $model = Race::findOne($id);
            $model->deleted = ($state == 'true')?0:1;
            if($model->save()){
                $saved = true;
            }
        }
        echo Json::encode(['saved'=>$saved]);
        return;
    }

    $dataProvider = new ActiveDataProvider([
        'pagination' => array('pageSize' => 5),
        'query' => Race::find()->orderby('name ASC'),
        'sort' => false
    ]);

    $model = new Race();


    return $this->render('race', [
        'dataProvider' => $dataProvider,
        'model' => $model
    ]);
}

public function actionState()
{
    if (Yii::$app->request->post('hasNew')) {
        $model = new State();
        $model->load(Yii::$app->request->post());
        $data=Yii::$app->request->post();
        $model->created_by = Yii::$app->user->identity->id;
        $hasError = true;
        if($model->save()) {
              $insert_id = Yii::$app->db->getLastInsertID();

                $logmodel= new Logadm();
            $logmodel->createInsertLogwM($data,$insert_id,"INSERT");
            $hasError = false;
        }
        echo Json::encode(['hasError'=>$hasError]);
        return;
    }

    if (Yii::$app->request->post('hasEditable')) {
        $id = Yii::$app->request->post('editableKey');
         $newName="";
        $message = '';
        $model = State::findOne($id);

                $oldName=$model->name;
                $newName=Yii::$app->request->post('State')[0]['name'];
      
           

        $post = [];
        $posted = current($_POST['State']);
        $post['State'] = $posted;

        if ($model->load($post)) {
            if(!$model->save())
                $message = '';
        }
        if($model->save(false)){
                
                 $logmodel= new Logadm();              
        $logmodel->createUpdateLogRule("State",$oldName,$newName);

            }
        echo Json::encode(['output'=>'', 'message'=>$message]);
        return;
    }

    if (Yii::$app->request->get('hasToggle')) {
        $state = Yii::$app->request->get('state');
        $id = Yii::$app->request->get('id');
        $saved = false;
        $data=Yii::$app->request->post();

        if(isset($state) && isset($id)) {
            $model = State::findOne($id);
              $oldName=$model->name;
                $newName=Yii::$app->request->post('name');
                            // $model->updated_by = Yii::$app->user->identity->id;
            $model->deleted = ($state == 'true')?0:1;
            if($model->save(false)){
                  $oldData="true";
                        if($state=="true"){

                            $oldData="false";
                        }
                $insert_id = Yii::$app->db->getLastInsertID();
                        $logmodel= new Logadm();
             $logmodel->createUpdateLogRule("State: ".$model->name,$oldData,$state );
                $saved = true;
            }
        }
        echo Json::encode(['saved'=>$saved]);
        return;
    }

    $dataProvider = new ActiveDataProvider([
        'pagination' => array('pageSize' => 5),
        'query' => State::find()->orderby('name ASC'),
        'sort' => false
    ]);

    $model = new State();


    return $this->render('state', [
        'dataProvider' => $dataProvider,
        'model' => $model
    ]);
}

public function actionBrand()
{
    if (Yii::$app->request->post('hasNew')) {
        $model = new Brand();
        $model->load(Yii::$app->request->post());

        $model->created_by = Yii::$app->user->identity->id;
        $hasError = true;
        if($model->save()) {
            $hasError = false;
        }
        echo Json::encode(['hasError'=>$hasError]);
        return;
    }

    if (Yii::$app->request->post('hasEditable')) {
        $id = Yii::$app->request->post('editableKey');
        $model = Brand::findOne($id);
        $message = '';

        $post = [];
        $posted = current($_POST['Brand']);
        $post['Brand'] = $posted;

        if ($model->load($post)) {
            if(!$model->save())
                $message = '';
        }
        echo Json::encode(['output'=>'', 'message'=>$message]);
        return;
    }

    if (Yii::$app->request->get('hasToggle')) {
        $state = Yii::$app->request->get('state');
        $id = Yii::$app->request->get('id');
        $saved = false;
        if(isset($state) && isset($id)) {
            $model = Brand::findOne($id);
            $model->deleted = ($state == 'true')?0:1;
            if($model->save()){
                $saved = true;
            }
        }
        echo Json::encode(['saved'=>$saved]);
        return;
    }

    $dataProvider = new ActiveDataProvider([
        'pagination' => array('pageSize' => 5),
        'query' => Brand::find()->orderby('name ASC'),
        'sort' => false
    ]);

    $model = new Brand();


    return $this->render('brand', [
        'dataProvider' => $dataProvider,
        'model' => $model
    ]);
}

public function actionEvents()
{
    if (Yii::$app->request->post('hasNew')) {
        $model = new Events();
        $model->load(Yii::$app->request->post());

        $model->created_by = Yii::$app->user->identity->id;
        $hasError = true;
        if($model->save()) {
            $hasError = false;
        }
        echo Json::encode(['hasError'=>$hasError]);
        return;
    }

    if (Yii::$app->request->post('hasEditable')) {
        $id = Yii::$app->request->post('editableKey');
        $model = Events::findOne($id);
        $message = '';

        $post = [];
        $posted = current($_POST['Events']);
        $post['Events'] = $posted;

        if ($model->load($post)) {
            if(!$model->save())
                $message = '';
        }
        echo Json::encode(['output'=>'', 'message'=>$message]);
        return;
    }

    if (Yii::$app->request->get('hasToggle')) {
        $state = Yii::$app->request->get('state');
        $id = Yii::$app->request->get('id');
        $saved = false;
        if(isset($state) && isset($id)) {
            $model = Events::findOne($id);
            $model->is_deleted = ($state == 'true')?0:1;
            if($model->save()){
                $saved = true;
            }
        }
        echo Json::encode(['saved'=>$saved]);
        return;
    }

    $dataProvider = new ActiveDataProvider([
        'pagination' => array('pageSize' => 5),
        'query' => Events::find()->orderby('event_name ASC'),
        'sort' => false
    ]);

    $model = new Events();


    return $this->render('events', [
        'dataProvider' => $dataProvider,
        'model' => $model
    ]);
}

public function actionSeverityLevel()
{

    if (Yii::$app->request->post('hasNew')) {
        $model = new SeverityLevel();
        $model->load(Yii::$app->request->post());

        $model->created_by = Yii::$app->user->identity->id;
        $hasError = true;
        if($model->save()) {
            $hasError = false;
        }
        echo Json::encode(['hasError'=>$hasError]);
        return;
    }

    if (Yii::$app->request->post('hasEditable')) {
        $id = Yii::$app->request->post('editableKey');
        $model = SeverityLevel::findOne($id);
		        $model->updated_by = Yii::$app->user->identity->id;

        $message = '';

        $post = [];
        $posted = current($_POST['SeverityLevel']);
        $post['SeverityLevel'] = $posted;

        if ($model->load($post)) {
            if(!$model->save())
                $message = '';
        }
        echo Json::encode(['output'=>'', 'message'=>$message]);
        return;
    }

    if (Yii::$app->request->get('hasToggle')) {
        $state = Yii::$app->request->get('state');
        $id = Yii::$app->request->get('id');
        $saved = false;
        if(isset($state) && isset($id)) {
            $model = SeverityLevel::findOne($id);
            $model->deleted = ($state == 'true')?0:1;
            if($model->save()){
                $saved = true;
            }
        }
        echo Json::encode(['saved'=>$saved]);
        return;
    }

    $dataProvider = new ActiveDataProvider([
        'pagination' => array('pageSize' => 5),
        'query' => SeverityLevel::find()->orderby('name ASC'),
        'sort' => false
    ]);

    $model = new SeverityLevel();

    return $this->render('severity_level', [
        'dataProvider' => $dataProvider,
        'model' => $model
    ]);
}

//end jalis's code here...

}
