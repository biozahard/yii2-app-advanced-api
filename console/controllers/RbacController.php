<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // MANAGER ROLE

        $manageCRM = $auth->createPermission('manageCRM');
        $manageCRM->description = 'Can manage CRM';
        $auth->add($manageCRM);


        $manager = $auth->createRole('manager');
        $auth->add($manager);
        $auth->addChild($manager, $manageCRM);



        //  ADMIN ROLE
        $changeSettings = $auth->createPermission('changeSettings');
        $changeSettings->description = 'Change settings of CRM system';
        $auth->add($changeSettings);

        $createUsers = $auth->createPermission('createUsers');
        $createUsers->description = 'Create a users';
        $auth->add($createUsers);


        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $createUsers);
        $auth->addChild($admin, $changeSettings);
        $auth->addChild($admin, $manager);



        // SUPER ADMIN ROLE

        $createAdministrator = $auth->createPermission('createAdministrators');
        $createAdministrator->description = 'Create an administrator user';
        $auth->add($createAdministrator);

        // добавляем роль "superadmin" и даём роли разрешение "createAdministrators"
        // а также все разрешения роли "admin"
        $superadmin = $auth->createRole('superadmin');
        $auth->add($superadmin);
        $auth->addChild($superadmin, $createAdministrator);
        $auth->addChild($superadmin, $admin);

        $auth->assign($admin, 1);
        /*
        // Назначение ролей пользователям. 1 и 2 это IDs возвращаемые IdentityInterface::getId()
        // обычно реализуемый в модели User.
        $auth->assign($author, 2);
        $auth->assign($admin, 1);
        */
    }
}