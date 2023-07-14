<?php

namespace app\modules\iclock\controllers;

use app\components\Session;
use app\models\Pegawai;
use app\modules\absensi\models\JamKerja;
use Yii;
use app\models\User;
use app\modules\iclock\models\Checkinout;
use app\modules\iclock\models\CheckinoutSearch;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CheckinoutController implements the CRUD actions for Checkinout model.
 */
class CheckinoutController extends Controller
{
    /**
     * @inheritdoc
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
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'perawatan', 'view', 'editable-update'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        },
                    ],
                    [
                        'actions' => ['view-peta'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Session::isAdmin() OR Session::isInstansi() OR Session::isAdminInstansi()
                                OR Session::isPemeriksaAbsensi() OR Session::isPemeriksaKinerja();
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Checkinout models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CheckinoutSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Checkinout model.
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
     * Displays a single Checkinout model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewPeta($id)
    {
        return $this->render('view-peta', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionPerawatan($proses = 0)
    {

        $query = Checkinout::find();
        $query->joinWith(['userinfo']);
        //$query->andWhere('userinfo.badgenumber != :nip',[':nip'=>'3683']);
        $query->andWhere([
            'userinfo.badgenumber' => '197411112002122006',
        ]);

        $query->groupBy(['checktime']);

        foreach ($query->all() as $data) {
            print $data->checktime . '-' . $data->userinfo->badgenumber . '-';

            $bantu = Checkinout::find();
            $bantu->andWhere([
                'checkinout.userid' => '3481',
                'checktime' => $data->checktime,
            ]);

            if ($bantu->count() == 0) {
                $data->userid = '3481';
                $data->save();
                print "Kosong";
            } else {
                print "Ada";
            }

            if ($proses == 1) {
                $data->deleteDuplicate();
            }

            print "<br>";

        }

        print $query->count();
        die;

    }

    public function actionDeleteDuplicate($userid, $checktime, $sn)
    {
        if ($userid != '3683') {
            Yii::$app->session->setFlash('error', 'Salah userid');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $query = Checkinout::find();
        $query->andWhere('userid != :userid', [':userid' => $userid]);
        $query->andWhere([
            'checktime' => $checktime,
            'SN' => $sn,
        ]);

        Checkinout::deleteAll('userid != :userid AND checktime = :checktime AND SN = :SN', [
            ':userid' => $userid,
            ':checktime' => $checktime,
            ':SN' => $sn,
        ]);

        return $this->redirect(Yii::$app->request->referrer);

    }

    /**
     * Finds the Checkinout model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Checkinout the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Checkinout::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionEditableUpdate()
    {
        if (Yii::$app->request->post('hasEditable')) {

            $out = Json::encode(['output'=>'', 'message'=>'']);

            $id = Yii::$app->request->post('editableKey');
            $pegawai = Pegawai::findOne($id);
            $id_jam_kerja = Yii::$app->request->post('editableJamKerjaId');
            $jamKerja = JamKerja::findOne($id_jam_kerja);
            $tanggal = Yii::$app->request->post('editableTanggal');
            if ($pegawai !== null && $jamKerja !== null) {
                $posted = Yii::$app->request->post();
                if (isset($posted['id'])) {
                    $nilai = (int) $posted['id'];
                    if ($nilai === 1) {
                        $new = new Checkinout([
                            'userid' => $pegawai->findOrCreateUserInfo()->userid,
                            'checktime' => $tanggal . ' ' . $jamKerja->jam_minimal_absensi,
                            'checktype' => '1',
                            'verifycode' => 1
                        ]);
                        $new->save();
                        $output = 'Hadir';
                    } else {
                        Checkinout::deleteAll(['userid' => $pegawai->findOrCreateUserInfo()->userid, 'checktime' => $tanggal . ' ' . $jamKerja->jam_minimal_absensi]);
                        $output = 'Tidak Hadir';
                    }
                }
                $out = Json::encode(['output' => $output, 'message' => '']);
            }
            return $out;
        }
    }
}
