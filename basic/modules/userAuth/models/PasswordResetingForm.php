<?php

	namespace app\modules\userAuth\models;

	use Yii;
	use yii\base\Model;
	use yii\base\InvalidParamException;

	class PasswordResetingForm extends Model{

		public $password;
		private $_user;

		public function rules(){
			return[
				[['password'],'required','message' => 'You did miss something!'],
			];
		}

		public function attributeLabels() {
	        return[
	            'password' => 'Password',
	        ];
    	}

    	public function __construct($key, $config = []){

    		if(empty($key) || !is_string($key))
    			throw new InvalidParamException("Reset key can not be empty!");
    		$this->_user = User::findByResetKey($key);
    		if(!$this->_user)
    			throw new InvalidParamException("User not found!");
    		parent::__construct($config);

    	}

    	public function resetPassword(){

    		$user = $this->_user;
    		$user->setPassword($this->password);
    		$user->resetKey = sha1(uniqid(time(), true));
    		return $user->save();

    	}
    		
	}
	
?>