<table class="table table-bordered" style="margin-bottom: 0px;">
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
            <?= $model->instansiPegawai->jabatanAtasan->nama ?>
        </td>
    </tr>
    <tr>
        <th>INSTANSI</th>
        <td><?=strtoupper(@$model->instansiPegawai->instansi->nama) ?></td>
        <th>INSTANSI</th>
        <td>
            <?=strtoupper(@$model->instansiPegawai->jabatanAtasan->instansi->nama) ?></td>
    </tr>
</table>
<table class="table table-bordered">
    <tr>
        <th style="text-align: center">NO</th>
        <th style="text-align: center">RENCANA KINERJA</th>
        <th style="text-align: center" colspan="2">INDIKATOR INDIVIDU</th>
        <th style="text-align: center;width: 150px;">TARGET</th>
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
    <?php $allKegiatanTahunanUtama = $model->findAllKegiatanTahunan([
        'id_kegiatan_tahunan_versi' => 2,
        'id_kegiatan_tahunan_jenis' => 1,
    ]) ?>
    <?php $i=1; foreach ($allKegiatanTahunanUtama as $kegiatanTahunan) { ?>
        <tr>
            <td style="vertical-align: middle; text-align: center;" rowspan="4"><?= $i++ ?></td>
            <td style="vertical-align: middle;" rowspan="4">
                <?= $kegiatanTahunan->nama ?>
            </td>
            <td style="vertical-align: middle; text-align: center; width: 100px;">Kuantitas</td>
            <td style="vertical-align: middle; width: 400px">
                <?= $kegiatanTahunan->indikator_kuantitas ?>
            </td>
            <td style="vertical-align: middle; text-align: left;">
                <?= $kegiatanTahunan->target_kuantitas ?> <?= $kegiatanTahunan->satuan_kuantitas ?>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle; text-align: center;">Kualitas</td>
            <td style="vertical-align: middle;"><?= $kegiatanTahunan->indikator_kualitas ?></td>
            <td style="vertical-align: middle; text-align: left;">
                <?= $kegiatanTahunan->target_kualitas ?> <?= $kegiatanTahunan->satuan_kualitas ?>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle; text-align: center;">Waktu</td>
            <td style="vertical-align: middle;"><?= $kegiatanTahunan->indikator_waktu ?></td>
            <td style="vertical-align: middle; text-align: left;">
                <?= $kegiatanTahunan->target_waktu ?> <?= $kegiatanTahunan->satuan_waktu ?>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle; text-align: center;">Biaya</td>
            <td style="vertical-align: middle;"><?= $kegiatanTahunan->indikator_biaya ?></td>
            <td style="vertical-align: middle; text-align: left;">
                <?= $kegiatanTahunan->target_biaya ?> <?= $kegiatanTahunan->satuan_biaya ?>
            </td>
        </tr>
    <?php } ?>
    <!-- B. KINERJA TAMBAHAN -->
    <tr>
        <th colspan="5">B. KINERJA TAMBAHAN</th>
    </tr>
    <?php $allKegiatanTahunanTambahan = $model->findAllKegiatanTahunan([
        'id_kegiatan_tahunan_versi' => 2,
        'id_kegiatan_tahunan_jenis' => 2,
    ]) ?>
    <?php $i=1; foreach ($allKegiatanTahunanTambahan as $kegiatanTambahan) { ?>
        <tr>
            <td style="vertical-align: middle; text-align: center;" rowspan="4"><?= $i++ ?></td>
            <td style="vertical-align: middle;" rowspan="4">
                <?= $kegiatanTambahan->nama ?>
            </td>
            <td style="vertical-align: middle; text-align: center; width: 100px;">Kuantitas</td>
            <td style="vertical-align: middle; width: 400px">
                <?= $kegiatanTambahan->indikator_kuantitas ?>
            </td>
            <td style="vertical-align: middle; text-align: left;">
                <?= $kegiatanTambahan->target_kuantitas ?> <?= $kegiatanTambahan->satuan_kuantitas ?>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle; text-align: center;">Kualitas</td>
            <td style="vertical-align: middle;"><?= $kegiatanTambahan->indikator_kualitas ?></td>
            <td style="vertical-align: middle; text-align: left;">
                <?= $kegiatanTambahan->target_kualitas ?> <?= $kegiatanTambahan->satuan_kualitas ?>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle; text-align: center;">Waktu</td>
            <td style="vertical-align: middle;"><?= $kegiatanTambahan->indikator_waktu ?></td>
            <td style="vertical-align: middle; text-align: left;">
                <?= $kegiatanTambahan->target_waktu ?> <?= $kegiatanTambahan->satuan_waktu ?>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle; text-align: center;">Biaya</td>
            <td style="vertical-align: middle;"><?= $kegiatanTambahan->indikator_biaya ?></td>
            <td style="vertical-align: middle; text-align: left;">
                <?= $kegiatanTambahan->target_biaya ?> <?= $kegiatanTambahan->satuan_biaya ?>
            </td>
        </tr>
    <?php } ?>
</table>