<?php

use app\modules\kinerja\models\KegiatanRhk;
use yii\helpers\Html;

/* @see \app\modules\kinerja\controllers\KegiatanRhkController::actionIndex() */
/* @var $this yii\web\View */
/* @var $searchModel app\modules\kinerja\models\KegiatanRhkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $allKegiatanRhkUtama KegiatanRhk[] */
/* @var $allKegiatanRhkTambahan KegiatanRhk[] */
/* @var $instansiPegawaiSkp \app\modules\kinerja\models\InstansiPegawaiSkp */

$this->title = 'Daftar RHK';
if ($searchModel->nomor_skp != null) {
    $this->title .= ' : SKP ' . $searchModel->nomor_skp;
}

$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-index', [
    'searchModel' => $searchModel,
]) ?>

<div class="kegiatan-rhk-index box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->title ?></h3>
    </div>

    <div class="box-body">

        <?php if ($instansiPegawaiSkp == null) { ?>
            <i>Silahkan pilih skp terlebih dahulu</i>
        <?php } ?>

        <?php if ($instansiPegawaiSkp != null) { ?>

            <div style="margin-bottom: 20px;">
                <?= $this->render('_modal-skp', [
                    'searchModel' => $searchModel,
                ]); ?>

                <?php if ($instansiPegawaiSkp->isJpt() == false) { ?>
                    <?= Html::a('<i class="fa fa-list"></i> Lampiran SKP', [
                        '/kinerja/instansi-pegawai-skp/index-lampiran',
                        'id_pegawai' => $searchModel->id_pegawai,
                        'InstansiPegawaiSkpSearch[nomor]' => $searchModel->nomor_skp
                    ], ['class' => 'btn btn-success btn-flat']); ?>
                <?php } ?>

                <?php if ($instansiPegawaiSkp->isJpt() == true) { ?>
                    <?= Html::a('<i class="fa fa-list"></i> Manual Indikator Kinerja', [
                        '/kinerja/kegiatan-tahunan/index-mik',
                        'id_instansi_pegawai_skp' => $instansiPegawaiSkp->id,
                    ], ['class' => 'btn btn-success btn-flat']); ?>
                <?php } ?>
            </div>

            <?php if ($instansiPegawaiSkp->isJpt() == false) { ?>
                <?= $this->render('/instansi-pegawai-skp/_table-kegiatan-rhk-non-jpt', [
                    'allKegiatanRhkUtama' => $allKegiatanRhkUtama,
                    'allKegiatanRhkTambahan' => $allKegiatanRhkTambahan,
                ]) ?>
            <?php } ?>

            <?php if ($instansiPegawaiSkp->isJpt() == true) { ?>
                <?= $this->render('/instansi-pegawai-skp/_table-kegiatan-rhk-jpt', [
                    'allKegiatanRhkUtama' => $allKegiatanRhkUtama,
                    'allKegiatanRhkTambahan' => $allKegiatanRhkTambahan,
                ]) ?>
            <?php } ?>

        <?php } ?>

    </div>
</div>
