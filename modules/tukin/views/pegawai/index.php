<?php

use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tukin\models\PegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-index box box-primary">

    <div class="box-header">
        <h3 class="box-title">Daftar Pegawai</h3>
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
                'headerOptions' => ['style' => 'text-align:center'],
                'contentOptions' => ['style' => 'text-align:center']
            ],

            [
                'attribute' => 'nama',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function (\app\modules\tukin\models\Pegawai $data) {
                    return "$data->nama <br> $data->nip";
                }
            ],
            [
                'attribute' => 'namaInstansi',
                'header' => 'Instansi',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function (\app\modules\tukin\models\Pegawai $data) {
                    return @$data->instansi->nama;
                }
            ],
            [
                'attribute' => 'id_jabatan',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function (\app\modules\tukin\models\Pegawai $data) {
                    return @$data->jabatan->nama;
                }
            ],
            [
                'attribute' => 'kelas_jabatan',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function (\app\modules\tukin\models\Pegawai $data) {
                    return @$data->jabatan->kelas_jabatan;
                }
            ],
            [
                'attribute' => 'id_eselon',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function (\app\modules\tukin\models\Pegawai $data) {
                    return @$data->eselon->nama;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => User::isAdmin() ? '{view} {update}' : '{view}',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
