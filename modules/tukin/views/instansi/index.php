<?php

use app\modules\tukin\models\Instansi;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\tukin\models\InstansiJenis;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tukin\models\InstansiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Instansi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-index box box-primary">

    <div class="box-header">
        <h3 class="box-title">Daftar Instansi</h3>
    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => function($data) {
                    return Html::a($data->nama,[
                        '/instansi/view-jabatan',
                        'id'=>$data->id
                    ]);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'singkatan',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'id_instansi_jenis',
                'format' => 'raw',
                'value' => function($data) {
                    return @$data->instansiJenis->nama;
                },
                'filter'=>InstansiJenis::getList(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'label'=>'Jumlah<br/>Jabatan',
                'encodeLabel'=>false,
                'value'=>function($data) {
                    /* @var $data \app\modules\tukin\models\Instansi */
                    return $data->countJabatan();
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'format'=>'raw',
                'value' => function(Instansi $data) {
                    return $data->getLinkIconViewJabatan();
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
        ],
    ]); ?>
    </div>
</div>
