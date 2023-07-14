<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RefPegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Ref Pegawai';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-pegawai-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Ref Pegawai', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
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
                'headerOptions' => ['style'=>'text-align:center'],
                'contentOptions' => ['style'=>'text-align:center']
            ],
            /*
            [
                'attribute'=>'kode_pegawai',
                'format'=>'raw',
                'headerOptions'=>['style'=>'text-align:center;'],
                'contentOptions'=>['style'=>'text-align:center;'],
            ],
            */
            [
                'attribute'=>'nama',
                'format'=>'raw',
                'headerOptions'=>['style'=>'text-align:center;'],
                'contentOptions'=>['style'=>'text-align:left;'],
            ],
            [
                'attribute'=>'nip',
                'format'=>'raw',
                'headerOptions'=>['style'=>'text-align:center;'],
                'contentOptions'=>['style'=>'text-align:center;'],
            ],
            [
                'attribute'=>'kode_absensi',
                'format'=>'raw',
                'headerOptions'=>['style'=>'text-align:center;'],
                'contentOptions'=>['style'=>'text-align:center;'],
            ],
            [
                'format'=>'raw',
                'value'=>function($data) {
                    return Html::a('<i class="fa fa-lock"></i>',['user/set-password','id'=>$data->getIdUser()]);
                },
                'headerOptions'=>['style'=>'text-align:center;'],
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
