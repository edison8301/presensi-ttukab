<?php

/**
 * Created by PhpStorm.
 * User: iqbal
 * Date: 1/20/2019
 * Time: 2:00 PM
 */

/* @var $this \yii\web\View */
/* @var $kegiatanTahunan \app\modules\kinerja\models\KegiatanTahunan */
/* @var $level int */
/* @var $bulan int */
/* @var $readOnly boolean */

use app\components\Helper;
use app\models\User;
use kartik\editable\Editable;
use yii\helpers\Html;
?>
<tr>
    <td style="padding-left:<?= 8+$level*20; ?>px">
        <?= $kegiatanTahunan->getButtonDropdown(); ?>
        <?= Html::encode($kegiatanTahunan->nama); ?>
    </td>
    <td style="text-align: center"><?= $kegiatanTahunan->satuan_kuantitas; ?></td>
        <td style="text-align:center; width:30px">
        <?php $kegiatanBulanan = $kegiatanTahunan->findOrCreateKegiatanBulan($bulan); ?>
        <?= $kegiatanBulanan->accessUpdate() && !$readOnly ?
            Editable::widget([
                'model' => $kegiatanBulanan,
                'value' => $kegiatanBulanan->target,
                'name' => 'target',
                'valueIfNull' =>  '...',
                'header' => 'Target',
                'formOptions' => ['action' => ['kegiatan-bulanan/editable-update']],
                'beforeInput' => Html::hiddenInput('editableKey', $kegiatanBulanan->id),
                'inputType' => Editable::INPUT_TEXT,
                'placement' => 'top',
                'options' => ['placeholder' => 'Input Target...'],
                //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
            ]) :
            $kegiatanBulanan->target; ?>
    </td>
    <td style="text-align:center; width:30px">
        <?php $kegiatanBulanan = $kegiatanTahunan->findOrCreateKegiatanBulan($bulan); ?>
        <?php if ($bulan !== 12 and User::getTahun() === 2018) { ?>
            <?= $kegiatanBulanan->accessUpdate() && !$readOnly ?
                Editable::widget([
                    'model' => $kegiatanBulanan,
                    'value' => $kegiatanBulanan->realisasi,
                    'name' => 'realisasi',
                    'valueIfNull' =>  '...',
                    'header' => 'Realisasi',
                    'formOptions' => ['action' => ['kegiatan-bulanan/editable-update']],
                    'beforeInput' => Html::hiddenInput('editableKey', $kegiatanBulanan->id),
                    'inputType' => Editable::INPUT_TEXT,
                    'placement' => 'top',
                    'options' => ['placeholder' => 'Input Realisasi...'],
                    //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                ]) :
                $kegiatanBulanan->realisasi; ?>
        <?php } else { ?>
            <?= Html::a(Helper::rp($kegiatanBulanan->getTotalRealisasi(), 0), ['kegiatan-bulanan/view', 'id' => $kegiatanBulanan->id]); ?>
        <?php } ?>
    </td>
    <td style="text-align:center; width:30px"><?= $kegiatanTahunan->getLabelIdKegiatanStatus(); ?></td>
</tr>

