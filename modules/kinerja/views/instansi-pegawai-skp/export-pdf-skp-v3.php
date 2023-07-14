<?php

use app\components\Helper;
use app\modules\kinerja\models\SkpIkiMik;
use app\modules\kinerja\models\SkpPerilaku;

/* @see \app\modules\kinerja\controllers\InstansiPegawaiSkpController::actionExportPdfSkpV3() */
/* @var $this \yii\web\View */
/* @var $model \app\modules\kinerja\models\InstansiPegawaiSkp */
/* @var $allKegiatanRhkUtama \app\modules\kinerja\models\KegiatanRhk[] */
/* @var $allKegiatanRhkTambahan \app\modules\kinerja\models\KegiatanRhk[] */
/* @var $allSkpPerilakuJenis \app\modules\kinerja\models\SkpPerilakuJenis[] */
/* @var $allKegiatanTahunan \app\modules\kinerja\models\KegiatanTahunan[] */

?>


<p style="text-align: center;">SASARAN KINERJA PEGAWAI</p><br/>

<table>
    <tr>
        <td style="text-transform: uppercase; width: 50%">
            <?= @$model->instansi->nama ?>
        </td>
        <td style="text-transform: uppercase; text-align: right;">
            PERIODE PENILAIAN: <?= Helper::getTanggalBulan($model->tanggal_mulai) ?> SD <?= Helper::getTanggal($model->tanggal_selesai) ?>
        </td>
    </tr>
</table>

<?= $this->render('_table-pegawai-v3', [
    'model' => $model,
]) ?>

<table class="table table-bordered">
    <tr>
        <th>HASIL KERJA</th>
    </tr>
</table>

<?php if ($model->isJpt() === false) { ?>
    <table class="table table-bordered">
        <tr>
            <th style="text-align: center; width: 3%;">No</th>
            <th style="text-align: center; width: 400px;">Rencana Hasil Kerja Atasan Yang Diintervensi</th>
            <th style="text-align: center; width: 300px;">Rencana Hasil Kerja</th>
            <th style="text-align: center; width: 80px;">Aspek</th>
            <th style="text-align: center;">Indikator Kinerja Individu</th>
            <th style="text-align: center; width: 100px;">Target</th>
        </tr>
        <tr>
            <th style="text-align: center;">(1)</th>
            <th style="text-align: center;">(2)</th>
            <th style="text-align: center;">(3)</th>
            <th style="text-align: center;">(4)</th>
            <th style="text-align: center;">(5)</th>
            <th style="text-align: center;">(6)</th>
        </tr>
        <tr>
            <th colspan="6">UTAMA</th>
        </tr>
        <?php $no=1; foreach ($allKegiatanRhkUtama as $kegiatanRhk) { ?>
            <?= $this->render('_tr-kegiatan-rhk-non-jpt', [
                'kegiatanRhk' => $kegiatanRhk,
                'no' => $no++,
                'level' => 0,
                'pdf' => true,
            ]) ?>
        <?php } ?>
        <tr>
            <th colspan="6">TAMBAHAN</th>
        </tr>
        <?php $no=1; foreach ($allKegiatanRhkTambahan as $kegiatanRhk) { ?>
            <?= $this->render('_tr-kegiatan-rhk-non-jpt', [
                'kegiatanRhk' => $kegiatanRhk,
                'no' => $no++,
                'level' => 0,
                'pdf' => true,
            ]) ?>
        <?php } ?>
    </table>
<?php } ?>

