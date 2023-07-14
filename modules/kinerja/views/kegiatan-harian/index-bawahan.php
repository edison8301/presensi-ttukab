<?php

use app\modules\kinerja\models\KegiatanHarian;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\Helper;
use app\modules\kinerja\models\Kinerja;
use app\modules\kinerja\models\KegiatanHarianJenis;
use app\modules\kinerja\models\KegiatanStatus;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\kinerja\models\KegiatanHarianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Kegiatan Harian';

if($searchModel->mode == 'bawahan')
    $this->title .= ' Bawahan';

$this->title .= ' : '.$searchModel->getHariTanggal();

$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-index',['searchModel'=>$searchModel]); ?>

<?= Html::beginForm(['/kinerja/kegiatan-harian/index-bawahan'], 'post',[]); ?>


<div class="box box-primary">
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
        <?= $this->render('_grid-index',[
            'searchModel'=>$searchModel,
            'dataProvider' => $dataProvider
        ]); ?>
    </div>
</div>
