<?php

use app\models\User;
use app\models\Opd;
use app\components\Helper;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Realisasi per OPD';
?>

<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">Pagu Per OPD</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
		<thead>
		<tr>
			<th width="40px">No</th>
			<th>Nama OPD</th>
			<th width="150px" style="text-align: center">Pagu (Rp)</th>
			<th width="150px" style="text-align: center">Realisasi (Rp)</th>
			<th width="150px" style="text-align: center">Sisa (Rp)</th>
			<th width="50px" style="text-align: center">%</th>
		</tr>
		</thead>
		<?php 
			$total_realisasi = 0; 
			$total_pagu = 0;
		?>
		<?php $i=1; foreach(Opd::find()->all() as $data) { ?>
		<?php 
			$pagu = $data->getJumlahPaguByTahun();
			$realisasi = $data->getJumlahRealisasi();
			$selisih = $pagu-$realisasi;
			$total_pagu = $total_pagu + $pagu;
			$total_realisasi = $total_realisasi + $realisasi;

			$persen = 0;
			if($pagu!=0)
			{
				$persen = $realisasi/$pagu*100;
				$persen = round($persen,2);
			}
		?>
		<tr>
			<td style="text-align: center"><?= $i; ?></td>
			<td><?= Html::a($data->nama,['opd/realisasi','id'=>$data->id]); ?></td>
			<td style="text-align: right"><?= Helper::rp($pagu); ?></td>
			<td style="text-align: right"><?= Helper::rp($realisasi); ?></td>
			<td style="text-align: right"><?= Helper::rp($selisih); ?></td>
			<td style="text-align: right"><?= $persen; ?></td>
		</tr>
		<?php $i++; } ?>
		<?php
			$total_selisih = $total_pagu - $total_realisasi;
			$total_persen = 0;
			if($total_pagu!=null)
			{
				$total_persen = $total_realisasi/$total_pagu*100;
				$total_persen = round($total_persen,2);
			}
		?>
		<tr>
			<th>&nbsp;</th>
			<th>Total</th>
			<th style="text-align: right"><?= Helper::rp($total_pagu,0); ?></th>
			<th style="text-align: right"><?= Helper::rp($total_realisasi,0); ?></th>
			<th style="text-align: right"><?= Helper::rp($total_selisih,0); ?></th>
			<th style="text-align: right"><?= $total_persen; ?></th>
		</table>
	</div>
</div>

