<?php

use app\components\Helper;
use app\models\Kegiatan;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KegiatanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Kegiatan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Kegiatan', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>

    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center;width:50px;'],
                'contentOptions' => ['style' => 'text-align:center;']
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'tanggal',
                'format' => 'raw',
                'value' => function (Kegiatan $data) {
                    return Helper::getTanggal($data->tanggal);
                },
                'headerOptions' => ['style' => 'text-align:center;width:150px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'jam_mulai_absen',
                'label' => 'Jam Mulai<br/>Absen',
                'encodeLabel' => false,
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;width:102px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'jam_selesai_absen',
                'label' => 'Jam Selesai<br/>Absen',
                'encodeLabel' => false,
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;width:120px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'text-align:center;width:80px;'],
                'contentOptions' => ['style' => 'text-align:center;']
            ],
        ],
    ]); ?>
    </div>
</div>
