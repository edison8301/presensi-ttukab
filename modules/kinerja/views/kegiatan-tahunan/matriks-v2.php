<?php

use app\modules\kinerja\models\KegiatanTahunanSearch;
use yii\helpers\Html;
use app\modules\kinerja\models\KegiatanTahunan;
use app\models\User;


/* @var $this yii\web\View */
/* @var $model KegiatanTahunan */
/* @var $allKegiatanTahunanInduk KegiatanTahunan[] */
/* @var $searchmodel KegiatanTahunanSearch */

$this->title = "Matriks Kinerja Tahunan ".User::getTahun();
$this->params['breadcrumbs'][] = ['label' => 'Kinerja Tahunan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php /*
<?= AlertKegiatan::widget(['kegiatan' => $model]); ?>
*/ ?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-index-v2',['searchModel'=>$searchModel]); ?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Matriks Target Kinerja Bulanan SKP <?= $searchModel->nomor_skp; ?></h3>
    </div>
    <div class="box-header">
        <?php if(KegiatanTahunan::accessCreate()) { ?>
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Kinerja Tahunan',[
                'kegiatan-tahunan/create-v2',
                'id_pegawai'=>User::getIdPegawai(),
                'nomor_skp'=>$searchModel->nomor_skp
            ],['class' => 'btn btn-primary btn-flat']); ?>
        <?php } ?>
    </div>
    <div class="box-body">
        <?= $this->render('_matriks-kegiatan-tahunan-all',[
            'allKegiatanTahunanUtamaInduk' => $allKegiatanTahunanUtamaInduk,
            'allKegiatanTahunanTambahanInduk' => $allKegiatanTahunanTambahanInduk,
        ]); ?>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
    </div>
</div>

<?php /*
<?= $this->render('_kegiatan-bulanan', ['model' => $model]); ?>
<?= $this->render('_kegiatan-harian', ['model' => $model]); ?>
*/ ?>
