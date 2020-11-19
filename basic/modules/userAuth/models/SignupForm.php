<?php

namespace app\modules\userAuth\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\IntegrityException;

class SignupForm extends Model{

	public $username;
	public $email;
	public $displayname;
	public $password;
    public $resetKey;
    public $authKey;

	public function rules(){
		return[
			[['username','email','displayname','password'],'required','message' => 'Вы кое-что забыли!'],
            ['username','unique','targetClass' => 'app\modules\userAuth\models\User'],
			[['email'],'email'],
			['email','unique','targetClass' => 'app\modules\userAuth\models\User'],
			['username','match','pattern' => '/^[a-zA-Z0-9]+$/', 'message' => 'Please, use this symbols: a-zA-Z0-9'],
			['password','match', 'pattern' => '(^(?xi)(?=(?:.*[0-9]){2})(?=(?:.*[a-z]){2})(?=(?:.*[!"#$%&\'()*+,./:;<=>?@\[\]^_`{|}~-]){2}).{6,}$)', 'message' => 'Password must contain at least 2 letters, 2 numbers and 2 special symbhols!'],
            ['resetKey','safe'],
            ['authKey','safe'],
		];
	}

	public function attributeLabels() {
        return [
            'username' => 'Login',
            'password' => 'Password',
            'email' => 'E-mail',
            'displayname' => 'Name',
        ];
    }

}

?>
