<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UserRole */

$this->title = "Detail User Role";
$this->params['breadcrumbs'][] = ['label' => 'User Role', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-role-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail User Role</h3>
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
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting User Role', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar User Role', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>

<div class="user-role-menu-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Akses Menu</h3>
    </div>

    <div class="box-body">
        <div style="margin-bottom: 20px">
            <?= Html::a('<i class="fa fa-plus"></i> Tambah Data',[
                '/user-role-menu/create',
                'id_user_role' => $model->id
            ],['class'=>'btn btn-success btn-flat']); ?>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 50px; text-align: center">No</th>
                <th>Nama</th>
                <th>Path</th>
                <th></th>
            </tr>
            <?php $i=1; foreach($model->findAllUserRoleMenu() as $data) { ?>
                <tr>
                    <td style="text-align: center"><?= $i; ?></td>
                    <td><?= $data->nama; ?></td>
                    <td><?= $data->path; ?></td>
                    <td style="width: 120px; text-align: center">
                        <?= $data->getLinkUpdateIcon(); ?>
                        <?= $data->getLinkDeleteIcon(); ?>
                    </td>
                </tr>
                <?php $i++; } ?>
        </table>
    </div>
</div>
