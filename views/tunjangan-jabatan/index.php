<?php

use app\components\Helper;
use app\models\TunjanganJabatan;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TunjanganJabatanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Tunjangan Jabatan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-jabatan-index box box-primary">

    <div class="box-header">
        <?= $this->render('_modal-instansi'); ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Tunjangan Jabatan', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>
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
                'headerOptions' => ['style' => 'text-align:center; width: 50px'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'id_jabatan',
                'format' => 'raw',
                'value' => function(TunjanganJabatan $data) {
                    return @$data->jabatan->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'jumlah_tunjangan',
                'format' => 'raw',
                'value' => function($data) {
                    return Helper::rp($data->jumlah_tunjangan,0);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:right;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
