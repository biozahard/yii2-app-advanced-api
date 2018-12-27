<?php

namespace backend\controllers;

use backend\models\user\CreateUserForm;
use Yii;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['manageUsers'],
                    ],
                    [
                        'actions' => ['update','create'],
                        'allow' => true,
                        'roles' => ['manageUsers'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = User::find();


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        //$canManageAdminsIds = Yii::$app->authManager->get
        return $this->render('index', [
            'canManageAdmins' => \Yii::$app->user->can('manageAdmins'),
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CreateUserForm(['scenario' => CreateUserForm::SCENARIO_CREATE]);

        if ($model->load(Yii::$app->request->post()) && $model->createUser()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = new CreateUserForm();
        //$model->scenario = CreateUserForm::SCENARIO_UPDATE;
        $model->loadById($id);

        $canManageAdmins = \Yii::$app->user->can('manageAdmins');
        if (Yii::$app->request->isPost && !$canManageAdmins && $model->role == 'admin') {
            throw new ForbiddenHttpException('У Вас нет доступа к управлению администраторами!');
        }

        if ($model->load(Yii::$app->request->post()) && $model->updateUser()) {
            return $this->redirect(['index']);
        }
        return $this->render('update', [
            'model' => $model,
            'user' => $model->getUser(),
            'canManageAdmins' => $canManageAdmins
        ]);
    }

}