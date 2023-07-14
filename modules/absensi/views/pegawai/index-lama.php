<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\components\Helper;
use app\models\Instansi;

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
        'action'=>Url::to(['index'])
]); ?>

<div class="pegawai-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Pegawai', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-primary btn-flat']) ?>
    </div>

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
                'headerOptions' => ['style' => 'text-align:center; width:60px;vertical-align:middle'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => function ($data) {

                    return Html::a($data->nama,['/absensi/pegawai/view','id'=>$data->id]).'<br>NIP. '.$data->nip;
                },
                'headerOptions' => ['style' => 'text-align:center;;vertical-align:middle'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->instansi ? ($data->instansi->singkatan != null ? $data->instansi->singkatan : $data->instansi->nama) : null;
                },
                'filter'=>Instansi::getList(),
                'headerOptions' => ['style' => 'text-align:center;width:200px;vertical-align:middle'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'label'=>'Jumlah<br>Absensi',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) use ($searchModel) {
                    $label = $data->countCheckinout($searchModel->toArray()).' Absensi';
                    return $label;
                },
                'headerOptions' => ['style'=>'text-align:center;width:120px;vertical-align:middle'],
                'contentOptions' => ['style'=>'text-align:center;'],
            ],
            [
                'label'=>'Terdaftar<br>Pada',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) use ($searchModel) {
                    $label = $data->countUserinfo().' Mesin';
                    return Html::a($label,['pegawai/userinfo','id'=>$data->id]);
                },
                'headerOptions' => ['style'=>'text-align:center;width:120px;vertical-align:middle'],
                'contentOptions' => ['style'=>'text-align:center;'],
            ],
            [
                'format'=>'raw',
                'value'=>function($data) {
                    $output  = '';
                    $output .= Html::a('<i class="fa fa-eye"></i>',['view','id'=>$data->id]);
                    return $output;
                },
                'headerOptions'=>['style'=>'text-align:center;width:50px;vertical-align:middle'],
                'contentOptions'=>['style'=>'text-align:center;'],
            ]
        ],
    ]); ?>
    </div>
</div>
