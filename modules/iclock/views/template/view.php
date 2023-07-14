<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Template */

$this->title = "Detail Template";
$this->params['breadcrumbs'][] = ['label' => 'Template', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Template</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'templateid',
                'format' => 'raw',
                'value' => $model->templateid,
            ],
            [
                'attribute' => 'userid',
                'format' => 'raw',
                'value' => $model->userid,
            ],
            [
                'attribute' => 'Template',
                'format' => 'raw',
                'value' => $model->Template,
            ],
            [
                'attribute' => 'FingerID',
                'format' => 'raw',
                'value' => $model->FingerID,
            ],
            [
                'attribute' => 'Valid',
                'format' => 'raw',
                'value' => $model->Valid,
            ],
            [
                'attribute' => 'DelTag',
                'format' => 'raw',
                'value' => $model->DelTag,
            ],
            [
                'attribute' => 'SN',
                'format' => 'raw',
                'value' => $model->SN,
            ],
            [
                'attribute' => 'UTime',
                'format' => 'raw',
                'value' => $model->UTime,
            ],
            [
                'attribute' => 'BITMAPPICTURE',
                'format' => 'raw',
                'value' => $model->BITMAPPICTURE,
            ],
            [
                'attribute' => 'BITMAPPICTURE2',
                'format' => 'raw',
                'value' => $model->BITMAPPICTURE2,
            ],
            [
                'attribute' => 'BITMAPPICTURE3',
                'format' => 'raw',
                'value' => $model->BITMAPPICTURE3,
            ],
            [
                'attribute' => 'BITMAPPICTURE4',
                'format' => 'raw',
                'value' => $model->BITMAPPICTURE4,
            ],
            [
                'attribute' => 'USETYPE',
                'format' => 'raw',
                'value' => $model->USETYPE,
            ],
            [
                'attribute' => 'Template2',
                'format' => 'raw',
                'value' => $model->Template2,
            ],
            [
                'attribute' => 'Template3',
                'format' => 'raw',
                'value' => $model->Template3,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Template', ['update', 'id' => $model->templateid], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Template', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
