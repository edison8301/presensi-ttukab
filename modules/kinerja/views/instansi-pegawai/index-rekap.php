<?php

use app\components\Helper;
use app\components\Session;
use app\models\Instansi;
use app\models\InstansiPegawai;
use app\modules\tandatangan\models\BerkasJenis;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @see \app\modules\kinerja\controllers\InstansiPegawaiController::actionIndexRekap() */
/* @var $this yii\web\View */
/* @var $searchModel app\models\InstansiPegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rekap SKP dan RKB '.$searchModel->getBulanLengkapTahun();
$this->params['breadcrumbs'][] = $this->title;
?>

<?php //$this->render('//filter/_filter-tahun') ?>

<?php $form = ActiveForm::begin([
    'action' => ['/kinerja/instansi-pegawai/index-rekap'],
    'type'=>'inline',
    'method' => 'get',
]); ?>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Filter Kegiatan Harian</h3>
    </div>
    <div class="box-body">
        <?= $form->field($searchModel, 'bulan')->dropDownList(
            Helper::getListBulan(),
            ['prompt' => '- Pilih Bulan -']
        ); ?>

        <?php //$form->field($searchModel, 'tahun')->textInput() ?>

        <?= $form->field($searchModel, 'id_instansi')->widget(Select2::className(), [
            'data' => Instansi::getList(),
            'options' => [
                'placeholder' => '- Pilih Instansi -',
                'id' => 'id-eselon',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'width'=>'450px'
            ]
        ]); ?>
        <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<div class="box box-primary">
    <div class="box-header">
        <?= Html::a('<i class="fa fa-file-pdf-o"></i> Export PDF Rekap', Yii::$app->request->url.'&export=1', ['target' => '_blank', 'class' => 'btn btn-danger btn-flat']) ?>
        <?= Html::a('<i class="fa fa-send"></i> Kirim Dokumen Rekap SKP dan RKB', Yii::$app->request->url.'&kirim-dokumen='.BerkasJenis::REKAP_SKP_DAN_RKB, [
            'class' => 'btn btn-primary btn-flat',
            'onclick'=>'return confirm("Yakin akan mengirim dokumen? Proses kirim akan memakan waktu beberapa menit")'
        ]) ?>
    </div>
    <div class="box-body" style="overflow: auto;">
        <table class="table table-bordered rekap">
            <thead>
            <tr>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">No</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">Nama <br> Pegawai</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">NIP</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">Jabatan</th>
                <th style="text-align: center; vertical-align: middle;" colspan="4">Perhitungan SKP dan RKB</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">TOT.<br>POT%</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">KET</th>
            </tr>
            <tr>
                <th style="text-align: center; vertical-align: middle;">SKP</th>
                <th style="text-align: center; vertical-align: middle;">POTONGAN<br>SKP<br>(%)</th>
                <th style="text-align: center; vertical-align: middle;">REALISASI<br/>RKB<br/>(%)</th>
                <th style="text-align: center; vertical-align: middle;">POTONGAN<br>RKB<br>(%)</th>
            </tr>
            </thead>
            <?php if ($searchModel->id_instansi != null) { ?>
                <?php $no = 1; foreach ($searchModel->getAll() as $data) { ?>
                    <?= $this->render('_tr-rekap',[
                        'searchModel' => $searchModel,
                        'data' => $data,
                        'no' => $no,
                    ]); ?>
                <?php $no++; } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="9" style="font-style: italic;">
                        Silahkan Pilih Instansi Terlebih dahulu
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
