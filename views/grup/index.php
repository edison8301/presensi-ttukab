<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Grup;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GrupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Grup Pegawai';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grup-index box box-primary">

    <div class="box-header">
        <?php if (Grup::accessCreate()): ?>
            <?= Html::a('<i class="fa fa-plus"></i> Tambah Grup Pegawai', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?php endif ?>

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
                'headerOptions' => ['style' => 'text-align:center; width: 60px'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function ($data) {
                    return @$data->instansi->nama;
                }
            ],
            [
                'label'=>'Jumlah<br>Pegawai',
                'encodeLabel'=>false,
                'format' => 'raw',
                'value'=>function($data) {
                    return $data->countGrupPegawai();
                },
                'headerOptions' => ['style' => 'text-align:center; width: 80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'label'=>'Username',
                'format'=>'raw',
                'value'=>function(Grup $data) {
                    return $data->getUsername();
                },
                'headerOptions' => ['style'=>'text-align:center;width:100px;'],
                'contentOptions'=>['style'=>'text-align:center;']
            ],
            [
                'format'=>'raw',
                'headerOptions' => ['style'=>'text-align:center;width:20px;'],
                'value'=>function($data) {
                    return Html::a('<i class="glyphicon glyphicon-lock"></i>',['user/set-password','id'=>$data->getIdUser()],['data-toggle'=>'tooltip','title'=>'Set Password']);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
