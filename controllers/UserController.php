<?php

namespace app\controllers;

use app\models\ChangePasswordForm;
use app\models\Pegawai;
use app\models\SetPasswordForm;
use app\models\User;
use app\models\UserSearch;
use Yii;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return User::isAdmin();
                        },
                    ],
                    [
                        'actions' => [
                            'index', 'create', 'update', 'view', 'delete', 'set-password',
                            'reset-imei',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return User::isAdmin();
                        },
                    ],
                    [
                        'actions' => ['change-password', 'change-password-v2'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return $this->accessChangePassword();
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex($id_user_role = null)
    {
        $searchModel = new UserSearch();
        $searchModel->id_user_role = $id_user_role;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id_user_role' => $id_user_role,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $debug=false)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
            'debug' => $debug, 
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_user_role)
    {
        $model = new User();

        $model->id_user_role = $id_user_role;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->setPasswordHash();
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);

    }

    public function actionSetKodeUserRole()
    {
        $query = \app\models\User::find();
        $query->andWhere(['id_user_role' => 0]);
        foreach ($query->all() as $data) {
            $data->id_user_role = UserRole::PEGAWAI;
            $data->save();
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->setPasswordHash();
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSetPassword($id)
    {
        $user = $this->findModel($id);

        $model = new SetPasswordForm;
        $model->username = $user->username;

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if ($model->validate()) {
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
                $user->username = $model->username;

                if ($user->save()) {
                    Yii::$app->session->setFlash('success', 'Password berhasil diperbarui');
                    return $this->redirect($referrer);
                }
            }
        }

        return $this->render('set-password', [
            'model' => $model,
            'user' => $user,
            'referrer' => $referrer,
        ]);
    }

    public function actionSetKodePegawai()
    {
        $query = User::find();
        $query->andWhere('kode_pegawai IS NULL');

        foreach ($query->all() as $user) {
            $user->kode_pegawai = $user->username;
            $user->save();
        }

    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionLogin($id)
    {
        if(User::isAdmin()==false) {
            throw new ForbiddenHttpException();
        }

        $model = $this->findModel($id);

        Yii::$app->user->login($model);
        Yii::$app->session->setFlash('success','Ganti session berhasil');

        return $this->redirect(['/site/index']);
    }


    public function actionChangePassword($is_password_default = null)
    {
        $query = User::find();
        $query->andWhere(['username' => Yii::$app->user->identity->username]);

        $user = $query->one();

        $model = new ChangePasswordForm;

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $model->attributes = $_POST['ChangePasswordForm'];

            $referrer = $_POST['referrer'];

            if ($model->validate()) {
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($model->password_baru);

                if ($user->save()) {
                    Yii::$app->session->setFlash('success', 'Password berhasil diperbarui');
                    return $this->redirect($referrer);
                } else {
                    print_r($user->getErrors());
                }

            }
        }

        return $this->render('change-password', [
            'model' => $model,
            'referrer' => $referrer,
            'is_password_default' => $is_password_default
        ]);
    }

    public function actionChangePasswordV2($is_password_default = null)
    {
        $model = new ChangePasswordForm;

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $model->attributes = $_POST['ChangePasswordForm'];

            $model->validateSso = true;

            $referrer = $_POST['referrer'];

            if ($model->validate()) {

                $url = Yii::$app->params['url_sso'];
                $params = '/api/user/change-password';

                $client = new Client();
                $response = $client->createRequest()
                    ->setMethod('POST')
                    ->setUrl($url.$params)
                    ->addData([
                        'username' => Yii::$app->user->identity->username,
                        'password_baru' => $model->password_baru,
                    ])
                    ->send();

                $responseJson = json_decode($response->content);

                if($response->getStatusCode() == 200 AND $responseJson->status == 'success') {
                    Yii::$app->session->setFlash('success', 'Password berhasil diperbarui');
                    return $this->redirect($referrer);        
                }

                Yii::$app->session->setFlash('error', 'Passoword gagal diperbarui');
            }
        }

        return $this->render('change-password', [
            'model' => $model,
            'referrer' => $referrer,
            'is_password_default' => $is_password_default
        ]);
    }

    public function actionSetIdPegawai()
    {
        foreach (User::find()->where(['id_user_role' => 2])->all() as $data) {
            $data->id_pegawai = Pegawai::find()->where(['nip' => $data->username])->one()->id;
            $data->save();
        }
    }

    public function actionResetImei($id)
    {
        $model = $this->findModel($id);
        $model->imei = null;
        $model->save();

        Yii::$app->session->setFlash('success', 'IMEI berhasil direset');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function accessChangePassword()
    {
        if (!Yii::$app->user->isGuest) {
            return true;
        }

        return false;
    }

}
