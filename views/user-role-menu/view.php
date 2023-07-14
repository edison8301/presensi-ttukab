<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UserRoleMenu */

$this->title = "Detail User Role Menu";
$this->params['breadcrumbs'][] = ['label' => 'User Role Menu', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-role-menu-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail User Role Menu</h3>
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
                'attribute' => 'id_user_role',
                'format' => 'raw',
                'value' => $model->id_user_role,
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
            ],
            [
                'attribute' => 'path',
                'format' => 'raw',
                'value' => $model->path,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting User Role Menu', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar User Role Menu', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
