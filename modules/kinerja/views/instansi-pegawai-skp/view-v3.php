<?php

use app\components\Helper;
use app\components\Session;
use app\modules\kinerja\models\KegiatanRhkJenis;
use app\modules\kinerja\models\SkpPerilaku;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiSkp */
/* @var $allKegiatanRhkUtama \app\modules\kinerja\models\KegiatanRhk[] */
/* @var $allKegiatanRhkTambahan \app\modules\kinerja\models\KegiatanRhk[] */
/* @var $allSkpPerilakuJenis \app\modules\kinerja\models\SkpPerilakuJenis[] */
/* @var $debug bool */

$this->title = "Detail SKP Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Daftar SKP', 'url' => ['index-v3']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="instansi-pegawai-skp-view box box-primary">

    <div class="box-header with-border">
        <?php if ($model->canCreateKegiatanRhk()) { ?>
            <?= Html::a('<i class="fa fa-plus"></i> Tambah RHK', [
                '/kinerja/kegiatan-rhk/create',
                'id_instansi_pegawai_skp' => $model->id,
            ], ['class' => 'btn btn-success btn-flat']) ?>
        <?php } ?>

        <?= Html::a('<i class="fa fa-file-pdf-o"></i> Cetak SKP', [
            '/kinerja/instansi-pegawai-skp/export-pdf-skp-v3',
            'id' => $model->id,
        ], ['class' => 'btn btn-success btn-flat', 'target' => '_blank']) ?>

        <?php if ($model->isJpt() == true) { ?>
            <?= Html::a('<i class="fa fa-list"></i> Manual Indikator Kinerja', [
                '/kinerja/kegiatan-tahunan/index-mik',
                'id_instansi_pegawai_skp' => $model->id,
            ], ['class' => 'btn btn-success btn-flat']); ?>
        <?php } ?>
    </div>

    <div class="box-body">

        <b style="text-transform: uppercase">
            PERIODE PENILAIAN: <?= Helper::getTanggalBulan($model->tanggal_mulai) ?> SD <?= Helper::getTanggal($model->tanggal_selesai) ?>
        </b>
        <?= $this->render('_table-pegawai-v3', [
            'model' => $model,
        ]) ?>

        <br/>
        <b>HASIL KERJA</b>

        <?php if ($model->isJpt() === false) { ?>
            <?= $this->render('_table-kegiatan-rhk-non-jpt', [
                'allKegiatanRhkUtama' => $allKegiatanRhkUtama,
                'allKegiatanRhkTambahan' => $allKegiatanRhkTambahan,
            ]) ?>
        <?php } ?>

        <?php if ($model->isJpt() === true) { ?>
            <?= $this->render('_table-kegiatan-rhk-jpt', [
                'allKegiatanRhkUtama' => $allKegiatanRhkUtama,
                'allKegiatanRhkTambahan' => $allKegiatanRhkTambahan,
            ]) ?>
        <?php } ?>

        <b>PERILAKU KERJA</b>

        <table class="table table-bordered">
            <?php $no=1; foreach ($allSkpPerilakuJenis as $skpPerilakuJenis) { ?>
                <?php $skpPerilaku = SkpPerilaku::findOrCreate([
                    'id_skp' => $model->id,
                    'id_skp_perilaku_jenis' => $skpPerilakuJenis->id,
                ]) ?>
                <tr>
                    <td style="text-align: center; width: 50px;" rowspan="2">
                        <?= $no++ ?>
                    </td>
                    <td colspan="2">
                        <?= $skpPerilakuJenis->nama ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php $array = explode("\n", $skpPerilakuJenis->uraian); ?>
                        <ol style="margin-bottom: 0; padding-left: 17px;">
                            <?php foreach ($array as $value) { ?>
                                <li><?= $value ?></li>
                            <?php } ?>
                        </ol>
                    </td>
                    <td style="width: 600px;">
                        Ekspektasi Khusus Pimpinan:
                        <?= $skpPerilaku->getLinkUpdateIcon() ?><br/>

                        <?php $arrayEkspektasi = explode("\n", $skpPerilaku->ekspektasi); ?>
                        <?php if (count($arrayEkspektasi) <= 1) { ?>
                            <?= $skpPerilaku->ekspektasi ?>
                        <?php } ?>
                        <?php if (count($arrayEkspektasi) > 1) { ?>
                            <ol style="margin-bottom: 0; padding-left: 17px;">
                                <?php foreach ($arrayEkspektasi as $ekspektasi) { ?>
                                    <li><?= $ekspektasi ?></li>
                                <?php } ?>
                            </ol>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>

    </div>

</div>

<?php if ($model->isJpt() == false) { ?>
    <div class="box box-primary">

        <div class="box-header with-border">
            <h3 class="box-title">Lampiran SKP</h3>
        </div>

        <div class="box-body">
            <?= $this->render('_table-lampiran', [
                'model' => $model,
            ]) ?>
        </div>

    </div>
<?php } ?>

<?php if (Session::getIdPegawai() != @$model->instansiPegawai->id_pegawai) { ?>
    <?= $this->render('_box-skp-nilai',[
        'model' => $model
    ]); ?>
<?php } ?>
