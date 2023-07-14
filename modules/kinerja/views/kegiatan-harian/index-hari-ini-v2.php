<?php

use app\modules\kinerja\models\KegiatanHarian;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\Helper;
use app\modules\kinerja\models\Kinerja;
use app\modules\kinerja\models\KegiatanHarianJenis;
use app\modules\kinerja\models\KegiatanStatus;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KegiatanHarianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Kegiatan Harian';
if($searchModel->mode == 'bawahan')
    $this->title .= ' Bawahan';

$this->title .= ' : '.$searchModel->getHariTanggal();


$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_filter-index',['searchModel'=>$searchModel]); ?>

<div class="box box-primary">
    <div class="box-header">
        <?= Html::beginForm(['/kinerja/kegiatan-harian/index-v2'], 'post'); ?>
        
        <?php if(KegiatanHarian::accessCreate()) { ?>
            <?= $this->render('_modal-kinerja-tahunan', [
                'bulan' => $searchModel->bulan,
                'id_kegiatan_harian_jenis' => 1,
                'id_modal' => 'modal-utama',
            ]); ?>

            <?= $this->render('_modal-kinerja-tahunan', [
                'bulan' => $searchModel->bulan,
                'id_kegiatan_harian_jenis' => 2,
                'id_modal' => 'modal-tambahan'
            ]); ?>

            <?php // echo Html::a('<i class="fa fa-print"></i> Export Excel Kegiatan', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::submitButton('<i class="fa fa-send-o"></i> Kirim Kinerja Harian', [
                    'class' => 'btn btn-warning btn-flat',
                    'data-confirm' => 'Yakin akan kirim kegiatan yang dipilih?',
                    'name' => 'aksi',
                    'value' => 'yii1'
            ]); ?>
        <?php } ?>
    </div>

    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th style="text-align:center;width:10px;">
                        <?= Html::checkbox('pilih', false, [
                            'onClick' => 'toggleCheckbox(this)',
                        ]); ?>
                    </th>
                    <th style="text-align:center;width:10px;">No</th>
                    <th style="text-align:center;">Uraian</th>
                    <th style="text-align:center;width:50px;">Aspek</th>
                    <th style="text-align:center;width:300px;">Indikator Kinerja Individu</th>
                    <th style="text-align:center;width:100px;">Realisasi</th>
                    <th style="width: 100px;"></th>
                </tr>
                <tr>
                    <th colspan="8">
                        Kinerja Utama
                    </th>
                </tr>
                <?php $i=1; foreach($allKegiatanHarianUtama as $data)  { ?>
                    <tr>
                        <td rowspan="4" style="text-align: center">
                            <?php if ($data->accessSetPeriksa() OR User::isAdmin()) { ?>
                                <?= Html::checkbox('selection[]', false, [
                                    'value' => $data->id,
                                    'class' => 'checkbox-data',
                                ]); ?>
                            <?php } ?>
                        </td>
                        <td rowspan="4" style="text-align: center"><?= $i; ?></td>
                        <td rowspan="4">
                            <?= $data->uraian ?>
                            <?= $data->kegiatanStatus->getLabel(); ?><br>
                            <?= $data->getKeteranganTolak(); ?>
                        </td>
                        <td style="text-align: center">Kuantitas</td>
                        <td>
                            <?= @$data->kegiatanTahunan->indikator_kuantitas; ?>
                        </td>
                        <td style="text-align: center">
                            <?= $data->realisasi_kuantitas; ?>
                        </td>
                        <td rowspan="4" style="text-align: center">
                            <?php
                                $output = '';

                                if($data->accessSetPeriksa()) {
                                    $output .= Html::a('<i class="fa fa-send-o"></i>',['/kinerja/kegiatan-harian/set-periksa','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Kirim Untuk Diperiksa','onclick'=>'return confirm("Yakin akan mengirim data?");']).' ';
                                }
                
                                if($data->accessSetSetuju()) {
                                    $output .= Html::a('<i class="fa fa-check"></i>', ['/kinerja/kegiatan-harian/set-setuju', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Setujui Kegiatan', 'onclick' => 'return confirm("Yakin akan menyetujui kegiatan?");']) . ' ';
                                }
                
                                if($data->accessSetKonsep()) {
                                    $output .= Html::a('<i class="fa fa-refresh"></i>', ['/kinerja/kegiatan-harian/set-konsep', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Ubah Jadi Konsep', 'onclick' => 'return confirm("Yakin akan mengubah kegiatan jadi konsep?");']) . ' ';
                                }
                
                                if($data->accessSetTolak()) {
                                    $output .= Html::a('<i class="fa fa-remove"></i>', ['/kinerja/kegiatan-harian/tolak', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Tolak Kegiatan']) . ' ';
                                }

                                $output .= Html::a('<i class="fa fa-comment"></i>', ['/kinerja/kegiatan-harian/view-catatan', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Lihat Catatan']) . ' ';
                
                                $output .= Html::a('<i class="fa fa-eye"></i>',['/kinerja/kegiatan-harian/view','id'=>$data->id,'mode'=>$searchModel->mode],['data-toggle'=>'tooltip','title'=>'Lihat']).' ';
                
                                if($data->accessUpdate()) {
                                    $output .= Html::a('<i class="fa fa-pencil"></i>', ['/kinerja/kegiatan-harian/update-v2', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Ubah']) . ' ';
                                }
                
                                if($data->accessDelete()) {
                                    $output .= Html::a('<i class="fa fa-trash"></i>', ['kegiatan-harian/delete', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Hapus', 'onclick' => 'return confirm("Yakin akan menghapus data?");']) . ' ';
                                }

                                print trim($output);

                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">Kualitas</td>
                        <td>
                            <?= @$data->kegiatanTahunan->indikator_kualitas; ?>
                        </td>
                        <td style="text-align: center">
                            <?= $data->realisasi_kualitas; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">Waktu</td>
                        <td>
                            <?= @$data->kegiatanTahunan->indikator_waktu; ?>
                        </td>
                        <td style="text-align: center">
                            <?= $data->realisasi_waktu; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">Biaya</td>
                        <td>
                            <?= @$data->kegiatanTahunan->indikator_biaya; ?>
                        </td>
                        <td style="text-align: center">
                            <?= $data->realisasi_biaya; ?>
                        </td>
                    </tr>
                <?php $i++; } ?>
                <tr>
                    <th colspan="8">
                        Kinerja Tambahan
                    </th>
                </tr>
                <?php $i=1; foreach($allKegiatanHarianTambahan as $data)  { ?>
                    <tr>
                        <td style="text-align: center" rowspan="4">
                            <?php if ($data->accessSetPeriksa() OR User::isAdmin()) { ?>
                                <?= Html::checkbox('selection[]', false, [
                                    'value' => $data->id,
                                    'class' => 'checkbox-data',
                                ]); ?>
                            <?php } ?>
                        </td>
                        <td rowspan="4" style="text-align: center"><?= $i; ?></td>
                        <td rowspan="4">
                            <?= $data->uraian ?>
                            <?= $data->kegiatanStatus->getLabel(); ?><br>
                            <?= $data->getKeteranganTolak(); ?>
                        </td>
                        <td style="text-align: center">Kuantitas</td>
                        <td>
                            <?= @$data->kegiatanTahunan->indikator_kuantitas; ?>
                        </td>
                        <td style="text-align: center">
                            <?= $data->realisasi_kuantitas; ?>
                        </td>
                        <td rowspan="4" style="text-align: center">
                            <?php
                            $output = '';

                            if($data->accessSetPeriksa()) {
                                $output .= Html::a('<i class="fa fa-send-o"></i>',['/kinerja/kegiatan-harian/set-periksa','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Kirim Untuk Diperiksa','onclick'=>'return confirm("Yakin akan mengirim data?");']).' ';
                            }
            
                            if($data->accessSetSetuju()) {
                                $output .= Html::a('<i class="fa fa-check"></i>', ['/kinerja/kegiatan-harian/set-setuju', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Setujui Kegiatan', 'onclick' => 'return confirm("Yakin akan menyetujui kegiatan?");']) . ' ';
                            }
            
                            if($data->accessSetKonsep()) {
                                $output .= Html::a('<i class="fa fa-refresh"></i>', ['/kinerja/kegiatan-harian/set-konsep', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Ubah Jadi Konsep', 'onclick' => 'return confirm("Yakin akan mengubah kegiatan jadi konsep?");']) . ' ';
                            }
            
                            if($data->accessSetTolak()) {
                                $output .= Html::a('<i class="fa fa-remove"></i>', ['/kinerja/kegiatan-harian/tolak', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Tolak Kegiatan']) . ' ';
                            }

                            $output .= Html::a('<i class="fa fa-comment"></i>', ['/kinerja/kegiatan-harian/view-catatan', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Lihat Catatan']) . ' ';
            
                            $output .= Html::a('<i class="fa fa-eye"></i>',['/kinerja/kegiatan-harian/view','id'=>$data->id,'mode'=>$searchModel->mode],['data-toggle'=>'tooltip','title'=>'Lihat']).' ';
            
                            if($data->accessUpdate()) {
                                $output .= Html::a('<i class="fa fa-pencil"></i>', ['/kinerja/kegiatan-harian/update-v2', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Ubah']) . ' ';
                            }
            
                            if($data->accessDelete()) {
                                $output .= Html::a('<i class="fa fa-trash"></i>', ['kegiatan-harian/delete', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Hapus', 'onclick' => 'return confirm("Yakin akan menghapus data?");']) . ' ';
                            }

                            print trim($output);

                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">Kualitas</td>
                        <td>
                            <?= @$data->kegiatanTahunan->indikator_kualitas; ?>
                        </td>
                        <td style="text-align: center">
                            <?= $data->realisasi_kualitas; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">Waktu</td>
                        <td>
                            <?= @$data->kegiatanTahunan->indikator_waktu; ?>
                        </td>
                        <td style="text-align: center">
                            <?= $data->realisasi_waktu; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">Biaya</td>
                        <td>
                            <?= @$data->kegiatanTahunan->indikator_biaya; ?>
                        </td>
                        <td style="text-align: center">
                            <?= Helper::rp($data->realisasi_biaya, null); ?>
                        </td>
                    </tr>
                    <?php $i++; } ?>
            </table>
        </div>
    </div>
</div>

<script>
function toggleCheckbox(element) {
    let arrayCheckbox = document.getElementsByClassName('checkbox-data');
    for(let i=0; i <= arrayCheckbox.length; i++) {
        arrayCheckbox[i].checked = element.checked;
    }
}
</script>
