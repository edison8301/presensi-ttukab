<?php

/* @var $dasborKinerja \app\modules\kinerja\models\Dasbor */

$this->title = "Dasbor Kinerja"

?>

<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">Rekapitulasi</h3>
	</div>

	<div class="box-body">
		<div class="row">
			<div class="col-lg-4">
				<div class="small-box bg-green">
					<div class="inner">
						<p>Capaian Bulan Ini</p>
						<h3><?= $dasborKinerja->getPersenRealisasiKegiatanBulanan(); ?> %</h3>

					</div>
					<div class="icon">
						<i class="fa fa-remove"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>

			<div class="col-lg-4">

				<div class="small-box bg-aqua">
					<div class="inner">
						<p>Kegiatan/Tahapan Bulan Ini</p>
						<h3>Rp. 0</h3>
					</div>
					<div class="icon">
						<i class="fa fa-refresh"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>

			<div class="col-lg-4">

				<div class="small-box bg-blue">
					<div class="inner">
						<p>Capaian Tahun Ini</p>

						<h3>Rp. 0</h3>
					</div>
					<div class="icon">
						<i class="fa fa-refresh"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>

		</div>
	</div>
</div>
