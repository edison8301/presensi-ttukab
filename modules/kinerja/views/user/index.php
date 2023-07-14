<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\kinerja\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai';
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
            'email',
            'password',
            [
                'header'=>'No ID Absensi',
                'attribute'=>'no_id_absensi',
                'format'=>'raw',
                'value'=>function($data) {

                    $label = "Belum Diset";

                    if($data->no_id_absensi!=null)
                    {
                        $label = $data->no_id_absensi;

                    }

                    return Html::a($label,['/kinerja/user/set-no-id-absensi','id'=>$data->id]);
                },
                'contentOptions'=>['style'=>'text-align:center']
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
