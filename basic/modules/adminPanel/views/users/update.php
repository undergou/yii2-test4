<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use app\modules\userAuth\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = 'Update Users: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="users-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="users-form">

	    <?php $form = ActiveForm::begin([
            'id' => 'update',
        ]); ?>

	    <?= $form->field($model, 'username',['inputOptions' => ['id' => 'update-username']])->textInput(['maxlength' => true]) ?>

	    <?= $form->field($model, 'email',['inputOptions' => ['id' => 'update-email']])->textInput(['maxlength' => true]) ?>

	    <?= $form->field($model, 'displayname',['inputOptions' => ['id' => 'update-displayname']])->textInput(['maxlength' => true]) ?>

	    <?= $form->field($model, 'password',['inputOptions' => ['id' => 'update-password']])->passwordInput(['maxlength' => true]) ?>
	
	    <?php
	    	$user = User::findOne(['username' => $model->username]);
	    	$checkAdmin = Yii::$app->authManager->getAssignment('admin', $user->id);

	    	if($checkAdmin){
	    		echo $form->field($model, 'makeAdmin')->checkbox(['value' => 'admin','checked' => true]);
	    	} else{
	    		echo $form->field($model, 'makeAdmin')->checkbox(['value' => 'admin','checked' => false]);
	    	}
	    ?>

	    <div class="form-group">
	        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
