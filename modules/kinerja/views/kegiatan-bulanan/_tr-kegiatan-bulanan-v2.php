<tr>
    <td rowspan="4" style="text-align: center"><?= $no; ?></td>
    <td rowspan="4">
        <?php $kegiatanTahunan = @$kegiatanBulanan->kegiatanTahunan; ?>

        <?= $kegiatanBulanan->kegiatanTahunan->nama; ?> <?= @$status_plt ? '(Plt)' : '' ?><br/>
        <i class="fa fa-user"></i> <?= @$kegiatanTahunan->namaPegawai ?><br>
        <i class="fa fa-tag"></i> <?= @$kegiatanTahunan->instansiPegawaiSkp->nomor ?>
    </td>
    <td>Kuantitas</td>
    <td><?= $kegiatanBulanan->kegiatanTahunan->indikator_kuantitas; ?></td>
    <td style="text-align: center"><?= $kegiatanBulanan->target; ?></td>
    <td style="text-align: center"><?= $kegiatanBulanan->realisasi; ?></td>
    <td style="text-align: center"><?= $kegiatanBulanan->getPersenRealisasi(); ?>%</td>
    <td style="text-align: center" rowspan="4">
        <?= $kegiatanBulanan->getLinkUpdateRealisasiIcon(); ?>
    </td>
</tr>
<tr>
    <td>Kualitas</td>
    <td><?= $kegiatanBulanan->kegiatanTahunan->indikator_kualitas; ?></td>
    <td style="text-align: center"><?= $kegiatanBulanan->target_kualitas; ?></td>
    <td style="text-align: center"><?= $kegiatanBulanan->realisasi_kualitas; ?></td>
    <td style="text-align: center"><?= $kegiatanBulanan->getPersenRealisasiKualitas(); ?>%</td>
</tr>
<tr>
    <td>Waktu</td>
    <td><?= $kegiatanBulanan->kegiatanTahunan->indikator_waktu; ?></td>
    <td style="text-align: center"><?= $kegiatanBulanan->target_waktu; ?></td>
    <td style="text-align: center"><?= $kegiatanBulanan->realisasi_waktu; ?></td>
    <td style="text-align: center"><?= $kegiatanBulanan->getPersenRealisasiWaktu(); ?>%</td>
</tr>
<tr>
    <td>Biaya</td>
    <td><?= $kegiatanBulanan->kegiatanTahunan->indikator_biaya; ?></td>
    <td style="text-align: center"><?= $kegiatanBulanan->target_biaya; ?></td>
    <td style="text-align: center"><?= $kegiatanBulanan->realisasi_biaya; ?></td>
    <td style="text-align: center"><?= $kegiatanBulanan->getPersenRealisasiBiaya(); ?>%</td>
</tr>