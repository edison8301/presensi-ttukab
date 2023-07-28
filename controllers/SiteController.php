<?php

namespace app\controllers;

use app\components\Helper;
use Imagine\Image\ManipulatorInterface;
use Yii;
use app\components\Session;
use app\models\ContactForm;
use app\models\FilterForm;
use app\models\Instansi;
use app\models\LoginForm;
use app\models\Pegawai;
use app\models\User;
use app\modules\absensi\models\ExportPdf;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\imagine\Image;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'logout', 'detailOpd', 'tunjangan', 'user-kinerja',
                    'verifi',
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        return $this->redirect(['/kegiatan/index']);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        print @Yii::$app->user->identity->username;

        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }

        $this->layout = 'login';
        $model = new LoginForm();
        $model->tahun = date('Y');

        if ($model->load(Yii::$app->request->post())) {

            if($model->login()) {
                return $this->redirect(['/site/index']);
            }
        }
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLoginV2()
    {
        print @Yii::$app->user->identity->username;

        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }

        $this->layout = 'login';
        $model = new LoginForm();
        $model->tahun = date('Y');

        if ($model->load(Yii::$app->request->post())) {
            $model->validateSso = true;

            if($model->login()) {
                return $this->redirect(['/site/index']);
            }
        }
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLoginSso($token)
    {
        $jwt = Yii::$app->jwt;
        $token = Yii::$app->jwt->getParser()->parse((string) $token);

        $secret = @Yii::$app->params['jwt_secret'];

        if($token->verify($jwt->getSigner('HS256'), $secret) == false) {
            print_r('Key tidak valid');
            die;
        }

        $user =  User::findByUsername($token->getClaim('username'));

        if($user === null) {
            print_r('Username tidak ditemukan');
            die;
        }

        Yii::$app->session->set('tahun', date('Y'));
        Yii::$app->user->login($user);
        Yii::$app->session->setFlash('success','Login Berhasil');

        return $this->redirect(['/site/index']);
    }

    public function actionDashboardPegawai()
    {
        return $this->renderContent('<h1>wip dashboard pegawai</h1>');
    }

    /*
    public function actionTunjangan($unit_kerja)
    {
        $unitKerja = \app\models\kinerja\UnitKerja::findOne($unit_kerja);

        $filter = new FilterForm;

        if ($filter->load(Yii::$app->request->post()) && $filter->validate()) {
            $filter->setSession();
            return $this->refresh();
        }

        return $this->render('tunjangan', [
            'unitKerja' => $unitKerja,
            'filter' => $filter,
        ]);
    }
    */

    public function actionVerifi($hash)
    {
        $model = $this->findHash($hash);

        if ($model !== null) {
            Yii::$app->session->setFlash('success', 'Barcode telah sesuai');
            return $this->redirect(['/site/index']);
        }

        Yii::$app->session->setFlash('danger', 'Barcode tidak sesuai');
        return $this->redirect(['/site/index']);
    }

    public function actionIp()
    {
        return Yii::$app->getRequest()->getUserIP();
    }

    protected function findHash($hash)
    {
        if (($model = ExportPdf::find()->andWhere(['hash' => $hash])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect([
            '/site/login',
        ]);
    }

    /*
    public function actionResize()
    {
        $path = Yii::$app->basePath . "/web/images/";
        $foto = "tes.jpg";

        $img = Image::thumbnail($path . @$foto, 300, 400);
        if($img !== null) {
            $img->save();
        }
    }
    */
}
