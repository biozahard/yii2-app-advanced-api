<?php

namespace backend\models\user;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;
use yii\web\NotFoundHttpException;

class CreateUserForm extends Model
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /* @var User */
    public $_user;

    public $name;
    public $email;
    public $newPassword;
    public $canManageAdmins;

    /* @var string */
    public $role;
    /* @var bool */
    public $status;

    /*
        public function scenarios()
        {
            return [
                self::SCENARIO_CREATE => ['username', 'password'],
                self::SCENARIO_UPDATE => ['username', 'email', 'password'],
            ];
        }
    */
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
            [['email'], 'required'],
            [['newPassword'], 'required', 'on' => self::SCENARIO_CREATE],
            ['email', 'unique', 'targetClass' => 'common\models\User', 'filter' => ($this->_user) ? 'id <> ' . $this->_user->id : '1'],
            // rememberMe must be a boolean value
            // password is validated by validatePassword()
            ['name', 'string'],
            ['name', 'default', 'value' => ''],

            ['newPassword', 'string', 'min' => '5'],

            ['status', 'integer'],
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

            $this->_user = $user;
            return $user;
        }

        $this->_user = null;
        return null;
    }

    /**
     * @param integer $id user's id
     */
    public function loadById($id)
    {
        $model = $this->findModel($id);
        $this->_user = $model;

        $this->name = $model->name;
        $this->email = $model->email;
        $this->status = $model->status;

        $this->role = ArrayHelper::getValue($this->getUserRole($model->id), 'name');
        $this->canManageAdmins = $this->getUserCanManageAdmins($model->id);
    }

    public function updateUser()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->email = $this->email;
            $user->name = $this->name;
            $user->status = $this->status;
            if (!empty($this->newPassword)) {
                $user->setPassword($this->newPassword);
            }

            if ($user->save()) {
                $auth = Yii::$app->authManager;

                //Переопределяем роль
                $curUserRole = $this->getUserRole($user->id);
                if ($this->role != $curUserRole->name) {
                    iF (!$curUserRole || $auth->revoke($curUserRole, $user->id)) {
                        $newRole = $auth->getRole($this->role);
                        $auth->assign($newRole, $user->id);
                    } else {
                        $this->addError('Не могу отменить текущую роль! =( Обратитесь к администратору.');
                    }
                }

                //Переопределяем управление админами
                $oldCanManageAdmins = $this->getUserCanManageAdmins($user->id);
                if ($this->canManageAdmins == false && $oldCanManageAdmins == true) {
                    $manageAdmins = $auth->getPermission('manageAdmins');
                    $auth->revoke($manageAdmins, $user->id);
                } elseif ($this->canManageAdmins == true && $oldCanManageAdmins == false) {
                    $manageAdmins = $auth->getPermission('manageAdmins');
                    $auth->assign($manageAdmins, $user->id);
                }
            }
            return $user;
        }
        return false;
    }

    /**
     * @param $id
     * @return Role|null
     */
    protected function getUserRole($id)
    {
        $auth = Yii::$app->authManager;
        $role = ArrayHelper::getValue(array_values($auth->getRolesByUser($id)), 0);
        return $role;
    }

    protected function getUserCanManageAdmins($id)
    {
        $auth = Yii::$app->authManager;
        $permissions = ArrayHelper::getColumn($auth->getPermissionsByUser($id), 'name');
        return in_array('manageAdmins', $permissions);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function getUser()
    {
        return $this->_user;
    }
}
