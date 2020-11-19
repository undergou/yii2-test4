<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $displayname
 * @property string $password
 * @property string $resetKey
 * @property string $makeAdmin
 */
class Users extends \yii\db\ActiveRecord
{
    public $makeAdmin = true;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'displayname', 'password','makeAdmin','resetKey','authKey'], 'required'],
            ['makeAdmin','boolean'],
            ['email', 'email'],
            ['password','match', 'pattern' => '(^(?xi)(?=(?:.*[0-9]){2})(?=(?:.*[a-z]){2})(?=(?:.*[!"#$%&\'()*+,./:;<=>?@\[\]^_`{|}~-]){2}).{6,}$)', 'message' => 'Password must contain at least 2 letters, 2 numbers and 2 special symbhols!'],
            ['username','match','pattern' => '/^[a-zA-Z0-9]+$/', 'message' => 'Please, use this symbols: a-zA-Z0-9'],
            [['username', 'email', 'displayname', 'password'], 'string', 'max' => 64],
            ['resetKey', 'safe'],
            ['authKey', 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'displayname' => 'Displayname',
            'password' => 'Password',
            'makeAdmin' => 'Admin',
        ];
    }

    public function setPassword($password){
        $this->password = sha1($password);
    }
}
