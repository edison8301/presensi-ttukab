<?php

use app\modules\kinerja\models\KegiatanTahunanSearch;
use yii\helpers\Html;
use app\modules\kinerja\models\KegiatanTahunan;
use app\models\User;


/* @var $this yii\web\View */
/* @var $model KegiatanTahunan */
/* @var $allKegiatanTahunanInduk KegiatanTahunan[] */
/* @var $searchmodel KegiatanTahunanSearch */

$this->title = "Matriks Kinerja Triwulan ".User::getTahun();
$this->params['breadcrumbs'][] = ['label' => 'Kinerja Triwulan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php /*
<?= AlertKegiatan::widget(['kegiatan' => $model]); ?>
*/ ?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?php /* $this->render('_filter-index',['searchModel'=>$searchModel]); */ ?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Matriks Target Kinerja Triwulan</h3>
    </div>
    <div class="box-header">
        <?php if(KegiatanTahunan::accessCreate()) { ?>
        <?php /* Html::a('<i class="fa fa-plus"></i> Tambah Kinerja Triwulan',[
                'kegiatan-triwulan/create',
                'id_pegawai'=>User::getIdPegawai()
            ],['class' => 'btn btn-primary btn-flat']); */ ?>
        <?php } ?>
    </div>
    <div class="box-body">
        <?= $this->render('_matriks-kegiatan-triwulan-all',[
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
