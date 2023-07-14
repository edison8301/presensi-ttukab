<?php

use yii\helpers\Html;
use app\modules\kinerja\models\KegiatanTahunan;

?>



<h3 style="text-align: center">SASARAN KERJA PEGAWAI</h3>
<div>&nbsp;</div>
<table style="border-collapse: collapse;" width="100%"  border="1" cellpadding="3">
<tr>
    <th style="text-align: center">No</th>
    <th colspan="3">I. Pejabat Penilai</th>
    <th style="text-align: center">No</th>
    <th colspan="7">II. Pegawai Negeri Sipil yang Dinilai</th>
</tr>
<tr>
    <td style="text-align:center; padding: 3px">1</td>
    <td style="width:160px; padding: 10px">Nama</td>
    <td colspan="2"><?= @$pegawai->atasan->nama; ?></td>
    <td style="text-align: center; padding: 10px">1</td>
    <td colspan="2" width="180px">Nama</td>
    <td colspan="5" style="padding: 10px"><?= Html::encode($pegawai->nama); ?></td>
</tr>
<tr>
    <td style="text-align:center; padding: 3px">2</td>
    <td style="width:160px; padding: 10px">NIP</td>
    <td colspan="2"><?= @$pegawai->atasan->nip; ?></td>
    <td style="text-align: center; padding: 10px">2</td>
    <td colspan="2">NIP</td>
    <td colspan="5" style="padding: 10px"><?= $pegawai->nip; ?></td>
</tr>
<tr>
    <td style="text-align:center; padding: 3px">3</td>
    <td style="width:160px; padding: 10px">Pangkat/Gol.Ruang</td>
    <td colspan="2">&nbsp;</td>
    <td style="text-align: center; padding: 10px">3</td>
    <td colspan="2">Pangkat/Gol.Ruang</td>
    <td colspan="5" style="padding: 10px">&nbsp;</td>
</tr>
<tr>
    <td style="text-align:center; padding: 3px">4</td>
    <td style="width:160px; padding: 10px">Jabatan</td>
    <td colspan="2"><?= @$pegawai->atasan->nama_jabatan; ?></td>
    <td style="text-align: center; padding: 10px">4</td>
    <td colspan="2">Jabatan</td>
    <td colspan="5" style="padding: 10px"><?= Html::encode($pegawai->nama_jabatan); ?></td>
</tr>
<tr>
    <td style="text-align:center; padding: 3px">5</td>
    <td style="width:160px; padding: 10px">Perangkat Daerah</td>
    <td colspan="2"><?= @$pegawai->atasan->instansi->nama; ?></td>
    <td style="text-align: center; padding: 10px">5</td>
    <td colspan="2">Perangkat Daerah</td>
    <td colspan="5" style="padding: 10px"><?= @$pegawai->atasan->instansi->nama; ?></td>
</tr>
<tr>
    <th rowspan="2" style="text-align: center; vertical-align: middle;">No</th>
    <th style="vertical-align:middle;text-align: center" rowspan="2" colspan="3">III. Kegiatan Tugas Jabatan</th>
    <th style="vertical-align:middle;text-align: center" rowspan="2">AK</th>
    <th style="vertical-align:middle;text-align: center;" colspan="6">Target</th>
    <th style="vertical-align:middle;text-align: center" rowspan="2">Status</th>
</tr>
<tr>
    <th colspan="2" style="text-align: center;">Kuant/Ouputput</th>
    <th style="text-align: center;">Kual/Mutu</th>
    <th colspan="2" style="text-align: center;">Waktu</th>
    <th style="text-align: center;">Biaya</th>
</tr>
<?php $total_ak = 0; ?>
<?php $i=1; foreach(KegiatanTahunan::allIndukByIdPegawai($pegawai->id) as $kegiatanTahunan) { ?>
<?php $total_ak += $kegiatanTahunan->target_angka_kredit; ?>
<tr>
    <td style="text-align:center; width: 50px"><?= $i; ?></td>
    <td colspan="3"><?= Html::encode($kegiatanTahunan->nama); ?></td>
    <td style="width: 50px"><?= $kegiatanTahunan->target_angka_kredit; ?></td>
    <td style="text-align: center; width: 50px;"><?= $kegiatanTahunan->target_kuantitas; ?></td>
    <td style="text-align: center; width: 50px;"><?= $kegiatanTahunan->satuan_kuantitas; ?></td>
    <td style="text-align: center; width: 50px;">100</td>
    <td style="text-align: center; width: 50px;"><?= $kegiatanTahunan->target_waktu; ?></td>
    <td style="text-align: center; width: 50px;">Bulan</td>
    <td style="text-align: right; width: 50px"><?= $kegiatanTahunan->target_biaya; ?></td>
    <td style="text-align: center; width: 50px"><?= $kegiatanTahunan->kegiatanStatus->nama; ?></td>
</tr>
<?php $i++; } ?>
<tr>
    <td>&nbsp;</td>
    <td colspan="3" style="text-align: center;">Jumlah Angka Kredit</td>
    <td><?= $total_ak; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
</table>

