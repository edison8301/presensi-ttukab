<?php

use app\modules\kinerja\models\SkpPerilakuJenis;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\kinerja\models\SkpPerilakuJenisSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Skp Perilaku Jenis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skp-perilaku-jenis-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Skp Perilaku Jenis', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Skp Perilaku Jenis', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>

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
                'headerOptions' => ['style' => 'text-align:center;width:50px;'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'uraian',
                'format' => 'raw',
                'value' => function (SkpPerilakuJenis $data) {
                    $array = explode("\n", $data->uraian);
                    $no = 1;
                    $list = '';
                    foreach ($array as $value) {
                        $list .= $no++ . '. ' . $value .'<br/>';
                    }

                    return $list;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
