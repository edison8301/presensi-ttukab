<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\iclock\models\Devcmds */

$this->title = "Detail Devcmds";
$this->params['breadcrumbs'][] = ['label' => 'Devcmds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devcmds-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Devcmds</h3>
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
                'attribute' => 'SN_id',
                'format' => 'raw',
                'value' => $model->SN_id,
            ],
            [
                'attribute' => 'CmdContent',
                'format' => 'raw',
                'value' => $model->CmdContent,
            ],
            [
                'attribute' => 'CmdCommitTime',
                'format' => 'raw',
                'value' => $model->CmdCommitTime,
            ],
            [
                'attribute' => 'CmdTransTime',
                'format' => 'raw',
                'value' => $model->CmdTransTime,
            ],
            [
                'attribute' => 'CmdOverTime',
                'format' => 'raw',
                'value' => $model->CmdOverTime,
            ],
            [
                'attribute' => 'CmdReturn',
                'format' => 'raw',
                'value' => $model->CmdReturn,
            ],
            [
                'attribute' => 'User_id',
                'format' => 'raw',
                'value' => $model->User_id,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Devcmds', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Devcmds', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
