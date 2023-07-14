<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\Helper;
use app\modules\kinerja\models\KegiatanStatus;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KegiatanHarianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Kegiatan Harian';
if($searchModel->mode == 'bawahan')
    $this->title .= ' Bawahan';

$this->title .= ' : '.$searchModel->getHariTanggal();


$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_filter-index',['searchModel' => $searchModel, 'bawahan' => true]); ?>

<div class="box box-primary">

    <div class="box-header">
        <?php if($searchModel->getIsScenarioBawahan() && User::isPegawaiEselon()) { ?>
            <?= Html::beginForm(['kegiatan-harian/subordinat'], 'post'); ?>
            <?php if($searchModel->mode == 'pegawai') { ?>
            <?= Html::a('<i class="fa fa-plus"></i> Kegiatan SKP', ['create','id_kegiatan_harian_jenis'=>1,'tanggal'=>$searchModel->tanggal], ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::a('<i class="fa fa-plus"></i> Kegiatan Tambahan', ['create','id_kegiatan_harian_jenis'=>2,'tanggal'=>$searchModel->tanggal], ['class' => 'btn btn-primary btn-flat']) ?>
            <?php } ?>
            <?= Html::a('<i class="fa fa-print"></i> Export Excel Kegiatan', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::submitButton('<i class="fa fa-check"></i> Setujui Kegiatan',
                [
                    'class' => 'btn btn-info btn-flat',
                    'data-confirm' => 'Yakin akan menyetujui kegiatan yang dipilih?',
                    'name' => 'aksi',
                    'value' => 'yii1'
                ]); ?>
            <?php // Html::a('<i class="fa fa-check"></i> Setujui Semua', Yii::$app->request->url . '&setujui=1', ['class' => 'btn btn-success btn-flat']) ?>
            <?php // Html::a('<i class="fa fa-remove"></i> Tolak Semua', Yii::$app->request->url . '&tolak=1', ['class' => 'btn btn-danger btn-flat']) ?>
        <?php } elseif ($searchModel->mode == 'pegawai') { ?>
            <?= Html::a('<i class="fa fa-plus"></i> Kegiatan SKP', ['create','id_kegiatan_harian_jenis'=>1,'tanggal'=>$searchModel->tanggal], ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::a('<i class="fa fa-plus"></i> Kegiatan Tambahan', ['create','id_kegiatan_harian_jenis'=>2,'tanggal'=>$searchModel->tanggal], ['class' => 'btn btn-primary btn-flat']) ?>
        <?php } ?>

    </div>

    <div class="box-body">
        <?= $this->render('_grid-index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]); ?>
        <?php if ($searchModel->getIsScenarioBawahan() && User::isPegawaiEselon()): ?>
            <?= Html::endForm();; ?>
        <?php endif ?>
    </div>
</div>
