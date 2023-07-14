<?php

/* @var $dasborKinerja \app\models\DasborKinerja */

$this->title = "Dasbor Kinerja"

?>

<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">Rekap Keg. Harian : <?= $dasborKinerja->getHariTanggal(); ?></h3>
	</div>

	<div class="box-body">
		<div class="row">
			<div class="col-sm-3">
				<div class="small-box bg-green">
					<div class="inner">
						<p>Kegiatan Disetujui</p>
						<h3>
							<?= $dasborKinerja->countKegiatanHarian([
								'id_pegawai'=>$dasborKinerja->id_pegawai,
								'id_kegiatan_status'=>KegiatanStatus::SETUJU,
								'tanggal'=>$dasborKinerja->tanggal
							]); ?>
						</h3>
					</div>
					<div class="icon">
						<i class="fa fa-check"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="small-box bg-aqua">
					<div class="inner">
						<p>Kegiatan Konsep</p>
						<h3>
							<?= $dasborKinerja->countKegiatanHarian([
								'id_pegawai'=>$dasborKinerja->id_pegawai,
								'id_kegiatan_status'=>KegiatanStatus::KONSEP,
								'tanggal'=>$dasborKinerja->tanggal
							]); ?>
						</h3>

					</div>
					<div class="icon">
						<i class="fa fa-pencil-square-o"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>

			<div class="col-sm-3">
				<div class="small-box bg-yellow">
					<div class="inner">
						<p>Kegiatan Diperiksa</p>
						<h3>
							<?= $dasborKinerja->countKegiatanHarian([
								'id_pegawai'=>$dasborKinerja->id_pegawai,
								'id_kegiatan_status'=>KegiatanStatus::PERIKSA,
								'tanggal'=>$dasborKinerja->tanggal
							]); ?>
						</h3>
					</div>
					<div class="icon">
						<i class="fa fa-search"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>

			<div class="col-sm-3">
				<div class="small-box bg-red">
					<div class="inner">
						<p>Kegiatan Ditolak</p>
						<h3>
							<?= $dasborKinerja->countKegiatanHarian([
								'id_pegawai'=>$dasborKinerja->id_pegawai,
								'id_kegiatan_status'=>KegiatanStatus::TOLAK,
								'tanggal'=>$dasborKinerja->tanggal
							]); ?>
						</h3>
					</div>
					<div class="icon">
						<i class="fa fa-remove"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>


		</div>
	</div>
</div>
