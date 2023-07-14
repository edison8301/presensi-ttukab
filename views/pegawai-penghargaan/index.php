<?php

use app\components\Helper;
use app\models\PegawaiPenghargaan;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PegawaiPenghargaanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai Penghargaan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-penghargaan-index box box-primary">

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
                    'value' => function (PegawaiPenghargaan $data) {
                        return @$data->pegawai->nama;
                    },
                    'headerOptions' => ['style' => 'text-align:center;width:350px;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute' => 'nama',
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute' => 'id_pegawai_penghargaan_tingkat',
                    'label' => 'Tingkat',
                    'format' => 'raw',
                    'filter' => \app\models\PegawaiPenghargaanTingkat::getList(),
                    'value' => function (PegawaiPenghargaan $data) {
                        return @$data->pegawaiPenghargaanTingkat->nama;
                    },
                    'headerOptions' => ['style' => 'text-align:center;width:120px;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'tanggal',
                    'format' => 'raw',
                    'value' => function (PegawaiPenghargaan $data) {
                        return Helper::getTanggal($data->tanggal);
                    },
                    'headerOptions' => ['style' => 'text-align:center;width:140px;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'id_pegawai_penghargaan_status',
                    'label' => 'Status',
                    'format' => 'raw',
                    'filter' => \app\models\PegawaiPenghargaanStatus::getList(),
                    'value' => function (PegawaiPenghargaan $data) {
                        return $data->getLabelPegawaiPenghargaanStatus();
                    },
                    'headerOptions' => ['style' => 'text-align:center;width:80px;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'format' => 'raw',
                    'value' => function (PegawaiPenghargaan $data) {
                        $btn = '';
                        $btn .= $data->getLinkSetSetujuIcon() . ' ';
                        $btn .= $data->getLinkSetTolakIcon() . ' ';
                        $btn .= $data->getLinkViewIcon() . ' ';
                        $btn .= $data->getLinkUpdateIcon() . ' ';
                        $btn .= $data->getLinkDeleteIcon() . ' ';
                        return $btn;
                    },
                    'headerOptions' => ['style' => 'text-align:center;width:100px;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
            ],
        ]); ?>
    </div>
</div>
