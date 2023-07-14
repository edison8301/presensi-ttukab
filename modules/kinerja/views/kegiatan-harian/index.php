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

<div class="box box-primary">
    <div class="box-header">
        <?= Html::beginForm(['/kinerja/kegiatan-harian/index'], 'post'); ?>
        <?php if(KegiatanHarian::accessCreate()) { ?>
        <?= Html::a('<i class="fa fa-plus"></i> Kegiatan SKP', ['create','id_kegiatan_harian_jenis'=>1,'tanggal'=>$searchModel->tanggal], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('<i class="fa fa-plus"></i> Kegiatan Tambahan', ['create','id_kegiatan_harian_jenis'=>2,'tanggal'=>$searchModel->tanggal], ['class' => 'btn btn-primary btn-flat']) ?>

        <?php // echo Html::a('<i class="fa fa-print"></i> Export Excel Kegiatan', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::submitButton('<i class="fa fa-send-o"></i> Kirim Kegiatan', [
                'class' => 'btn btn-warning btn-flat',
                'data-confirm' => 'Yakin akan kirim kegiatan yang dipilih?',
                'name' => 'aksi',
                'value' => 'yii1'
        ]); ?>
        <?php } ?>
    </div>
    <div class="box-body">
        <?= $this->render('_grid-index',[
            'searchModel'=>$searchModel,
            'dataProvider' => $dataProvider
        ]); ?>
    </div>
</div>
