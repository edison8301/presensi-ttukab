<?php 

use yii\helpers\Html;

/* @var $allInstansiPegawai InstansiPegawai[] */

$this->title = 'Matriks Pembagian Peran Hasil';
?>

<?php if ($searchModel->id_instansi == null) { ?>
    <div class="callout callout-info">
        <p>Silahkan pilih unit kerja terlebih dahulu</p>
    </div>
<?php } ?>

<?= $this->render('_filter-matriks-peran-hasil', [
    'searchModel' => $searchModel
]) ?>

<div class="box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->title ?></h3>
    </div>

    <div class="box-body">

        <?php /*
            <?= Html::a('<i class="fa fa-file-pdf-o"></i> Export PDF', Yii::$app->request->url . '&export-pdf=1', [
                'class' => 'btn btn-success btn-flat',
                'target' => '_blank',
            ]) ?>
        */ ?>

        <?= Html::a('<i class="fa fa-file-excel-o"></i> Export Excel', Yii::$app->request->url . '&export-excel=1', [
            'class' => 'btn btn-success btn-flat',
        ]) ?>

        <?= Html::a('<i class="fa fa-list"></i> Pimpinan', [
            '/kinerja/instansi-pegawai/matriks-peran-hasil',
            'InstansiPegawaiSearch[id_instansi]' => $searchModel->id_instansi,
        ], [
            'class' => 'btn btn-success btn-flat',
        ]) ?>

        <?= Html::a('<i class="fa fa-list"></i> Administrator', [
            '/kinerja/instansi-pegawai/matriks-peran-hasil',
            'InstansiPegawaiSearch[id_instansi]' => $searchModel->id_instansi,
            'mode' => 'administrator',
        ], [
            'class' => 'btn btn-success btn-flat',
        ]) ?>

        <?= Html::a('<i class="fa fa-list"></i> Pengawas', [
            '/kinerja/instansi-pegawai/matriks-peran-hasil',
            'InstansiPegawaiSearch[id_instansi]' => $searchModel->id_instansi,
            'mode' => 'pengawas',
        ], [
            'class' => 'btn btn-success btn-flat',
        ]) ?>

    </div>
</div>

<?php if ($searchModel->id_instansi != null) { ?>
    <?php foreach ($allInstansiPegawai as $instansiPegawai) { ?>
        <div class="box box-primary">
            <div class="box-body">
                <div style="overflow: auto;">
                    <?= $this->render('_table-matriks-peran-hasil', [
                        'instansiPegawai' => $instansiPegawai,
                    ]); ?>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
