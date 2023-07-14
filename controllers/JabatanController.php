<?php

namespace app\controllers;

use app\models\Instansi;
use app\models\Jabatan;
use app\models\JabatanSearch;
use app\models\User;
use app\modules\tukin\models\Pegawai;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * JabatanController implements the CRUD actions for Jabatan model.
 */
class JabatanController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete',
                            'assign', 'remove', 'update-editable-status-verifikasi',
                            'update-atasan-kepala','perawatan', 'index-tpp',
                            'view-tpp', 'update-editable', 'update-atasan-kepala-v2',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isAdmin() || User::isMapping();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Jabatan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JabatanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Jabatan models.
     * @return mixed
     */
    public function actionIndexTpp($debug=false)
    {
        $searchModel = new JabatanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-tpp', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'debug' => $debug
        ]);
    }

    /**
     * Displays a single Jabatan model.
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
     * Displays a single Jabatan model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewTpp($id)
    {
        return $this->render('view-tpp', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Jabatan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Jabatan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Jabatan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new Jabatan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $id_instansi
     * @param int|null $id_induk
     * @return mixed
     */
    public function actionCreate($id_instansi, $id_instansi_bidang = null, $id_induk = null)
    {
        $model = new Jabatan([
            'id_instansi' => $id_instansi,
            'id_induk' => $id_induk,
            'id_instansi_bidang' => $id_instansi_bidang
        ]);

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            if ($model->nama_2021 != null) {
                $model->nama = $model->nama_2021;
            }

            $referrer = $_POST['referrer'];

            $model->setIdEselon();

            $model->copyJabatanEvjab(false);
            if ($model->save()) {
                $model->updateAtasanKepalaSubinstansi();
                Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error', 'Data gagal disimpan. Silahkan periksa kembali isian Anda.');

        }

        return $this->render('create', [
            'model' => $model,
            'referrer' => $referrer
        ]);

    }

    /**
     * Updates an existing Jabatan model.
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

            if ($model->nama_2021 != null) {
                $model->nama = $model->nama_2021;
            }

            $model->setIdEselon();

            $model->copyJabatanEvjab(false);

            if ($model->save()) {
                $model->updateAtasanKepalaSubinstansi();

                if (@$model->jabatanInduk->status_kepala == 1) {
                    $model->updateIdInstansiBidang($model->id_instansi_bidang);
                }

                Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error', 'Data gagal disimpan. Silahkan periksa kembali isian Anda.');

        }

        return $this->render('update', [
            'model' => $model,
            'referrer' => $referrer
        ]);

    }

    public function actionUpdateAtasanKepala($id)
    {
        $model = $this->findModel($id);

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if($model->save()) {
                Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
                return $this->redirect($referrer);
            }
        }

        return $this->render('update-atasan-kepala',[
            'model' => $model,
            'referrer' => $referrer
        ]);
    }

    public function actionUpdateAtasanKepalaV2($id_instansi, $id_instansi_atasan)
    {
        $model = Jabatan::findOne([
            'id_instansi' => $id_instansi,
            'status_kepala' => 1,
        ]);

        $instansiAtasan = Instansi::findOne($id_instansi_atasan);

        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $referrer = $_POST['referrer'];

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error', 'Data gagal disimpan. Silahkan periksa kembali isian Anda.');

        }

        return $this->render('update-atasan-kepala-v2', [
            'model' => $model,
            'instansiAtasan' => $instansiAtasan,
            'referrer' => $referrer,
        ]);
    }

    /**
     * Deletes an existing Jabatan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->countSub() != 0) {
            Yii::$app->session->setFlash('error', 'Silahkan hapus sub jabatan terlebih dahulu');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if ($model->countInstansiPegawai() != 0) {
            Yii::$app->session->setFlash('error', 'Silahkan hapus terlebih dahulu data mutasi dan promosi dari jabatan terkait');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if ($model->softDelete()) {
            Pegawai::updateAll(['id_jabatan' => null], ['id_jabatan' => $id]);
            Yii::$app->session->setFlash('success', 'Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error', 'Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);


    }

    public function actionAssign($id_pegawai, $id_jabatan)
    {
        $pegawai = Pegawai::findOne($id_pegawai);
        $pegawai->updateAttributes(['id_jabatan' => $id_jabatan]);
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRemove($id_pegawai, $id_jabatan)
    {
        $pegawai = Pegawai::findOne($id_pegawai);
        $pegawai->updateAttributes(['id_jabatan' => null]);
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUpdateEditableStatusVerifikasi()
    {
        if (Yii::$app->request->post('hasEditable')) {

            $id = Yii::$app->request->post('editableKey');
            $model = $this->findModel($id);

            $post = Yii::$app->request->post();
            $status_verifikasi = $post['status_verifikasi'];

            $out = '';
            if ($status_verifikasi != null) {
                $model->updateAttributes([
                    'status_verifikasi' => $status_verifikasi,
                    'waktu_verifikasi' => date('Y-m-d H:i:s'),
                    'id_user_verifikasi' => User::getIdUser()
                ]);

                $output = '';

                $out = Json::encode(['output' => $output, 'message' => '']);
            }

            return $out;
        }
    }

    public function actionUpdateEditable()
    {
        if (Yii::$app->request->post('hasEditable')) {

            $id = Yii::$app->request->post('editableKey');
            $model = $this->findModel($id);

            $posted = Yii::$app->request->post();
            $post = ['Jabatan' => $posted];

            $out = '';
            if ($model->load($post)) {
                $model->save(false);

                $output = '';

                $out = Json::encode(['output' => $output, 'message' => '']);
            }

            return $out;
        }
    }

    public function actionPerawatan()
    {
        $query = Jabatan::find();
        $query->joinWith(['instansi']);
        $query->andWhere('instansi.id_instansi_jenis = 1');

        $query->andWhere([
            'status_kepala'=>1
        ]);

        print $query->count();

        $i=1;
        foreach($query->all() as $data) {
            print $i.' '.$data->nama.'<br/>';
            $i++;
        }

        return true;
    }
}
