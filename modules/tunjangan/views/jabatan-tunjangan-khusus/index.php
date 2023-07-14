<?php

use app\components\Helper;
use app\modules\tunjangan\models\JabatanTunjanganKhusus;
use app\modules\tunjangan\models\JabatanTunjanganKhususJenis;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tunjangan\models\JabatanTunjanganKhususSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Jabatan Tunjangan Khusus';

if ($searchModel->status_p3k == 1) {
    $this->title .= ' (P3K)';
}

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-tunjangan-khusus-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Jabatan Tunjangan Khusus', [
            '/tunjangan/jabatan-tunjangan-khusus/create',
            'status_p3k' => $searchModel->status_p3k,
        ], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Jabatan Tunjangan Khusus', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>

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
                'attribute' => 'id_jabatan_tunjangan_khusus_jenis',
                'format' => 'raw',
                'value' => function($data) {
                    return @$data->jabatanTunjanganKhususJenis->nama;
                },
                'filter' => JabatanTunjanganKhususJenis::getList(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'kelas_jabatan',
                'label' => 'Kelas<br>Jabatan',
                'encodeLabel' => false,
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'id_jabatan_tunjangan_golongan',
                'format' => 'raw',
                'value' => function($data) {
                    return @$data->jabatanTunjanganGolongan->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'besaran_tpp',
                'format' => 'raw',
                'value' => function($data) {
                    return Helper::rp($data->besaran_tpp,0);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:right;'],
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => function (JabatanTunjanganKhusus $data) {
                    return $data->getLabelTanggalMulai();
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => function (JabatanTunjanganKhusus $data) {
                    return $data->getLabelTanggalSelesai();
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'keterangan',
                'format' => 'raw',
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
