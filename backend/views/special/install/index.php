<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Установка CRM';
$this->params['breadcrumbs'][] = $this->title;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Sign In';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<div class="login-box">
    <div class="login-logo">
        <a href="#">Установка<br><strong>MediSpark CRM</strong></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Чтобы начать пользоваться системой необходимо создать <strong>СуперАдминистратора</strong>, это самая всемогущая роль в системе.</p>

        <?php $form = ActiveForm::begin(['id' => 'form-install']); ?>

        <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'email') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Создать пользователя и начать работу', ['class' => 'btn btn-primary center-block', 'name' => 'install-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>


    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->

