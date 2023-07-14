<?php

use app\components\Helper;
use app\components\Session;
use app\modules\kinerja\models\KegiatanTahunan;
use yii\helpers\Html;
use kartik\editable\Editable;

/** @var KegiatanTahunan $kegiatanTahunan */
?>

<tr>
    <td rowspan="4" style="text-align: center"><?= @$no; ?></td>
    <td rowspan="4" style="padding-left:<?= 8+$level*20; ?>px;">
        <?= $kegiatanTahunan->getButtonDropdownV2(); ?>
        <?= Html::a(Html::encode($kegiatanTahunan->nama),['/kinerja/kegiatan-tahunan/view-v2','id'=>$kegiatanTahunan->id]); ?>
    </td>
    <td rowspan="4" style="text-align:center; width:30px"><?= @$kegiatanTahunan->instansiPegawaiSkp->nomor; ?></td>
    <td rowspan="4" style="text-align:center; width:30px"><?= $kegiatanTahunan->getLabelIdKegiatanStatus(); ?></td>
    <td>Kuantitas</td>
    <td><?= $kegiatanTahunan->indikator_kuantitas; ?></td>
    <td style="text-align:center"><?= $kegiatanTahunan->getTargetKuantitas(); ?></td>
    <td style="text-align:center; width:30px"><?= $kegiatanTahunan->getTotalTarget().' '.$kegiatanTahunan->satuan_kuantitas; ?></td>
    <td style="text-align:center;">
        <?php $totalRealisasi = $kegiatanTahunan->getTotalRealisasi([
            'attribute' => 'realisasi_kuantitas'
        ]); ?>
        <?= Html::a($totalRealisasi.' '.$kegiatanTahunan->satuan_kuantitas,[
            '/kinerja/kegiatan-harian/index-v2',
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
            <?= $kegiatanBulanan->canUpdate()
                ? Editable::widget([
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
                ])
                : '-' ?>
        </td>
        <td style="text-align:center;">
            <?= Html::a($kegiatanTahunan->getTotalRealisasi([
                'bulan' => $i,
                'attribute' => 'realisasi_kuantitas',
            ]),[
                '/kinerja/kegiatan-harian/index-v2',
                'KegiatanHarianSearch[bulan]'=>$i,
                'KegiatanHarianSearch[id_kegiatan_tahunan]'=>$kegiatanTahunan->id
            ],[
                'data-toggle'=>'tooltip',
                'title'=>'Lihat Rincian Realisasi'
            ]); ?>
        </td>
    <?php } ?>
</tr>

<tr>
    <td>Kualitas</td>
    <td><?= $kegiatanTahunan->indikator_kualitas; ?></td>
    <td style="text-align: center">
        <?= $kegiatanTahunan->target_kualitas; ?>
        <?= $kegiatanTahunan->satuan_kualitas; ?>
    </td>
    <td style="text-align: center">
        <?= $kegiatanTahunan->getTotalTarget(['attribute' => 'target_kualitas']) ?>
        <?= $kegiatanTahunan->satuan_kualitas; ?>
    </td>
    <td style="text-align: center">
        <?php $totalRealisasi = $kegiatanTahunan->getTotalRealisasi([
            'attribute' => 'realisasi_kualitas'
        ]); ?>
        <a href="#"><?= $totalRealisasi ?> <?= $kegiatanTahunan->satuan_kualitas; ?></a>
    </td>
    <?php $total_target = 0; for($i=1;$i<=12;$i++) { ?>

        <?php $datetimeSession = \DateTime::createFromFormat('Y-n-d', Session::getTahun().'-'.$i.'-01'); ?>
        <?php $kegiatanBulanan = $kegiatanTahunan->findOrCreateKegiatanBulan($i); ?>
        <td style="text-align:center; padding-top:6px">
            <?= $kegiatanBulanan->canUpdate()
                ? Editable::widget([
                    'model' => $kegiatanBulanan,
                    'value' => $kegiatanBulanan->target_kualitas,
                    'name' => 'target_kualitas',
                    'valueIfNull' =>  '...',
                    'header' => 'Target',
                    'formOptions' => ['action' => ['kegiatan-bulanan/editable-update']],
                    'beforeInput' => Html::hiddenInput('editableKey', $kegiatanBulanan->id),
                    'inputType' => Editable::INPUT_TEXT,
                    'placement' => 'top',
                    'options' => ['placeholder' => 'Input Target...'],
                    //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                ])
                : '-' ?>
        </td>
        <td style="text-align:center;">
            <?= Html::a($kegiatanTahunan->getTotalRealisasi([
                'bulan' => $i,
                'attribute' => 'realisasi_kualitas'
            ]),[
                '/kinerja/kegiatan-harian/index-v2',
                'KegiatanHarianSearch[bulan]'=>$i,
                'KegiatanHarianSearch[id_kegiatan_tahunan]'=>$kegiatanTahunan->id
            ],[
                'data-toggle'=>'tooltip',
                'title'=>'Lihat Rincian Realisasi'
            ]); ?>
        </td>
    <?php } ?>
</tr>

<tr>
    <td>Waktu</td>
    <td><?= $kegiatanTahunan->indikator_waktu; ?></td>
    <td style="text-align: center">
        <?= $kegiatanTahunan->target_waktu; ?>
        <?= $kegiatanTahunan->satuan_waktu; ?>
    </td>
    <td style="text-align: center">
        <?= $kegiatanTahunan->getTotalTarget(['attribute' => 'target_waktu']); ?>
        <?= $kegiatanTahunan->satuan_waktu; ?>
    </td>
    <td style="text-align: center"><a href="#">
        <?php $totalRealisasi = $kegiatanTahunan->getTotalRealisasi([
            'attribute' => 'realisasi_waktu'
        ]); ?>
        <?= $totalRealisasi ?> <?= $kegiatanTahunan->satuan_waktu; ?></a>
    </td>
    <?php $total_target = 0; for($i=1;$i<=12;$i++) { ?>

        <?php $datetimeSession = \DateTime::createFromFormat('Y-n-d', Session::getTahun().'-'.$i.'-01'); ?>
        <?php $kegiatanBulanan = $kegiatanTahunan->findOrCreateKegiatanBulan($i); ?>
        <td style="text-align:center; padding-top:6px">
            <?= $kegiatanBulanan->canUpdate()
                ? Editable::widget([
                    'model' => $kegiatanBulanan,
                    'value' => $kegiatanBulanan->target_waktu,
                    'name' => 'target_waktu',
                    'valueIfNull' =>  '...',
                    'header' => 'Target',
                    'formOptions' => ['action' => ['kegiatan-bulanan/editable-update']],
                    'beforeInput' => Html::hiddenInput('editableKey', $kegiatanBulanan->id),
                    'inputType' => Editable::INPUT_TEXT,
                    'placement' => 'top',
                    'options' => ['placeholder' => 'Input Target...'],
                    //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                ])
                : '-' ?>
        </td>
        <td style="text-align:center;">
            <?= Html::a($kegiatanTahunan->getTotalRealisasi([
                'bulan' => $i,
                'attribute' => 'realisasi_waktu',
            ]),[
                '/kinerja/kegiatan-harian/index-v2',
                'KegiatanHarianSearch[bulan]'=>$i,
                'KegiatanHarianSearch[id_kegiatan_tahunan]'=>$kegiatanTahunan->id
            ],[
                'data-toggle'=>'tooltip',
                'title'=>'Lihat Rincian Realisasi'
            ]); ?>
        </td>
    <?php } ?>
</tr>

<tr>
    <td>Biaya</td>
    <td><?= $kegiatanTahunan->indikator_biaya; ?></td>
    <td style="text-align: center">
        <?= $kegiatanTahunan->target_biaya; ?>
        <?= $kegiatanTahunan->satuan_biaya; ?>
    </td>
    <td style="text-align: center">
        <?= $kegiatanTahunan->getTotalTarget(['attribute' => 'target_biaya']) ?>
        <?= $kegiatanTahunan->satuan_biaya; ?>
    </td>
    <td style="text-align: center"><a href="#">
        <?php $totalRealisasi = $kegiatanTahunan->getTotalRealisasi([
            'attribute' => 'realisasi_biaya'
        ]); ?>
        <?= $totalRealisasi ?> <?= $kegiatanTahunan->satuan_biaya; ?></a>
    </td>
    <?php $total_target = 0; for($i=1;$i<=12;$i++) { ?>

        <?php $datetimeSession = \DateTime::createFromFormat('Y-n-d', Session::getTahun().'-'.$i.'-01'); ?>
        <?php $kegiatanBulanan = $kegiatanTahunan->findOrCreateKegiatanBulan($i); ?>
        <td style="text-align:center; padding-top:6px">
            <?= $kegiatanBulanan->canUpdate()
                ? Editable::widget([
                    'model' => $kegiatanBulanan,
                    'value' => $kegiatanBulanan->target_biaya,
                    'name' => 'target_biaya',
                    'valueIfNull' =>  '...',
                    'header' => 'Target',
                    'formOptions' => ['action' => ['kegiatan-bulanan/editable-update']],
                    'beforeInput' => Html::hiddenInput('editableKey', $kegiatanBulanan->id),
                    'inputType' => Editable::INPUT_TEXT,
                    'placement' => 'top',
                    'options' => ['placeholder' => 'Input Target...'],
                    //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                ]) : '-' ?>
        </td>
        <td style="text-align:center;">
            <?= Html::a($kegiatanTahunan->getTotalRealisasi([
                'bulan' => $i,
                'attribute' => 'realisasi_biaya',
            ]), [
                '/kinerja/kegiatan-harian/index-v2',
                'KegiatanHarianSearch[bulan]'=>$i,
                'KegiatanHarianSearch[id_kegiatan_tahunan]'=>$kegiatanTahunan->id
            ],[
                'data-toggle'=>'tooltip',
                'title'=>'Lihat Rincian Realisasi'
            ]); ?>
        </td>
    <?php } ?>
</tr>
