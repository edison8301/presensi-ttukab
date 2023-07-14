<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InstansiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Unit Kerja : '.$searchModel->getMasa();

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="instansi-index box box-primary">
    <div class="box-header">
        <h3 class="box-title">Daftar Unit Kerja</h3>
    </div>
    <div class="box-body table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header'=> 'No',
                'headerOptions' => ['style'=>'text-align:center;vertical-align:middle'],
                'contentOptions' => ['style'=>'text-align:center']
            ],
            [
                'attribute'=>'nama',
                'format'=>'raw',
                'value'=>function($data) {
                    return Html::a($data->nama,['/absensi/pegawai/index','PegawaiSearch[id_instansi]'=>$data->id]);
                },
                'headerOptions'=>['style'=>'text-align:center;vertical-align:middle'],
                'contentOptions'=>['style'=>'text-align:left;'],
            ],
            [
                'label'=>'Jumlah<br>Mesin<br>Absensi',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) {
                    return $label = $data->countMesinAbsensi().' Mesin';
                    //return Html::a($label,['/absensi/instansi/mesin-absensi/','id'=>$data->id]);
                },
                'headerOptions'=>['style'=>'text-align:center;vertical-align:middle'],
                'contentOptions'=>['style'=>'text-align:center;'],
            ],
            [
                'format'=>'raw',
                'value'=>function($data) {
                    $output  = '';
                    $output .= Html::a('<i class="fa fa-eye"></i>',['/absensi/instansi/mesin-absensi','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Daftar Rekap Absensi']).' ';
                    return trim($output);
                },
                'headerOptions'=>['style'=>'text-align:center;vertical-align:middle; width: 80px'],
                'contentOptions'=>['style'=>'text-align:center;'],
            ]

        ],
    ]); ?>
    </div>
</div>
