<?php


use app\components\Helper;
use app\models\PegawaiRekapBulan;
use app\models\User;
use yii\helpers\Html;

$bulan = 6;

?>



<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">
            Rekap Bulan <?= Helper::getBulanSingkat($bulan); ?> <?= User::getTahun(); ?>
        </h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th style="text-align: center">Uraian</th>
                <th style="text-align: center">Nilai</th>
                <th style="text-align: center">Keterangan</th>
            </tr>
            <tr>
                <td>Jumlah Pegawai Aktif</td>
                <td style="text-align: center">
                    <?= PegawaiRekapBulan::count([
                        'bulan'=>6,
                        'tahun'=>2020,
                        'id_pegawai_rekap_jenis'=>1
                    ]); ?>
                </td>
            </tr>
            <tr>
                <td>Jumlah Pegawai TPP Awal 0</td>
                <td style="text-align: center">
                    <?php $label = PegawaiRekapBulan::count([
                        'bulan'=>6,
                        'tahun'=>2020,
                        'id_pegawai_rekap_jenis'=>1,
                        'nilai' => 0
                    ]); ?>
                    <?= Html::a($label,[
                        '/pegawai-rekap-bulan/index',
                        'PegawaiRekapBulanSearch[bulan]'=>6,
                        'PegawaiRekapBulanSearch[tahun]'=>2020,
                        'PegawaiRekapBulanSearch[nilai]'=>0,
                    ]); ?>
                </td>
                <td></td>
            </tr>
        </table>
    </div>
</div>
