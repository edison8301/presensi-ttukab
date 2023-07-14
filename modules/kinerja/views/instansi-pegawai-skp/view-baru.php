<?php

$this->title = 'SKP Pegawai';
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai SKP', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary">
    <div class="box-body">
        <table class="table table-bordered table-hover">
            <tr>
                <th colspan="2">PEGAWAI YANG DINILAI</th>
                <th colspan="2">PEJABAT PENILAI KINERJA</th>
            </tr>
            <tr>
                <th>NAMA</th>
                <td style="width: 350px;">
                    <?= @$model->instansiPegawai->atasan->nama ?>
                </td>
                <th>NAMA</th>
                <td style="width: 350px;">
                    <?= @$model->pegawai->nama ?>
                </td>
            </tr>
            <tr>
                <th>NIP</th>
                <td>
                    <?= @$model->instansiPegawai->atasan->nip ?>
                </td>
                <th>NIP</th>
                <td>
                    <?= @$model->pegawai->nip ?>
                </td>
            </tr>
            <tr>
                <th>PANGKAT/GOL RUANG</th>
                <td>
                    <?= @$model->instansiPegawai->atasan->golongan->golongan ?>
                </td>
                <th>PANGKAT/GOL RUANG</th>
                <td>
                    <?= @$model->pegawai->golongan->golongan ?>
                </td>
            </tr>
            <tr>
                <th>JABATAN</th>
                <td>
                    <?= $model->instansiPegawai->atasan ? $model->instansiPegawai->atasan->namaJabatan : '' ?>
                </td>
                <th>JABATAN</th>
                <td>
                    <?= $model->instansiPegawai->namaJabatan ?>
                </td>
            </tr>
            <tr>
                <th>INSTANSI</th>
                <td>BADAN KEPEGAWAIAN DAN PENGEMBANGAN SDM DAERAH</td>
                <th>INSTANSI</th>
                <td>BADAN KEPEGAWAIAN DAN PENGEMBANGAN SDM DAERAH</td>
            </tr>
        </table>
        <table class="table table-bordered table-hover">
            <tr>
                <th style="text-align: center">NO</th>
                <th style="text-align: center">RENCANA KINERJA</th>
                <th style="text-align: center" colspan="2">INDIKATOR INDIVIDU</th>
                <th style="text-align: center">TARGET</th>
            </tr>
            <tr>
                <th style="text-align: center">(1)</th>
                <th style="text-align: center">(2)</th>
                <th style="text-align: center" colspan="2">(3)</th>
                <th style="text-align: center">(4)</th>
            </tr>
            <!-- A. KINERJA UTAMA -->
            <tr>
                <th colspan="5">A. KINERJA UTAMA</th>
            </tr>
            <?php $i=1; foreach ($model->findAllKegiatanTahunanUtama() as $kegiatanTahunan) { ?>
                <tr>
                    <td style="vertical-align: middle; text-align: center;" rowspan="4"><?= $i++ ?></td>
                    <td style="vertical-align: middle;" rowspan="4">
                        <?= $kegiatanTahunan->nama ?>
                    </td>
                    <td style="vertical-align: middle; text-align: center; width: 100px;">1.1 Kuantitas</td>
                    <td style="vertical-align: middle; width: 400px">
                        <?= $kegiatanTahunan->target_kuantitas ?> <?= $kegiatanTahunan->satuan_kuantitas ?>
                    </td>
                    <td style="vertical-align: middle; text-align: right;"></td>
                </tr>
                <tr>
                    <td style="vertical-align: middle; text-align: center;">1.2 Kualitas</td>
                    <td style="vertical-align: middle;"><?= $kegiatanTahunan->target_kualitas ?></td>
                    <td style="vertical-align: middle;"></td>
                </tr>
                <tr>
                    <td style="vertical-align: middle; text-align: center;">1.3 Waktu</td>
                    <td style="vertical-align: middle;"><?= $kegiatanTahunan->target_waktu ?> Bulan</td>
                    <td style="vertical-align: middle;"></td>
                </tr>
                <tr>
                    <td style="vertical-align: middle; text-align: center;">1.4 Biaya</td>
                    <td style="vertical-align: middle;"><?= $kegiatanTahunan->target_biaya ?></td>
                    <td style="vertical-align: middle;"></td>
                </tr>
            <?php } ?>
            <!-- B. KINERJA TAMBAHAN -->
            <tr>
                <th colspan="5">B. KINERJA TAMBAHAN</th>
            </tr>
            <?php foreach ($model->findAllKegiatanTahunanTambahan() as $kegiatanTambahan) { ?>
                <tr>
                    <td style="vertical-align: middle;"></td> 
                    <td style="vertical-align: middle;"><?= $kegiatanTambahan->nama ?></td>   
                    <td style="vertical-align: middle;"></td>
                    <td style="vertical-align: middle;"></td>
                    <td style="vertical-align: middle;"></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>