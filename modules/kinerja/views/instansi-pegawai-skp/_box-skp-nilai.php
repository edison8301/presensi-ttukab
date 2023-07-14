<?php

use app\components\Helper;
use app\components\Session;
use app\models\SkpNilai;
use app\modules\tukin\models\InstansiPegawai;
use yii\helpers\Html;
use dosamigos\chartjs\ChartJs;
use yii\helpers\VarDumper;
/* @var $model \app\modules\kinerja\models\InstansiPegawaiSkp */

?>

<div class="box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title">Penilaian SKP Tahun
            <?= Session::getTahun(); ?>
        </h3>
    </div>

    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th>Triwulan</th>
                <th>Umpan Balik Hasil Kerja</th>
                <th>Nilai Hasil Kerja</th>
                <th>Umpan Balik Perilaku Kerja</th>
                <th>Nilai Perilaku Kerja</th>
                <th style="text-align:center">Kurva Nilai</th>
                <th></th>
            </tr>
            <?php 
                $nilaiHasilKerja=[];
                $nilaiPerilakuKerja= []; 
            ?>
            <?php for ($i = 1; $i <= 4; $i++) { ?>
                <?php
                    $bulanIni=Helper::getBulanLengkap(SkpNilai::getBulanSaatIni());
                    $skpNilaiTriwulan = $model->getOneOrCreateSkpNilai([
                        'id_skp_periode' => 2,
                        'periode' => $i
                    ]);

                    $disabled = "disabled";
                    if ($skpNilaiTriwulan->canUpdate()) {
                        $disabled = "";
                    }
                ?>
                <tr>
                    <td>Triwulan
                        <?= $i; ?>
                    </td>
                    <td><?= $skpNilaiTriwulan->feedback_hasil_kerja ?></td>
                    <td>
                        <?= $skpNilaiTriwulan->getNamaNilaiHasilKerja(); ?>
                        <?php $nilaiHasilKerja[] = $skpNilaiTriwulan->nilai_hasil_kerja;?>
                    </td>
                    <td><?= $skpNilaiTriwulan->feedback_perilaku_kerja ?></td>
                    <td>
                        <?= $skpNilaiTriwulan->getNamaNilaiPerilakuKerja(); ?>
                        <?php $nilaiPerilakuKerja[] = $skpNilaiTriwulan->nilai_perilaku_kerja;?>
                    </td>
                   
                    <td>
                        <?= Html::a('<i class="fa fa-pencil"></i> Isi Nilai', [
                            '/skp-nilai/update',
                            'id' => $skpNilaiTriwulan->id
                        ], [
                                'class' => 'btn '.$disabled.' btn-xs btn-primary btn-flat'
                            ]); ?>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <th>Semesteran</th>
                <th>Umpan Balik Hasil Kerja</th>
                <th>Nilai Hasil Kerja</th>
                <th>Umpan Balik Perilaku Kerja</th>
                <th>Nilai Perilaku Kerja</th>
                <th></th>
            </tr>
            <?php for ($i = 1; $i <= 2; $i++) { ?>
                <?php
                    $skpNilaiSemester = $model->getOneOrCreateSkpNilai([
                        'id_skp_periode' => 3,
                        'periode' => $i
                    ]);
                    $disabled = "disabled";
                    if ($skpNilaiSemester->canUpdate()) {
                        $disabled = "";
                    }
                ?>

                <tr>
                <?php if (SkpNilai::getBulanSaatIni() <= 6 && $i == 1 || SkpNilai::getBulanSaatIni() >= 6 && $i == 2) { ?>
                        <td>Semester
                            <?= $i; ?>
                        </td>
                        <td><?= $skpNilaiSemester->feedback_hasil_kerja ?></td>
                        <td>
                            <?= $skpNilaiSemester->getNamaNilaiHasilKerja(); ?>
                            <?php $nilaiHasilKerja[] = $skpNilaiSemester->nilai_hasil_kerja;?>
                        </td>
                        <td><?= $skpNilaiSemester->feedback_perilaku_kerja ?></td>
                        <td>
                            <?= $skpNilaiSemester->getNamaNilaiPerilakuKerja(); ?>
                            <?php $nilaiPerilakuKerja[] = $skpNilaiSemester->nilai_perilaku_kerja;?>
                        </td>
                        <td>
                            <?= Html::a('<i class="fa fa-pencil"></i> Isi Nilai', [
                                '/skp-nilai/update',
                                'id' => $skpNilaiSemester->id
                            ], [
                                    'class' => 'btn '.$disabled.' btn-xs btn-primary btn-flat'
                                ]); ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            <?php
                $skpNilaiTahun = $model->getOneOrCreateSkpNilai([
                    'id_skp_periode' => 1,
                    'periode' => 1
                ]);
                $disabled = "disabled";
                if ($skpNilaiTahun->canUpdate()) {
                    $disabled = "";
                }
            ?>
                <tr>
                    <th>Tahunan</th>
                    <th>Umpan Balik Hasil Kerja</th>
                    <th>Nilai Hasil Kerja</th>
                    <th>Umpan Balik Perilaku Kerja</th>
                    <th>Nilai Perilaku Kerja</th>
                    <th></th>
                </tr>
                <tr>
                    <td> Tahun
                        <?= Session::getTahun(); ?>
                    </td>
                    <td><?= $skpNilaiTahun->feedback_hasil_kerja ?></td>
                    <td>
                        <?= $skpNilaiTahun->getNamaNilaiHasilKerja(); ?>
                        <?php $nilaiHasilKerja[] = $skpNilaiTahun->nilai_hasil_kerja;?>
                    </td>
                    <td><?= $skpNilaiTahun->feedback_perilaku_kerja ?></td>
                    <td>
                        <?= $skpNilaiTahun->getNamaNilaiPerilakuKerja(); ?>
                        <?php $nilaiPerilakuKerja[] = $skpNilaiTahun->nilai_perilaku_kerja;?>
                    </td>
                    <td>
                        <?= Html::a('<i class="fa fa-pencil"></i> Isi Nilai', [
                            '/skp-nilai/update',
                            'id' => $skpNilaiTahun->id
                        ], [
                                'class' => 'btn '.$disabled.' btn-xs btn-primary btn-flat'
                            ]); ?>
                    </td>
                </tr>
                <tr>
                    <th colspan="6">Kurva Keseluruhan Periode</th>
                </tr>
                <tr>
                    <td colspan="6">
                        <?php
                            $nilaiHasilKerjaTriwulan = [];
                            $nilaiHasilKerjaSemester = [];
                            $nilaiHasilKerjaTahunan = [];
                            
                            $nilaiHasilPerilakuTriwulan = [];
                            $nilaiHasilPerilakuSemester = [];
                            $nilaiHasilPerilakuTahunan = [];
                            
                            for ($i = 1; $i <= 4; $i++) 
                            { 
                                $skpNilai = $model->getOneOrCreateSkpNilai([
                                    'id_skp_periode' => 2,
                                    'periode' => $i
                                ]);
                                $nilaiHasilKerjaTriwulan[] = $skpNilai->nilai_hasil_kerja;
                                $nilaiPerilakuKerjaTriwulan[] = $skpNilai->nilai_perilaku_kerja;
                            } 
                            $dataTriwulan = $skpNilai->getKurvaSum($nilaiHasilKerjaTriwulan, $nilaiPerilakuKerjaTriwulan,"Triwulan");

                            for ($i = 1; $i <= 4; $i++) 
                            { 
                                $skpNilaiSemester = $model->getOneOrCreateSkpNilai([
                                    'id_skp_periode' => 3,
                                    'periode' => $i
                                ]);
                                if($skpNilaiSemester->nilai_hasil_kerja == null || $skpNilaiSemester->nilai_perilaku_kerja == null){
                                    $nilaiHasilKerjaSemester[] = 0;
                                    $nilaiPerilakuKerjaSemester[] = 0;
                                }
                                $nilaiHasilKerjaSemester[] = $skpNilaiSemester->nilai_hasil_kerja;
                                $nilaiPerilakuKerjaSemester[] = $skpNilaiSemester->nilai_perilaku_kerja;
                            }
                            
                            $dataSemester = $skpNilaiSemester->getKurvaSum($nilaiHasilKerjaSemester, $nilaiPerilakuKerjaSemester, "Semester");
                            
                            $skpNilaiTahunan = $model->getOneOrCreateSkpNilai([
                                'id_skp_periode' => 1,
                                'periode' => 1
                            ]);
                            
                            $nilaiHasilKerjaTahunan = $skpNilaiTahunan->nilai_hasil_kerja;
                            $nilaiPerilakuKerjaTahunan = $skpNilaiTahunan->nilai_perilaku_kerja;

                            $dataTahunan = $skpNilaiTahunan->getKurvaSum($nilaiHasilKerjaTahunan, $nilaiPerilakuKerjaTahunan, "Tahunan");
                            $labels = array_merge($dataTriwulan['labels'], $dataSemester['labels'], $dataTahunan['labels']);
                            $datasets = array_merge($dataTriwulan['datasets'], $dataSemester['datasets'], $dataTahunan['datasets']);

                            $data = [
                                'labels' => $labels,
                                'datasets' => $datasets,
                            ];

                            echo ChartJs::widget([
                                'type' => 'line',
                                'options' => [
                                    'height' => 100,
                                    'width' => 400,
                                ],
                                'data' => $data,
                            ]);
                        ?>
                    </td>
                </tr>
        </table>
    </div>

</div>
