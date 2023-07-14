<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Mutasi Pegawai</h3>
    </div>
    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Mutasi', ['pegawai-mutasi/create', 'id_pegawai' => $model->id], ['class' => 'btn btn-primary btn-flat']); ?>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="text-align: center; width: 50px;">No</th>
                <th style="text-align: center;">Instansi</th>
                <th style="text-align: center; width: 150px">Tanggal Berlaku</th>
                <th style="text-align: center; width: 150px">Fingerprint</th>
                <th style="text-align: center; width: 150px">Pengiriman Data</th>
                <th style="width: 100px"">&nbsp;</th>
            </tr>
            </thead>
            <?php $i = 1; ?>
            <?php foreach ($model->findAllPegawaiMutasi() as $mutasi): ?>
                <tr>
                    <td style="text-align: center;"><?= $i++; ?></td>
                    <td><?= @$mutasi->instansi->nama; ?></td>
                    <td style="text-align: center;"><?= Helper::getTanggalSingkat($mutasi->tanggal_berlaku); ?></td>
                    <td style="text-align: center;"><?= $mutasi->countTemplate(); ?></td>
                    <td style="text-align: center;">
                        <?= $mutasi->getLabelStatusKirim(); ?>
                    </td>
                    <td style="text-align: center;">
                        <?= Html::a('<i class="fa fa-paper-plane-o"></i>', ['pegawai-mutasi/kirim-mutasi', 'id' => $mutasi->id], ['data-toggle' => 'tooltip', 'title' => 'Kirim Data ke Mesin']); ?>
                        <?= Html::a('<i class="fa fa-pencil"></i>', ['pegawai-mutasi/update', 'id' => $mutasi->id], ['data-toggle' => 'tooltip', 'title' => 'Ubah']); ?>
                        <?= Html::a('<i class="fa fa-trash"></i>', ['pegawai-mutasi/delete', 'id' => $mutasi->id], ['data' => ['toggle' => 'tooltip', 'method' => 'post', 'confirm' => 'Yakin akan menghapus data?'], 'title' => 'Hapus']); ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>
