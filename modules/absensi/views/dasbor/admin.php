<?php

use yii\helpers\Html;
use app\models\User;
use app\models\Instansi;

/* @var $dasbor \app\modules\absensi\models\Dasbor */

$this->title = "Dasbor Absensi Tahun ".User::getTahun();

?>

<?= $this->render('_admin-search',['dasbor'=>$dasbor]); ?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Daftar SKPD</h3>
    </div>

    <div class="box-body">
        <table class="table table-bordered">
        <thead>
        <tr>
            <th style="text-align: center">No</th>
            <th style="text-align: left">Nama OPD</th>
            <th style="text-align: center">Jumlah<br>Pegawai</th>
            <th style="text-align: center">Jumlah<br>Absensi</th>
            <th style="text-align: center">Mesin<br>Total</th>
            <th style="text-align: center">Mesin<br>Aktif</th>
        </tr>
        </thead>
        <?php $i=1; ?>
        <?php foreach(Instansi::find()->all() as $instansi) { ?>
        <?php $latestModel = $instansi->getCheckinoutTerakhir(); ?>
        <tr>
            <td style="text-align:center"><?= $i; ?></td>
            <td><?= Html::a($instansi->nama,['/absensi/dasbor/instansi','id_instansi'=>$instansi->id]); ?></td>
            <td style="text-align:center"><?= $instansi->countPegawai(); ?> Pegawai</td>
            <td style="text-align:center"><?= $instansi->countCheckinout(); ?> Absensi</td>
            <td style="text-align:center"><?= Html::a($instansi->countMesinAbsensi(),['/absensi/instansi/mesin-absensi/','id'=>$instansi->id]); ?></td>
            <td style="text-align:center"><?= Html::a($instansi->countMesinAbsensiAktif(),['/absensi/instansi/mesin-absensi/','id'=>$instansi->id]); ?></td>
        </tr>
        <?php $i++; } ?>
        </table>
    </div>
</div>



