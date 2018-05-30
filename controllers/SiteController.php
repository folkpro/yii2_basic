<?php

namespace app\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\TransferForm;
use app\models\TransferSearch;
use app\models\User;
use app\models\UserSearch;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'profile', 'transfer', 'search'],
                'rules' => [
                    [
                        'actions' => ['logout', 'profile', 'transfer', 'search'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
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
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays Profile page.
     *
     * @return Response|string
     */
    public function actionProfile()
    {
        $searchModel = new TransferSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('profile', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays Transfer page.
     *
     * @return Response|string
     */
    public function actionTransfer()
    {
        $model = new TransferForm();
        $model->donor = Yii::$app->user->identity->username;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->send() === false) {
                Yii::$app->session->setFlash('transferFormError');
            } else {
                Yii::$app->session->setFlash('transferFormSubmitted');
            }

            return $this->refresh();
        }

        return $this->render('transfer', [
            'model' => $model,
        ]);
    }

    /**
     * Validate Transfer.
     *
     * @return Response|string
     */
    public function actionValidateTransfer()
    {
        $model = new TransferForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * Ajax AutoComplete.
     *
     * @return Array|boolen
     */
    public function actionSearch($term = null)
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return User::find()->select('id, username as label, username as value')->where(['like', 'username', $term])->asArray()->all();
    }
}
