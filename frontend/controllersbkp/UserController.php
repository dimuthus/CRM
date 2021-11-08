<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use frontend\modules\tools\models\user\User;
use frontend\modules\tools\models\user\UserLoginAttempt;
use frontend\modules\tools\models\user\UserPasswordHistory;
use yii\data\ActiveDataProvider;
use yii\db\Query;
/**
 * Site controller
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'login','changepassword'],
                'rules' => [
                    [
                        'actions' => ['changepassword'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
       // die('useractionindex');
        return true;
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(isset(Yii::$app->user->identity->id))
        return $this->render('view', [
            'model' => $this->findModel(Yii::$app->user->identity->id),
        ]);
		else
		{
 
            return $this->goHome();
    
		}
    }


    public function actionLogin()
    {
        $this->layout = 'login';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) ) {
            //Check if user account is locked!!
            

            if($this->isLoggedIn($model)){
                    
                    // var_dump(Yii::$app->getRequest()->userIP);
                
                 $model->addError("username", Yii::t('user', 'Account Logged in already. Please Contact Line Manager to unlock'));
                return $this->render('login', [
                    'model' => $model,
                ]);
                


            }

            if(!$this->isLocked($model)){
                if($model->login()){
                    //Update Login Attempt table
                    UserLoginAttempt::updateAll(['deleted' => 1], ['=', 'username', $model->username]);
                    
                                 $lastRecord= User::find()->where(['=', 'username', $model->username])->limit(1)->all();
           if($lastRecord){

            $lastRecord[0]->logged_in='1';
             $lastRecord[0]->ip_address =Yii::$app->getRequest()->userIP;
            $lastRecord[0]->save();
        }


                    if(Yii::$app->user->identity->firsttime==0){
                        return $this->redirect('changepassword');
                    }
                    if($this->dateDifference(date('Y-m-d h:i:s'),Yii::$app->user->identity->passwordtupdate_datetime)>89 || Yii::$app->user->identity->passwordtupdate_datetime==NULL)
                    {           



                       return $this->redirect('changepassword');
                    }         
        
                    return $this->redirect('../search/');                
                }
                else {

                    if($model->username!=null){
                        $invalidLoginAttempt=new UserLoginAttempt();
						$invalidLoginAttempt->ip_address=Yii::$app->getRequest()->userIP;

                        $invalidLoginAttempt->username=$model->username;
                        $invalidLoginAttempt->save();
                    }
                    //Locked at Third time
                    if($this->isLocked($model)){
                        $model->addError("username", Yii::t('user', 'Account locked!! Try again after 10 mins or Contact with Line Manager.'));
                    }
                    
                    return $this->render('login', [
                        'model' => $model,
                    ]);
                }
            }
            else{
                //echo "Locked";
                $model->addError("username", Yii::t('user', 'Account locked!! Try again after 10 mins or Contact with Line Manager.'));
                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    private function isLocked($model)
    {
        $stamp = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -10 minutes"));
       // phpinfo();
      //  print_r(date("Y-m-d H:i:s"));
      //  echo "</br>";
      //  print_r($stamp);
        $result = UserLoginAttempt::find()
                ->where('creation_datetime > :creation_datetime', [':creation_datetime' => $stamp])
                ->andWhere('deleted = :deleted', [':deleted' => 0])
                ->andWhere('username = :username', [':username' => $model->username])
                ->all();
       //print_r($result);
       //echo count($result);
       if(count($result)>2)
            return true;
       else
           return false;
    }
private function isLoggedIn($model)
    {
       
       // phpinfo();
      //  print_r(date("Y-m-d H:i:s"));
      //  echo "</br>";
      //  print_r($stamp);


             $result2 = User::find()
                ->where('logged_in = :logged_in', [':logged_in' => 1])
                ->andWhere('username = :username', [':username' => $model->username])
                ->andWhere('ip_address = :ip_address', [':ip_address' => Yii::$app->getRequest()->userIP])
                ->all();
                if(sizeof($result2 )>0){

                        
               return false;


                }



        $result = User::find()
                ->where('logged_in = :logged_in', [':logged_in' => 1])
                ->andWhere('username = :username', [':username' => $model->username])
                ->all();
         //    echo $result->createCommand()->getRawSql();
         // die('as');
                
       //print_r($result);
       // //echo count($result);
       //          var_dump($result); die('puka');
       if(count($result)>0)
            return true;
       else
           return false;
    }

    private function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);

    }
    public function actionLogout()
    {
		$this->layout = 'login';
        
          $lastRecord= User::find()->where(['=', 'id', Yii::$app->user->identity->id])->limit(1)->all();
           if($lastRecord){
            $lastRecord[0]->logged_in='0';

            Yii::$app->user->logout();    
            $lastRecord[0]->save();
        }
        
        $model = new LoginForm();
		return $this->redirect('login');
    }

 public function actionChangepassword()
    {
       
        $model = Yii::$app->user->identity;
        // $model->scenario = 'changepassword';
        $model->passwordtupdate_datetime=date('Y-m-d h:i:s'); 
        $model->firsttime=1;

        if ($model->load(Yii::$app->request->post())  ) {
            //Update Password History Table
            $oldpass= $model->oldpassword;
            $this->updatePasswordHistory($model);
            $check= $model->newpassword;
            
             $newpasswordhash= Yii::$app->security->generatePasswordHash($check);   
             $model->password_hash=$newpasswordhash;
             
           if(!$this->changePasswordCheck($model,$oldpass)){
             Yii::$app->session->setFlash('error', "Wrong Current Password");
                // print_r("lalala from");
                return  $this->redirect('changepassword');
                
           }
            
            $model->save();

           
			 

             $this->layout = 'login';

			Yii::$app->user->logout();
			 

            $model = new LoginForm();
           Yii::$app->session->setFlash('success', "Password successfully updated");
            return $this->redirect('login');
            


        } else {
			 $this->layout = 'login';

                    
            return $this->render('update_password', [
                'model' => $model,
            ]);
        }
    }
    private function updatePasswordHistory($model)
    {
        $result = UserPasswordHistory::find()
                ->where('user_id = :user_id', [':user_id' => $model->id])
                ->andWhere('deleted = :deleted', [':deleted' => 0])
                ->orderBy(['last_modified_datetime' => SORT_DESC])
                ->all();
        $userPasswordHistory=new UserPasswordHistory();
       if(count($result)>12){
            //Update Last one
           $lastRecord= UserPasswordHistory::find()->where(['=', 'id', $result[12]->id])->limit(1)->all();
           if($lastRecord){
            $lastRecord[0]->password_hash=$model->password_hash;
            $lastRecord[0]->created_by=Yii::$app->user->identity->id;
            $lastRecord[0]->save();
           }
       }
       else
       {
           //insert new one
           $userPasswordHistory->user_id=$model->id;
           $userPasswordHistory->password_hash=$model->password_hash;
           $userPasswordHistory->created_by=Yii::$app->user->identity->id;
           $userPasswordHistory->save();
       }
    }
    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    
}
