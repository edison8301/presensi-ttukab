<?php

namespace app\modules\tunjangan\controllers;

use app\components\Helper;
use app\components\Session;
use app\models\Instansi;
use app\models\InstansiPegawai;
use app\models\InstansiPegawaiSearch;
use app\models\User;
use app\modules\absensi\models\ExportPdf;
use app\modules\tandatangan\models\Berkas;
use app\modules\tandatangan\models\BerkasJenis;
use app\modules\tukin\models\Pegawai;
use Exception;
use kartik\mpdf\Pdf;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Yii;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class InstansiPegawaiController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'index', 'rekap', 'generate-pdf', 'index-rekap-pegawai',
                            'refresh-rekap-pegawai-bulan',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ]
        ];
    }

    public function actionIndex(
        $export_pdf_perhitungan=null,
        $export_pdf_pembayaran=null,
        $export_pdf_lembar=null,
        $export_pdf_pembayaran_13=null,
        $export_pdf_lembar_13=null,
        $export_pdf_pembayaran_14=null,
        $export_pdf_lembar_14=null,
        $debug = false,
        $offset = 0,
        $limit = null,
        $jenis = 'tpp'
    ) {
        $searchModel = new InstansiPegawaiSearch();
        $searchModel->jenis = $jenis;

        $bulan = date('n');

        if(date('j') <= 10 AND $bulan != 1) {
            $bulan = $bulan - 1;
        }
        $searchModel->bulan = $bulan;
        $searchModel->tahun = Session::getTahun();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if($export_pdf_perhitungan==1) {
            if ($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['/tunjangan/instansi-pegawai/index', 'jenis' => $jenis]);
            }

            return $this->exportPdfPerhitungan($searchModel,false, false,[
                'offset' => $offset,
                'limit' => $limit,
            ]);
        }

        if($export_pdf_pembayaran==1) {
            if ($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['/tunjangan/instansi-pegawai/index', 'jenis' => $jenis]);
            }

            return $this->exportPdfPembayaran($searchModel, false, false,[
                'offset' => $offset,
                'limit' => $limit,
            ]);
        }

        if($export_pdf_lembar==1) {
            if ($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['/tunjangan/instansi-pegawai/index', 'jenis' => $jenis]);
            }

            return $this->exportPdfLembar($searchModel, $debug, false, false, [
                'offset' => $offset,
                'limit' => $limit,
            ]);
        }

        if($export_pdf_pembayaran_14 == 1 OR $export_pdf_pembayaran_13 == 1) {
            if ($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['/tunjangan/instansi-pegawai/index', 'jenis' => $jenis]);
            }

            return $this->exportPdfPembayaranTambahan($searchModel, false, false,[
                'offset' => $offset,
                'limit' => $limit,
                'tpp13' => $export_pdf_pembayaran_13,
                'tpp14' => $export_pdf_pembayaran_14,
            ]);
        }

        if($export_pdf_lembar_14 == 1 OR $export_pdf_lembar_13 == 1) {
            if ($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['/tunjangan/instansi-pegawai/index', 'jenis' => $jenis]);
            }

            return $this->exportPdfLembarTambahan($searchModel, false, false,[
                'offset' => $offset,
                'limit' => $limit,
                'tpp13' => $export_pdf_lembar_13,
                'tpp14' => $export_pdf_lembar_14,
            ]);
        }

        if (Yii::$app->request->get('refresh-ip-asn') !== null) {
            if ($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['/tunjangan/instansi-pegawai/index']);
            }

            $this->refreshIndeksIpAsn($searchModel);

            Yii::$app->session->setFlash('success', 'Refresh Skor IP ASN berhasil dilakukan');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if (Yii::$app->request->get('kirim-dokumen') !== null) {
            if ($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['/tunjangan/instansi-pegawai/index']);
            }

            return $this->kirimDokumen($searchModel,[
                'offset' => $offset,
                'limit' => $limit,
            ]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function actionIndexRekapPegawai($export_pdf_rekap_pembayaran=null)
    {
        $searchModel = new InstansiPegawaiSearch();
        $searchModel->bulan = date('n');
        $searchModel->tahun = Session::getTahun();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($export_pdf_rekap_pembayaran == 1) {
            return $this->exportPdfRekapPembayaran($searchModel);
        }

        if(Yii::$app->request->get('export-excel-rekap-pegawai') == 1) {
            return $this->exportExcelRekapPegawai($searchModel);
        }

        return $this->render('index-rekap-pegawai', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRefreshRekapPegawaiBulan($id_instansi=null, $bulan=null)
    {
        if ($id_instansi == null) {
            Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if ($bulan == null) {
            $bulan = date('n');
        }

        exec("php ../yii rekap-pegawai-bulan/update-rekap-pembayaran $bulan $id_instansi");

        Yii::$app->session->setFlash('sucess', 'Refresh berhasil dilakukan');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function kirimDokumen($searchModel, $params=[])
    {
        ini_set('memory_limit', '-1');
        set_time_limit(300); // max 5 mnt

        $url = @Yii::$app->params['url_tandatangan'];
        $params = '/api/berkas/save';
        $id_berkas_jenis = Yii::$app->request->get('kirim-dokumen');

        if($url == null) {
            Yii::$app->session->setFlash('warning', 'URL pengiriman belum disetting!');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $file = Berkas::findFileBerkas($searchModel, $id_berkas_jenis);
        if($file != null) {
            Yii::$app->session->setFlash('danger', 'Dokumen sudah pernah dikirim');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $nama = null;
        $berkas_mentah = null;
        $berkas_mentah_tandatangan = null;

        try {
            if($id_berkas_jenis == BerkasJenis::PERHITUNGAN_TPP) {
                $nama = 'Perhitungan TPP';
                $berkas_mentah = $this->exportPdfPerhitungan($searchModel, false, true);
                $berkas_mentah_tandatangan = $this->exportPdfPerhitungan($searchModel, true, true);
            }
            if($id_berkas_jenis == BerkasJenis::PEMBAYARAN_TPP) {
                $nama = 'Pembayaran TPP';
                $berkas_mentah = $this->exportPdfPembayaran($searchModel, false, true);
                $berkas_mentah_tandatangan = $this->exportPdfPembayaran($searchModel, true, true);
            }
            if($id_berkas_jenis == BerkasJenis::LEMBAR_3) {
                $nama = 'Lembar 3';
                $berkas_mentah = $this->exportPdfLembar($searchModel, false, false, true);
                $berkas_mentah_tandatangan = $this->exportPdfLembar($searchModel, false, true, true);
            }

            if($berkas_mentah == null OR $berkas_mentah_tandatangan == null) {
                Yii::$app->session->setFlash('warning', 'Jenis dokumen tidak ditemukan');
                return $this->redirect(Yii::$app->request->referrer);
            }

            // $data_verifikasi = InstansiPegawai::getListDataVerifikasiTandatangan();
            $data_verifikasi = [];
            $instansi = Instansi::findOne($searchModel->id_instansi);
            $nip_tandatangan = @$instansi->pegawaiKepala->nip;

            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($url.$params)
                ->addData([
                    'nama' => $nama,
                    'id_berkas_jenis' => $id_berkas_jenis,
                    'id_instansi' => $searchModel->id_instansi,
                    'bulan' => $searchModel->bulan,
                    'tahun' => Session::getTahun(),
                    'nip_tandatangan' => $nip_tandatangan,
                    'data_verifikasi' => $data_verifikasi,
                    'user_create' => Session::getUsername(),
                ])
                ->addFile('berkas_mentah', $berkas_mentah)
                ->addFile('berkas_mentah_tandatangan', $berkas_mentah_tandatangan)
                ->send();

            $responseJson = json_decode($response->content);

            if ($response->statusCode != 200) {
                Yii::$app->session->setFlash('danger', $responseJson->message);
                return $this->redirect(Yii::$app->request->referrer);
            }

            Yii::$app->session->setFlash('success', 'Dokumen berhasil dikirim');
            return $this->redirect(Yii::$app->request->referrer);
        } catch (Exception $e) {
            Yii::$app->session->setFlash('danger', 'Terjadi kesalahan');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionRekap($export_pdf_perhitungan=null, $export_pdf_pembayaran=null)
    {
        $searchModel = new InstansiPegawaiSearch();

        $searchModel->bulan = date('n');

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if($export_pdf_perhitungan==1) {
            if ($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['/tunjangan/instansi-pegawai/index']);
            }

            return $this->exportPdfPerhitungan($searchModel);
        }

        if($export_pdf_pembayaran==1) {
            if ($searchModel->id_instansi == null) {
                Yii::$app->session->setFlash('danger', 'Silahkan pilih perangkat daerah terlebih dahulu');
                return $this->redirect(['/tunjangan/instansi-pegawai/index']);
            }

            return $this->exportPdfPembayaran($searchModel);
        }

        return $this->render('rekap', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function exportPdfPerhitungan($searchModel, $tandatangan=false, $save=false, $params = [])
    {
        ini_set('memory_limit', '-1');
        set_time_limit(600);

        $file = Berkas::findFileBerkas($searchModel, BerkasJenis::PERHITUNGAN_TPP);
        if($file !== null) {
            return $this->redirect($file);
        }

        $exportPdf = ExportPdf::find()
            ->andWhere(['id_instansi' => $searchModel->id_instansi])
            ->andWhere(['bulan' => $searchModel->bulan])
            ->andWhere(['tahun' => $searchModel->tahun])
            ->one();

        $cssInline = <<<CSS
        table {
            border-spacing: 0;
            padding: 7px;
            font-size: 14px;
            width: 100%;
            border-collapse: collapse;
        }
        th {
            vertical-align: middle;
            text-align: center;
        }
CSS;

        // Cek model export pdf
        if ($exportPdf == null) {
            $exportPdf = new ExportPdf();
            $exportPdf->id_instansi = $searchModel->id_instansi;
            $exportPdf->bulan = $searchModel->bulan;
            $exportPdf->tahun = $searchModel->tahun;
            $exportPdf->hash = Yii::$app->getSecurity()->generateRandomString(7);
            $exportPdf->save(false);
        }

        if (php_sapi_name() == 'cli') {
            $query = $searchModel->getQuerySearch();
        } else {
            $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);
        }

        /* $query->andWhere([
            'status_plt' => 0
        ]); */

        $query->joinWith(['pegawai','jabatan']);
        $query->with(['pegawai']);
        $query->orderBy([
            'jabatan.id_eselon' => SORT_ASC,
            'pegawai.nama' => SORT_ASC,
        ]);
        $query->groupBy(['pegawai.nip']);

        $offset = @$params['offset'];
        if($offset !== null) {
            $query->offset($offset);
        }

        $limit = @$params['limit'];
        if($limit !== null) {
            $query->limit($limit);
        }

        if ($exportPdf->tahun >= 2021){
            $content = $this->renderPartial('export-pdf-perhitungan-baru', [
                'query' => $query,
                'searchModel' => $searchModel,
                'modelExportPdf' => $exportPdf,
                'tandatangan' => $tandatangan,
                'offset' => $offset,
            ]);
        } else {
            $content = $this->renderPartial('export-pdf-perhitungan', [
                'query' => $query,
                'searchModel' => $searchModel,
                'modelExportPdf' => $exportPdf,
                'tandatangan' => $tandatangan
            ]);
        }

        $footer = '';
        if($tandatangan == true) {
            $footer = 'Dokumen ini ditandatangani secara elektronik menggunakan Sertifikat Elektronik yang diterbitkan oleh Balai Sertifikasi Elektronik (BSrE), Badan Siber dan Sandi Negara (BSSN)';
        }

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
            'marginBottom' => 15,
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
            'options' => ['title' => 'Export PDF Perhitungan'],
            'cssInline' => $cssInline,
            'methods' => [
                'SetHTMLHeader' => $this->renderPartial('_barcode', ['modelExportPdf' => $exportPdf]),
                'SetHTMLFooter' => $footer,
            ],
            // call mPDF methods on the fly

        ]);

        if($save == true) {
            $path = '../files/';
            $filename = time().'-perhitungan-tpp.pdf';
            if(file_exists($path.$filename)) {
                $filename = (time()+60).'-perhitungan-tpp.pdf';
            }
            $filepath = $path.$filename;
            $pdf->Output($content, $filepath, 'F');
            return $filepath;
        }

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function exportPdfPembayaran($searchModel, $tandatangan=false, $save=false, $params = [])
    {
        ini_set('memory_limit', '-1');

        $file = Berkas::findFileBerkas($searchModel, BerkasJenis::PEMBAYARAN_TPP);
        if($file !== null) {
            return $this->redirect($file);
        }

        $exportPdf = ExportPdf::find()
            ->andWhere(['id_instansi' => $searchModel->id_instansi])
            ->andWhere(['bulan' => $searchModel->bulan])
            ->andWhere(['tahun' => $searchModel->tahun])
            ->one();

        $cssInline = <<<CSS
        table {
            border-spacing: 0;
            padding: 7px;
            font-size: 11px;
            width: 100%;
            border-collapse: collapse;
        }
        th {
            vertical-align: middle;
            text-align: center;
        }
CSS;

        // Cek model export pdf
        if ($exportPdf == null) {
            $exportPdf = new ExportPdf();
            $exportPdf->id_instansi = $searchModel->id_instansi;
            $exportPdf->bulan = $searchModel->bulan;
            $exportPdf->tahun = $searchModel->tahun;
            $exportPdf->hash = Yii::$app->getSecurity()->generateRandomString(7);
            $exportPdf->save(false);
        }

        $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);
        /* $query->andWhere([
            'status_plt' => 0
        ]); */
        $query->joinWith(['pegawai']);
        $query->with(['pegawai']);
        $query->orderBy([
            'jabatan.id_eselon' => SORT_ASC,
            'pegawai.nama' => SORT_ASC,
        ]);
        $query->groupBy([
            'pegawai.nip',
        ]);

        $offset = @$params['offset'];
        if($offset !== null) {
            $query->offset($offset);
        }

        $limit = @$params['limit'];
        if($limit !== null) {
            $query->limit($limit);
        }

        if ($exportPdf->tahun >= 2021){
            $content = $this->renderPartial('export-pdf-pembayaran-baru', [
                'query' => $query,
                'searchModel' => $searchModel,
                'modelExportPdf' => $exportPdf,
                'tandatangan' => $tandatangan,
            ]);
        } else {
            $content = $this->renderPartial('export-pdf-pembayaran', [
                'query' => $query,
                'searchModel' => $searchModel,
                'modelExportPdf' => $exportPdf,
                'tandatangan' => $tandatangan,
            ]);
        }

        $footer = '';
        if($tandatangan == true) {
            $footer = 'Dokumen ini ditandatangani secara elektronik menggunakan Sertifikat Elektronik yang diterbitkan oleh Balai Sertifikasi Elektronik (BSrE), Badan Siber dan Sandi Negara (BSSN)';
        }

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
            'marginBottom' => 15,
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
            'options' => ['title' => 'Export PDF Pembayaran'],
            'cssInline' => $cssInline,
            // call mPDF methods on the fly
            'methods' => [
                'SetHTMLHeader' => $this->renderPartial('_barcode', ['modelExportPdf' => $exportPdf]),
                'SetHTMLFooter' => $footer,
            ],
        ]);

        if($save == true) {
            $path = '../files/';
            $filename = time().'-pembayaran-tpp.pdf';
            if(file_exists($path.$filename)) {
                $filename = (time()+60).'-pembayaran-tpp.pdf';
            }
            $filepath = $path.$filename;
            $pdf->Output($content, $filepath, 'F');
            return $filepath;
        }

        return $pdf->render();
    }

    public function exportPdfLembar($searchModel, $debug= false, $tandatangan=false, $save=false, $params=[])
    {
        ini_set('memory_limit', '-1');

        $file = Berkas::findFileBerkas($searchModel, BerkasJenis::LEMBAR_3);
        if($file !== null) {
            return $this->redirect($file);
        }

        $exportPdf = ExportPdf::find()
            ->andWhere(['id_instansi' => $searchModel->id_instansi])
            ->andWhere(['bulan' => $searchModel->bulan])
            ->andWhere(['tahun' => $searchModel->tahun])
            ->one();

        $cssInline = <<<CSS
        table {
            border-spacing: 0;
            padding: 7px;
            font-size: 11px;
            width: 100%;
            border-collapse: collapse;
        }
        th {
            vertical-align: middle;
            text-align: center;
        }
CSS;

        // Cek model export pdf
        if ($exportPdf == null) {
            $exportPdf = new ExportPdf();
            $exportPdf->id_instansi = $searchModel->id_instansi;
            $exportPdf->bulan = $searchModel->bulan;
            $exportPdf->tahun = $searchModel->tahun;
            $exportPdf->hash = Yii::$app->getSecurity()->generateRandomString(7);
            $exportPdf->save(false);
        }

        $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);
        /* $query->andWhere([
            'status_plt' => 0
        ]); */
        $query->joinWith(['pegawai','jabatan']);
        $query->with(['pegawai']);
        $query->orderBy([
            'jabatan.id_eselon' => SORT_ASC,
            'pegawai.nama' => SORT_ASC,
        ]);
        $query->groupBy([
            'pegawai.nip',
        ]);

        $offset = @$params['offset'];
        if($offset !== null) {
            $query->offset($offset);
        }

        $limit = @$params['limit'];
        if($limit !== null) {
            $query->limit($limit);
        }

        $allInstansiPegawai = $query->all();

        if($debug != false) {
            return $this->render('export-pdf-lembar', [
                'allInstansiPegawai' => $allInstansiPegawai,
                'searchModel' => $searchModel,
                'modelExportPdf' => $exportPdf,
                'tandatangan' => $tandatangan,
            ]);
        }

        $content = $this->renderPartial('export-pdf-lembar', [
            'allInstansiPegawai' => $allInstansiPegawai,
            'searchModel' => $searchModel,
            'modelExportPdf' => $exportPdf,
            'tandatangan' => $tandatangan,
        ]);

        $footer = '';
        if($tandatangan == true) {
            $footer = 'Dokumen ini ditandatangani secara elektronik menggunakan Sertifikat Elektronik yang diterbitkan oleh Balai Sertifikasi Elektronik (BSrE), Badan Siber dan Sandi Negara (BSSN)';
        }

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
            'marginBottom' => 15,
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
            'options' => ['title' => 'Export PDF Lembar 3'],
            'cssInline' => $cssInline,
            // call mPDF methods on the fly
            'methods' => [
                'SetHTMLHeader' => $this->renderPartial('_barcode', ['modelExportPdf' => $exportPdf]),
                'SetHTMLFooter' => $footer,
            ],
        ]);

        if($save == true) {
            $path = '../files/';
            $filename = time().'-lembar-3.pdf';
            if(file_exists($path.$filename)) {
                $filename = (time()+60).'-lembar-3.pdf';
            }
            $filepath = $path.$filename;
            $pdf->Output($content, $filepath, 'F');
            return $filepath;
        }

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function exportPdfLembarTambahan($searchModel, $debug= false, $tandatangan=false, $params=[])
    {
        ini_set('memory_limit', '-1');


        $searchModel->bulan = date('n');
        $namaBulan = Helper::getBulanLengkap($searchModel->bulan);

        if (@$params['tpp13'] == 1) {
            $searchModel->bulan = 5;
            $namaBulan = 'TPP 13';
        }

        if (@$params['tpp14'] == 1) {
            $searchModel->bulan = 4;
            $namaBulan = 'TPP THR';
        }

        $file = Berkas::findFileBerkas($searchModel, BerkasJenis::LEMBAR_3);
        if($file !== null) {
            return $this->redirect($file);
        }

        $exportPdf = ExportPdf::find()
            ->andWhere(['id_instansi' => $searchModel->id_instansi])
            ->andWhere(['bulan' => $searchModel->bulan])
            ->andWhere(['tahun' => $searchModel->tahun])
            ->one();

        $cssInline = <<<CSS
        table {
            border-spacing: 0;
            padding: 7px;
            font-size: 11px;
            width: 100%;
            border-collapse: collapse;
        }
        th {
            vertical-align: middle;
            text-align: center;
        }
CSS;

        // Cek model export pdf
        if ($exportPdf == null) {
            $exportPdf = new ExportPdf();
            $exportPdf->id_instansi = $searchModel->id_instansi;
            $exportPdf->bulan = $searchModel->bulan;
            $exportPdf->tahun = $searchModel->tahun;
            $exportPdf->hash = Yii::$app->getSecurity()->generateRandomString(7);
            $exportPdf->save(false);
        }

        $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);
        /* $query->andWhere([
            'status_plt' => 0
        ]); */
        $query->joinWith(['pegawai','jabatan']);
        $query->with(['pegawai']);
        $query->orderBy([
            'jabatan.id_eselon' => SORT_ASC,
            'pegawai.nama' => SORT_ASC,
        ]);
        $query->groupBy([
            'pegawai.nip',
        ]);

        $offset = @$params['offset'];
        if($offset !== null) {
            $query->offset($offset);
        }

        $limit = @$params['limit'];
        if($limit !== null) {
            $query->limit($limit);
        }

        $allInstansiPegawai = $query->all();

        if($debug != false) {
            return $this->render('export-pdf-lembar-tambahan', [
                'allInstansiPegawai' => $allInstansiPegawai,
                'searchModel' => $searchModel,
                'modelExportPdf' => $exportPdf,
                'tandatangan' => $tandatangan,
                'namaBulan' => $namaBulan,
            ]);
        }

        $content = $this->renderPartial('export-pdf-lembar-tambahan', [
            'allInstansiPegawai' => $allInstansiPegawai,
            'searchModel' => $searchModel,
            'modelExportPdf' => $exportPdf,
            'tandatangan' => $tandatangan,
            'namaBulan' => $namaBulan,
        ]);

        $footer = '';

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
            'marginBottom' => 15,
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
            'options' => ['title' => 'Export PDF Lembar 3'],
            'cssInline' => $cssInline,
            // call mPDF methods on the fly
            'methods' => [
                'SetHTMLHeader' => $this->renderPartial('_barcode', ['modelExportPdf' => $exportPdf]),
                'SetHTMLFooter' => $footer,
            ],
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function exportPdfPembayaranTambahan($searchModel, $tandatangan=false, $save=false, $params = [])
    {
        ini_set('memory_limit', '-1');

        $namaBulan = null;
        $searchModel->bulan = date('n');

        if (@$params['tpp13'] == 1) {
            $namaBulan = 'TPP 13';
            $searchModel->bulan = 5;
        }

        if (@$params['tpp14'] == 1) {
            $namaBulan = 'TPP THR';
            $searchModel->bulan = 4;

            if ($searchModel->tahun == 2023) {
                $searchModel->bulan = 3;
            }
        }

        $exportPdf = ExportPdf::find()
            ->andWhere(['id_instansi' => $searchModel->id_instansi])
            ->andWhere(['bulan' => $searchModel->bulan])
            ->andWhere(['tahun' => $searchModel->tahun])
            ->one();

        $cssInline = <<<CSS
        table {
            border-spacing: 0;
            padding: 7px;
            font-size: 11px;
            width: 100%;
            border-collapse: collapse;
        }
        th {
            vertical-align: middle;
            text-align: center;
        }
CSS;

        // Cek model export pdf
        if ($exportPdf == null) {
            $exportPdf = new ExportPdf();
            $exportPdf->id_instansi = $searchModel->id_instansi;
            $exportPdf->bulan = $searchModel->bulan;
            $exportPdf->tahun = $searchModel->tahun;
            $exportPdf->hash = Yii::$app->getSecurity()->generateRandomString(7);
            $exportPdf->save(false);
        }

        $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);
        /* $query->andWhere([
            'status_plt' => 0
        ]); */
        $query->joinWith(['pegawai']);
        $query->with(['pegawai']);
        $query->orderBy([
            'jabatan.id_eselon' => SORT_ASC,
            'pegawai.nama' => SORT_ASC,
        ]);
        $query->groupBy([
            'pegawai.nip',
        ]);

        $offset = @$params['offset'];
        if($offset !== null) {
            $query->offset($offset);
        }

        $limit = @$params['limit'];
        if($limit !== null) {
            $query->limit($limit);
        }

        $content = $this->renderPartial('export-pdf-pembayaran-tambahan', [
            'query' => $query,
            'searchModel' => $searchModel,
            'modelExportPdf' => $exportPdf,
            'tandatangan' => $tandatangan,
            'namaBulan' => $namaBulan,
        ]);

        $footer = null;

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
            'marginBottom' => 15,
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
            'options' => ['title' => 'Export PDF Pembayaran 14'],
            'cssInline' => $cssInline,
            // call mPDF methods on the fly
            'methods' => [
                'SetHTMLHeader' => $this->renderPartial('_barcode', ['modelExportPdf' => $exportPdf]),
                'SetHTMLFooter' => $footer,
            ],
        ]);

        if($save == true) {
            $path = '../files/';
            $filename = time().'-pembayaran-tpp.pdf';
            if(file_exists($path.$filename)) {
                $filename = (time()+60).'-pembayaran-tpp.pdf';
            }
            $filepath = $path.$filename;
            $pdf->Output($content, $filepath, 'F');
            return $filepath;
        }

        return $pdf->render();
    }

    public function actionGeneratePdf($id_jenis)
    {
        $searchModel = new InstansiPegawaiSearch();
        $searchModel->jenis = 'tpp';

        $bulan = date('n');

        if(date('j') <= 10 AND $bulan != 1) {
            $bulan = $bulan - 1;
        }
        $searchModel->bulan = $bulan;
        $searchModel->tahun = Session::getTahun();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($id_jenis == 1) {
            $this->exportPdfPerhitungan($searchModel,false, true);
        }

        if ($id_jenis == 2) {
            $this->exportPdfPembayaran($searchModel, false, true);
        }

        if ($id_jenis == 3) {
            $this->exportPdfLembar($searchModel, false, false, true);
        }

        Yii::$app->session->setFlash('success', 'PDF berhasil digenerate');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function exportPdfRekapPembayaran($searchModel)
    {
        ini_set('memory_limit', '-1');

        $exportPdf = ExportPdf::find()
            ->andWhere(['id_instansi' => $searchModel->id_instansi])
            ->andWhere(['bulan' => $searchModel->bulan])
            ->andWhere(['tahun' => $searchModel->tahun])
            ->one();

        $cssInline = <<<CSS
        table {
            border-spacing: 0;
            padding: 7px;
            font-size: 11px;
            width: 100%;
            border-collapse: collapse;
        }
        th {
            vertical-align: middle;
            text-align: center;
        }
CSS;

        // Cek model export pdf
        if ($exportPdf == null) {
            $exportPdf = new ExportPdf();
            $exportPdf->id_instansi = $searchModel->id_instansi;
            $exportPdf->bulan = $searchModel->bulan;
            $exportPdf->tahun = $searchModel->tahun;
            $exportPdf->hash = Yii::$app->getSecurity()->generateRandomString(7);
            $exportPdf->save(false);
        }

        $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);
        $query->andWhere([
            'status_plt' => 0
        ]);
        $query->joinWith(['pegawai','jabatan']);
        $query->with(['pegawai']);
        $query->orderBy([
            'jabatan.id_eselon' => SORT_ASC,
            'pegawai.nama' => SORT_ASC,
        ]);

        $allInstansiPegawai = $query->all();

        $content = $this->renderPartial('export-pdf-rekap-pembayaran', [
            'allInstansiPegawai' => $allInstansiPegawai,
            'searchModel' => $searchModel,
            'modelExportPdf' => $exportPdf,
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
            'marginBottom' => 15,
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
            'options' => ['title' => 'Export PDF Lembar 3'],
            'cssInline' => $cssInline,
            // call mPDF methods on the fly
            'methods' => [
                'SetHTMLHeader' => $this->renderPartial('_barcode', ['modelExportPdf' => $exportPdf]),
                'SetHTMLFooter' => '',
            ],
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportExcelRekapPegawai(InstansiPegawaiSearch $searchModel)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->garbageCollect();
        $spreadsheet->getDefaultStyle()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet = $spreadsheet->getActiveSheet();

        $setBorderArray = array(
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        );

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(22);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(8);
        $sheet->getColumnDimension('F')->setWidth(40);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(30);

        $sheet->setCellValue('A2', 'DAFTAR NORMATIF PEMBAYARAN TAMBAHAN PENGHASILAN PEGAWAI ASN');
        $sheet->setCellValue('A3', 'KABUPATEN TIMOR TENGAH UTARA');

        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');
        $sheet->getStyle('A2:I3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A5', 'BULAN : ' . strtoupper(Helper::getBulanLengkap($searchModel->bulan)));

        $sheet->setCellValue('A6', 'NO');
        $sheet->setCellValue('B6', 'PD / UNIT KERJA');
        $sheet->setCellValue('C6', 'NIP');
        $sheet->setCellValue('D6', 'NAMA');
        $sheet->setCellValue('E6', 'GOL');
        $sheet->setCellValue('F6', 'JABATAN');
        $sheet->setCellValue('G6', 'KELAS JABATAN');
        $sheet->setCellValue('H6', 'BESARAN TPP');
        $sheet->setCellValue('I6', 'KETERANGAN');

        $bulan = $searchModel->bulan;
        $id_instansi = $searchModel->id_instansi;
        $isRecursive = $id_instansi == null;

        $query = Instansi::find();
        $query->andWhere(['instansi.status_aktif' => 1]);

        if ($id_instansi == null) {
            $query->andWhere('id_induk is null');
        }

        if ($id_instansi != null) {
            $query->andWhere(['id' => $id_instansi]);
        }

        $allInstansi = $query->all();

        $row = 6;
        $i = 1;

        /* @var $instansi Instansi */
        foreach ($allInstansi as $instansi) {
            $this->renderRowRekapPegawai($spreadsheet, $instansi, $bulan, $row, $i, $isRecursive);
        }

        $sheet->getStyle("A6:I$row")->getAlignment()->setWrapText(true);
        $sheet->getStyle("A6:I$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B7:B$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("D7:D$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("F7:F$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("H7:H$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("A7:I$row")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        $sheet->getStyle("A6:I$row")->applyFromArray($setBorderArray);

        $path = '../files/';
        $filename = time() . '_ExportRekapPegawai.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($path . $filename);
        return $this->redirect(['/file/get', 'fileName' => $filename]);
    }

    public function renderRowRekapPegawai(Spreadsheet $spreadsheet, Instansi $instansi, $bulan, &$row, &$i, $isRecursive=true)
    {
        $sheet = $spreadsheet->getActiveSheet();

        $query = $instansi->getManyInstansiPegawai();
        $query->filterByBulanTahun($bulan, Session::getTahun());
        $query->joinWith(['pegawai','jabatan']);
        $query->orderBy([
            'id_instansi' => SORT_ASC,
            'jabatan.id_eselon' => SORT_ASC
        ]);
        $query->andWhere('pegawai.id IS NOT NULL');

        $allInstansiPegawai = $query->all();

        /* @var $instansiPegawai InstansiPegawai */
        foreach ($allInstansiPegawai as $instansiPegawai) {
            $row++;
            $sheet->setCellValue("A$row", $i++);
            $sheet->setCellValue("B$row", @$instansiPegawai->instansi->nama);
            $sheet->setCellValueExplicit("C$row", @$instansiPegawai->pegawai->nip, DataType::TYPE_STRING);
            $sheet->setCellValue("D$row", @$instansiPegawai->pegawai->nama);
            $sheet->setCellValue("E$row", $instansiPegawai->pegawai->getNamaPegawaiGolonganBerlaku([
                'bulan' => $bulan,
            ]));
            $sheet->setCellValue("F$row", $instansiPegawai->nama_jabatan);
            $sheet->setCellValue("G$row", @$instansiPegawai->jabatan->kelas_jabatan);
            $sheet->setCellValue("H$row", Helper::rp(@$instansiPegawai->pegawai->getRupiahTppKotorFromRekap([
                'bulan' => $bulan,
            ]), 0));
            $sheet->setCellValue("I$row", "");
        }

        if ($isRecursive == true) {
            foreach ($instansi->findAllSub(['status_aktif' => 1]) as $sub) {
                $this->renderRowRekapPegawai($spreadsheet, $sub, $bulan, $row, $i);
            }
        }
    }

    public function refreshIndeksIpAsn(InstansiPegawaiSearch $searchModel)
    {
        $bulan = $searchModel->bulan;

        if (Pegawai::accessRefreshIpAsn(['bulan' => $bulan]) == false) {
            throw new ForbiddenHttpException('Anda tidak diperbolehkan mengakses aksi ini');
        }

        $allInstansiPegawai = $searchModel->getQuerySearch(Yii::$app->request->queryParams)
            ->groupBy(['pegawai.nip'])
            ->all();

        foreach ($allInstansiPegawai as $instansiPegawai) {
            $instansiPegawai->pegawai->updateRekapPegawaiBulanIpAsn([
                'bulan' => $bulan,
                'tahun' => Session::getTahun(),
            ]);
        }

        return true;
    }
}
