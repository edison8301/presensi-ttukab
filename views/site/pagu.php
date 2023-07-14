<?php

use app\models\User;
use app\models\Opd;
use app\components\Helper;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Pagu per OPD';
?>

<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">Pagu Per OPD</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered table-striped">
		<thead>
		<tr>
			<th width="40px">No</th>
			<th>Nama OPD</th>
			<th width="250px" style="text-align: center">Jumlah Pagu</th>
		</tr>
		</thead>
		<?php $total_pagu = 0; ?>
		<?php $i=1; foreach(Opd::find()->all() as $data) { ?>
		<?php $pagu = $data->getJumlahPaguByTahun(); ?>
		<?php $total_pagu = $total_pagu + $pagu; ?>
		<tr>
			<td style="text-align: center"><?= $i; ?></td>
			<td><?= $data->nama; ?></td>
			<?php $label = Helper::rp($pagu,0); ?>
			<td style="text-align: right"><?= Html::a($label,['opd/pagu','id'=>$data->id]); ?></td>
		</tr>
		<?php $i++; } ?>
		<tfoot>
		<tr>
			<th>&nbsp;</th>
			<th>Total</th>
			<th style="text-align: right"><?= Helper::rp($total_pagu,0); ?></th>
		</tr>
		</tfoot>
		</table>
	</div>
</div>

