<?php

use app\components\Helper;
use app\models\User;

/* @var $dasborKinerja \app\models\DasborKinerja */

$this->title = "Dasbor Kinerja"

?>

<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">Rekap Keg. Bulanan : <?= Helper::getBulanLengkap($dasborKinerja->bulan).' '.User::getTahun(); ?></h3>
	</div>

	<div class="box-body">
		<div class="row">
			<div class="col-sm-3">
				<div class="small-box bg-green">
					<div class="inner">
						<p>Rata-rata Capaian</p>
						<h3>0 %</h3>

					</div>
					<div class="icon">
						<i class="fa fa-percent"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>

			<div class="col-sm-3">

				<div class="small-box bg-aqua">
					<div class="inner">
						<p>Kegiatan/Tahapan</p>
						<h3>
							<?= $dasborKinerja->countKegiatanBulanan([
								'id_pegawai'=>$dasborKinerja->id_pegawai,
								'bulan'=>$dasborKinerja->bulan
							]); ?>
						</h3>
					</div>
					<div class="icon">
						<i class="fa fa-tags"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>

			<div class="col-sm-3">

				<div class="small-box bg-yellow">
					<div class="inner">
						<p>Target</p>
						<h3>
							<?= $dasborKinerja->sumKegiatanBulanan([
								'attribute'=>'target',
								'id_pegawai'=>$dasborKinerja->id_pegawai,
								'bulan'=>$dasborKinerja->bulan
							]); ?>
						</h3>
					</div>
					<div class="icon">
						<i class="fa fa-bullseye"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-sm-3">

				<div class="small-box bg-red">
					<div class="inner">
						<p>Realisasi</p>
						<h3>
							<?= $dasborKinerja->sumKegiatanBulanan([
								'attribute'=>'realisasi',
								'id_pegawai'=>$dasborKinerja->id_pegawai,
								'bulan'=>$dasborKinerja->bulan
							]); ?>
						</h3>
					</div>
					<div class="icon">
						<i class="fa fa-check"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>

		</div>
	</div>
</div>
