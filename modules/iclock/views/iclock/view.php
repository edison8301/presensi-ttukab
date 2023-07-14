<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\iclock\Iclock */

$this->title = "Detail Iclock";
$this->params['breadcrumbs'][] = ['label' => 'Iclock', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iclock-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Iclock</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'SN',
                'format' => 'raw',
                'value' => $model->SN,
            ],
            [
                'attribute' => 'State',
                'format' => 'raw',
                'value' => $model->State,
            ],
            [
                'attribute' => 'LastActivity',
                'format' => 'raw',
                'value' => $model->LastActivity,
            ],
            [
                'attribute' => 'TransTimes',
                'format' => 'raw',
                'value' => $model->TransTimes,
            ],
            [
                'attribute' => 'TransInterval',
                'format' => 'raw',
                'value' => $model->TransInterval,
            ],
            [
                'attribute' => 'LogStamp',
                'format' => 'raw',
                'value' => $model->LogStamp,
            ],
            [
                'attribute' => 'OpLogStamp',
                'format' => 'raw',
                'value' => $model->OpLogStamp,
            ],
            [
                'attribute' => 'PhotoStamp',
                'format' => 'raw',
                'value' => $model->PhotoStamp,
            ],
            [
                'attribute' => 'Alias',
                'format' => 'raw',
                'value' => $model->Alias,
            ],
            [
                'attribute' => 'DeptID',
                'format' => 'raw',
                'value' => $model->DeptID,
            ],
            [
                'attribute' => 'UpdateDB',
                'format' => 'raw',
                'value' => $model->UpdateDB,
            ],
            [
                'attribute' => 'Style',
                'format' => 'raw',
                'value' => $model->Style,
            ],
            [
                'attribute' => 'FWVersion',
                'format' => 'raw',
                'value' => $model->FWVersion,
            ],
            [
                'attribute' => 'FPCount',
                'format' => 'raw',
                'value' => $model->FPCount,
            ],
            [
                'attribute' => 'TransactionCount',
                'format' => 'raw',
                'value' => $model->TransactionCount,
            ],
            [
                'attribute' => 'UserCount',
                'format' => 'raw',
                'value' => $model->UserCount,
            ],
            [
                'attribute' => 'MainTime',
                'format' => 'raw',
                'value' => $model->MainTime,
            ],
            [
                'attribute' => 'MaxFingerCount',
                'format' => 'raw',
                'value' => $model->MaxFingerCount,
            ],
            [
                'attribute' => 'MaxAttLogCount',
                'format' => 'raw',
                'value' => $model->MaxAttLogCount,
            ],
            [
                'attribute' => 'DeviceName',
                'format' => 'raw',
                'value' => $model->DeviceName,
            ],
            [
                'attribute' => 'AlgVer',
                'format' => 'raw',
                'value' => $model->AlgVer,
            ],
            [
                'attribute' => 'FlashSize',
                'format' => 'raw',
                'value' => $model->FlashSize,
            ],
            [
                'attribute' => 'FreeFlashSize',
                'format' => 'raw',
                'value' => $model->FreeFlashSize,
            ],
            [
                'attribute' => 'Language',
                'format' => 'raw',
                'value' => $model->Language,
            ],
            [
                'attribute' => 'VOLUME',
                'format' => 'raw',
                'value' => $model->VOLUME,
            ],
            [
                'attribute' => 'DtFmt',
                'format' => 'raw',
                'value' => $model->DtFmt,
            ],
            [
                'attribute' => 'IPAddress',
                'format' => 'raw',
                'value' => $model->IPAddress,
            ],
            [
                'attribute' => 'IsTFT',
                'format' => 'raw',
                'value' => $model->IsTFT,
            ],
            [
                'attribute' => 'Platform',
                'format' => 'raw',
                'value' => $model->Platform,
            ],
            [
                'attribute' => 'Brightness',
                'format' => 'raw',
                'value' => $model->Brightness,
            ],
            [
                'attribute' => 'BackupDev',
                'format' => 'raw',
                'value' => $model->BackupDev,
            ],
            [
                'attribute' => 'OEMVendor',
                'format' => 'raw',
                'value' => $model->OEMVendor,
            ],
            [
                'attribute' => 'City',
                'format' => 'raw',
                'value' => $model->City,
            ],
            [
                'attribute' => 'AccFun',
                'format' => 'raw',
                'value' => $model->AccFun,
            ],
            [
                'attribute' => 'TZAdj',
                'format' => 'raw',
                'value' => $model->TZAdj,
            ],
            [
                'attribute' => 'DelTag',
                'format' => 'raw',
                'value' => $model->DelTag,
            ],
            [
                'attribute' => 'FPVersion',
                'format' => 'raw',
                'value' => $model->FPVersion,
            ],
            [
                'attribute' => 'PushVersion',
                'format' => 'raw',
                'value' => $model->PushVersion,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Iclock', ['update', 'id' => $model->SN], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Iclock', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