<?php if ($model->isJpt() === true) { ?>
    <table class="table table-bordered">
        <tr>
            <th style="text-align: center; width: 3%">No</th>
            <th style="text-align: center;">Rencana Hasil Kerja</th>
            <th style="text-align: center; width: 350px;">Indikator Kinerja Individu</th>
            <th style="text-align: center; width: 100px;">Target</th>
            <th style="text-align: center; width: 200px;">Perspektif</th>
        </tr>
        <tr>
            <th style="text-align: center;">(1)</th>
            <th style="text-align: center;">(2)</th>
            <th style="text-align: center;">(3)</th>
            <th style="text-align: center;">(4)</th>
            <th style="text-align: center;">(5)</th>
        </tr>
        <tr>
            <th colspan="5">UTAMA</th>
        </tr>
        <?php $no=1; foreach ($allKegiatanRhkUtama as $kegiatanRhk) { ?>
            <?= $this->render('_tr-kegiatan-rhk-jpt', [
                'kegiatanRhk' => $kegiatanRhk,
                'no' => $no++,
                'level' => 0,
                'pdf' => true,
            ]) ?>
        <?php } ?>
        <tr>
            <th colspan="5">TAMBAHAN</th>
        </tr>
        <?php $no=1; foreach ($allKegiatanRhkTambahan as $kegiatanRhk) { ?>
            <?= $this->render('_tr-kegiatan-rhk-jpt', [
                'kegiatanRhk' => $kegiatanRhk,
                'no' => $no++,
                'level' => 0,
                'pdf' => true,
            ]) ?>
        <?php } ?>
    </table>
<?php } ?>

<table class="table table-bordered">
    <tr>
        <th colspan="3">PERILAKU KERJA</th>
    </tr>
    <?php $no=1; foreach ($allSkpPerilakuJenis as $skpPerilakuJenis) { ?>
        <?php $skpPerilaku = SkpPerilaku::findOrCreate([
            'id_skp' => $model->id,
            'id_skp_perilaku_jenis' => $skpPerilakuJenis->id,
        ]) ?>
        <tr>
            <td style="text-align: center; width: 3%;" rowspan="2">
                <?= $no++ ?>
            </td>
            <td colspan="2">
                <?= $skpPerilakuJenis->nama ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php $array = explode("\n", $skpPerilakuJenis->uraian); ?>
                <?php foreach ($array as $value) { ?>
                    - <?= $value ?><br/>
                <?php } ?>
            </td>
            <td style="width: 600px;">
                Ekspektasi Khusus Pimpinan:<br/>
                <?php $arrayEkspektasi = explode("\n", $skpPerilaku->ekspektasi); ?>
                <?php if (count($arrayEkspektasi) <= 1) { ?>
                    <?= $skpPerilaku->ekspektasi ?>
                <?php } ?>
                <?php if (count($arrayEkspektasi) > 1) { ?>
                    <?php foreach ($arrayEkspektasi as $ekspektasi) { ?>
                        - <?= $ekspektasi ?><br/>
                    <?php } ?>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>

<br/><br/>
<?= $this->render('_ttd-skp', [
    'model' => $model,
]) ?>

<?php if ($model->isJpt() == false) { ?>
    <pagebreak/>

    <p style="text-align: center;">LAMPIRAN SASARAN KINERJA PEGAWAI</p><br/>

    <table>
        <tr>
            <td style="text-transform: uppercase; width: 50%">
                <?= @$model->instansi->nama ?>
            </td>
            <td style="text-transform: uppercase; text-align: right;">
                PERIODE PENILAIAN: <?= Helper::getTanggalBulan($model->tanggal_mulai) ?> SD <?= Helper::getTanggal($model->tanggal_selesai) ?>
            </td>
        </tr>
    </table>

    <?= $this->render('_table-lampiran', [
        'model' => $model,
        'pdf' => true,
    ]) ?>

    <br/><br/>
    <?= $this->render('_ttd-skp', [
        'model' => $model,
    ]) ?>
<?php } ?>

<?php if ($model->isJpt() == true) { ?>

    <?php foreach ($allKegiatanTahunan as $kegiatanTahunan) { ?>
        <pagebreak/>

        <?php $skpIkiMik = SkpIkiMik::findOrCreate([
            'id_skp' => $kegiatanTahunan->id_instansi_pegawai_skp,
            'id_skp_iki' => $kegiatanTahunan->id,
        ]); ?>

        <p style="text-align: center;">MANUAL INDIKATOR KINERJA</p><br/>

        <?= $this->render('/kegiatan-tahunan/_table-mik', [
            'skpIkiMik' => $skpIkiMik,
        ]) ?>
    <?php } ?>

<?php } ?>
