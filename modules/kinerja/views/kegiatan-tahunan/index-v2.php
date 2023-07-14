<?php

use app\components\Helper;
use app\modules\kinerja\models\KegiatanTahunanSearch;
use yii\helpers\Html;
use app\models\User;
use app\modules\kinerja\models\KegiatanStatus;

/* @see \app\modules\kinerja\controllers\KegiatanTahunanController::actionIndexV2() */
/* @var $this yii\web\View */
/* @var $searchModel KegiatanTahunanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $allKegiatanTahunanUtama \app\modules\kinerja\models\KegiatanTahunan[] */
/* @var $allKegiatanTahunanTambahan \app\modules\kinerja\models\KegiatanTahunan[] */

$this->title = 'Daftar Kinerja Tahunan';
if($searchModel->mode == 'bawahan')
    $this->title .= ' Bawahan';

$this->title .= ' : ' . $searchModel->tahun;

$this->params['breadcrumbs'][] = $this->title;

if(isset($debug)==false) {
    $debug = false;
}

?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-index-v2',['searchModel'=>$searchModel]); ?>

<?= Html::beginForm(['/kinerja/kegiatan-tahunan/index-v2'], 'post'); ?>

<div class="kegiatan-tahunan-index box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Kinerja Tahunan</h3>
    </div>

    <div class="box-header">
        <?= $this->render('_menu-index-v2',[
              'searchModel'=>$searchModel
        ]); ?>
    </div>

    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th>
                        <?= Html::checkbox('pilih', false, [
                            'onClick' => 'toggleCheckbox(this)',
                        ]); ?>
                    </th>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">Rencana Kerja Atasan Langsung</th>
                    <th style="text-align: center;">Rencana Kinerja</th>
                    <th style="text-align: center">Aspek</th>
                    <th style="text-align: center">Indikator Kinerja Individu</th>
                    <th style="text-align: center; width: 100px;">Target</th>
                    <th style="width: 100px;"></th>
                </tr>
                <tr>
                    <th colspan="8">
                        Kinerja Utama
                    </th>
                </tr>
                <?php if ($searchModel->id_pegawai == null) { ?>
                    <tr>
                        <td colspan="8">
                            Silahkan pilih pegawai pada filter untuk menampilkan Kinerja Tahunan
                        </td>
                    </tr>
                <?php } ?>
                <?php $i=1; foreach($allKegiatanTahunanUtamaInduk as $data)  { ?>
                    <?= $this->render('_tr-index-kegiatan-tahunan-v2', [
                        'kegiatanTahunan' => $data,
                        'searchModel' => $searchModel,
                        'no' => $i,
                    ]) ?>
                <?php $i++; } ?>
                <tr>
                    <th colspan="8">
                        Kinerja Tambahan
                    </th>
                </tr>
                <?php $i=1; foreach($allKegiatanTahunanTambahanInduk as $data)  { ?>
                    <tr>
                        <td rowspan="4">
                            <?php if ($data->accessSetPeriksa() or User::isAdmin()) { ?>
                                <?= Html::checkbox('selection[]', false, [
                                    'value' => $data->id,
                                    'class' => 'checkbox-data',
                                ]); ?>
                            <?php } ?>
                        </td>
                        <td rowspan="4" style="text-align: center"><?= $i; ?></td>
                        <td rowspan="4">
                            <?= @$data->kegiatanTahunanAtasan->nama; ?>
                        </td>
                        <td rowspan="4">
                            <?= $data->nama; ?>
                            <?= $data->getLabelIdKegiatanStatus(); ?><br>
                            <i class="fa fa-user"></i> <?= @$data->pegawai->nama; ?><br>
                            <i class="fa fa-tag"></i> <?= @$data->instansiPegawaiSkp->nomor; ?><br>
                            <?= $data->getKeteranganTolak(); ?><br>
                        </td>
                        <td style="text-align: center">Kuantitas</td>
                        <td>
                            <?= $data->indikator_kuantitas; ?>
                        </td>
                        <td style="text-align: center">
                            <?= $data->target_kuantitas; ?> <?= $data->satuan_kuantitas; ?>
                        </td>
                        <td rowspan="4" style="text-align: center">
                            <?php
                            $output = '';

                            if($data->accessSetPeriksa()) {
                                $output .= Html::a('<i class="fa fa-send-o"></i>',['kegiatan-tahunan/set-periksa','id' => $data->id],['data-toggle' => 'tooltip','title' => 'Periksa Kegiatan','onclick' => 'return confirm("Yakin akan mengirim kegiatan untuk diperiksa?");']).' ';
                            }

                            if($data->accessSetSetuju()) {
                                $output .= Html::a('<i class="fa fa-check"></i>',['kegiatan-tahunan/set-setuju','id' => $data->id],['data-toggle' => 'tooltip','title' => 'Setuju Kegiatan','onclick' => 'return confirm("Yakin akan menyetujui kegiatan?");']).' ';
                            }

                            if($data->accessSetTolak()) {
                                $output .= Html::a('<i class="fa fa-remove"></i>',['kegiatan-tahunan/set-tolak','id' => $data->id],['data-toggle' => 'tooltip','title' => 'Tolak Kegiatan','onclick' => 'return confirm("Yakin akan menolak kegiatan?");']).' ';
                            }

                            $output .= Html::a('<i class="fa fa-comment"></i>', ['kegiatan-tahunan/view-catatan', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Lihat Catatan']) . ' ';

                            $output .= Html::a('<i class="fa-edit-square-o"></i>', ['kegiatan-tahunan/update-rpjmd-indikator', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Ubah Renstra']) . ' ';

                            if($data->accessView()) {
                                $output .= Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['kegiatan-tahunan/view-v2', 'id' => $data->id, 'mode' => $searchModel->mode], ['data-toggle' => 'tooltip', 'title' => 'Lihat']) . ' ';
                            }

                            if($data->accessUpdate()) {
                                $output .= Html::a('<i class="glyphicon glyphicon-pencil"></i>',['kegiatan-tahunan/update-v2','id' => $data->id],['data-toggle' => 'tooltip','title' => 'Ubah']).' ';
                            }

                            if($data->accessDelete()) {
                                $output .= Html::a('<i class="glyphicon glyphicon-trash"></i>',['kegiatan-tahunan/delete','id' => $data->id],['data-toggle' => 'tooltip','title' => 'Hapus','onclick' => 'return confirm("Yakin akan menghapus data?");']).' ';
                            }

                            print trim($output);

                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">Kualitas</td>
                        <td>
                            <?= $data->indikator_kualitas; ?>
                        </td>
                        <td style="text-align: center">
                            <?= $data->target_kualitas; ?> <?= $data->satuan_kualitas; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">Waktu</td>
                        <td>
                            <?= $data->indikator_waktu; ?>
                        </td>
                        <td style="text-align: center">
                            <?= $data->target_waktu; ?> <?= $data->satuan_waktu ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">Biaya</td>
                        <td>
                            <?= $data->indikator_biaya; ?>
                        </td>
                        <td style="text-align: center">
                            <?= Helper::rp($data->target_biaya, null); ?> <?= $data->satuan_biaya ?>
                        </td>
                    </tr>
                    <?php $i++; } ?>
            </table>
        </div>
    </div>
</div>
<?php Html::endForm(); ?>

<script>
function toggleCheckbox(element) {
    let arrayCheckbox = document.getElementsByClassName('checkbox-data');
    for(let i=0; i <= arrayCheckbox.length; i++) {
        arrayCheckbox[i].checked = element.checked;
    }
}
</script>
