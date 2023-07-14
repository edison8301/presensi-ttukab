<?php
use yii\helpers\Html;
use app\modules\kinerja\models\KegiatanTahunan;
use app\models\User;
use app\models\Pegawai;
use app\components\Helper;
use app\models\KegiatanTahunanFungsional;

$pegawai = $model->pegawai;
$instansiPegawai = $model->getInstansiPegawai();
?>



<h3 style="text-align: center">SASARAN KERJA PEGAWAI</h3>
<div>&nbsp;</div>

<table style="border-collapse: collapse;" width="100%"  border="1" cellpadding="3">
    <tr>
        <th colspan="2">PEGAWAI YANG DINILAI</th>
        <th colspan="2">PEJABAT PENILAI KINERJA</th>
    </tr>
    <tr>
        <th>NAMA</th>
        <td style="width: 350px;">
            <?= @$model->pegawai->nama ?>
        </td>
        <th>NAMA</th>
        <td style="width: 350px;">
            <?= @$model->instansiPegawai->atasan->nama ?>
        </td>
    </tr>
    <tr>
        <th>NIP</th>
        <td>
            <?= @$model->pegawai->nip ?>
        </td>
        <th>NIP</th>
        <td>
            <?= @$model->instansiPegawai->atasan->nip ?>
        </td>
    </tr>
    <tr>
        <th>PANGKAT/GOL RUANG</th>
        <td>
            <?= @$model->pegawai->golongan->getPangkatKomaGolongan() ?>
        </td>
        <th>PANGKAT/GOL RUANG</th>
        <td>
            <?= @$model->instansiPegawai->atasan->golongan->getPangkatKomaGolongan() ?>
        </td>
    </tr>
    <tr>
        <th>JABATAN</th>
        <td>
            <?= $model->instansiPegawai->namaJabatan ?>
        </td>
        <th>JABATAN</th>
        <td>
            <?= @$model->instansiPegawai->jabatanAtasan->nama ?>
        </td>
    </tr>
    <tr>
        <th>UNIT KERJA</th>
        <td><?=strtoupper(@$model->instansiPegawai->instansi->nama) ?></td>
        <th>UNIT KERJA</th>
        <td><?=strtoupper(@$model->instansiPegawai->jabatanAtasan->instansi->nama) ?></td>
    </tr>
</table>
<table style="border-collapse: collapse;" width="100%"  border="1" cellpadding="3" cellspacing="0">
    <tr>
        <th style="text-align: center;width: 10px;">NO</th>
        <th style="text-align: center">RENCANA KINERJA</th>
        <th style="text-align: center; width: 400px;">BUTIR KEGIATAN</th>
        <th style="text-align: center; width: 100px;">OUTPUT</th>
        <th style="text-align: center; width: 100px;">ANGKA KREDIT</th>
    </tr>
    <tr>
        <th style="text-align: center">1</th>
        <th style="text-align: center">2</th>
        <th style="text-align: center">3</th>
        <th style="text-align: center">4</th>
        <th style="text-align: center">5</th>
    </tr>
    <tr>
        <th colspan="5">A. KINERJA UTAMA</th>
    </tr>
    <?php $allKegiatanTahunanUtama = $model->findAllKegiatanTahunan([
        'id_kegiatan_tahunan_versi' => 2,
        'id_kegiatan_tahunan_jenis' => 1,
    ]) ?>
    <?php $no=1; foreach($allKegiatanTahunanUtama as $kegiatanTahunan) { ?>
        <?php 
            $row = count($kegiatanTahunan->manyKegiatanTahunanFungsional);
        ?>
        <tr>
            <td style="text-align: center; vertical-align: middle; width: 10px;" rowspan="<?= $row ?>">
                <?= $no++ ?>
            </td>
            <td style="vertical-align: middle;" rowspan="<?= $row ?>">
                <?= $kegiatanTahunan->nama ?>
            </td>
            <td>
                <?= @$kegiatanTahunan->oneKegiatanTahunanFungsional->butir_kegiatan ?>
            </td>
            <td style="text-align: center;">
                <?= @$kegiatanTahunan->oneKegiatanTahunanFungsional->output ?>
            </td>
            <td style="text-align: center;">
                <?= @$kegiatanTahunan->oneKegiatanTahunanFungsional->angka_kredit ?>
            </td>
        </tr>
        <?php $query = $kegiatanTahunan->getManyKegiatanTahunanFungsional();
            $query->andWhere(['!=', 'id', @$kegiatanTahunan->oneKegiatanTahunanFungsional->id]); ?>
            <?php foreach ($query->all() as $fungsional) { ?>
                <tr>
                    <td><?= $fungsional->butir_kegiatan ?></td>
                    <td style="text-align: center;"><?= $fungsional->output ?></td>
                    <td style="text-align: center;"><?= $fungsional->angka_kredit ?></td>
                </tr>
            <?php } ?>
    <?php } ?>
    
    <tr>
        <th colspan="5">A. KINERJA UTAMA</th>
    </tr>
    <?php $allKegiatanTahunanTambahan = $model->findAllKegiatanTahunan([
        'id_kegiatan_tahunan_versi' => 2,
        'id_kegiatan_tahunan_jenis' => 2,
    ]) ?>
    <?php $no=1; foreach($allKegiatanTahunanTambahan as $kegiatanTahunan) { ?>
        <?php 
            $row = count($kegiatanTahunan->manyKegiatanTahunanFungsional);
        ?>
        <tr>
            <td style="text-align: center; vertical-align: middle; width: 10px;" rowspan="<?= $row ?>">
                <?= $no++ ?>
            </td>
            <td style="vertical-align: middle;" rowspan="<?= $row ?>">
                <?= $kegiatanTahunan->nama ?>
            </td>
            <td>
                <?= @$kegiatanTahunan->oneKegiatanTahunanFungsional->butir_kegiatan ?>
            </td>
            <td style="text-align: center;">
                <?= @$kegiatanTahunan->oneKegiatanTahunanFungsional->output ?>
            </td>
            <td style="text-align: center;">
                <?= @$kegiatanTahunan->oneKegiatanTahunanFungsional->angka_kredit ?>
            </td>
        </tr>
        <?php $query = $kegiatanTahunan->getManyKegiatanTahunanFungsional();
            $query->andWhere(['!=', 'id', @$kegiatanTahunan->oneKegiatanTahunanFungsional->id]); ?>
            <?php foreach ($query->all() as $fungsional) { ?>
                <tr>
                    <td><?= $fungsional->butir_kegiatan ?></td>
                    <td style="text-align: center;"><?= $fungsional->output ?></td>
                    <td style="text-align: center;"><?= $fungsional->angka_kredit ?></td>
                </tr>
            <?php } ?>
    <?php } ?>
</table>

