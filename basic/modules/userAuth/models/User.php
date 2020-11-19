<?php

namespace app\modules\userAuth\models;
use Yii;


class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName(){
        return 'users';
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    
    public function getId()
    {
        return $this->id;   
    }
    
    public static function findIdentityByAccessToken($token, $type = null)
    {
    
    }

    public function getAuthKey()
    {
        
    }

    public function validateAuthKey($authKey)
    {
        
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function isResetKeyExpire($key){

        if(empty($key))
            return false;

        $expire = Yii::$app->params['resetKeyExpire'];
        $parts = explode('_', $key);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();

    }

    public static function findByResetKey($key){

        if(!static::isResetKeyExpire($key))
            return null;

        return static::findOne(['resetKey' => $key]);

    }

    public function generateResetKey(){

        $this->resetKey = Yii::$app->security->generateRandomString().'_'.time();

    }

    public function removeResetKey(){

        $this->resetKey = null;

    }

    public function setPassword($password){
        $this->password = sha1($password);
    }

    public function validatePassword($password)
    {
        return $this->password === sha1($password);
    }

    public function create(){

        return $this->save(false);

    }

    public function saveFromVk($uid, $first_name, $last_name){

        $user = User::findOne($uid);

        if($user){
            return Yii::$app->user->login($user);
        }

        $this->id = $uid;
        $this->username = "VK-user";
        $this->displayname = $first_name." ".$last_name;
        $this->password = sha1('password');
        $this->resetKey = sha1(uniqid(time(), true));
        $this->create();

        return Yii::$app->user->login($this);
        
    }

    public function saveFromGoogleAndFacebook($email, $name){

        $user = static::findOne(['email' => $email]);

        if($user){
            return Yii::$app->user->login($user);
        } else{
            $this->username = "Google or Facebook user";
            $this->displayname = $name;
            $this->email = $email;
            $this->resetKey = sha1(uniqid(time(), true));
            $this->password = sha1('password');
            $this->create();

            return Yii::$app->user->login($this);
        }
        
    }

}