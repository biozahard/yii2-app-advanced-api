<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\models\user\CreateUserForm */
/* @var $user \common\models\User */
/* @var $role string */
/* @var $canManageAdmins bool */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Редактирование пользователя ' . $model->name . " (id: $user->id)";
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
            <?= Html::submitButton('Сохранить изменения', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
