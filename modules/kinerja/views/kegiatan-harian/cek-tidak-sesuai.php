<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Daftar Tidak Sesuai</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th style="width: 10px;">No</th>
                <th>Pegawai</th>
                <th>Tanggal</th>
                <th>Waktu Dibuat</th>
                <th style="text-align: 50px;"></th>
            </tr>
            <?php $no=1; foreach ($allKegiatanHarian as $kegiatanHarian) { ?>
                <?php $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $kegiatanHarian->waktu_dibuat);

                if ($kegiatanHarian->waktu_dibuat == null) {
                    continue;
                }

                $tanggal_dibuat = $datetime->format('Y-m-d');
                if ($kegiatanHarian->tanggal == $tanggal_dibuat) {
                    continue;
                } ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td>
                        <?= @$kegiatanHarian->pegawai->nama ?> (<?= @$kegiatanHarian->pegawai->nip ?>)
                    </td>
                    <td><?= $kegiatanHarian->tanggal ?></td>
                    <td><?= $kegiatanHarian->waktu_dibuat ?></td>
                    <td style="text-align: center;">
                        <?= \yii\helpers\Html::a('<i class="fa fa-eye"></i>', [
                            '/kinerja/kegiatan-harian/view-v3',
                            'id' => $kegiatanHarian->id,
                        ]) ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
