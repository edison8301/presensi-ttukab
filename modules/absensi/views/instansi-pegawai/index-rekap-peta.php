<?php

use app\components\Session;
use yii\helpers\Html;

$this->title = 'Daftar Rekap Absensi';
?>

<?= $this->render('_filter-index-rekap-peta', [
    'pegawaiPetaAbsensiForm' => $pegawaiPetaAbsensiForm,
]) ?>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Daftar Pegawai Absensi (<?= @$pegawaiPetaAbsensiForm->peta->nama ?>)</h3>
    </div>

    <div class="box-body">

        <div style="margin-bottom: 20px;">
            <?= Html::a('<i class="fa fa-file-pdf-o"></i> Export PDF', Yii::$app->request->url . '&export-pdf=1', ['class' => 'btn btn-primary btn-flat', 'target' => '_blank']) ?>

            <?= Html::a('<i class="fa fa-file-excel-o"></i> Export Rekap PD', Yii::$app->request->url . '&export-excel=1', ['class' => 'btn btn-success btn-flat']) ?>
        </div>

        <div style="overflow: auto;">
            <?= $this->render('_table-rekap-peta', [
                'pegawaiPetaAbsensiForm' => $pegawaiPetaAbsensiForm,
                'allPegawaiPetaAbsensiReport' => $allPegawaiPetaAbsensiReport,
            ]) ?>
        </div>

        <?php /*
        <table class="table table-bordered table-condensed">
            <tr>
                <th style="text-align: center; width: 50px;">No</th>
                <th style="text-align: center; width: 250px;">Perangkat Daerah</th>
                <th>Nama Pegawai</th>
                <th style="text-align: center; width: 200px;">Waktu Absen</th>
            </tr>
            <?php if ($pegawaiPetaAbsensiForm->id_instansi == null) { ?>
                <tr>
                    <td colspan="4"><i>Silahkan pilih perangkat daerah terlebih dahulu</i></td>
                </tr>
            <?php } ?>
            <?php $no=1; foreach ($allPegawaiPetaAbsensiReport as $pegawaiPetaAbsensiReport) { ?>
                <tr>
                    <td style="text-align: center;"><?= $no++; ?></td>
                    <td style="text-align: center;"><?= $pegawaiPetaAbsensiReport->instansi->nama ?></td>
                    <td>
                        <?= $pegawaiPetaAbsensiReport->pegawai->nama ?><br/>
                        NIP.<?= $pegawaiPetaAbsensiReport->pegawai->nip ?>
                    </td>
                    <td>
                        <?= $pegawaiPetaAbsensiReport->getStringCheckinout([
                            'tanggal' => $pegawaiPetaAbsensiForm->tanggal,
                        ]) ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
        */ ?>
    </div>

</div>