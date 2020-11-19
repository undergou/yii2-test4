<?php

namespace app\modules\userAuth\controllers;

use Yii;
use yii\web\IdentityInterface;
use yii\base\Security;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\authclient\ClientInterface;
use app\modules\userAuth\models\LoginForm;
use app\modules\userAuth\models\SignupForm;
use app\modules\userAuth\models\PasswordResetingForm;
use app\modules\userAuth\models\RetrievePasswordForm;
use app\modules\userAuth\models\User;

/**
 * Default controller for the `usAuth` module
 */
class DefaultController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout', 'register','retrieve-password','password-reseting','index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'register'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout','index'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['retrieve-password','password-reseting'],
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this,'oAuthSuccess'],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function oAuthSuccess($client){

        $userAttributes = $client->getUserAttributes();

    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionPasswordReseting($key){

        try{
    	   $model = new PasswordResetingForm($key);
        }
        catch(InvalidParamException $err){
            throw new BadRequestHttpException($err->getMessage());
        }

        if($model->load(Yii::$app->request->post())){

            if($model->validate() && $model->resetPassword()){

                Yii::$app->getSession()->setFlash('success','Password was changed!');
                return $this->goHome();

            }

        }

    	return $this->render('password-reseting', compact('model'));
    }

    public function actionRetrievePassword(){
    	$model = new RetrievePasswordForm();

        if($model->load(Yii::$app->request->post())){

            if($model->validate()){

                if($model->sendEmail()){
                    Yii::$app->getSession()->setFlash('warning','Please, check your E-mail!');
                    return $this->goHome();
                } else{
                    Yii::$app->getSession()->setFlash('error','Impossible to reset password!');
                }

            }

        }

    	return $this->render('retrieve-password', compact('model'));
    }

    public function actionUserActivation($authKey){

        $user = User::findOne(['authKey' => $authKey]);

        $role = Yii::$app->authManager->getRole('active');
        Yii::$app->authManager->assign($role, $user->id);

        Yii::$app->getSession()->setFlash('success','You proved that you deserve be with us!');

        return $this->goHome();

    }

}
