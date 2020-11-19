<?php

namespace app\modules\adminPanel\controllers;

use Yii;
use app\models\Users;
use app\models\UsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\modules\userAuth\models\User;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['users','index','create','update','view','_form','_search'],
                'rules' => [
                    [
                        'actions' => ['users','index','create','update','view','_form','_search'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();
        
        $model->resetKey = sha1(uniqid(time(), true));
        $model->authKey = sha1(uniqid(time(), true));

        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword($model->password);
            if($model->save(false)){
                Yii::$app->mailer->compose('toActivateUser',['user' => $model])
                    ->setFrom('volikov.dmitrie@yandex.ru')
                    ->setTo($model->email)
                    ->setSubject('User activation in Yii2 project')
                    ->send();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user = User::findOne(['username' => $model->username]);

        if ($model->load(Yii::$app->request->post())) {

            if($model->makeAdmin === "admin"){
                $checkAdmin = Yii::$app->authManager->getAssignment('admin', $user->id);
                if(!$checkAdmin){
                    $role = Yii::$app->authManager->getRole('admin');
                	Yii::$app->authManager->assign($role, $user->id);
                }
            } else{
                $checkAdmin = Yii::$app->authManager->getAssignment('admin', $user->id);
                if($checkAdmin){
                    $role = Yii::$app->authManager->getRole('admin');
                    Yii::$app->authManager->revoke($role, $user->id);
                }
            }

            if($model->password === $user->password){
	            $model->save(false);
	            return $this->redirect(['view', 'id' => $model->id]);
	        } else{
	        	$model->setPassword($model->password);
	        	$model->save(false);
	        	return $this->redirect(['view', 'id' => $model->id]);
	        }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
//        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
