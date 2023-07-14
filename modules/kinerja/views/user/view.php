<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $user \app\models\User */

$this->title = "Detail User";
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>


<?= Yii::$app->controller->renderPartial('_filter',['user'=>$user]); ?>


<div class="user-view box box-primary">

    <div class="box-header">
        <h3 class="box-title"><?= $user->nama; ?></h3>
    </div>

    <div class="box-body">

        <?= DetailView::widget([
            'model' => $user,
            'attributes' => [
                'nama',
            ],
        ]) ?>
    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai',['/kinerja/user/index'],['class'=>'btn btn-warning btn-flat']); ?>
    </div>

</div>

<div class="user-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Kegiatan Bulanan</h3>
    </div>

    <div class="box-body">

        <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th style="vertical-align: middle; text-align: center">No</th>
            <th style="vertical-align: middle; text-align: center">Kegiatan Bulanan</th>
            <th style="vertical-align: middle; text-align: center">Kegiatan Tahunan</th>
            <th style="vertical-align: middle; text-align: center">Target<br>Kuantitas</th>
            <th style="vertical-align: middle; text-align: center">Realisasi<br>Kuantitas</th>
            <th style="vertical-align: middle; text-align: center">Hasil</th>
            <th style="vertical-align: middle; text-align: center">Keterangan</th>
        </tr>
        </thead>
        <?php
            $totalHasil = 0;
            $rataHasil = 0;
        ?>
        <?php $i=0; foreach($user->findAllKegiatanTahunanDetil() as $data) { ?>
        <?php foreach($data->findAllKegiatanBulananBreakdown() as $subdata) { ?>
        <?php
            $i++;
            $target = $subdata->kuantitas;
            $realisasi = $subdata->countKegiatanHarian();
            $hasil = 0;

            if($target!=0)
                $hasil = $realisasi/$target*100;

            if($hasil>=100)
                $hasil = 100;

            $totalHasil = $totalHasil + $hasil;
        ?>
        <tr>
            <td style="text-align: center"><?= $i; ?></td>
            <td><?= Html::a($subdata->kegiatan,['/kinerja/kegiatan-bulanan-breakdown/view','id'=>$subdata->id]); ?></td>
            <td><?= $data->kegiatanTahunan->kegiatan; ?></td>
            <td style="text-align: right"><?= $target; ?></td>
            <td style="text-align: right"><?= $realisasi; ?></td>
            <td style="text-align: right"><?= $hasil; ?>%</td>
            <td>&nbsp;</td>
        </tr>
        <?php } ?>
        <?php } ?>

        <?php
            if($i!=0)
                $rataHasil = round($totalHasil/$i,2);
        ?>
        <tr>
            <td colspan="5" style="font-weight: bold">Capaian Rata-rata</td>
            <td style="text-align: right"><?= $rataHasil; ?>%</td>
            <td>&nbsp;</td>
        </tr>
        </table>
    </div>

</div>

<div class="user-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Jumlah Tunjangan Kinerja</h3>
    </div>

    <div class="box-body">
        <?php

            $rataHasilBobot = $rataHasil * 0.6;
            $pokokTunjangan = $user->getPokokTunjangan();
            $jumlahTunjangan = $rataHasilBobot*$pokokTunjangan/100;

            $tunjanganAbsensi = $user->findTunjanganKinerja();
            $tunjanganAbsensi->persen_nilai = (string) $rataHasil;
            $tunjanganAbsensi->persen_bobot = (string) 0.6;
            $tunjanganAbsensi->pokok_tunjangan = (string) $pokokTunjangan;
            $tunjanganAbsensi->jumlah_tunjangan = (string) $jumlahTunjangan;

            $tunjanganAbsensi->save();
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
                <?= Html::a('<i class="fa fa-wrench"></i>',['/kinerja/user/set-grade','id'=>$user->id],['data-toggle'=>'tooltip','title'=>'Set Pokok Tunjangan']); ?>
            </td>
        </tr>
        <tr>
            <th>Jumlah Tunjangan Kinerja</th>
            <td style="text-align: right">Rp <?= Helper::rp($jumlahTunjangan,0); ?></td>
            <td>&nbsp;</td>
        </tr>

        </table>
    </div>
</div>
