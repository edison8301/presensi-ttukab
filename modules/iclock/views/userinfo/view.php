<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\iclock\Userinfo */

$this->title = "Detail Userinfo";
$this->params['breadcrumbs'][] = ['label' => 'Userinfo', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="userinfo-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Userinfo</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'userid',
                'format' => 'raw',
                'value' => $model->userid,
            ],
            [
                'attribute' => 'badgenumber',
                'format' => 'raw',
                'value' => $model->badgenumber,
            ],
            [
                'attribute' => 'defaultdeptid',
                'format' => 'raw',
                'value' => $model->defaultdeptid,
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => $model->name,
            ],
            [
                'attribute' => 'Password',
                'format' => 'raw',
                'value' => $model->Password,
            ],
            [
                'attribute' => 'Card',
                'format' => 'raw',
                'value' => $model->Card,
            ],
            [
                'attribute' => 'Privilege',
                'format' => 'raw',
                'value' => $model->Privilege,
            ],
            [
                'attribute' => 'AccGroup',
                'format' => 'raw',
                'value' => $model->AccGroup,
            ],
            [
                'attribute' => 'TimeZones',
                'format' => 'raw',
                'value' => $model->TimeZones,
            ],
            [
                'attribute' => 'Gender',
                'format' => 'raw',
                'value' => $model->Gender,
            ],
            [
                'attribute' => 'Birthday',
                'format' => 'raw',
                'value' => $model->Birthday,
            ],
            [
                'attribute' => 'street',
                'format' => 'raw',
                'value' => $model->street,
            ],
            [
                'attribute' => 'zip',
                'format' => 'raw',
                'value' => $model->zip,
            ],
            [
                'attribute' => 'ophone',
                'format' => 'raw',
                'value' => $model->ophone,
            ],
            [
                'attribute' => 'FPHONE',
                'format' => 'raw',
                'value' => $model->FPHONE,
            ],
            [
                'attribute' => 'pager',
                'format' => 'raw',
                'value' => $model->pager,
            ],
            [
                'attribute' => 'minzu',
                'format' => 'raw',
                'value' => $model->minzu,
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => $model->title,
            ],
            [
                'attribute' => 'SN',
                'format' => 'raw',
                'value' => $model->SN,
            ],
            [
                'attribute' => 'SSN',
                'format' => 'raw',
                'value' => $model->SSN,
            ],
            [
                'attribute' => 'UTime',
                'format' => 'raw',
                'value' => $model->UTime,
            ],
            [
                'attribute' => 'State',
                'format' => 'raw',
                'value' => $model->State,
            ],
            [
                'attribute' => 'City',
                'format' => 'raw',
                'value' => $model->City,
            ],
            [
                'attribute' => 'SECURITYFLAGS',
                'format' => 'raw',
                'value' => $model->SECURITYFLAGS,
            ],
            [
                'attribute' => 'DelTag',
                'format' => 'raw',
                'value' => $model->DelTag,
            ],
            [
                'attribute' => 'RegisterOT',
                'format' => 'raw',
                'value' => $model->RegisterOT,
            ],
            [
                'attribute' => 'AutoSchPlan',
                'format' => 'raw',
                'value' => $model->AutoSchPlan,
            ],
            [
                'attribute' => 'MinAutoSchInterval',
                'format' => 'raw',
                'value' => $model->MinAutoSchInterval,
            ],
            [
                'attribute' => 'Image_id',
                'format' => 'raw',
                'value' => $model->Image_id,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Userinfo', ['update', 'id' => $model->userid], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Userinfo', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
