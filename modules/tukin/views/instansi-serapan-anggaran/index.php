<?php

use app\components\Helper;
use app\modules\tukin\models\Instansi;
use kartik\editable\Editable;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $allInstansi Instansi[] */

$this->title = 'Daftar Instansi Serapan Anggaran';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-serapan-anggaran-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Instansi Serapan Anggaran', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>

    <div class="box-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="text-align: center; vertical-align: middle; width: 40px" rowspan="3">No</th>
                <th style="text-align: center; vertical-align: middle" rowspan="3">Instansi</th>
                <th style="text-align: center" colspan="24">Bulan</th>
            </tr>
            <tr>
                <?php foreach (range(1, 12) as $bulan) { ?>
                    <th colspan="2" style="text-align: center"><?= Helper::getBulanLengkap($bulan) ?></th>
                <?php } ?>
            </tr>
            <tr>
                <?php for ($i = 1; $i <= 12; $i++) { ?>
                    <th style="text-align: center">T</th>
                    <th style="text-align: center">R</th>
                <?php } ?>
            </tr>
            </thead>
            <?php $i = 1; ?>
            <?php foreach ($allInstansi as $instansi) { ?>
                <tr>
                    <td style="text-align: center"><?= $i++; ?></td>
                    <td><?= Html::a($instansi->nama, ['instansi/anggaran', 'id' => $instansi->id])?></td>
                    <?php foreach ($instansi->findOrCreateSerapanAnggaranTahun() as $serapan) { ?>
                        <td style="text-align: center">
                            <?= Editable::widget([
                                'model' => $serapan,
                                'value' => $serapan->target,
                                'name' => 'target',
                                'valueIfNull' =>  '...',
                                'header' => 'Target',
                                'formOptions' => ['action' => ['instansi-serapan-anggaran/editable-update']],
                                'beforeInput' => Html::hiddenInput('editableKey', $serapan->id),
                                'inputType' => Editable::INPUT_TEXT,
                                'placement' => 'top',
                                'options' => ['placeholder' => 'Input Target...'],
                                //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                            ]) ?>
                        </td>
                        <td style="text-align: center">
                            <?= Editable::widget([
                                'model' => $serapan,
                                'value' => $serapan->realisasi,
                                'name' => 'realisasi',
                                'valueIfNull' =>  '...',
                                'header' => 'Target',
                                'formOptions' => ['action' => ['instansi-serapan-anggaran/editable-update']],
                                'beforeInput' => Html::hiddenInput('editableKey', $serapan->id),
                                'inputType' => Editable::INPUT_TEXT,
                                'placement' => 'top',
                                'options' => ['placeholder' => 'Input Realisasi...'],
                                //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                            ]) ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
