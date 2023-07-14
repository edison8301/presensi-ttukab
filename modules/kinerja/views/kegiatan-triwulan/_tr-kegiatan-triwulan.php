<?php

use app\components\Helper;
use app\components\Session;
use app\modules\kinerja\models\KegiatanTriwulan;
use yii\helpers\Html;
use kartik\editable\Editable;

/** @var KegiatanTriwulan $kegiatanTriwulan */
?>

<tr>
    <td rowspan="4" style="text-align: center"><?= @$no; ?></td>
    <td rowspan="4">
        <?= $kegiatanTahunan->nama ?>
    </td>
    <td rowspan="4" style="text-align:center; width:30px"><?= @$kegiatanTahunan->instansiPegawaiSkp->nomor; ?></td>
    <td rowspan="4" style="text-align:center; width:30px"><?= $kegiatanTahunan->getLabelIdKegiatanStatus(); ?></td>
    <td>Kuantitas</td>
    <?php /*
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
    */ ?>

    <?php $total_target = 0; for($i=1;$i<=4;$i++) { ?>
        
        <?php $datetimeSession = \DateTime::createFromFormat('Y-n-d', Session::getTahun().'-'.$i.'-01'); ?>
        <?php $kegiatanTriwulan = $kegiatanTahunan->findOrCreateKegiatanTriwulan($i); ?>
        <td style="text-align:center; padding-top:6px">
            <?= $kegiatanTriwulan->accessUpdate() ?
                Editable::widget([
                    'model' => $kegiatanTriwulan,
                    'value' => $kegiatanTriwulan->target_kuantitas,
                    'name' => 'target_kuantitas',
                    'valueIfNull' =>  '...',
                    'header' => 'Target',
                    'formOptions' => ['action' => ['kegiatan-triwulan/editable-update']],
                    'beforeInput' => Html::hiddenInput('editableKey', $kegiatanTriwulan->id),
                    'inputType' => Editable::INPUT_TEXT,
                    'placement' => 'top',
                    'options' => ['placeholder' => 'Input Target...'],
                    //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                ]) :
                $kegiatanTriwulan->target; 
            ?>
        </td>
        <td style="text-align:center;">
        <?= $kegiatanTriwulan->accessUpdate() ?
                Editable::widget([
                    'model' => $kegiatanTriwulan,
                    'value' => $kegiatanTriwulan->realisasi_target_kuantitas,
                    'name' => 'realisasi_target_kuantitas',
                    'valueIfNull' =>  '...',
                    'header' => 'Target',
                    'formOptions' => ['action' => ['kegiatan-triwulan/editable-update']],
                    'beforeInput' => Html::hiddenInput('editableKey', $kegiatanTriwulan->id),
                    'inputType' => Editable::INPUT_TEXT,
                    'placement' => 'top',
                    'options' => ['placeholder' => 'Input Target...'],
                    //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                ]) :
                $kegiatanTriwulan->target; 
            ?>
        </td>
    <?php } ?>
</tr>

<tr>
    <td>Kualitas</td>
    <?php /*
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
    */ ?>
    <?php $total_target = 0; for($i=1;$i<=4;$i++) { ?>

        <?php $datetimeSession = \DateTime::createFromFormat('Y-n-d', Session::getTahun().'-'.$i.'-01'); ?>
        <?php $kegiatanTriwulan = $kegiatanTahunan->findOrCreateKegiatanTriwulan($i); ?>
        <td style="text-align:center; padding-top:6px">
            <?= $kegiatanTriwulan->accessUpdate() ?
                Editable::widget([
                    'model' => $kegiatanTriwulan,
                    'value' => $kegiatanTriwulan->target_kualitas,
                    'name' => 'target_kualitas',
                    'valueIfNull' =>  '...',
                    'header' => 'Target',
                    'formOptions' => ['action' => ['kegiatan-triwulan/editable-update']],
                    'beforeInput' => Html::hiddenInput('editableKey', $kegiatanTriwulan->id),
                    'inputType' => Editable::INPUT_TEXT,
                    'placement' => 'top',
                    'options' => ['placeholder' => 'Input Target...'],
                    //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                ]) :
                $kegiatanTriwulan->target_kualitas; 
            ?>
        </td>
        <td style="text-align:center;">
            <?= $kegiatanTriwulan->accessUpdate() ?
                Editable::widget([
                    'model' => $kegiatanTriwulan,
                    'value' => $kegiatanTriwulan->realisasi_target_kualitas,
                    'name' => 'realisasi_target_kualitas',
                    'valueIfNull' =>  '...',
                    'header' => 'Target',
                    'formOptions' => ['action' => ['kegiatan-triwulan/editable-update']],
                    'beforeInput' => Html::hiddenInput('editableKey', $kegiatanTriwulan->id),
                    'inputType' => Editable::INPUT_TEXT,
                    'placement' => 'top',
                    'options' => ['placeholder' => 'Input Target...'],
                    //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                ]) :
                $kegiatanTriwulan->realisasi_target_kualitas; 
            ?>
        </td>
    <?php } ?>
</tr>

<tr>
    <td>Waktu</td>
    <?php /*
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
    */ ?>
    <?php $total_target = 0; for($i=1;$i<=4;$i++) { ?>

        <?php $datetimeSession = \DateTime::createFromFormat('Y-n-d', Session::getTahun().'-'.$i.'-01'); ?>
        <?php $kegiatanTriwulan = $kegiatanTahunan->findOrCreateKegiatanTriwulan($i); ?>
        <td style="text-align:center; padding-top:6px">
            <?= $kegiatanTriwulan->accessUpdate() ?
                Editable::widget([
                    'model' => $kegiatanTriwulan,
                    'value' => $kegiatanTriwulan->target_waktu,
                    'name' => 'target_waktu',
                    'valueIfNull' =>  '...',
                    'header' => 'Target',
                    'formOptions' => ['action' => ['kegiatan-triwulan/editable-update']],
                    'beforeInput' => Html::hiddenInput('editableKey', $kegiatanTriwulan->id),
                    'inputType' => Editable::INPUT_TEXT,
                    'placement' => 'top',
                    'options' => ['placeholder' => 'Input Target...'],
                    //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                ]) :
                $kegiatanTriwulan->target_waktu; 
            ?>
        </td>
        <td style="text-align:center;">
            <?= $kegiatanTriwulan->accessUpdate() ?
                Editable::widget([
                    'model' => $kegiatanTriwulan,
                    'value' => $kegiatanTriwulan->realisasi_target_waktu,
                    'name' => 'realisasi_target_waktu',
                    'valueIfNull' =>  '...',
                    'header' => 'Target',
                    'formOptions' => ['action' => ['kegiatan-triwulan/editable-update']],
                    'beforeInput' => Html::hiddenInput('editableKey', $kegiatanTriwulan->id),
                    'inputType' => Editable::INPUT_TEXT,
                    'placement' => 'top',
                    'options' => ['placeholder' => 'Input Target...'],
                    //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                ]) :
                $kegiatanTriwulan->realisasi_target_waktu; 
            ?>
        </td>
    <?php } ?>
</tr>

<tr>
    <td>Biaya</td>
    <?php /*
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
    */ ?>
    <?php $total_target = 0; for($i=1;$i<=4;$i++) { ?>

        <?php $datetimeSession = \DateTime::createFromFormat('Y-n-d', Session::getTahun().'-'.$i.'-01'); ?>
        <?php $kegiatanTriwulan = $kegiatanTahunan->findOrCreateKegiatanTriwulan($i); ?>
        <td style="text-align:center; padding-top:6px">
            <?= $kegiatanTriwulan->accessUpdate() ?
                Editable::widget([
                    'model' => $kegiatanTriwulan,
                    'value' => $kegiatanTriwulan->target_biaya,
                    'name' => 'target_biaya',
                    'valueIfNull' =>  '...',
                    'header' => 'Target',
                    'formOptions' => ['action' => ['kegiatan-triwulan/editable-update']],
                    'beforeInput' => Html::hiddenInput('editableKey', $kegiatanTriwulan->id),
                    'inputType' => Editable::INPUT_TEXT,
                    'placement' => 'top',
                    'options' => ['placeholder' => 'Input Target...'],
                    //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                ]) :
                $kegiatanTriwulan->target_biaya; 
            ?>
        </td>
        <td style="text-align:center;">
            <?= $kegiatanTriwulan->accessUpdate() ?
                Editable::widget([
                    'model' => $kegiatanTriwulan,
                    'value' => $kegiatanTriwulan->realisasi_target_biaya,
                    'name' => 'realisasi_target_biaya',
                    'valueIfNull' =>  '...',
                    'header' => 'Target',
                    'formOptions' => ['action' => ['kegiatan-triwulan/editable-update']],
                    'beforeInput' => Html::hiddenInput('editableKey', $kegiatanTriwulan->id),
                    'inputType' => Editable::INPUT_TEXT,
                    'placement' => 'top',
                    'options' => ['placeholder' => 'Input Target...'],
                    //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                ]) :
                $kegiatanTriwulan->realisasi_target_biaya; 
            ?>
        </td>
    <?php } ?>
</tr>

<?php $level++; foreach($kegiatanTahunan->manySub as $sub) {
    echo $this->render('_tr-kegiatan-triwulan', [
        'kegiatanTahunan' => $sub,
        'level' => $level,
    ]);
} ?>
