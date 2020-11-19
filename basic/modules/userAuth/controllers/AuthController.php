<?php

namespace app\modules\userAuth\controllers;

use Yii;
use yii\web\IdentityInterface;
use yii\base\Security;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\db\IntegrityException;
use yii\authclient\ClientInterface;
use app\modules\userAuth\models\LoginForm;
use app\modules\userAuth\models\SignupForm;
use app\modules\userAuth\models\User;

class AuthController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout', 'register'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'register'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
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


    public function actionLogin(){

        $model = new LoginForm();

        if(!Yii::$app->user->isGuest){
            return $this->goHome();
        }

        if($model->load(Yii::$app->request->post())){

            $user = User::findOne(['username' => $model->username]);

            if(!$user){
                Yii::$app->getSession()->setFlash('error','Who are you? We don`t know you!');
            } else{

                $checkAdmin = Yii::$app->authManager->getAssignment('admin', $user->getId());
                $checkActive = Yii::$app->authManager->getAssignment('active', $user->getId());

                if(!$checkAdmin && !$checkActive){
                    Yii::$app->getSession()->setFlash('error','Stop! You have not proved that you deserve to be with us!');
                } else if(sha1($model->password) !== $user->password){
                    Yii::$app->getSession()->setFlash('error','ERROR!BLEAT!!');
                } else {
                    $model->login();
                    return $this->goHome();
                }
            }
        }

        $model->password = '';

        return $this->render('login', compact('model'));

    }

    public function oAuthSuccess($client){

        $userAttributes = $client->getUserAttributes();

        $email = $userAttributes['email'];
        $name = $userAttributes['name'];

        $user = new User();
        if($user->saveFromGoogleAndFacebook($email, $name)){
            return $this->goHome();
        }

    }

    public function actionRegister(){

    	$model = new SignupForm();

		if($model->load(Yii::$app->request->post()) && $model->validate()){
            $user = new User();

            $user->username = $model->username;
            $user->email = $model->email;
            $user->setPassword($model->password);
            $user->displayname = $model->displayname;
            $user->resetKey = sha1(uniqid(time(), true));
            $user->authKey = sha1(uniqid(time(), true));

            if($user->save()){

                Yii::$app->mailer->compose('toActivateUser',['user' => $user])
                    ->setFrom('volikov.dmitrie@yandex.ru')
                    ->setTo($user->email)
                    ->setSubject('User activation in Yii2 project')
                    ->send();

                Yii::$app->getSession()->setFlash('success','Welcome to the club, budy! Open your mail and prove, that you deserve to participate in our club!');

                return $this->goHome();
            }
			//Yii::$app->getSession()->setFlash('success','Welcome to the club, budy!');
		}

		return $this->render('register', compact('model'));

    }

    public function actionLoginVk($uid, $first_name, $last_name){

        $user = new User();
        if($user->saveFromVk($uid, $first_name, $last_name)){
            return $this->goHome();
        }

    }

}


/*
public function validatePassword($password)
    {
        return $this->password === sha1($password);
    }

    public function setPassword($password){
        $this->password = sha1($password);
    }

    public function setResetKey($resetKey){
        $this->resetKey = Yii::$app->security->generateRandomString(10);
    }

    public function create(){
        return $this->save(false);
    }

    public function saveFromVk($uid, $first_name){
        $user = User::findOne($uid);

        if($user){
            return Yii::$app->user->login($user);
        }

        $this->id = $uid;
        $this->displayname = $first_name;
        $this->create();

        return Yii::$app->user->login($this);
    }
    */
