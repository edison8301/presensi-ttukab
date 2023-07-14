<?php

use app\models\Instansi;
use app\models\Jabatan;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Instansi */
/* @var $searchModel \app\models\InstansiSearch */

$this->title = "Struktur Jabatan ".$searchModel->getBulanLengkapTahun();
$this->params['breadcrumbs'][] = ['label' => 'Instansi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-view-jabatan',[
        'searchModel'=>$searchModel,
        'action'=>[
            '/instansi/view-jabatan',
            'id'=>$model->id
        ]
]); ?>

<div class="instansi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Instansi</h3>
    </div>

	<div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template'=>'<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
    		[
            	'label'=>'Nama',
            	'format'=>'raw',
                'value'=>$model->nama
            ],
            [
                'label'=>'Singkatan',
                'format'=>'raw',
                'value'=>$model->singkatan
            ],
        ],
    ]) ?>
    </div>
</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Peta Jabatan</h3>
    </div>
    <div class="box-body" style="text-align: center;">
        <a href="<?= Url::to(['instansi/peta','id' => $model->id]) ?>" target="_blank">
            <?= Html::img(Url::to(['instansi/peta','id' => $model->id]),['class' => 'img-responsive', 'style' => 'margin-left:auto;margin-right:auto']) ?>
        </a>
    </div>
</div>
<div class="instansi-view box box-primary collapsed-box">

    <div class="box-header">
        <h3 class="box-title">Rekap Data Jabatan</h3>
        <div class="box-tools">
            <button class="btn btn-sm btn-primary btn-flat" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
    </div>

    <div class="box-body">
        <table class="table">
            <tr>
                <th style="text-align:center; width:50px">No</th>
                <th>Uraian</th>
                <th style="text-align:center;">Nilai</th>
                <th style="text-align:center;">Keterangan</th>
            </tr>
            <tr>
                <td colspan="4" style="font-weight: bold">Pegawai</td>
            </tr>
            <tr>
                <td style="text-align:center;">1</td>
                <td>Jumlah Pegawai</td>
                <td style="text-align:center;"><?= $model->countInstansiPegawaiByBulanTahun(); ?> Pegawai</td>
                <td></td>
            </tr>


            <tr>
                <td colspan="4" style="font-weight: bold">Jabatan Berdasarkan Jenis Jabatan</td>
            </tr>
            <tr>
                <td style="text-align:center;">1</td>
                <td>Jumlah Jabatan Struktural</td>
                <td style="text-align:center;"><?= $model->countJabatan(['id_jenis_jabatan'=>1]); ?> Jabatan</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align:center;">2</td>
                <td>Jumlah Jabatan Fungsional Tertentu (JFT)</td>
                <td style="text-align:center;"><?= $model->countJabatan(['id_jenis_jabatan'=>2]); ?> Jabatan</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align:center;">3</td>
                <td>Jumlah Jabatan Fungsional Umum (JFU)</td>
                <td style="text-align:center;"><?= $model->countJabatan(['id_jenis_jabatan'=>3]); ?> Jabatan</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4" style="font-weight: bold">Jabatan Berdasarkan Eselon</td>
            </tr>
            <tr>
                <td style="text-align:center;">1</td>
                <td>Jumlah Jabatan Eselon I</td>
                <td style="text-align:center;"><?= $model->countJabatan(['id_jabatan_eselon'=>1]); ?> Jabatan</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align:center;">2</td>
                <td>Jumlah Jabatan Eselon II</td>
                <td style="text-align:center;"><?= $model->countJabatan(['id_jabatan_eselon'=>2]); ?> Jabatan</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align:center;">3</td>
                <td>Jumlah Jabatan Eselon III</td>
                <td style="text-align:center;"><?= $model->countJabatan(['id_jabatan_eselon'=>3]); ?> Jabatan</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align:center;">4</td>
                <td>Jumlah Jabatan Eselon IV</td>
                <td style="text-align:center;"><?= $model->countJabatan(['id_jabatan_eselon'=>4]); ?> Jabatan</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align:center;">5</td>
                <td>Jumlah Jabatan Eselon V</td>
                <td style="text-align:center;"><?= $model->countJabatan(['id_jabatan_eselon'=>5]); ?> Jabatan</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4" style="font-weight: bold">Jabatan Berdasarkan Sudah/Belum Dipetakan</td>
            </tr>
            <tr>
                <td style="text-align:center;">1</td>
                <td>Jumlah Jabatan Sudah Dipetakan</td>
                <td style="text-align:center;"><?= $model->countJabatanSudahDipetakan(); ?> Jabatan</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align:center;">2</td>
                <td>Jumlah Jabatan Belum Dipetakan</td>
                <td style="text-align:center;"><?= $model->countJabatanBelumDipetakan(); ?> Jabatan</td>
                <td></td>
            </tr>

        </table>
    </div>
