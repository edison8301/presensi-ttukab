<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\absensi\models\HariLiburSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Hari Libur';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hari-libur-index box box-primary">
    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Hari Libur',['/absensi/hari-libur/create'],['class'=>'btn btn-flat btn-success']); ?>
    </div>

    <div class="box-body">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => 'No',
                    'headerOptions'=>['style'=>'text-align:center; width: 60px'],
                    'contentOptions'=>['style'=>'text-align:center'],
                ],
                [
                    'attribute'=>'tanggal',
                    'value'=>function($data) {
                        return Helper::getTanggalSingkat($data->tanggal);
                    },
                    'headerOptions'=>['style'=>'text-align:center; width: 200px'],
                    'contentOptions'=>['style'=>'text-align:center'],
                ],
                'keterangan',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions'=>['style'=>'text-align:center; width: 80px'],
                    'contentOptions'=>['style'=>'text-align:center'],
                ],
            ],
        ]); ?>

    </div>
</div>
