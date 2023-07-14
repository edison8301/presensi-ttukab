<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UserMenu */

$this->title = "Detail User Menu";
$this->params['breadcrumbs'][] = ['label' => 'User Menu', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-menu-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail User Menu</h3>
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
                'attribute' => 'id_user_role_menu',
                'format' => 'raw',
                'value' => $model->id_user_role_menu,
            ],
            [
                'attribute' => 'path',
                'format' => 'raw',
                'value' => $model->path,
            ],
            [
                'attribute' => 'status_aktif',
                'format' => 'raw',
                'value' => $model->status_aktif,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting User Menu', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar User Menu', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
