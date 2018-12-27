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
        'tableOptions' => ['class' => 'table table-striped table-bordered'],
        'rowOptions' => function ($model) {
            if ($model->id == Yii::$app->user->id) {
                return ['class' => 'warning'];
            }
            return null;
        },
        'columns' => [
            'id',
            [
                'header' => 'Имя',
                'content' => function ($model, $key, $index, $column) {
                    return \yii\bootstrap\Html::a($model->name, ['update', 'id' => $model->id]);
                }
            ],
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
                    $names = \yii\helpers\ArrayHelper::getColumn($perms, 'name');

                    if (in_array('manageAdmins', $names)) {
                        $string .= '<br>' . '<small>Управление админами</small>';
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
            [
                'header' => 'Действие',
                'content' => function ($model, $key, $index, $column) {
                    return \yii\bootstrap\Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn-xs btn-default']);
                }
            ],
        ],
    ]); ?>
</div>