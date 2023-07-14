<?php

namespace app\modules\absensi\controllers;

use app\models\User;
use Yii;
use app\modules\absensi\models\HukumanDisiplin;
use app\modules\absensi\models\HukumanDisiplinSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UnauthorizedHttpException;

/**
 * HukumanDisiplinController implements the CRUD actions for HukumanDisiplin model.
 */
class HukumanDisiplinController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        /*'matchCallback' => function ($rule, $action) {
                            return User::isAdmin();
                        }*/
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all HukumanDisiplin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HukumanDisiplinSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HukumanDisiplin model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new HukumanDisiplin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_hukuman_disiplin_jenis)
    {
        $access = HukumanDisiplin::accessCreate([
            'id_hukuman_disiplin_jenis'=>$id_hukuman_disiplin_jenis
        ]);

        if($access == false) {
            throw new UnauthorizedHttpException();
        }

        $model = new HukumanDisiplin();
        $model->id_hukuman_disiplin_jenis = $id_hukuman_disiplin_jenis;

        $referrer = Yii::$app->request->referrer;
        $model->tahun = User::getTahun();

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            $model->setTanggalMulaiSelesai();
            $model->setIdInstansi();

            if($model->save()) {
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');

        }

        if($model->tanggal_selesai == '9999-12-31') {
            $model->tanggal_selesai = null;
        }

        return $this->render('create', [
            'model' => $model,
            'referrer'=>$referrer
        ]);

    }

    /**
     * Updates an existing HukumanDisiplin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->getAccessUpdate() == false) {
            throw new UnauthorizedHttpException();
        }

        $referrer = Yii::$app->request->referrer;

        if($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            $model->setTanggalMulaiSelesai();
            $model->setIdInstansi();

            if($model->save())
            {
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');

        }

        if($model->tanggal_selesai == '9999-12-31') {
            $model->tanggal_selesai = null;
        }

        return $this->render('update', [
            'model' => $model,
            'referrer'=>$referrer
        ]);

    }

    /**
     * Deletes an existing HukumanDisiplin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if($model->getAccessDelete() == false) {
            throw new UnauthorizedHttpException();
        }

        if($model->softDelete()) {
            Yii::$app->session->setFlash('success','Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error','Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);


    }

    /**
     * Finds the HukumanDisiplin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HukumanDisiplin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HukumanDisiplin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
