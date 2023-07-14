<?php

use app\components\Helper;
use app\components\Session;
use app\modules\kinerja\models\KegiatanHarianDiskresi;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\kinerja\models\KegiatanHarianDiskresiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Kegiatan Harian Diskresi Tahun ' . Session::getTahun();
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-harian-diskresi-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Data', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>

    <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center;width:10px;'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => function (KegiatanHarianDiskresi $data) {
                    return @$data->pegawai->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'tanggal',
                'format' => 'raw',
                'value' => function (KegiatanHarianDiskresi $data) {
                    return Helper::getTanggal($data->tanggal);
                },
                'headerOptions' => ['style' => 'text-align:center;width:200px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'keterangan',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;width:250px;'],
                'contentOptions' => ['style' => 'text-align:lefft;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
