<?php

use app\models\User;
use app\models\Instansi;

/* @var $instansi Instansi */

?>
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">Data Tunjangan</h3>
	</div>
	<div class="box-body">
		<table class="table table-hover">
		<thead>
		<tr>
			<th>No</th>
			<th>NIP</th>
			<th>Nama</th>
			<th style="text-align: center">Tunjangan Prestasi</th>
			<th style="text-align: center">Tunjangan Perilaku</th>
			<th style="text-align: center">Tunjangan Total</th>
		</tr>
		</thead>

		<?php $query = Instansi::findOne(['id_instansi'=>User::getIdInstansi()]); ?>

		<?php $i=1; foreach($instansi->findAllPegawai() as $pegawai) { ?>
		<tr>
			<td><?= $i; ?></td>
			<td><?= $pegawai->nip; ?></td>
			<td><?= $pegawai->nama; ?></td>
			<td style="text-align: right">
				<?php /*
				<?php $label = "Rp ".Helper::rp($pegawai->getTunjanganKinerja(),0); ?>
				<?= Html::a($label,['/kinerja/user/view','id'=>$user->id]); ?>
				*/ ?>
			</td>
			<td style="text-align: right">
				<?php /*
				<?php $label = "Rp ".Helper::rp($pegawai->getTunjanganAbsensi(),0); ?>
				<?= Html::a($label,['/absensi/user/view','id'=>$pegawai->id]); ?>
				*/ ?>
			</td>
			<td style="text-align: right">
				<?php /*
				Rp <?= Helper::rp($pegawai->getTunjanganTotal(),0); ?>
				*/ ?>
			</td>
		</tr>
		<?php $i++; } ?>
		</table>
	</div>
</div>
