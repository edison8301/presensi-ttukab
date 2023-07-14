<?php

use app\modules\absensi\models\PegawaiDispensasi;
use app\modules\absensi\models\PegawaiDispensasiJenis;
use yii\helpers\Html;
use app\components\Helper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\absensi\models\PegawaiDispensasiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai Dispensasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-dispensasi-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Pegawai Dispensasi', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Pegawai Dispensasi', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>

    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => 'First',
            'lastPageLabel' => 'Last',
        ],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
                'value' => function ($data) {
                    return @$data->pegawai->nama;
                }
            ],
            [
                'attribute' => 'id_pegawai_dispensasi_jenis',
                'format' => 'raw',
                'value' => function (PegawaiDispensasi $data) {
                    return @$data->pegawaiDispensasiJenis->nama;
                },
                'filter' => PegawaiDispensasiJenis::list(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'id_instansi',
                'header' => 'Instansi',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function ($data) {
                    return @$data->pegawai->instansi->nama;
                }
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function ($data) {
                    return Helper::getTanggal($data->tanggal_mulai);
                }
            ],
            [
                'attribute' => 'tanggal_akhir',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function ($data) {
                    return Helper::getTanggal($data->tanggal_akhir);
                }
            ],
            [
                'attribute' => 'keterangan',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->keterangan;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
