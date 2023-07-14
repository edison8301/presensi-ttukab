<?php

use app\components\Helper;
use app\components\Session;
use app\modules\kinerja\models\KegiatanBulanan;
use app\modules\kinerja\models\KegiatanTahunan;
use yii\helpers\Html;

/* @var $model app\modules\tukin\models\Pegawai */

?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-index',[
    'action' => ['/kinerja/pegawai/view','id' => $searchModel->id_pegawai],
    'searchModel' => $searchModel
]); ?>

<div class="pegawai-view box box-primary">

    <div class="box-header with-border">
        <h2 class="box-title"> <i class="fa fa-calendar"></i> Daftar Kegiatan Bulanan</h2>
    </div>

    <div class="box-body">
    	<table class="table table-bordered">
    		<tr>
    			<th>No</th>
    			<th>Kegiatan Bulanan</th>
    			<th style="text-align: center">Nomor SKP</th>
    			<th style="text-align: center">Bulan</th>
    			<th style="text-align: center">Jenis</th>
    			<th style="text-align: center">Target</th>
    			<th style="text-align: center">Realisasi</th>
    			<th style="text-align: center">% Realisasi</th>
    			<th>Status</th>
    			<th>&nbsp;</th>
    		</tr>
            <?php
                $total_persen = 0;

                $allKegiatanBulanan = KegiatanBulanan::findAll([
                    'joinWith' => ['kegiatanTahunan'],
                    'kegiatan_tahunan.id_pegawai' => $model->id,
                    'kegiatan_tahunan.tahun' => Session::getTahun(),
                    'kegiatan_tahunan.id_kegiatan_status' => 1,
                    'kegiatan_tahunan.status_hapus' => 0,
                    'kegiatan_tahunan.id_kegiatan_tahunan_versi' => 1,
                    'bulan' => $searchModel->bulan,
                    'target_is_not_null' => true,
                    'kegiatan_tahunan.status_plt'=>'0'
                ]);

                //$allKegiatanBulanan = $queryKegiatanBulan->all();
            ?>

    		<?php
    		    $i=1;
    		    $persenRealisasiTotal = 0;
            ?>
    		<?php foreach ($allKegiatanBulanan as $kegiatanBulanan) { ?>
    		<tr>
    			<td class="text-center"><?= $i; ?></td>
    			<td>
                    <?= Html::a($kegiatanBulanan->kegiatanTahunan->nama,[
                        'kegiatan-bulanan/view',
                        'id'=>$kegiatanBulanan->id
                    ]); ?>
                </td>
    			<td class="text-center">
                    <?= @$kegiatanBulanan->instansiPegawaiSkp->nomor; ?>
                </td>
    			<td class="text-center">
                    <?= $kegiatanBulanan->getNamaBulanSingkat(); ?>
                </td>
    			<td style="text-align: center">
                    <?= $kegiatanBulanan->kegiatanTahunan ? $kegiatanBulanan->kegiatanTahunan->getTextInduk() : ''; ?>
                </td>
    			<td style="text-align: center">
                    <?= $target = $kegiatanBulanan->target; ?> <?= $kegiatanBulanan->getSatuanKuantitas(); ?>
                </td>
    			<td style="text-align: center">
                    <?php $label = Helper::rp($realisasi = $kegiatanBulanan->realisasi,0,0); ?>
                    <?php $label .= ' '.$kegiatanBulanan->getSatuanKuantitas(); ?>
                    <?= Html::a($label,[
                        '/kinerja/kegiatan-harian/index',
                        'KegiatanHarianSearch[id_kegiatan_tahunan]'=>$kegiatanBulanan->id_kegiatan_tahunan,
                        'KegiatanHarianSearch[bulan]'=>$kegiatanBulanan->bulan,
                        'KegiatanHarianSearch[id_pegawai]'=>$kegiatanBulanan->kegiatanTahunan->id_pegawai
                    ]); ?>
                </td>
    			<td style="text-align: center">
                    <?= Helper::rp($persenRealisasi = $kegiatanBulanan->persen_realisasi,0,1); ?>
                </td>
    			<td>
                    <?= @$kegiatanBulanan->kegiatanTahunan->labelIdKegiatanStatus; ?>
                </td>
    			<td>
    				<?= Html::a('<i class="fa fa-eye"></i>',['kegiatan-bulanan/view','id'=>$kegiatanBulanan->id],['data-toggle'=>'tooltip','title'=>'Lihat']); ?>
    				<?= Html::a('<i class="fa fa-refresh"></i>',['kegiatan-bulanan/update-realisasi','id'=>$kegiatanBulanan->id],['data-toggle'=>'tooltip','title'=>'Perbarui Realisasi']); ?>
    			</td>
    		</tr>

            <?php
                $persenRealisasiTotal += $persenRealisasi;
            ?>

    		<?php $i++; } ?>
            <?php
                $jumlah = $i-1;

                if($jumlah == 0) {
                    $jumlah = 1;
                }
            ?>
            <tr>
                <th colspan="7" class="text-center">RATA - RATA</th>
                <th style="text-align: center"><?= Helper::rp($persenRealisasiTotal/$jumlah,0,1); ?></th>
                <th colspan="2">&nbsp;</th>
            </tr>
    	</table>
    </div>
</div>

