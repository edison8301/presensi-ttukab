<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\absensi\models\ShiftKerjaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Shift Kerja';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shift-kerja-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Shift Kerja',['/absensi/shift-kerja/create'],['class'=>'btn btn-flat btn-success']); ?>
    </div>

    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => 'No',
                    'headerOptions'=>['style'=>'text-align:center; width:60px;'],
                    'contentOptions'=>['style'=>'text-align:center;'],
                ],
                'nama',
                [
                    'attribute' => 'status_libur_nasional',
                    'format' => 'boolean',
                    'filter' => [
                        0 => 'Tidak',
                        1 => 'Ya',
                    ],
                    'headerOptions'=>['style'=>'text-align:center; width:120px;'],
                    'contentOptions'=>['style'=>'text-align:center;'],
                ],
                [
                    'attribute' => 'status_double_shift',
                    'header' => 'Double Shift',
                    'filter' => [
                        0 => 'Tidak',
                        1 => 'Ya',
                    ],
                    'value' => function ($data) {
                        return $data->getStringIsDoubleShift();
                    },
                    'headerOptions'=>['style'=>'text-align:center; width:120px;'],
                    'contentOptions'=>['style'=>'text-align:center;'],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions'=>['style'=>'text-align:center; width:100px;'],
                    'contentOptions'=>['style'=>'text-align:center;'],
                ],
            ],
        ]); ?>
    </div>
</div>