</div>

<div class="instansi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Struktur Jabatan</h3>
        <div class="box-tools">
            <button class="btn btn-sm btn-primary btn-flat" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-header">
        <?php if (User::isAdmin() || User::isMapping()) { ?>
            <?= Html::a('<i class="fa fa-plus"></i> Tambah Jabatan', ['/jabatan/create', 'id_instansi' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>

            <?= $this->render('_modal-instansi-atasan', [
                'id_instansi' => $model->id,
            ]) ?>
        <?php } ?>

        <?php if (Instansi::accessExportJabatan()){ ?>
            <?= Html::a('<i class="fa fa-file-pdf-o"></i> Export Pdf Jabatan', ['/instansi/export-pdf-jabatan', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat','target'=>'_blank']) ?>
            <?= Html::a('<i class="fa fa-file-excel-o"></i> Export Excel Jabatan', ['/instansi/export-excel-jabatan', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?php } ?>

        <?php if ($searchModel->status_tampil == 1) {
            echo Html::a('<i class="fa fa-eye"></i> Tampilkan Semua Jabatan', [
                '/instansi/view-jabatan',
                'id' => $model->id,
                'mode' => $searchModel->mode,
                'status_tampil_is_null' => true,
            ], ['class' => 'btn btn-primary btn-flat']);
        } ?>

        <?php if ($searchModel->status_tampil != 1) {
            echo Html::a('<i class="fa fa-eye-slash"></i> Sembuyikan Jabatan', [
                '/instansi/view-jabatan',
                'id' => $model->id,
                'mode' => $searchModel->mode,
                'status_tampil_is_null' => false,
            ], ['class' => 'btn btn-primary btn-flat']);
        } ?>

        <?php if ($searchModel->mode != 'abk') {
            echo Html::a('<i class="fa fa-refresh"></i> Mode ABK', [
                '/instansi/view-jabatan',
                'id' => $model->id,
                'mode' => 'abk'
            ], ['class' => 'btn btn-primary btn-flat']);
        } ?>

        <?php if ($searchModel->mode == 'abk') {
            echo Html::a('<i class="fa fa-refresh"></i> Mode Kelas Jabatan', [
                '/instansi/view-jabatan',
                'id' => $model->id,
                'mode' => 'kelas-jabatan'
            ], ['class' => 'btn btn-primary btn-flat']);
        } ?>

    </div>

    <div class="box-body">

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="text-align: center; vertical-align: middle">Nama Jabatan</th>
                <th style="text-align: center; vertical-align: middle">Bidang</th>
                <th style="text-align: center;">Jenis Jabatan</th>
                <?php if ($searchModel->mode != 'abk') { ?>
                    <th style="text-align: center; width: 50px">Nilai<br/>Jabatan</th>
                    <th style="text-align: center; width: 50px">Kelas<br/>Jabatan</th>
                    <th style="text-align: center; vertical-align: middle">Pegawai</th>
                    <th style="text-align: center">Mutasi/<br/>Promosi</th>
                    <th style="text-align: center">Status<br/>Verifikasi</th>
                <?php } ?>
                <?php if ($searchModel->mode == 'abk') { ?>
                    <th style="text-align: center; width: 100px;">Jumlah Pegawai</th>
                    <th style="text-align: center; width: 100px;">Hasil ABK</th>
                    <th style="text-align: center; width: 100px;">Selisih</th>
                <?php } ?>
                <th style="width: 80px;"></th>
            </tr>
            </thead>
            <?php foreach($model->manyJabatanKepala as $jabatan) { ?>
                <?= $this->render('_tr-jabatan', [
                    'jabatan' => $jabatan,
                    'level' => 0,
                    'searchModel' => $searchModel,
                    'id_instansi' => $model->id,
                ]); ?>
            <?php } ?>
        </table>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">
            Jabatan Yang Tidak Ditampilkan / Sembunyikan
        </h3>
        <div class="box-tools">
            <button class="btn btn-sm btn-primary btn-flat" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <?= $this->render('_table-jabatan-belum-dipetakan', [
            'allJabatan' => $model->findAllJabatan([
                'status_tampil' => Jabatan::TIDAK_TAMPIL,
            ]),
        ]) ?>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">
            Jabatan Yang Belum Dipetakan (Belum Ditentukan Atasan Langsung)
        </h3>
        <div class="box-tools">
            <button class="btn btn-sm btn-primary btn-flat" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <?= $this->render('_table-jabatan-belum-dipetakan', [
            'allJabatan' => $model->manyJabatanBelumDipetakan,
        ]) ?>
    </div>
</div>


