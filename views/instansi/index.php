<?php

use app\models\Instansi;
use app\models\InstansiJenis;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InstansiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Perangkat Daerah';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="instansi-index box box-primary">

    <div class="box-header">
        <h3 class="box-title">
            <?= $this->title ?>
        </h3>
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
                'format'=>'raw',
                'value'=>function(Instansi $data) {
                    $output = '';
                    $output .= $data->getLinkIconViewJabatan().' ';

                    return trim($output);
                },
                'headerOptions' => ['style'=>'text-align:center;width:50px;'],
                'contentOptions'=>['style'=>'text-align:center;']
            ],
        ],
    ]); ?>
    </div>
</div>
