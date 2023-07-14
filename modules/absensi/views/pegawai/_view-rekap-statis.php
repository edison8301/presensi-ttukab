<?php

/* @var $this \yii\web\View */
/* @var $pegawaiRekapAbsensi \app\modules\absensi\models\PegawaiRekapAbsensi */
?>
<?php /*
<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Rekap Potongan Disiplin</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="small-box bg-red">
                    <div class="inner">
                        <p>Potongan Disiplin Total</p>
                        <h3><?= $pegawaiRekapAbsensi->persen_potongan_total; ?> %</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-scissors"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <p>Potongan Disiplin Kehadiran</p>
                        <h3><?= $pegawaiRekapAbsensi->persen_potongan_fingerprint; ?> %</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-scissors"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <p>Potongan Disiplin Kegiatan</p>
                        <h3><?= $pegawaiRekapAbsensi->persen_potongan_kegiatan; ?> %</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-scissors"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
        </div>
    </div><!-- .box-body -->
</div>
 */ ?>


<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Rekap Kehadiran</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <p>Hari Kerja</p>
                        <h3><?= $pegawaiRekapAbsensi->jumlah_hari_kerja; ?> Hari</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="small-box bg-green">
                    <div class="inner">
                        <p>Hadir Kerja</p>
                        <h3><?= $pegawaiRekapAbsensi->jumlah_hadir; ?> Hari</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-check"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="small-box bg-red">
                    <div class="inner">
                        <p>Tidak Hadir</p>
                        <h3><?= $pegawaiRekapAbsensi->jumlah_tidak_hadir; ?> Hari</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-remove"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
        </div>
    </div><!-- .box-body -->
</div>

<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Rekap Ketidakhadiran</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <p>Izin</p>
                        <h3><?= $pegawaiRekapAbsensi->jumlah_izin; ?> Hari</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-car"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="small-box bg-green">
                    <div class="inner">
                        <p>Sakit</p>
                        <h3><?= $pegawaiRekapAbsensi->jumlah_sakit; ?> Hari</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-frown-o"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="small-box bg-purple">
                    <div class="inner">
                        <p>Cuti</p>
                        <h3><?= $pegawaiRekapAbsensi->jumlah_cuti; ?> Hari</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-smile-o"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <p>Dinas Luar</p>
                        <h3><?= $pegawaiRekapAbsensi->jumlah_dinas_luar; ?> Hari</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-plane"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="small-box bg-orange">
                    <div class="inner">
                        <p>Tugas Belajar</p>
                        <h3><?= $pegawaiRekapAbsensi->jumlah_tugas_belajar; ?> Hari</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="small-box bg-green">
                    <div class="inner">
                        <p>Tugas Kedinasan</p>
                        <h3><?= $pegawaiRekapAbsensi->jumlah_tugas_kedinasan; ?> Hari</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-star"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="small-box bg-red">
                    <div class="inner">
                        <p>Tanpa Keterangan</p>
                        <h3><?= $pegawaiRekapAbsensi->jumlah_tanpa_keterangan; ?> Hari</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-remove"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
        </div>
    </div><!-- .box-body -->
</div>
