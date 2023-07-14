<?php

use app\models\Instansi;
use app\models\Pegawai;
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

<div class="pegawai-index box box-primary">
    <div class="box-header">
        <h3 class="box-title">Daftar Pegawai Bawahan</h3>
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
                        $output .= $data->getLinkIconUserSetPassword().' ';
                        $output .= $data->getLinkIconView().' ';
                        $output .= $data->getLinkIconUpdate().' ';
                        $output .= $data->getLinkIconDelete().' ';

                        return trim($output);
                    },
                    'headerOptions' => ['style'=>'text-align:center;width:100px;'],
                    'contentOptions'=>['style'=>'text-align:center;']
                ]
            ],
        ]); ?>
    </div>
</div>
