<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\iclock\Departments;

/* @var $this yii\web\View */
/* @var $searchModel app\models\iclock\IclockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Mesin Absensi';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="iclock-index box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title">Daftar Mesin Absensi</h3>
    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <div style="margin-bottom: 20px">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Mesin',[
            '/iclock/iclock/create'
        ],[
            'class' => 'btn btn-primary'
        ]); ?>
    </div>

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
                'attribute' => 'SN',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'LastActivity',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'FWVersion',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'label'=>'SKPD',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) {
                    return $data->instansi ? $data->instansi->nama : Html::a('<i class="fa fa-pencil"></i>',['/absensi/mesin-absensi/create','serialnumber'=>$data->SN]);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'label'=>'Jumlah<br>Userinfo',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) {
                    return $data->countUserinfo();
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'label'=>'Jumlah<br>Pegawai<br>(Userinfo)',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) {
                    return $data->countPegawaiUserinfo();
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'label'=>'Jumlah<br>Pegawai<br>(Instansi)',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) {
                    return $data->countPegawaiInstansi();
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'label'=>'Jumlah<br>Template<br>Fingerprint',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) {
                    return $data->getManyTemplate()->count();
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
