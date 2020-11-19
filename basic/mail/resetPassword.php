<?php 

	use yii\helpers\Html;
	use app\modules\userAuth\models\User;

	echo "Hello, ".Html::encode($user->username)."!";
	echo Html::a('Enter this link to reset password.',
		Yii::$app->urlManager->createAbsoluteUrl(
		[
			'/usAuth/default/password-reseting',
			'key' => $user->resetKey,
		]
	));
?>