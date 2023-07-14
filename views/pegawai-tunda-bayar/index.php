<?php

use app\components\Helper;
use app\models\PegawaiTundaBayar;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PegawaiTundaBayarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Penundaan Pembayaran TPP Pegawai';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-tunda-bayar-index box box-primary">

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
                    'value' => function (PegawaiTundaBayar $data) {
                        return @$data->pegawai->nama;
                    },
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute' => 'id_pegawai_tunda_bayar_jenis',
                    'label' => 'Jenis',
                    'format' => 'raw',
                    'filter' => \app\models\PegawaiTundaBayarJenis::getList(),
                    'value' => function (PegawaiTundaBayar $data) {
                        return @$data->pegawaiTundaBayarJenis->nama;
                    },
                    'headerOptions' => ['style' => 'text-align:center;width:300px;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'tanggal_mulai',
                    'format' => 'raw',
                    'value' => function (PegawaiTundaBayar $data) {
                        return Helper::getTanggal($data->tanggal_mulai);
                    },
                    'headerOptions' => ['style' => 'text-align:center;width:150px;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'tanggal_selesai',
                    'format' => 'raw',
                    'value' => function (PegawaiTundaBayar $data) {
                        if ($data->tanggal_selesai == '9999-12-31') {
                            return '';
                        }
                        return Helper::getTanggal($data->tanggal_selesai);
                    },
                    'headerOptions' => ['style' => 'text-align:center;width:150px;'],
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
