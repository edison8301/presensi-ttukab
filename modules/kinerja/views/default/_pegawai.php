<?php

use yii\helpers\Html;

use app\models\User;
use app\components\Helper;

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
			<th style="text-align: center">% Rata-rata Kinerja</th>
			<th style="text-align: center">Jumlah Tunjangan Kinerja</th>
		</tr>
		</thead>
		<?php
			$query = \app\modules\kinerja\models\User::find();
			$query->andWhere(['unit_kerja'=>\app\models\User::getUnitKerja()]);
		?>
		<?php $i=1; foreach($query->all() as $user) { ?>
		<?php
			$tunjanganKinerja = $user->findTunjanganKinerja();
		?>
		<tr>
			<td style="text-align: center"><?= $i; ?></td>
			<td><?= $user->nip; ?></td>
			<td><?= Html::a($user->nama,['/kinerja/user/view','id'=>$user->id]); ?></td>
			<td style="text-align: center"><?= $tunjanganKinerja->persen_nilai; ?> %</td>
			<td style="text-align: right">Rp <?= Helper::rp($tunjanganKinerja->jumlah_tunjangan,0); ?></td>
		</tr>
		<?php $i++; } ?>
		</table>
	</div>

</div>