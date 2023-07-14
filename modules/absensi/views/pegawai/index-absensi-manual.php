<?php

use app\models\Pegawai;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\components\Helper;
use app\models\Instansi;
use app\modules\absensi\models\Absensi;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Absensi Pegawai : '.$searchModel->getMasa();

if($searchModel->mode == 'bawahan')
    $this->title .= ' Bawahan';

$this->params['breadcrumbs'][] = $this->title;


?>

<?= $this->render('_search-index',[
        'searchModel'=>$searchModel,
        'action'=>Url::to(['index-absensi-manual'])
]); ?>

<div class="pegawai-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-print"></i> Export Excel', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export PDF', Yii::$app->request->url.'&export-pdf=1', ['class' => 'btn btn-primary btn-flat','target'=>'_blank']) ?>
    </div>

    <div class="box-body table-responsive">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => 'First',
            'lastPageLabel'  => 'Last'
        ],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center; width:60px;vertical-align:middle'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => function ($data) {

                    return Html::a(Html::encode($data->nama),['/absensi/pegawai/view-shift-kerja','id'=>$data->id]).'<br>'.$data->nip;
                },
                'headerOptions' => ['style' => 'text-align:center;;vertical-align:middle'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'id_instansi',
                'label' => 'Perangkat Daerah',
                'format' => 'raw',
                'value' => function ($data) {
                    /* @var $data \app\models\Pegawai */
                    return $data->getNamaInstansi();
                },
                'headerOptions' => ['style' => 'text-align:center;;vertical-align:middle; width: 250px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'status_absen_ceklis',
                'filter' => [0 => 'Tidak Aktif', 1 => 'Aktif'],
                'headerOptions' => ['style' => 'text-align:center;width:200px'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function (Pegawai $data) {
                    return $data->getIsAbsensiManual() ? $data->findShiftKerjaManual()->nama : 'Tidak Aktif';
                }
            ],
            [
                'format'=>'raw',
                'value'=>function($data) {
                    $output  = '';
                    $output .= Html::a('<i class="fa fa-eye"></i>',['view-shift-kerja','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Lihat Shift Kerja']);
                    return trim($output);
                },
                'headerOptions'=>['style'=>'text-align:center;width:50px;vertical-align:middle'],
                'contentOptions'=>['style'=>'text-align:center;'],
            ]
        ],
    ]); ?>
    </div>
</div>
