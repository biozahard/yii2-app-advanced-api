<?php

namespace backend\models;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class CreateUserForm extends Model
{
    public $name;
    public $email;
    public $newPassword;
    public $canManageAdmins;
    public $role;
    public $status;

    public function getAllowedStatuses()
    {
        return [
            User::STATUS_ACTIVE => 'Активен',
            User::STATUS_DELETED => 'Отключен'
        ];
    }

    public function getAllowedRoles()
    {
        return [
            'manager' => 'Менеджер',
            'admin' => 'Администратор'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'newPassword'], 'required'],
            ['email', 'unique', 'targetClass'=>'common\models\User'],
            // rememberMe must be a boolean value
            // password is validated by validatePassword()
            ['name', 'string'],
            ['name', 'default', 'value' => ''],

            ['newPassword', 'string', 'min' => '5'],

            ['status', 'in', 'range' => array_keys($this->getAllowedStatuses())],
            ['status', 'default', 'value' => User::STATUS_ACTIVE],

            ['role', 'in', 'range' => array_keys($this->getAllowedRoles())],
            ['canManageAdmins', 'boolean'],
            ['canManageAdmins', function ($attribute, $params) {
                if ($this->$attribute == 1 && $this->role != 'admin') {
                    $this->addError($attribute, 'Управление админами доступно только для администраторов!');
                }
            }],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return User|null whether the user is logged in successfully
     */
    public function createUser()
    {
        if ($this->validate()) {
            $user = new User();
            $user->email = $this->email;
            $user->name = $this->name;
            $user->status = $this->status;
            $user->setPassword($this->newPassword);
            $user->generateAuthKey();
            if ($user->save()) {
                $auth = Yii::$app->authManager;
                $adminRole = $auth->getRole($this->role);
                $auth->assign($adminRole, $user->id);
                if ($this->canManageAdmins) {
                    $manageAdmins = $auth->getPermission('manageAdmins');
                    $auth->assign($manageAdmins, $user->id);
                }
            }
            return $user;
        }

        return null;
    }

}
