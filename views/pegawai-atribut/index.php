<?php

use app\components\Helper;
use app\models\PegawaiAtribut;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PegawaiAtributSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai Seragam Dinas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-atribut-index box box-primary">

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
                    'attribute' => 'nama_pegawai',
                    'label' => 'Pegawai',
                    'format' => 'raw',
                    'value' => function (PegawaiAtribut $data) {
                        return @$data->pegawai->nama;
                    },
                    'headerOptions' => ['style' => 'text-align:center;width:300px;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute' => 'keterangan',
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute' => 'tanggal',
                    'format' => 'raw',
                    'value' => function (PegawaiAtribut $data) {
                        return Helper::getTanggal($data->tanggal);
                    },
                    'headerOptions' => ['style' => 'text-align:center;width:180px;'],
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
