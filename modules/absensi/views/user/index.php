<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\kinerja\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar User';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index box box-primary">

    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nip',
            'nama',
            [
                'header'=>'Kode Pegawai',
                'value'=>function($data) {
                    $absensiUser = $data->findAbsensiUser();
                    return $absensiUser->kode_pegawai;
                }
            ],
            [
                'header'=>'Perangkat Daerah',
                'value'=>function($data) {
                    $absensiUser = $data->findAbsensiUser();
                    return $absensiUser->kode_unit_kerja;
                }
            ],
            [
                'attribute'=>'no_id_absensi',
                'format'=>'raw',
                'value'=>function($data) {
                    $absensiUser = $data->findAbsensiUser();
                    return Html::a('<i class="fa fa-pencil"></i>',['/absensi/user/update','id'=>$absensiUser->id]);
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
