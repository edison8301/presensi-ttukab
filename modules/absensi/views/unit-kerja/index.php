<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\kinerja\models\UnitKerjaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Perangkat Daerah';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-kerja-index box box-primary">

    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'unit_kerja',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
