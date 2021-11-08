<?php

namespace frontend\modules\tools\controllers;

use Yii;
use frontend\modules\tools\models\user\User;
use mdm\admin\models\searchs\AuthItem as AuthItemSearch;
use yii\rbac\Item;
use mdm\admin\models\AuthItem;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use frontend\models\Logadm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use frontend\modules\tools\models\user\UserLoginAttempt;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update','resetpassword','role'],
                'rules' => [
                    [
                       'actions' => ['index', 'view'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('User Management Module'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                            return true;
                        }
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                             if(!Yii::$app->user->can('User Management Module'))
                                 throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                             if(!Yii::$app->user->can('Create User'))
                                 throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                             return true;
                         }
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                             if(!Yii::$app->user->can('User Management Module'))
                                 throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                             if(!Yii::$app->user->can('Update User'))
                                 throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                             return true;
                         }
                    ],
                    [
                        'actions' => ['resetpassword'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                             if(!Yii::$app->user->can('User Management Module'))
                                 throw new ForbiddenHttpException(Yii::$app->params['authorizationErrorAction']);
                             if(!Yii::$app->user->can('Reset User Password'))
                                 throw new ForbiddenHttpException(Yii::$app->params['authorizationErrorAction']);
                             return true;
                         }
                    ],
                    [
                        'actions' => ['role'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                             if(!Yii::$app->user->can('Role Management Module'))
                                 throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                             return true;
                         }
                    ],
               ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
		//die('ddddddddddddddddddddddddddddd');
        $dataProvider = new ActiveDataProvider([
            'pagination' =>false, // array('pageSize' => 5),
            'query' => User::find()
            ->joinWith('status')
            ->where('user.role_id != :role_id', ['role_id'=>'Admin']),
            'sort' => ['attributes' => [
                    'username',
                    'email',
                    'full_name' => [
                        'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
                        'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
                        'default' => SORT_DESC
                    ],
                    'status.name' => [
                        'asc' => ['user_status.name' => SORT_ASC],
                        'desc' => ['user_status.name' => SORT_DESC],
                        'default' => SORT_DESC
                    ],
                    'role.name'
                ]
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if($id == 1)
            throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create';
        
        if ($model->load(Yii::$app->request->post())) {
             $data=Yii::$app->request->post();
            $model->created_by = Yii::$app->user->identity->id;
            $model->firsttime=1;
            if($model->save()) {
                try {

 $logmodel= new Logadm();
 $data['User']["password_hash"]= "";
 $data['User']["repeatpassword"]="";
            $logmodel->createInsertLog($data,$model);
                    $manager = Yii::$app->authManager;
                    $item = $manager->getRole($model->role_id);
                    $item = $item ? : $manager->getPermission($model->role_id);
                    $manager->assign($item, $model->id);
                    return $this->redirect(['view', 'id' => $model->id]);
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                    return $this->render('create', [
                        'model' => $model,
                        'error' => $error
                    ]);
                }
            }
            else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            $model->status_id = 1;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if($id == 1)
            throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
      
        $model = $this->findModel($id);
        $oldData= $model->getOldAttributes();
        $role = $model->role_id;
        if ($model->load(Yii::$app->request->post())){
            $data=Yii::$app->request->post();
         
            if($model->save(false)){
                  $logmodel= new Logadm();
                  //$logmodel->createUpdateLog($oldData,$data,$model);

            }
            
               

            if($model->role_id != $role) {
                try {
                    $manager = Yii::$app->authManager;
                    $item = $manager->getRole($role);
                    $item = $item ? : $manager->getPermission($role);
                    $manager->revoke($item, $model->id);

                    $item = $manager->getRole($model->role_id);
                    $item = $item ? : $manager->getPermission($model->role_id);
                    $manager->assign($item, $model->id);

                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                    return $this->render('update', [
                        'model' => $model,
                        'error' => $error
                    ]);
                }
            }
			Yii::$app->session->setFlash('success', "User info sucessfully updated!."); 

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If reset password is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionResetpassword($id)
    {
		//$id=1;
        $model = $this->findModel($id);
        $model->scenario = 'resetpassword';
        $model->firsttime=0;
        $model->password_hash = $model->default_password; 
        $oldData= $model->getOldAttributes();
        if ($model->save()) {
            //Update Login Attmpt History
            //UserLoginAttempt::model()->updateAll(array('deleted'=>1),'username="'.$model->username.'"');
             $logmodel= new Logadm();

            $logmodel->createUpdateLogRule("Reset Password",$model->username,"");
            $models = UserLoginAttempt::find()->where('username = "'.$model->username.'"')->all();
            foreach ($models as $aModel) {
                  
            
                $aModel->deleted = 1;
                $aModel->update(false); // skipping validation as no user input is involved
            }
			 Yii::$app->session->setFlash('success', "Password sucessfully reset."); 
             return $this->redirect(['view', 'id'=>$id,'message'=>'Password Successfully reset']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


      public function actionUnlockuser($id)
    {
        //$id=1;
        
        $model = $this->findModel($id);
        
        $model->logged_in=0;
        
        if ($model->save(false)) {
            //Update Login Attmpt History
            //UserLoginAttempt::model()->updateAll(array('deleted'=>1),'username="'.$model->username.'"');
                         $logmodel= new Logadm();              
            $logmodel->createUpdateLogRule("Unlock User: ",$model->username,"");
			Yii::$app->session->setFlash('success', "Password sucessfully unlocked."); 

            return $this->redirect(['view', 'id'=>$id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }






    public function actionRole()
    {

        if (Yii::$app->request->post('hasNew')) {
            $model = new AuthItem(null);
            $model->load(Yii::$app->request->post());
            $data=Yii::$app->request->post();
            $model->type = Item::TYPE_ROLE;
            $model->ruleName = 3;

            $hasError = true;
            if($model->save()) {
                $insert_id = Yii::$app->db->getLastInsertID();
                $keys =array_keys($data);

                $tablename=$keys[1];
                $dataUpdate= $data[$tablename];

                $data[$tablename]="Roles";
                $logmodel= new Logadm();
            $logmodel->createInsertLogwM($data,$insert_id,"INSERT");
                $hasError = false;
            }
            echo Json::encode(['hasError'=>$hasError]);
            return;
        }

        if (Yii::$app->request->post('hasEditable')) {
            $oldName="";
            $newName="";
            $id = Yii::$app->request->post('editableKey');
            $message = ''; 
            $item = Yii::$app->getAuthManager()->getRole($id);

            if ($item) {
                $model = new AuthItem($item);
               
                $oldName=$model->name;
                $newName=Yii::$app->request->post('name');
                $model->name = Yii::$app->request->post('name');
                if(!$model->save()){
                     
                    $message = ' ';
                }
            }
            
              $logmodel= new Logadm();
              
            $logmodel->createUpdateLogRule("Roles",$oldName,$newName);
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }

        if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
        
            $id = Yii::$app->request->get('id');
            if(!$this->checkExistingUsers($id)){


                
                return false;
            };

            $saved = false;
            $data=Yii::$app->request->post();

            if(isset($state) && isset($id)) {
                $item = Yii::$app->getAuthManager()->getRole($id);
                if ($item) {
                    $model = new AuthItem($item);
                    

                    $model->ruleName = ($state == 'true')?3:4;
                    if($model->save()){
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
                
            }

            echo Json::encode(['saved'=>$saved]);
            return;
        }

        $model = new AuthItem(null);

        $searchModel = new AuthItemSearch(['type' => Item::TYPE_ROLE]);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->pagination = ['pageSize' => 5];

        return $this->render('role', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
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


    protected function checkExistingUsers($id){
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
    SELECT id FROM user  WHERE role_id = :role_id ", [':role_id' => $id]);

$result= $command->queryAll();

if(sizeof($result)>0){
    return false;

}
return true;

    }
}
