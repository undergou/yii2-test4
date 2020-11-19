<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = 'Create Users';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="users-form">

        <?php $form = ActiveForm::begin([
            'id' => 'register',
        ]); ?>

        <?= $form->field($model, 'username',['inputOptions' => ['id' => 'register-username']])->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email',['inputOptions' => ['id' => 'register-email']])->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'displayname',['inputOptions' => ['id' => 'register-displayname']])->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'password',['inputOptions' => ['id' => 'register-password']])->passwordInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
