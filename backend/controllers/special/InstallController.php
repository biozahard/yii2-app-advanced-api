<?php

namespace backend\controllers\special;

use backend\models\special\InstallCrmModel;
use common\models\User;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class InstallController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $hasUsers = (int)User::find()->select('id')->count();
        if($hasUsers){
            return $this->goHome();
        }
        $this->layout = 'main-install';
        $model = new InstallCrmModel();
        if ($model->load(Yii::$app->request->post()) && $model->install()) {
            Yii::$app->user->login($model->getUser(), 3600 * 24 * 7);
            return $this->goHome();
        } else {
            $model->password = '';
            return $this->render('index', ['model' => $model]);
        }
    }
}
