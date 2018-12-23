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
        $createUsers = $auth->createPermission('createUsers');
        $createUsers->description = 'Create a users';
        $auth->add($createUsers);


        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $createUsers);
        $auth->addChild($admin, $manager);


        // Unasigned permissions:

        $changeSettings = $auth->createPermission('changeSettings');
        $changeSettings->description = 'Change settings of CRM system';
        $auth->add($changeSettings);

        $createAdministrator = $auth->createPermission('manageAdmins');
        $createAdministrator->description = 'Create an administrator user';
        $auth->add($createAdministrator);
    }
}