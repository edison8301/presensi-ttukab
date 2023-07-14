<?php

use app\components\Helper;
use app\components\Session;

/* @var $isTampilNilaiTpp bool */
/* @var $model app\models\Pegawai */

?>

<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">
            Rincian Pembayaran TPP Bulan <?= Helper::getBulanLengkap($filter->bulan); ?> <?= Session::getTahun(); ?>
        </h3>
    </div>
    <div class="box-body">
    	<div style="overflow-y: auto;">
            <table class="table table-bordered table-hover">
                <!-- UNSUR PRODUKTIFITAS -->
                <tr>
                    <th style="vertical-align: middle; width: 40px" rowspan="4">PERHITUNGAN TPP</th>
                    <th style="vertical-align: middle; width: 140px">JUMLAH TPP SEBELUM PAJAK</th>
                    <td width="60%">
                        <?php if(@$isTampilNilaiTpp == true) { ?>
                            Rp <?= Helper::rp($model->getRupiahTPPSebelumPajak($filter->bulan),0); ?>
                        <?php } else { ?>
                            Dalam Proses Penyesuaian
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">TUNJANGAN PLT.<br/>(20% DARI BESARAN TPP)</th>
                    <td>
                        <?php if(@$isTampilNilaiTpp == true) { ?>
                            Rp <?= Helper::rp($model->getRupiahTunjanganPlt($filter->bulan),0); ?>
                        <?php } else { ?>
                            Dalam Proses Penyesuaian
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">VOL/BLN</th>
                    <td><?= $model->getVolumePerBulan(); ?></td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">JUMLAH KOTOR</th>
                    <td>
                        <?php if(@$isTampilNilaiTpp == true) { ?>
                            Rp <?= Helper::rp($model->getJumlahKotorTPP($filter->bulan),0); ?>
                        <?php } else { ?>
                            Dalam Proses Penyesuaian
                        <?php } ?>
                    </td>
                </tr>

                <!-- UNSUR DISIPLLIL KERJA -->
                <tr>
                    <th style="vertical-align: middle" rowspan="6">PPh. PASAL 21</th>
                    <th style="vertical-align: middle">GOL. IX</th>
                    <td width="60%">
                        <?= Helper::rp($model->getPotonganPajakByGolonganIX(['bulan'=>$filter->bulan]),0,2); ?> %
                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">GOL. IV</th>
                    <td>
                        <?= Helper::rp($model->getPotonganPajakByGolonganIV(['bulan'=>$filter->bulan]),0,2); ?> %
                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">GOL. III</th>
                    <td>
                        <?= Helper::rp($model->getPotonganPajakByGolonganIII(['bulan'=>$filter->bulan]),0,2); ?> %
                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">GOL. X</th>
                    <td>
                        <?= Helper::rp($model->getPotonganPajakByGolonganX(['bulan'=>$filter->bulan]),0,2); ?> %
                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">JUMLAH PPh (%)</th>
                    <td><?= Helper::rp($model->getPersenPotonganPajak(['bulan' => $filter->bulan]),0,2); ?> %</td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">JUMLAH PPh (Rp)</th>
                    <td>
                        <?php if(@$isTampilNilaiTpp == true) { ?>
                            Rp <?= Helper::rp($model->getRupiahPotonganPajak($filter->bulan),0); ?>
                        <?php } else { ?>
                            Dalam Proses Penyesuaian
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle" colspan="2">JUMLAH BERSIH</th>
                    <td>
                        <?php if(@$isTampilNilaiTpp == true) { ?>
                            Rp <?= Helper::rp($model->getRupiahTPPBersih($filter->bulan),0); ?>
                        <?php } else { ?>
                            Dalam Proses Penyesuaian
                        <?php } ?>
                    </td>
                </tr>

            </table>
        </div>
    </div>
</div>
