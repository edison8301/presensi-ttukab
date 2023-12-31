<?php

use app\models\Instansi;
use app\models\Pegawai;
use app\models\PegawaiJenis;
use app\widgets\Label;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $pegawaiExportForm \app\models\PegawaiExportForm */

$this->title = 'Daftar Pegawai';

if($searchModel->mode == 'bawahan')
    $this->title .= ' Bawahan';

$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_search-index',[
    'action'=>['pegawai/index'],
    'searchModel'=>$searchModel
]); ?>

<div class="pegawai-index box box-primary">

    <?php /*
    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Pegawai', ['create'], ['class' => 'btn btn-primary btn-flat']) ?>
        <?php //Html::a('<i class="fa fa-print"></i> Export Excel Pegawai', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-primary btn-flat','data-confirm' => 'Proses Export Data Keseluruhan Ini Memakan Waktu Beberapa Menit']) ?>
        <?= $this->render('_modal-pegawai-export-form',[
            'pegawaiExportForm' => $pegawaiExportForm
        ]) ?>
    </div>
    */ ?>

    <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => 'First',
            'lastPageLabel'  => 'Last'
        ],
        //'perfectScrollbar'=>true,
        'responsiveWrap' => true,
        'hover'=>true,
        'striped'=>false,
        //'tableOptions'=>['class'=>'table-responsive'],
        'responsive'=>true,
        //'floatHeader'=>true,
        //'perfectScrollbar'=>true,
        //'floatOverflowContainer'=>true,
        'floatHeaderOptions'=>['scrollingTop'=>'0'],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center; width: 60px'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::encode($data->nama);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'nip',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::encode($data->nip);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'id_pegawai_jenis',
                'label' => 'Jenis',
                'encodeLabel' => false,
                'format' => 'raw',
                'filter' => PegawaiJenis::getList(),
                'value' => function (Pegawai $data) {
                    return @$data->pegawaiJenis->nama;
                },
                'headerOptions' => ['style' => 'text-align:center; width: 80px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'label' => 'Mutasi/<br/>Promosi',
                'encodeLabel' => false,
                'format' => 'raw',
                'value' => function (Pegawai $data) {
                    return Html::a($data->countInstansiPegawai(),[
                        '/instansi-pegawai/index',
                        'InstansiPegawaiSearch[id_pegawai]'=>$data->id
                    ]);
                },
                'headerOptions' => ['style' => 'text-align:center; width:80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'format'=>'raw',
                'value'=>function(Pegawai $data) {
                    $output = '';
                    $output .= $data->getLinkIconView().' ';

                    return trim($output);
                },
                'headerOptions' => ['style'=>'text-align:center;width:50px;'],
                'contentOptions'=>['style'=>'text-align:center;']
            ]
        ],
    ]); ?>
    </div>
</div>
