<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Instansi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Instansi', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header'=> 'No',
                'headerOptions' => ['style'=>'text-align:center; width: 60px'],
                'contentOptions' => ['style'=>'text-align:center']
            ],
            [
                'attribute'=>'nama_instansi',
                'label'=>'Nama Instansi',
                'format'=>'raw',
                'value'=>function($data) {
                    return $data->getRelationField("refInstansi","nama");
                },
                'headerOptions'=>['style'=>'text-align:center;'],
                'contentOptions'=>['style'=>'text-align:left;'],
            ],
            [
                'attribute'=>'tahun',
                'format'=>'raw',
                'headerOptions'=>['style'=>'text-align:center; width: 80px'],
                'contentOptions'=>['style'=>'text-align:center;'],
            ],
            [
                'label'=>'Jumlah<br>Pegawai',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) {
                    return $data->getAllPegawai()->count();
                },
                'headerOptions'=>['style'=>'text-align:center; width: 80px'],
                'contentOptions'=>['style'=>'text-align:center;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style'=>'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
