<?php

use app\components\Helper;
use app\components\Session;
use app\models\User;
use app\modules\tunjangan\models\TunjanganPotongan;
use yii\helpers\Html;

/* @var $model app\modules\tukin\models\Pegawai */
/* @var $isTampilNilaiTpp bool */

?>

<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">
            Rincian Perhitungan TPP Bulan <?= Helper::getBulanLengkap($filter->bulan); ?> <?= Session::getTahun(); ?>
        </h3>
    </div>
    <div class="box-body">
    	<div style="overflow-y: auto">
            <table class="table table-bordered table-hover">
                <!-- UNSUR PRODUKTIFITAS -->
                <tr>
                    <th width="466px">Besaran TPP :</th>
                    <td>
                        <?php if(@$isTampilNilaiTpp == true) { ?>
                            Rp <?= Helper::rp($model->getTppAwal($filter->bulan), 0) ?>
                        <?php } else { ?>
                            Dalam Proses Penyesuaian
                        <?php } ?>

                    </td>
                </tr>
            </table>
            <div>&nbsp;</div>
            <table class="table table-bordered table-hover">
                <tr>
                    <th colspan="3">Faktor Kinerja</th>
                    <th></th>
                </tr>
                <tr>
                    <th style="vertical-align: middle; width: 70px" rowspan="6">Rincian Faktor Kinerja</th>
                    <th style="vertical-align: middle; width: 40px" rowspan="3">Unsur Produktifitas</th>
                    <th style="vertical-align: middle; width: 140px">70% X BESARAN TPP</th>
                    <td width="60%">
                        <?php if(@$isTampilNilaiTpp == true) { ?>
                            Rp <?= Helper::rp($model->getTpp70($filter->bulan)) ?>
                        <?php } else { ?>
                            Dalam Proses Penyesuaian
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">POT. SKP BULANAN (%)</th>
                    <td><?= Helper::rp($model->getPersenPotonganSkpBulanan($filter->bulan),0,2); ?> %</td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">JUMLAH POT. (RP)</th>
                    <td>
                        <?php if(@$isTampilNilaiTpp == true) { ?>
                            Rp <?= Helper::rp($model->getRupiahPotonganProduktivitas($filter->bulan),0); ?>
                        <?php } else { ?>
                            Dalam Proses Penyesuaian
                        <?php } ?>
                    </td>
                </tr>

                <!-- UNSUR DISIPLLIL KERJA -->
                <tr>
                    <th style="vertical-align: middle" rowspan="3">UNSUR DISIPLIN KERJA</th>
                    <th style="vertical-align: middle">30% BESARAN TPP</th>
                    <td width="60%">
                        <?php if(@$isTampilNilaiTpp == true) { ?>
                            Rp <?= Helper::rp($model->getTpp30($filter->bulan)); ?>
                        <?php } else { ?>
                            Dalam Proses Penyesuaian
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">POT. PRESENSI</th>
                    <td><?= Helper::rp($model->getPersenPotonganPresensi($filter->bulan,['tukin'=>true]),0,2); ?> %</td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">JUMLAH POT (RP)</th>
                    <td>
                        <?php if(@$isTampilNilaiTpp == true) { ?>
                            Rp <?= Helper::rp($model->getRupiahPotonganDisiplinKerja($filter->bulan,['tukin'=>true]),0) ?>
                        <?php } else { ?>
                            Dalam Proses Penyesuaian
                        <?php } ?>
                    </td>
                </tr>
                <!-- JUMLAH POT -->
                <tr>
                    <th style="vertical-align: middle" colspan="3">JUMLAH POT.</th>
                    <td>
                        <?php if(@$isTampilNilaiTpp == true) { ?>
                            Rp <?= Helper::rp($model->getRupiahPotonganFaktorKinerja($filter->bulan),0); ?>
                        <?php } else { ?>
                            Dalam Proses Penyesuaian
                        <?php } ?>
                    </td>
                </tr>
            </table>
            <div>&nbsp;</div>

            <!-- HUKUMAN DISIPLIN -->
            <table class="table table-bordered table-hover">
                <tr>
                    <th colspan="3">Faktor Lainnya</th>
                    <th></th>
                </tr>
                <tr>
                    <th style="vertical-align: middle" rowspan="11">Rincian Faktor Lainnya</th>
                    <th style="vertical-align: middle" rowspan="7">Hukuman Disiplin</th>
                    <th style="vertical-align: middle">RINGAN (10%)</th>
                    <td width="60%">
                        <?= Helper::rp($model->getPersenPotonganHukumanDisiplinRingan($filter->bulan),0); ?> %
                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">SEDANG (20%)</th>
                    <td>
                        <?= Helper::rp($model->getPersenPotonganHukumanDisiplinSedang($filter->bulan),0); ?> %
                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">BERAT (30%)</th>
                    <td>
                        <?= Helper::rp($model->getPersenPotonganHukumanDisiplinBerat($filter->bulan),0); ?> %
                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">LHKPN/LHKASN (50%)</th>
                    <td>
                        <?= Helper::rp($model->getHukumanLHKPN($filter->bulan),0); ?> %
                        &nbsp;
                        <?= $model->getButtonTunjanganPotonganPegawai(TunjanganPotongan::LHKPN); ?>

                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">TPTGR (50%)</th>
                    <td>
                        <?= Helper::rp($model->getHukumanTPTGR($filter->bulan),0); ?> %
                        &nbsp;
                        <?= $model->getButtonTunjanganPotonganPegawai(TunjanganPotongan::TPTGR); ?>

                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">JUMLAH POT. (%)</th>
                    <td>
                        <?= Helper::rp($model->getPersenPotonganHukumanDisiplinTotal($filter->bulan),0); ?> %
                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">JUMLAH POT. (RP)</th>
                    <td>
                        <?php if(@$isTampilNilaiTpp == true) { ?>
                            Rp <?= Helper::rp($model->getRupiahPotonganHukumanDisiplinTotal($filter->bulan),0); ?>
                        <?php } else { ?>
                            Dalam Proses Penyesuaian
                        <?php } ?>
                    </td>
                </tr>

                <!-- PELANGGARAN KETENTUAN JF -->
                <tr>
                    <th style="vertical-align: middle" rowspan="4">Pelanggaran Ketentuan JF</th>
                    <th style="vertical-align: middle">TIDAK ADA DUPAK (25%)</th>
                    <td width="60%">
                        <?= Helper::rp($model->getPersenPotonganDupak($filter->bulan),0); ?> %
                        &nbsp;
                        <?= $model->getButtonTunjanganPotonganPegawai(TunjanganPotongan::TIDAK_ADA_DUPAK); ?>

                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">BLM DIANGKAT JF SELAMA 7 TH (10%)</th>
                    <td>
                        <?= Helper::rp($model->getPersenPotonganJfBelumDiangkat($filter->bulan),0); ?>

                        <?= $model->getButtonTunjanganPotonganPegawai(TunjanganPotongan::BELUM_DIANGKAT_JF_SELAMA_7_TAHUN); ?>

                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">JUMLAH POT. (%)</th>
                    <td><?= Helper::rp($model->getPersenPotonganPelanggaranKetentuanJf($filter->bulan),0,2); ?> %</td>
                </tr>
                <tr>
                    <th style="vertical-align: middle">JUMLAH POT. (Rp)</th>
                    <td>
                        <?php if(@$isTampilNilaiTpp == true) { ?>
                            Rp <?= Helper::rp($model->getRupiahPotonganPelanggaranKetentuanJf($filter->bulan),0); ?>
                        <?php } else { ?>
                            Dalam Proses Penyesuaian
                        <?php } ?>

                    </td>
                </tr>

                <!-- JUMLAH POT -->
                <tr>
                    <th style="vertical-align: middle" colspan="3">JUMLAH TOTAL POT.</th>
                    <td>
                        <?php if(@$isTampilNilaiTpp == true) { ?>
                            Rp <?= Helper::rp($model->getRupiahPotonganKeseluruhan($filter->bulan),0); ?>
                        <?php } else { ?>
                            Dalam Proses Penyesuaian
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th style="vertical-align: middle" colspan="3">JUMLAH TPP SEBELUM PAJAK.</th>
                    <td>
                        <?php if(@$isTampilNilaiTpp == true) { ?>
                            Rp <?= Helper::rp($model->getRupiahTPPSebelumPajak($filter->bulan), 0); ?>
                        <?php } else { ?>
                            Dalam Proses Penyesuaian
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
