<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\models\CreateUserForm */
/* @var $role string */
/* @var $canManageAdmins bool */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Создание нового пользователя CRM';
?>
<div class="user-update">

    <div class="user-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'newPassword')->textInput()->label('Пароль') ?>
        <?= $form->field($model, 'status')->dropDownList($model->getAllowedStatuses()) ?>
        <?= $form->field($model, 'role')->dropDownList($model->getAllowedRoles())->label('Роль') ?>
        <?= $form->field($model, 'canManageAdmins')->checkbox(['label'=>'Может управлять администраторами']) ?>


        <div class="form-group">
            <?= Html::submitButton('Добавить пользователя', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
