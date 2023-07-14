<?php

use app\models\Instansi;
use app\models\InstansiJenis;
use app\models\InstansiLokasi;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InstansiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Perangkat Daerah';
$this->params['breadcrumbs'][] = $this->title;

?>


<?= $this->render('_filter',['searchModel'=>$searchModel]); ?>

<div class="instansi-index box box-primary">

    <div class="box-header">
        <?= Instansi::getLinkButtonCreate() ?>
    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


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
                'headerOptions' => ['style'=>'text-align:center'],
                'contentOptions' => ['style'=>'text-align:center']
            ],
            [
                'attribute'=>'nama',
                'format'=>'raw',
                'headerOptions'=>['style'=>'text-align:center;'],
                'contentOptions'=>['style'=>'text-align:left;'],
            ],
            [
                'label'=>'Perangkat Daerah Induk',
                'format'=>'raw',
                'value' => function(Instansi $data) {
                    return @$data->instansiIndukBerlaku->instansiInduk->nama;
                },
                'headerOptions'=>['style'=>'text-align:center;'],
                'contentOptions'=>['style'=>'text-align:center;'],
            ],
            [
                'format'=>'raw',
                'value'=>function(Instansi $data) {
                    return Html::a('<i class="fa fa-eye"></i>',[
                        '/instansi/view-induk',
                        'id' => $data->id
                    ]);
                },
                'headerOptions' => ['style'=>'text-align:center;width:100px;'],
                'contentOptions'=>['style'=>'text-align:center;']
            ],
        ],
    ]); ?>
    </div>
</div>
