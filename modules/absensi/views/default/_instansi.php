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
			<th style="text-align: center">No</th>
			<th style="text-align: left">Nama OPD</th>
			<th style="text-align: center">Total<br>Tidak<br>Hadir</th>
			<th style="text-align: center">Total<br>Hadir</th>
			<th style="text-align: center">Jumlah<br>Hari</th>
			<th style="text-align: center">DL</th>
			<th style="text-align: center">S</th>
			<th style="text-align: center">I</th>
			<th style="text-align: center">C</th>
			<th style="text-align: center">TK</th>
			<th style="text-align: center">Ket</th>
		</tr>
		</thead>
		<?php $i=1; foreach(\app\models\Instansi::find()->all() as $data) { ?>
		<tr>
			<td style="text-align:center"><?= $i; ?></td>
			<td><?= Html::a($data->nama,['/absensi/default/index','FilterForm[kode_instansi]'=>$data->kode_instansi]); ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<?php $i++; } ?>
		</table>
	</div>

</div>