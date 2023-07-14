<?php

use app\components\Helper;
use app\components\Session;
use app\modules\kinerja\models\KegiatanTahunan;
use yii\helpers\Html;
use kartik\editable\Editable;

/** @var KegiatanTahunan $kegiatanTahunan */
?>

<tr>
    <td style="text-align: center"><?= @$no; ?></td>
    <td style="padding-left:<?= 8+$level*20; ?>px;">
        <?= $kegiatanTahunan->getButtonDropdown(); ?>
        <?= Html::a(Html::encode($kegiatanTahunan->nama),['/kinerja/kegiatan-tahunan/view','id'=>$kegiatanTahunan->id]); ?>
    </td>
    <td style="text-align:center; width:30px"><?= @$kegiatanTahunan->instansiPegawaiSkp->nomor; ?></td>
    <td style="text-align:center; width:30px"><?= $kegiatanTahunan->getLabelIdKegiatanStatus(); ?></td>
    <td>Kuantitas</td>
    <td style="text-align: center; width: 200px"><?= $kegiatanTahunan->indikator_kuantitas; ?></td>
    <td style="text-align: center"><?= $kegiatanTahunan->getTargetKuantitas(); ?></td>
    <td style="text-align:center; width:30px"><?= $kegiatanTahunan->getTotalTarget().' '.$kegiatanTahunan->satuan_kuantitas; ?></td>
    <td style="text-align:center;">
        <?= Html::a(Helper::rp($kegiatanTahunan->getTotalRealisasi(),0).' '.$kegiatanTahunan->satuan_kuantitas,[
            '/kinerja/kegiatan-harian/index',
            'KegiatanHarianSearch[id_kegiatan_tahunan]'=>$kegiatanTahunan->id
        ],[
            'data-toggle'=>'tooltip',
            'title'=>'Lihat Rincian Realisasi'
        ]); ?>
    </td>

    <?php $total_target = 0; for($i=1;$i<=12;$i++) { ?>

        <?php $datetimeSession = \DateTime::createFromFormat('Y-n-d', Session::getTahun().'-'.$i.'-01'); ?>
        <?php $kegiatanBulanan = $kegiatanTahunan->findOrCreateKegiatanBulan($i); ?>
        <td style="text-align:center; padding-top:6px">
            <?php if($datetimeSession->format('Y-m') <= '2021-06') {
                echo $kegiatanBulanan->accessUpdate() ?
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
                    $kegiatanBulanan->target;
            } else {
                echo '-';
            } ?>
        </td>
        <td style="text-align:center;">
            <?= Html::a(Helper::rp($kegiatanTahunan->getTotalRealisasi(['bulan'=>$i]),0),[
                '/kinerja/kegiatan-harian/index',
                'KegiatanHarianSearch[bulan]'=>$i,
                'KegiatanHarianSearch[id_kegiatan_tahunan]'=>$kegiatanTahunan->id
            ],[
                'data-toggle'=>'tooltip',
                'title'=>'Lihat Rincian Realisasi'
            ]); ?>
        </td>
    <?php } ?>
</tr>
