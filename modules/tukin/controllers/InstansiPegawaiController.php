<?php

namespace app\modules\tukin\controllers;

use app\models\InstansiPegawai;
use app\models\InstansiPegawaiSearch;
use app\models\User;
use app\modules\absensi\models\ExportPdf;
use kartik\mpdf\Pdf;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * InstansiPegawaiController implements the CRUD actions for InstansiPegawai model.
 */
class InstansiPegawaiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index-pegawai'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return User::isAdmin() OR User::isInstansi();
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all InstansiPegawai models.
     * @return mixed
     */
    public function actionIndexPegawai()
    {
        $searchModel = new InstansiPegawaiSearch();

        $searchModel->bulan = date('n');

        if(User::isInstansi()) {
            $searchModel->id_instansi = User::getIdInstansi();
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (isset($_GET['export-pdf'])) {
            if ($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih unit kerja terlebih dahulu');
                return $this->redirect(['index']);
            }

            return $this->exportPdf($searchModel);
        }

        return $this->render('index-pegawai', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function exportPdf(InstansiPegawaiSearch $searchModel)
    {
        $modelExportPdf = ExportPdf::find()
            ->andWhere(['id_instansi' => $searchModel->id_instansi])
            ->andWhere(['bulan' => $searchModel->bulan])
            ->andWhere(['tahun' => User::getTahun()])
            ->one();

        // Cek model export pdf
        if ($modelExportPdf == null) {
            $modelExportPdf = new ExportPdf();
            $modelExportPdf->id_instansi = $searchModel->id_instansi;
            $modelExportPdf->bulan = $searchModel->bulan;
            $modelExportPdf->tahun = User::getTahun();
            $modelExportPdf->hash = Yii::$app->getSecurity()->generateRandomString(7);
            $modelExportPdf->save(false);
        }

        $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);
        $query->joinWith(['pegawai']);
        $query->with(['pegawai']);
        $query->orderBy(['pegawai.id_golongan' => SORT_DESC]);

        $content = $this->renderPartial('export-pdf-index-statis', [
            'query' => $query,
            'searchModel' => $searchModel,
            'modelExportPdf' => $modelExportPdf,
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            //'format' => Pdf::FORMAT_F4,
            'format' => [215.9, 330],
            'defaultFontSize' => '5',
            // portrait orientation
            'marginLeft' => 7,
            'marginRight' => 7,
            'marginTop' => 7,
            'marginBottom' => 7,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            //'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Export PDF Presensi'],
            // call mPDF methods on the fly
            'methods' => [
                'SetHTMLHeader' => $this->renderPartial('_barcode', ['modelExportPdf' => $modelExportPdf]),
            ],
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }
}
