<?php

use app\models\Instansi;
use app\models\InstansiJenis;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InstansiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Rekap Kinerja per Perangkat Daerah';
$this->params['breadcrumbs'][] = $this->title;
?>


<?= $this->render('_filter-index-rekap-kinerja',[
    'searchModel'=>$searchModel,
]); ?>

<div class="instansi-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-file-excel-o"></i> Export Excel Semua Unit', [
            '/instansi/export-excel-rekap-ckhp-iki-seluruh-unit',
            'bulan' => $searchModel->bulan,
        ], [
            'class' => 'btn btn-success btn-flat',
        ]) ?>
    </div>

    <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'resizableColumns'=>true,
        'floatHeader'=>false,
        'floatHeaderOptions'=>['scrollingTop'=>'0'],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header'=> 'No',
                'headerOptions' => ['style'=>'text-align:center;width:50px;'],
                'contentOptions' => ['style'=>'text-align:center']
            ],
            [
                'attribute'=>'nama',
                'format'=>'raw',
                'headerOptions'=>['style'=>'text-align:center;'],
                'contentOptions'=>['style'=>'text-align:left;'],
            ],
            [
                'attribute'=>'id_instansi_jenis',
                'format'=>'raw',
                'value' => function(Instansi $data) {
                    return $data->instansiJenis->nama;
                },
                'filter'=>InstansiJenis::getList(),
                'headerOptions'=>['style'=>'text-align:center;width:100px;'],
                'contentOptions'=>['style'=>'text-align:center;'],
            ],
            [
                'format' => 'raw',
                'value' => function(Instansi $data) use ($searchModel) {
                    return Html::a('<i class="fa fa-eye"></i>', [
                        '/instansi/view-rekap-kinerja',
                        'id' => $data->id,
                        'bulan' => $searchModel->bulan,
                    ]);
                },
                'headerOptions' => ['style'=>'text-align:center;width:50px;'],
                'contentOptions'=>['style'=>'text-align:center;']
            ],
        ],
    ]); ?>
    </div>
</div>
