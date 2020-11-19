<?php 

	namespace app\modules\userAuth\models;

	use Yii;
	use yii\base\Model;
	use app\modules\userAuth\models\User;

	class RetrievePasswordForm extends Model{

		public $email;

		public function rules(){
			return[
				[['email'], 'required','message' => 'You did miss something!'],
				['email','email'],
				['email','exist','targetClass' => User::className(),'message' => 'We haven`t user with this E-mail!'],
			];
		}

		public function attributeLabels() {
	        return [
	            'email' => 'E-mail',
	        ];
    	}

    	public function sendEmail(){

    		$user = User::findOne(['email' => $this->email]);

    		if($user){

    			$user->generateResetKey();

				if($user->save()){
    			
	    			return Yii::$app->mailer->compose('resetPassword',['user' => $user])
	    				   ->setFrom('volikov.dmitrie@yandex.ru')
	    				   ->setTo($this->email)
	    				   ->setSubject('Password reseting for Yii2 project')
	    				   ->send();
    			
    			}

    		}

    		return false;

    	}

	}
?>