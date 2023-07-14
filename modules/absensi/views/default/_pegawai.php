<?php

use yii\helpers\Html;

use app\models\User;

?>

<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">Daftar OPD</h3>
	</div>

	<div class="box-body">
		<table class="table table-hover table-bordered">
		<thead>
		<tr>
			<th style="text-align: center" width="40px">No</th>
			<th style="text-align: center" width="100px">NIK</th>
			<th style="text-align: center">Nama</th>
			<th style="text-align: center">Total<br>Tidak<br>Hadir</th>
			<th style="text-align: center">Total<br>Hadir</th>
			<th style="text-align: center">Jumlah<br>Hari</th>
			<th style="text-align: center">DL</th>
			<th style="text-align: center">S</th>
			<th style="text-align: center">I</th>
			<th style="text-align: center">C</th>
			<th style="text-align: center">TK</th>
			<th style="text-align: center">Telat</th>
			<th style="text-align: center">% Potongan</th>
			<th style="text-align: center">Ket</th>
			<th style="text-align: center">&nbsp;</th>
		</tr>
		</thead>
		<?php
			
			/*
			

			$query = \app\modules\kinerja\models\User::find();
			$query->andWhere(['unit_kerja'=>\app\models\User::getUnitKerja()]);
			*/
		?>
		<?php
			$query = \app\modules\absensi\models\Pegawai::find();
			$query->andWhere('kode_instansi = :kode_instansi',[':kode_instansi'=>User::getKodeInstansi()]);
		?>
		<?php $i=1; foreach($query->all() as $pegawai) { ?>
		<?php 
			$jumlahHariKerja = \app\modules\absensi\models\Absensi::getJumlahHariKerja(['bulan'=>User::getBulan(),'tahun'=>User::getTahun()]);
			$jumlahHadir = $pegawai->getJumlahHadir(['bulan'=>User::getBulan(),'tahun'=>User::getTahun()]);
			
			$jumlahTidakHadir = $jumlahHariKerja - $jumlahHadir;

			$jumlahDinasLuar = $pegawai->getJumlahDinasLuar(['bulan'=>User::getBulan(),'tahun'=>User::getTahun()]);
			$jumlahSakit = $pegawai->getJumlahSakit(['bulan'=>User::getBulan(),'tahun'=>User::getTahun()]);
			$jumlahIzin = $pegawai->getJumlahIzin(['bulan'=>User::getBulan(),'tahun'=>User::getTahun()]);
			$jumlahCuti = $pegawai->getJumlahCuti(['bulan'=>User::getBulan(),'tahun'=>User::getTahun()]);
			$jumlahKeterangan = $jumlahDinasLuar + $jumlahSakit + $jumlahIzin + $jumlahCuti;
			$jumlahTanpaKeterangan = $jumlahTidakHadir - $jumlahKeterangan;

			$absensiRekap = $pegawai->findAbsensiRekap();

			$class = '';
			if($pegawai->kode_absensi == '')
				$class = "warning";
		?>

		<tr class="<?= $class; ?>">
			<td style="text-align: center"><?= $i; ?></td>
			<td><?= $pegawai->nip; ?></td>
			<td><?= Html::a($pegawai->nama,['/absensi/pegawai/view','id'=>$pegawai->id]); ?></td>
			<td style="text-align: center"><?= $jumlahTidakHadir; ?></td>
			<td style="text-align: center"><?= $jumlahHadir; ?></td>
			<td style="text-align: center"><?= $jumlahHariKerja; ?></td>
			<td style="text-align: center"><?= $jumlahDinasLuar; ?></td>
			<td style="text-align: center"><?= $jumlahSakit; ?></td>
			<td style="text-align: center"><?= $jumlahIzin; ?></td>
			<td style="text-align: center"><?= $jumlahCuti; ?></td>
			<td style="text-align: center"><?= $jumlahTanpaKeterangan; ?></td>
			<td style="text-align: center"><?= $absensiRekap->jumlah_menit_telat; ?></td>
			<td style="text-align: center"><?= $absensiRekap->jumlah_persen_potongan; ?></td>
			<td>&nbsp;</td>
			<td style="text-align: center">
				<?php if($pegawai->kode_absensi == '') { ?>
				<?= Html::a('<i class="fa fa-wrench"></i>',['/kinerja/user/set-no-id-absensi','id'=>$pegawai->id]); ?>
				<?php } ?>
			</td>
		</tr>
		<?php $i++; } ?>
		</table>
	</div>

</div>