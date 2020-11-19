<head>
    <title>
        Password reseting
    </title>
</head>

<?php
	
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;

	$this->title = 'Password reseting';
	$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<p>Please fill out the following fields to reset password:</p>

<?php $form = ActiveForm::begin([
    'id' => 'restore',
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
]); ?>

    <?= $form->field($model, 'password', ['inputOptions' => ['id' => 'restore-newpassword']])->passwordInput() ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Apply', ['class' => 'btn btn-primary', 'name' => 'reset-button']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>