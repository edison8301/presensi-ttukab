<?php

namespace app\modules\absensi\controllers;

use Yii;
use app\modules\absensi\models\AbsensiSearch;
use app\models\Instansi;
use app\models\InstansiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

/**
 * InstansiController implements the CRUD actions for Instansi model.
 */
class InstansiController extends Controller
{
    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Instansi models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InstansiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexMesinAbsensi()
    {
        $searchModel = new InstansiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-mesin-absensi', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Instansi model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id=null)
    {
        if(User::isInstansi()) {
            $id = User::getIdInstansi();
        }

        $instansiSearch = new InstansiSearch();
        $instansiSearch->load(Yii::$app->request->queryParams);

        $absensiSearch = new AbsensiSearch();
        $absensiSearch->load(Yii::$app->request->queryParams);

        return $this->render('view', [
            'instansi' => $this->findModel($id),
            'absensiSearch' => $absensiSearch,
            'instansiSearch'=>$instansiSearch,
            'id'=>$id
        ]);
    }

    public function actionMesinAbsensi($id)
    {

        $searchModel = new InstansiSearch();
        $searchModel->load(Yii::$app->request->queryParams);

        $absensiSearch = new AbsensiSearch();
        $absensiSearch->load(Yii::$app->request->queryParams);

        return $this->render('mesin-absensi', [
            'instansi' => $this->findModel($id),
            'absensiSearch' => $absensiSearch,
            'searchModel'=>$searchModel,
            'id'=>$id
        ]);
    }

    /**
     * Creates a new Instansi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Instansi();

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if($model->save())
            {
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');

        }

        return $this->render('create', [
            'model' => $model,
            'referrer'=>$referrer
        ]);

    }

    /**
     * Updates an existing Instansi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if($model->save())
            {
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');


        }

        return $this->render('update', [
            'model' => $model,
            'referrer'=>$referrer
        ]);

    }

    /**
     * Deletes an existing Instansi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->status_hapus = date('Y-m-d H:i:s');

        if($model->save())
        {
            Yii::$app->session->setFlash('success','Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error','Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);


    }

    /**
     * Finds the Instansi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Instansi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Instansi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
