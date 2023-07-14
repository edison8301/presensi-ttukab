<?php

use yii\helpers\Html;
use app\models\User;
use app\components\Helper;
use app\modules\absensi\models\Absensi;

/* @var $this yii\web\View */
/* @var $model backend\kinerja\models\User */

$this->title = "Detail Absensi Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>


<?= $this->render('_filter',['model'=>$model]); ?>


<div class="user-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Daftar Absensi</h3>
    </div>

    <div class="box-header with-border">
        <?= Html::a('<i class="fa fa-print"></i> Ekspor Data Absensi', ['pegawai/export-absensi', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
    </div>

    <div class="box-body">
        <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th style="text-align: center">Tanggal</th>
            <th style="text-align: center">Hari</th>
            <th style="text-align: center">Total Absensi</th>
            <th style="text-align: center">Telat/Kurang</th>
            <th style="text-align: center">DL</th>
            <th style="text-align: center">S</th>
            <th style="text-align: center">I</th>
            <th style="text-align: center">C</th>
            <th style="text-align: center">TK</th>
            <th style="text-align: center">% Potong</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <?php
            $dateTime = date_create(User::getTahun().'-'.User::getBulan().'-01');
            $jumlahHari = $dateTime->format('t');
            $sumTotalPersenPotongan = 0;
            $sumTotalMenitTelat = 0;
            $sumJumlahAbsensi = 0;
            $sumDinasLuar = 0;
            $sumSakit = 0;
            $sumIzin = 0;
            $sumCuti = 0;
            $sumTanpaKeterangan = 0;
        ?>
        <?php for($j=1;$j<=$jumlahHari;$j++) { ?>
        <?php
            $class = "";
            $libur = false;
            $tanggal = $dateTime->format('Y-m-d');

            if($dateTime->format('N')==6 OR $dateTime->format('N')==7 OR Absensi::isLibur($tanggal))
            {
                $class = 'danger';
                $libur = true;
            }

            $jumlahAbsensi = $model->getJumlahAbsensi(['tanggal'=>$tanggal]);

            $tk = null;
            $dl = null;
            $s = null;
            $i = null;
            $c = null;

            $sumJumlahAbsensi += $jumlahAbsensi;

            if($jumlahAbsensi==0 AND $libur == false)
            {
                $tk = 1;

                $keterangan = \app\modules\absensi\models\Keterangan::findOne([
                    'tanggal'=>$tanggal,
                    'nip'=>$model->nip
                ]);

                if($keterangan!==null)
                {
                    $tk = null;
                    switch ($keterangan->id_keterangan_jenis) {
                        case 1:
                            $dl=1;
                            $sumDinasLuar++;
                            break;

                        case 2:
                            $s=1;
                            $sumSakit++;
                            break;

                        case 3:
                            $i=1;
                            $sumIzin++;
                            break;

                        case 4:
                            $c=1;
                            $sumCuti++;
                            break;
                    }
                }
            }

            if($jumlahAbsensi == 0 AND $tk == 1)
            {
                $class = "warning";
                $sumTanpaKeterangan++;
            }

        ?>
        <?php
            $totalPersenPotongan = 0;
            $totalMenitTelat = 0;

            if($jumlahAbsensi!= 0) {
                foreach($model->findAllJamKerja($tanggal) as $jamKerja) {
                    $model->getJamAbsensi($tanggal,$jamKerja);
                    $totalMenitTelat += $model->getMenitTelat($tanggal,$jamKerja);
                    $totalPersenPotongan +=  $model->getPersenPotongan($tanggal,$jamKerja);
                }
            } else {
                if($tk==1) $totalPersenPotongan = 45;
            }

            $sumTotalMenitTelat += $totalMenitTelat;
            $sumTotalPersenPotongan += $totalPersenPotongan;

        ?>
        <tr class="<?= $class; ?>" style="cursor:pointer" data-tanggal="<?= $tanggal; ?>">
            <td style="text-align: center"><?= Helper::getTanggalSingkat($tanggal); ?></td>
            <td style="text-align: center"><?= Helper::getHari($tanggal); ?></td>
            <td style="text-align: center"><?= $jumlahAbsensi; ?> Absensi</td>
            <td style="text-align: center"><?= $totalMenitTelat; ?> Menit</td>
            <td style="text-align: center"><?= Helper::getIconRemove($dl,"Dinas Luar"); ?></td>
            <td style="text-align: center"><?= Helper::getIconRemove($s,"Sakit"); ?></td>
            <td style="text-align: center"><?= Helper::getIconRemove($i,"Izin"); ?></td>
            <td style="text-align: center"><?= Helper::getIconRemove($c,"Cuti"); ?></td>
            <td style="text-align: center"><?= Helper::getIconRemove($tk,"Tanpa Keterangan"); ?></td>
            <td style="text-align: center"><?= $totalPersenPotongan; ?> %</td>
            <td style="text-align: center">


            <?php if($jumlahAbsensi==0 AND $libur == false) { ?>
                <?= Html::a('<i class="fa fa-lightbulb-o"></i>',[
                    '/absensi/keterangan/create',
                    'nip'=>$model->nip,
                    'tanggal'=>$dateTime->format('Y-m-d')
                ],[
                    'data-toggle'=>'tooltip',
                    'title'=>'Input Keterangan/Izin'
                ]); ?>
            <?php } ?>
            </td>
        </tr>
        <?php if($jumlahAbsensi!=0) { ?>
        <?php foreach($model->findAllJamKerja($tanggal) as $jamKerja) { ?>
        <tr class="jam-<?= $tanggal; ?> info" >
            <td style="text-align:center"><?= $jamKerja->nama; ?></td>
            <td style="text-align:center"><?= substr($jamKerja->jam_mulai_normal,0,5); ?> - <?= substr($jamKerja->jam_selesai_normal,0,5); ?></td>

            <td style="text-align:center"><?= $model->getJamAbsensi($tanggal,$jamKerja); ?></td>
            <td style="text-align:center"><?= $model->getMenitTelat($tanggal,$jamKerja); ?> Menit</td>
            <td style="text-align:center">&nbsp;</td>
            <td style="text-align:center">&nbsp;</td>
            <td style="text-align:center">&nbsp;</td>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <td style="text-align:center">
                <?= $model->getPersenPotongan($tanggal,$jamKerja); ?> %
            </td>
            <td>&nbsp;</td>
        </tr>
        <?php } ?>
        <?php } ?>

        <?php /*
        <?php $k=1; foreach($model->findAllAbsensi(['tanggal'=>$dateTime->format('Y-m-d')]) as $data) { ?>
        <tr style="display:none" class="jam-<?= $tanggal; ?>">
            <td>&nbsp;</td>
            <td style="text-align:center">&nbsp;</td>
            <td style="text-align:center"><?= $data->jam_absensi; ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <?php $k++; } ?>
        */ ?>
        <?php $dateTime->modify('+1 day'); } ?>
        <tr>
            <th colspan="2" style="text-align: center">Total</th>
            <th style="text-align: center">
                <span data-toggle="tooltip" title="Jumlah Absensi"><?= $sumJumlahAbsensi; ?> Absensi</span>
            </th>
            <th style="text-align: center">
                <span data-toggle="tooltip" title="Menit Telat"><?= $sumTotalMenitTelat; ?> Menit</span>
            </th>
            <th style="text-align:center">
                <span data-toggle="tooltip" title="Dinas Luar"><?= $sumDinasLuar; ?></span>
            </th>
            <th style="text-align:center">
                <span data-toggle="tooltip" title="Sakit"><?= $sumSakit; ?></span>
            </th>
            <th style="text-align:center">
                <span data-toggle="tooltip" title="Izin"><?= $sumIzin; ?></span>
            </th>
            <th style="text-align:center">
                <span data-toggle="tooltip" title="Cuti"><?= $sumCuti; ?></span>
            </th>
            <th style="text-align:center">
                <span data-toggle="tooltip" title="Tanpa Keterangan"><?= $sumTanpaKeterangan; ?></span>
            </th>
            <th style="text-align: center">
                <span data-toggle="tooltip" title="% Potongan"><?= $sumTotalPersenPotongan; ?> %</span>
            </th>
            <th>&nbsp;</th>
        </tr>
        </table>

        <?php
            $rekap = $model->findAbsensiRekap();
            $rekap->jumlah_absensi = $sumJumlahAbsensi;
            $rekap->jumlah_menit_telat = $sumTotalMenitTelat;
            $rekap->jumlah_hadir = 0;
            $rekap->jumlah_persen_potongan = $sumTotalPersenPotongan;
            $rekap->save();
        ?>

        <script type="text/javascript">
            $("tr").click(function() {
                var tanggal = $(this).data("tanggal");
                $(".jam-"+tanggal).toggle("fast");
            });
        </script>

    </div>
</div>

<div class="user-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Jumlah Tunjangan Kinerja</h3>
    </div>

    <div class="box-body">
        <?php
            $rataHasil = 100-$sumTotalPersenPotongan;
            if($rataHasil<0)
                $rataHasil = 0;

            $rataHasilBobot = $rataHasil * 0.4;
            $pokokTunjangan = $model->getPokokTunjangan();
            $jumlahTunjangan = $rataHasilBobot*$pokokTunjangan/100;

            $tunjanganKinerja = $model->findTunjanganAbsensi();
            $tunjanganKinerja->persen_nilai = (string) $rataHasil;
            $tunjanganKinerja->persen_bobot = (string) 0.6;
            $tunjanganKinerja->pokok_tunjangan = (string) $pokokTunjangan;
            $tunjanganKinerja->jumlah_tunjangan = (string) $jumlahTunjangan;

            $tunjanganKinerja->save();
        ?>

        <table class="table">
        <tr>
            <th>Capaian Rata-rata</th>
            <td style="text-align: right"><?= $rataHasil; ?> %</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th>Capaian Rata-rata x Bobot 60%</th>
            <td style="text-align: right"><?= $rataHasilBobot; ?> %</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th>Pokok Tunjangan</th>
            <td style="text-align: right">Rp <?= Helper::rp($pokokTunjangan); ?></td>
            <td style="text-align: center">
                <?= Html::a('<i class="fa fa-wrench"></i>',['/kinerja/user/set-grade','id'=>$model->id],['data-toggle'=>'tooltip','title'=>'Set Pokok Tunjangan']); ?>
            </td>
        </tr>
        <tr>
            <th>Jumlah Tunjangan Kinerja</th>
            <td style="text-align: right">Rp <?= Helper::rp($jumlahTunjangan); ?></td>
            <td>&nbsp;</td>
        </tr>

        </table>
