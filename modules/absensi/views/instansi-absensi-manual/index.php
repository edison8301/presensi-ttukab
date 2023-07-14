<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PegawaiAbsensiManualSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Unit Kerja Absensi Manual';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-absensi-manual-index box box-primary">

    <div class="box-header">
        <?= $this->render('_modal-instansi'); ?>
        <?php /*
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Pegawai Absensi Manual', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        */ ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Pegawai Absensi Manual', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>

    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'nama_instansi',
                'label' => 'Instansi',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->instansi->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => function($data) {
                    return Helper::getTanggal($data->tanggal_mulai);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => function($data) {
                    if($data->tanggal_selesai==null OR $data->tanggal_selesai=='9999-12-31') {
                        return '';
                    }
                    return Helper::getTanggal($data->tanggal_selesai);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
