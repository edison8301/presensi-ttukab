<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CheckinoutSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Checkinout';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="checkinout-index box box-primary">

    <div class="box-body">
    
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
                [
                    'attribute'=>'userid',
                    'format'=>'raw',
                    'headerOptions'=>['style'=>'text-align:center;'],
                    'contentOptions'=>['style'=>'text-align:center;'],
                ],
                [
                    'attribute'=>'userinfo_badgenumber',
                    'format'=>'raw',
                    'value'=>function($data) {
                        return @$data->userinfo->badgenumber;
                    },
                    'headerOptions'=>['style'=>'text-align:center;'],
                    'contentOptions'=>['style'=>'text-align:center;'],
                ],
                [
                    'format'=>'raw',
                    'value'=>function($data) {
                        return @$data->pegawaiUserinfo->nama;
                    },
                    'headerOptions'=>['style'=>'text-align:center;'],
                    'contentOptions'=>['style'=>'text-align:center;'],
                ],
                [
                    'attribute'=>'nama_pegawai',
                    'format'=>'raw',
                    'value'=>function($data) {
                        return @$data->pegawai->nama;
                    },
                    'headerOptions'=>['style'=>'text-align:center;'],
                    'contentOptions'=>['style'=>'text-align:center;'],
                ],
                [
                    'attribute'=>'checktime',
                    'format'=>'raw',
                    'headerOptions'=>['style'=>'text-align:center;'],
                    'contentOptions'=>['style'=>'text-align:center;'],
                ],
                [
                    'attribute'=>'SN',
                    'format'=>'raw',
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
