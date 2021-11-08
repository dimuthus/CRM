<?php

namespace frontend\modules\tools\controllers;

use mdm\admin\models\AuthItem;
use mdm\admin\models\searchs\AuthItem as AuthItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\rbac\Item;
use Yii;
use mdm\admin\components\MenuHelper;
use yii\filters\AccessControl;
use yii\helpers\Html;
use frontend\models\Logadm;
/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class PermissionController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                             if(!Yii::$app->user->can('Permission Management Module'))
                                 throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                             return true;
                         }
                    ],
               ],
            ],
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $avaliable = $assigned = [
            'Resources' => [],
            'Actions' => []
        ];

        if (Yii::$app->request->isAjax && Yii::$app->request->get('refresh-widget')) {

            $id = Yii::$app->request->get('role-id');
            $model = $this->findModel($id);

            $authManager = Yii::$app->getAuthManager();
            
            $children = array_keys($authManager->getChildren($id));
            $children[] = $id;

            foreach ($authManager->getPermissions() as $permission) {
                $name = $permission->name;
                if (in_array($name, $children)) {
                    continue;
                }
                $avaliable[$permission->ruleName == 1 ? 'Resources' : 'Actions'][$name] = $name;
            }

            foreach ($authManager->getChildren($id) as $permission) {
                $name = $permission->name;
                if ($permission->type != Item::TYPE_ROLE) {
                    $assigned[$permission->ruleName == 1 ? 'Resources' : 'Actions'][$name] = $name;
                } 
            }

            $avaliable = array_filter($avaliable);
            $assigned = array_filter($assigned);

            return $this->renderAjax('view', ['model' => $model, 'avaliable' => $avaliable, 'assigned' => $assigned]);
        }

        $searchModel = new AuthItemSearch(['rule' => 3]);
        $roles = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', ['roles'=>$roles, 'avaliable' => $avaliable, 'assigned' => $assigned]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {	
        $model = new AuthItem(null);
        $model->type = Item::TYPE_ROLE;
        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {

        	$data=Yii::$app->request->post();
        	$logmodel= new Logadm();
            $logmodel->createInsertLogWm($data,$id);
            MenuHelper::invalidate();

            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('create', ['model' => $model,]);
        }
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param  string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            MenuHelper::invalidate();

            return $this->redirect(['view', 'id' => $model->name]);
        }

        return $this->render('update', ['model' => $model,]);
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param  string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Yii::$app->getAuthManager()->remove($model->item);
        MenuHelper::invalidate();

        return $this->redirect(['index']);
    }

    /**
     * Assign or remove items
     * @param string $id
     * @param string $action
     * @return array
     */
    public function actionAssign($id, $action)
    {	
        $post = Yii::$app->getRequest()->post();
        $roles = $post['roles'];
      	 $logmodel= new Logadm();
        $manager = Yii::$app->getAuthManager();
        $parent = $manager->getRole($id);
        $error = [];
        if ($action == 'assign') {

            foreach ($roles as $role) {
                $child = $manager->getRole($role);
                $child = $child ? : $manager->getPermission($role);
                try {
                    $manager->addChild($parent, $child);
                   
		            $logmodel->createInsertLogWm($post,$id,$action);

                } catch (\Exception $e) {
                    $error[] = $e->getMessage();
                }
            }
        } else {
        	
            foreach ($roles as $role) {
                $child = $manager->getRole($role);
                $child = $child ? : $manager->getPermission($role);
                try {
                
                    $manager->removeChild($parent, $child);
                     $logmodel->createInsertLogWm($post,$id,"REMOVE");
                } catch (\Exception $e) {
                    $error[] = $e->getMessage();
                }
            }
        }
        MenuHelper::invalidate();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [$this->actionRoleSearch($id, 'avaliable', $post['search_av']),
            $this->actionRoleSearch($id, 'assigned', $post['search_asgn']),
            $error];
    }

    /**
     * Search role
     * @param string $id
     * @param string $target
     * @param string $term
     * @return array
     */
    public function actionRoleSearch($id, $target, $term = '')
    {
        $result = [
            'Resources' => [],
            'Actions' => []
        ];

        $authManager = Yii::$app->authManager;
        if ($target == 'avaliable') {
            $children = array_keys($authManager->getChildren($id));
            $children[] = $id;
            foreach ($authManager->getPermissions() as $permission) {
                $name = $permission->name;
                if (in_array($name, $children)) {
                    continue;
                }
                if (empty($term) or strpos($name, $term) !== false) {
                    $result[$permission->ruleName == 1 ? 'Resources' : 'Actions'][$name] = $name;
                }
            }
        } else {
            foreach ($authManager->getChildren($id) as $permission) {
                $name = $permission->name;
                if (empty($term) or strpos($name, $term) !== false) {
                    if ($permission->type == Item::TYPE_ROLE) {
                        //$result['Roles'][$name] = $name;
                    } else {
                        $result[$permission->ruleName == 1 ? 'Resources' : 'Actions'][$name] = $name;
                    }
                }
            }
        }

        return Html::renderSelectOptions('', array_filter($result));
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  string        $id
     * @return AuthItem      the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $item = Yii::$app->getAuthManager()->getRole($id);
        if ($item) {
            return new AuthItem($item);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}