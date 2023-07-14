<?php

use app\components\Helper;
use app\modules\tukin\models\Eselon;
use app\modules\tukin\models\Instansi;
use app\modules\tunjangan\models\TingkatanFungsional;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tunjangan\models\JabatanTunjanganFungsionalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Jabatan Tunjangan Fungsional';

if ($searchModel->status_p3k == 1) {
    $this->title .= ' (P3K)';
}

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-tunjangan-struktural-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Jabatan Tunjangan Fungsional', [
            '/tunjangan/jabatan-tunjangan-fungsional/create',
            'status_p3k' => $searchModel->status_p3k
        ], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Jabatan Tunjangan Fungsional', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>
    </div>

    <div class="box-body">

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
                'attribute' => 'id_instansi',
                'filter' => Instansi::getList(),
                'value' => function($data) {
                    return @$data->instansi->nama;
                },
                'format' => 'raw',
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
                'attribute' => 'id_tingkatan_fungsional',
                'format' => 'raw',
                'filter' => TingkatanFungsional::getList(),
                'value' => function($data) {
                    return @$data->tingkatanFungsional->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_mulai',
                'label' => 'tanggal mulai<br>berlaku',
                'encodeLabel' => false,
                'format' => 'raw',
                'value' => function($data) {
                    return Helper::getTanggal(@$data->tanggal_mulai);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_selesai',
                'label' => 'tanggal selesai<br>berlaku',
                'encodeLabel' => false,
                'format' => 'raw',
                'value' => function($data) {
                    return @$data->getLabelTanggalSelesai();
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'besaran_tpp',
                'format' => 'raw',
                'value' => function($data) {
                    return Helper::rp(@$data->besaran_tpp);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:right;'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
