<?php

use app\components\Helper;
use app\models\InstansiPegawai;
use app\modules\tandatangan\models\BerkasJenis;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InstansiPegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$append = null;
if ($searchModel->jenis == 'penundaan-tpp') {
    $append = ' (Penundaan)';
}

$this->title = 'Daftar Pegawai Bulan '.$searchModel->getBulanLengkapTahun() . $append;
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('//filter/_filter-tahun') ?>

<?= $this->render('_filter',[
    'searchModel'=>$searchModel,
    'action'=>Url::to(['/tunjangan/instansi-pegawai/index', 'jenis' => $searchModel->jenis])
]); ?>

<div class="instansi-pegawai-index box box-primary">

    <div class="box-header">

        <?= Html::a('<i class="fa fa-refresh"></i> Refresh Rekap Absensi', Yii::$app->request->url.'&refresh=1', ['class' => 'btn btn-primary btn-flat btn-mb-3','onclick'=>'return confirm("Yakin akan merefresh rekap absensi? Proses refresh akan memakan waktu beberapa menit")', 'style' => 'margin-bottom: 3px;']) ?>
        <?= Html::a('<i class="fa fa-refresh"></i> Refresh IP ASN', Url::current(['refresh-ip-asn' => 1]), ['class' => 'btn btn-primary btn-flat btn-mb-3','onclick'=>'return confirm("Yakin akan me-refresh data skor IP ASN?")']) ?>
        |
        <?= Html::a('<i class="fa fa-print"></i> Export PDF Perhitungan TPP', Yii::$app->request->url.'&export_pdf_perhitungan=1', ['class' => 'btn btn-primary btn-flat btn-mb-3','target'=>'_blank']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export PDF Pembayaran TPP', Yii::$app->request->url.'&export_pdf_pembayaran=1', ['class' => 'btn btn-primary btn-flat btn-mb-3','target'=>'_blank']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export PDF Lembar 3', Yii::$app->request->url.'&export_pdf_lembar=1', ['class' => 'btn btn-primary btn-flat btn-mb-3','target'=>'_blank']) ?>

        <?= Html::a('<i class="fa fa-print"></i> Export PDF Pembayaran TPP 13', Yii::$app->request->url.'&export_pdf_pembayaran_13=1', ['class' => 'btn btn-success btn-flat btn-mb-3','target'=>'_blank']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export PDF Lembar 3 TPP 13', Yii::$app->request->url.'&export_pdf_lembar_13=1', ['class' => 'btn btn-success btn-flat btn-mb-3','target'=>'_blank']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export PDF Pembayaran TPP THR', Yii::$app->request->url.'&export_pdf_pembayaran_14=1', ['class' => 'btn btn-success btn-flat btn-mb-3','target'=>'_blank']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export PDF Lembar 3 TPP THR', Yii::$app->request->url.'&export_pdf_lembar_14=1', ['class' => 'btn btn-success btn-flat btn-mb-3','target'=>'_blank']) ?>

        <?php if ($searchModel->jenis !== 'penundaan-tpp') { ?>
            <?= Html::a('<i class="fa fa-send"></i> Kirim Dokumen Perhitungan TPP', Yii::$app->request->url.'&kirim-dokumen='.BerkasJenis::PERHITUNGAN_TPP, [
                'class' => 'btn btn-primary btn-flat btn-mb-3',
                'onclick'=>'return confirm("Yakin akan mengirim dokumen? Proses kirim akan memakan waktu beberapa menit")',
            ]) ?>
            <?= Html::a('<i class="fa fa-send"></i> Kirim Dokumen Pembayaran TPP', Yii::$app->request->url.'&kirim-dokumen='.BerkasJenis::PEMBAYARAN_TPP, [
                'class' => 'btn btn-primary btn-flat btn-mb-3',
                'onclick'=>'return confirm("Yakin akan mengirim dokumen? Proses kirim akan memakan waktu beberapa menit")',
            ]) ?>
            <?= Html::a('<i class="fa fa-send"></i> Kirim Dokumen Lembar 3', Yii::$app->request->url.'&kirim-dokumen='.BerkasJenis::LEMBAR_3, [
                'class' => 'btn btn-primary btn-flat btn-mb-3',
                'onclick'=>'return confirm("Yakin akan mengirim dokumen? Proses kirim akan memakan waktu beberapa menit")',
            ]) ?>
        <?php } ?>

        <?php if (Yii::$app->request->get('debug')==true) { ?>
            <?= Html::a('<i class="fa fa-print"></i> Generate PDF Perhitungan TPP', [
                '/tunjangan/instansi-pegawai/generate-pdf',
                'InstansiPegawaiSearch[id_instansi]' => $searchModel->id_instansi,
                'InstansiPegawaiSearch[bulan]' => $searchModel->bulan,
                'id_jenis' => 1,
            ], ['class' => 'btn btn-primary btn-flat','target'=>'_blank']) ?>
            <?= Html::a('<i class="fa fa-print"></i> Generate PDF Pembayaran TPP', [
                '/tunjangan/instansi-pegawai/generate-pdf',
                'InstansiPegawaiSearch[id_instansi]' => $searchModel->id_instansi,
                'InstansiPegawaiSearch[bulan]' => $searchModel->bulan,
                'id_jenis' => 2,
            ], ['class' => 'btn btn-primary btn-flat','target'=>'_blank']) ?>
            <?= Html::a('<i class="fa fa-print"></i> Generate PDF Lembar 3', [
                '/tunjangan/instansi-pegawai/generate-pdf',
                'InstansiPegawaiSearch[id_instansi]' => $searchModel->id_instansi,
                'InstansiPegawaiSearch[bulan]' => $searchModel->bulan,
                'id_jenis' => 3,
            ], ['class' => 'btn btn-primary btn-flat','target'=>'_blank']) ?>
        <?php } ?>

        <?php if ($searchModel->getJumlah() > 100) { ?>
            <br>
            <?php $form = ActiveForm::begin([
                'type'=>'inline',
                'method' => 'get',
            ]) ?>

            <?= $form->field($searchModel, 'exportJenis', [
                'addon' => ['prepend' => ['content' => 'Jenis']],
            ])->dropDownList([
                Yii::$app->request->url.'&export_pdf_perhitungan=1' => 'Export PDF Perhitungan TPP',
                Yii::$app->request->url.'&export_pdf_pembayaran=1' => 'Export PDF Pembayaran TPP',
                Yii::$app->request->url.'&export_pdf_lembar=1' => 'Export PDF Lembar 3',
            ], [
                'prompt' => '- Pilih Jenis -'
            ]) ?>

            <?= $form->field($searchModel, 'exportDari', [
                'addon' => ['prepend' => ['content' => 'Dari']],
            ])->dropDownList($searchModel->getListItemPage()) ?>

            <?= Html::a('<i class="fa fa-print"></i> Export', 'javascript:;', [
                'class' => 'btn btn-primary btn-flat',
                'onclick' => 'window.open($("#instansipegawaisearch-exportjenis").val() + $("#instansipegawaisearch-exportdari").val(), "_blank")',
            ]) ?>

            <?php ActiveForm::end() ?>
        <?php } ?>

    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => 'First',
            'lastPageLabel' => 'Last',
        ],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'nama_pegawai',
                'format' => 'raw',
                'value'=>function($data) use ($searchModel) {
                    return Html::a(@$data->pegawai->nama,[
                        '/tunjangan/pegawai/view-v3','id'=>$data->id_pegawai,
                        'FilterTunjanganForm[bulan]'=>$searchModel->bulan
                    ]).'<br/>NIP.'.@$data->pegawai->nip;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => function($data) {
                    return @$data->instansi->nama;
                },
                'headerOptions' => ['style' => 'text-align:center; width: 250px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'id_golongan',
                'format' => 'raw',
                'header' => 'Golongan',
                'value' => function($data) {
                    return @$data->pegawai->getLabelGolongan();
                },
                'headerOptions' => ['style' => 'text-align:center; width: 100px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'nama_jabatan',
                'format' => 'raw',
                'value' => function(InstansiPegawai $data) {
                    $html = null;
                    $html .= @$data->getNamaJabatan().'<br>';
                    $html .= $data->jabatan->getLabelTingkatanStruktural();
                    $html .= $data->jabatan->getLabelTingkatanFungsional();
                    return $html;

                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_berlaku',
                'label'=>'Tanggal<br/>TMT',
                'encodeLabel' => false,
                'format' => 'raw',
                'value'=>function($data) {
                    return Helper::getTanggalSingkat($data->tanggal_berlaku);
                },
                'headerOptions' => ['style' => 'text-align:center; width: 100px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_mulai',
                'label'=>'Tanggal<br/>Mulai<br/>Efektif',
                'encodeLabel' => false,
                'format' => 'raw',
                'value'=>function($data) {
                    return Helper::getTanggalSingkat($data->tanggal_mulai);
                },
                'headerOptions' => ['style' => 'text-align:center; width: 100px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_selesai',
                'label'=>'Tanggal<br/>Selesai<br/>Efektif',
                'encodeLabel' => false,
                'format' => 'raw',
                'value'=>function($data) {
                    return Helper::getTanggalSingkat($data->tanggal_selesai);
                },
                'headerOptions' => ['style' => 'text-align:center; width: 100px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'format' => 'raw',
                'value'=>function($data) use ($searchModel) {
                    return Html::a('<i class="glyphicon glyphicon-eye-open"></i>',[
                            '/tunjangan/pegawai/view-v3','id'=>$data->id_pegawai,
                            'FilterTunjanganForm[bulan]'=>$searchModel->bulan
                        ]);
                },
                'headerOptions' => ['style' => 'text-align:center; width: 50px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
        ],
    ]); ?>
    </div>
</div>

<style>
    .btn-mb-3 {
        margin-bottom: 3px;
    }
</style>
