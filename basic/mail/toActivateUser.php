<?php

	use yii\helpers\Html;
	use app\modules\userAuth\models\User;

	echo "Hello, ".Html::encode($user->username)."!<br />";
	echo Html::a('Follow the link to prove that you deserve to be with us!',

		"http://localhost/usAuth/default/user-activation?authKey=" . $user->authKey

	);
?>
