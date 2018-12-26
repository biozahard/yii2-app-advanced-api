<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $canManageAdmins bool */
/* @var $canManageAdminsIds array */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи CRM';
?>
<div class="user-index">
    <?php if ($canManageAdmins): ?>
        <p>
            <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'name',
            'email:email',
            [
                'header' => 'Роль',
                'content' => function ($model, $key, $index, $column) {
                    $auth = Yii::$app->authManager;
                    $roles = $auth->getRolesByUser($model->id);
                    $string = ' ';
                    foreach ($roles as $role) {
                        $string .= $role->name . " ";
                    }
                    $perms = $auth->getPermissionsByUser($model->id);
                    $names = \yii\helpers\ArrayHelper::getColumn($perms,'name');

                    if (in_array('manageAdmins',$names)){
                        $string .= '<br>'.'<small>Управление админами</small>';
                    }
                    return $string;
                }
            ],
            [
                'header' => 'Статус',
                'content' => function ($model, $key, $index, $column) {
                    if ($model->status == $model::STATUS_ACTIVE) {
                        return 'Активен';
                    } elseif ($model->status == $model::STATUS_DELETED) {
                        return 'Отключен';
                    } else {
                        return $model->status;
                    }
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>