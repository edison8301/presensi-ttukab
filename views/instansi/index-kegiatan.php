<?php

use app\models\Instansi;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InstansiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Perangkat Daerah';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="instansi-index box box-primary">

    <div class="box-header">
        <h3 class="box-title"><?= $this->title ?></h3>
    </div>

    <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
                'value' => function (Instansi $data) {
                    return Html::a($data->nama, [
                        '/instansi/view-kegiatan',
                        'id' => $data->id,
                    ]);
                },
                'headerOptions'=>['style'=>'text-align:center;'],
                'contentOptions'=>['style'=>'text-align:left;'],
            ],
            [
                'format'=>'raw',
                'value'=>function(Instansi $data) {
                    $output = '';
                    $output .= Html::a('<i class="fa fa-eye"></i>', [
                        '/instansi/view-kegiatan',
                        'id' => $data->id,
                    ]);

                    return trim($output);
                },
                'headerOptions' => ['style'=>'text-align:center;width:50px;'],
                'contentOptions'=>['style'=>'text-align:center;']
            ],
        ],
    ]); ?>
    </div>
</div>
