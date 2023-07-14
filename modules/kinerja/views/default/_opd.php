<?php

use yii\helpers\Html;

?>

<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">Daftar OPD</h3>
	</div>

	<div class="box-body">
		<table class="table table-bordered">
		<thead>
		<tr>
			<th>No</th>
			<th>Nama OPD</th>
			<th style="text-align: center">% Rata-rata Kinerja</th>
			<th style="text-align: center">Jumlah Tunjangan Kinerja</th>
		</tr>
		</thead>
		<?php $i=1; foreach(\app\modules\kinerja\models\UnitKerja::find()->all() as $data) { ?>
		<tr>
			<td><?= $i; ?></td>
			<td><?= Html::a($data->unit_kerja,['/kinerja/default/index','FilterForm[unit_kerja]'=>$data->id]); ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<?php $i++; } ?>
		</table>
	</div>

</div>