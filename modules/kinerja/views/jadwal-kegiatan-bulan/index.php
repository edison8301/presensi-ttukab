<?php

use app\components\Helper;
use kartik\editable\Editable;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\kinerja\models\JadwalKegiatanBulanIndex */

$this->title = 'Daftar Jadwal Kegiatan Bulan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-kegiatan-bulan-index box box-primary">
    <div class="box-body">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="text-align: center;" width="30px">No</th>
                <th style="text-align: center;">Bulan</th>
                <th style="text-align: center;">Tanggal</th>
            </tr>
            </thead>
            <?php $i = 1 ?>
            <?php foreach ($model->getAllModels() as $jadwalKegiatanBulan) { ?>
                <tr>
                    <td style="text-align: center"><?= $i++ ?></td>
                    <td style="text-align: center"><?= Helper::getBulanLengkap($jadwalKegiatanBulan->bulan) . " $jadwalKegiatanBulan->tahun" ?></td>
                    <td style="text-align: center">
                        <?= Editable::widget([
                            'model' => $jadwalKegiatanBulan,
                            'attribute' => 'tanggal',
                            'displayValue' => Helper::getTanggal($jadwalKegiatanBulan->tanggal),
                            'beforeInput' => Html::hiddenInput('editableKey', $jadwalKegiatanBulan->id),
                            'asPopover' => true,
                            'placement' => 'top',
                            'formOptions' => ['action' => ['jadwal-kegiatan-bulan/editable-update']],
                            'header' => 'Tanggal',
                            'inputType' => Editable::INPUT_DATE,
                            'options'=>[
                                'id' => "w-$jadwalKegiatanBulan->id",
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd'
                                ]
                            ]
                        ]); ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
