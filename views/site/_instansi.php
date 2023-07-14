<?php

use yii\helpers\Html;
use app\models\User;

?>

<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">Daftar Unit Kerja</h3>
	</div>
	<div class="box-body">
		<table class="table">
		<thead>
		<tr>
			<th>No</th>
			<th>Nama</th>
			<th style="text-align: center">Jumlah Pegawai</th>
			<th style="text-align: center">Tunjangan Kinerja</th>
			<th style="text-align: center">Tunjangan Absensi</th>
			<th style="text-align: center">Tunjangan Total</th>
		</tr>
		</thead>
		<?php 
			$query = \app\models\Instansi::find(); 
		?>

		<?php $i=1; foreach($query->all() as $data) { ?>
		<tr>
			<td><?= $i; ?></td>
			<td><?= Html::a($data->nama,['site/index','FilterForm[id_instansi]'=>$data->id]); ?></td>
			<td style="text-align: center"><?= $data->getManyPegawai()->count(); ?> Pegawai</td>
			<td style="text-align: right">
				<?php /*
				<?php $label = $data->getTunjanganKinerja(['bulan'=>User::getBulan(),'tahun'=>User::getTahun()]); ?>
				<?= Html::a($label,['/kinerja/default/index','FilterForm[unit_kerja]'=>$data->id]) ?>
				*/ ?>
			</td>
			<td style="text-align: right">
				<?php /*
				<?php $label = $data->getTunjanganAbsensi(['bulan'=>User::getBulan(),'tahun'=>User::getTahun()]); ?>
				<?= Html::a($label,['/absensi/default/index','FilterForm[unit_kerja]'=>$data->id]) ?>
				*/ ?>
			</td>
			<td style="text-align: right">
				<?php /*
				<?= $data->getTunjanganTotal(['bulan'=>User::getBulan(),'tahun'=>User::getTahun()]); ?>
				*/ ?>
			</td>
		</tr>
		<?php $i++; } ?>
		</table>

		</table>
	</div>
</div>