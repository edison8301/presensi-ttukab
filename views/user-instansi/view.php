<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UserInstansi */

$this->title = "Detail User Instansi";
$this->params['breadcrumbs'][] = ['label' => 'User Instansi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-instansi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail User Instansi</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id',
                'format' => 'raw',
                'value' => $model->id,
            ],
            [
                'attribute' => 'id_user',
                'format' => 'raw',
                'value' => $model->id_user,
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => $model->id_instansi,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting User Instansi', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar User Instansi', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
