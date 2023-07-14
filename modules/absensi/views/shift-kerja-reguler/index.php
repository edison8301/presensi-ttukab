<?php

use app\components\Helper;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\absensi\models\ShiftKerjaRegulerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Shift Kerja Reguler';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shift-kerja-reguler-index box box-primary">

    <div class="box-header">
        <?=Html::a('<i class="fa fa-plus"></i> Tambah Shift Kerja Reguler', ['create'], ['class' => 'btn btn-success btn-flat'])?>
        <?=Html::a('<i class="fa fa-print"></i> Export Excel Shift Kerja Reguler', Yii::$app->request->url . '&export=1', ['class' => 'btn btn-success btn-flat'])?>

    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center'],
                'contentOptions' => ['style' => 'text-align:center'],
            ],

            [
                'attribute' => 'id_shift_kerja',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function ($data) {
                    return @$data->shiftKerja->nama;
                },
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function ($data) {
                    return Helper::getTanggal($data->tanggal_mulai);
                },
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function ($data) {
                    return Helper::getTanggal($data->tanggal_selesai);
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px'],
            ],
        ],
    ]);?>
    </div>
</div>
