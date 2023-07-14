<?php

use app\modules\kinerja\models\KegiatanTahunanSearch;
use yii\helpers\Html;
use app\models\User;
use app\modules\kinerja\models\KegiatanStatus;

/* @see \app\modules\kinerja\controllers\KegiatanTahunanController::actionIndexBawahan()
/* @var $this yii\web\View */
/* @var $searchModel KegiatanTahunanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Kegiatan Tahunan';
if($searchModel->mode == 'bawahan')
    $this->title .= ' Bawahan';

$this->title .= ' : ' . $searchModel->tahun;

$this->params['breadcrumbs'][] = $this->title;

if(isset($debug)==false) {
    $debug = false;
}

?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-index',['searchModel'=>$searchModel]); ?>

<?= Html::beginForm(['/kinerja/kegiatan-tahunan/index-bawahan'], 'post'); ?>

<div class="kegiatan-tahunan-index box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Daftar Kegiatan Utama SKP <?= $searchModel->nomor_skp; ?></h3>
    </div>
    <div class="box-header">

        <div class="form-group form-inline">
            <div class="input-group form-inline">
                <?= Html::dropDownList('aksi',null,['1'=>'Setuju','4'=>'Tolak'],[
                    'class'=>'form-control',
                    'prompt'=>'- Pilih Aksi -'
                ],[
                    'style'=>'width:30px'
                ]); ?>
            </div>
            <div class="input-group">
                <?= Html::submitButton('<i class="fa fa-check"></i> Terapkan Aksi', [
                    'class' => 'btn btn-success btn-flat',
                    'data-confirm' => 'Yakin akan kirim kegiatan yang dipilih?',
                ]); ?>
            </div>
        </div>

    </div>

    <div class="box-body">
        <?= $this->render('_grid-index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'debug'=>$debug
        ]); ?>
    </div>
</div>
<?php Html::endForm(); ?>
