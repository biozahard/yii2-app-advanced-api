<?php

namespace backend\models\special;


use common\models\User;
use yii\base\Model;

class InstallCrmModel extends Model
{
    public $name;
    public $email;
    public $password;
    protected $user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            ['password', 'string', 'min' => 6],
            ['name', 'string', 'min' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Ваш email',
            'password' => 'Создайте сложный пароль',
        ];
    }

    public function install()
    {
        $res = $this->registerSuperAdmin();
        return $res;
    }

    public function registerSuperAdmin()
    {
        if (!$this->validate()) {
            return null;
        }
        $auth = \Yii::$app->authManager;


        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        if ($user->save()) {
            $adminRole = $auth->getRole('admin');
            $auth->assign($adminRole, $user->id);
            $manageAdmins = $auth->getPermission('manageAdmins');
            $auth->assign($manageAdmins, $user->id);

            $this->user = $user;
            return true;
        } else {
            return false;
        }
    }

    public function getUser()
    {
        return $this->user;
    }
}