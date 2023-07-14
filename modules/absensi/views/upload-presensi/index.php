<?php

use app\modules\absensi\models\UploadPresensi;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\absensi\models\UploadPresensiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $debug bool */

$this->title = 'Daftar Upload Presensi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="upload-presensi-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-upload"></i> Upload Presensi', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <div style="overflow: auto">
            <?= GridView::widget([
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
                        'attribute' => 'file',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'text-align:center;'],
                        'contentOptions' => ['style' => 'text-align:center;'],
                    ],
                    [
                        'attribute' => 'SN',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'text-align:center;'],
                        'contentOptions' => ['style' => 'text-align:center;'],
                    ],
                    [
                        'attribute' => 'user_pengupload',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'text-align:center;'],
                        'contentOptions' => ['style' => 'text-align:center;'],
                    ],
                    [
                        'attribute' => 'waktu_diupload',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'text-align:center;'],
                        'contentOptions' => ['style' => 'text-align:center;'],
                    ],
                    [
                        'label' => 'Log',
                        'format' => 'raw',
                        'value' => function(UploadPresensi $data) {
                            return $data->getCountUploadPresensiLog();
                        },
                        'visible' => ($debug==true),
                        'headerOptions' => ['style' => 'text-align:center;'],
                        'contentOptions' => ['style' => 'text-align:center;'],
                    ],
                    [
                        'attribute' => 'Status Upload',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'text-align:center;'],
                        'contentOptions' => ['style' => 'text-align:center;'],
                        'value' => function ($data) {
                            return $data->getStatusQueue();
                        }
                    ],
                    [
                        'label' => 'Reimport',
                        'format' => 'raw',
                        'visible' => ($debug==true),
                        'value' => function($data) {
                            return Html::a('<i class="fa fa-refresh"></i>',[
                                '/absensi/upload-presensi/reimport',
                                'id'=>$data->id
                            ]);
                        },
                        'headerOptions' => ['style' => 'text-align:center;'],
                        'contentOptions' => ['style' => 'text-align:center;'],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'visible' => ($debug==true),
                        'contentOptions' => ['style' => 'text-align:center;width:120px']
                    ],
                ],
            ]); ?>

        </div>
    </div>
</div>
