<?php

use app\components\Helper;
use app\components\Session;
use app\models\Instansi;
use app\models\Pegawai;
use app\models\User;
use app\modules\kinerja\models\KegiatanBulanan;
use app\modules\kinerja\models\KegiatanHarian;
use app\modules\kinerja\models\KegiatanStatus;
use app\modules\kinerja\models\KegiatanTahunan;
use app\modules\kinerja\models\KegiatanTahunanSearch;
use kartik\form\ActiveForm;
use yii\bootstrap\Modal;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/* @var $this \yii\web\View */
/* @var $instansi Instansi */
/* @var $id_modal string */
/* @var $title string */
/* @var $id_kegiatan_harian_jenis int */
/* @var $tanggal string */
/* @var $bulan int */

$tanggal = date('Y-m-d');

$datetimeSession = DateTime::createFromFormat('Y-n-d',Session::getTahun().'-'.$bulan.'-01');

if($datetimeSession->format('Y-m') < date('Y-m')) {
    $tanggal = $datetimeSession->format('Y-m-t');
}

$modalTitle = "N/A";
$buttonTitle = "N/A";

if($id_kegiatan_harian_jenis == 1) {
    $modalTitle = 'Kinerja Harian Utama Bulan '.Helper::getBulanLengkap($bulan).' '.Session::getTahun();
    $buttonTitle = "<i class='fa fa-plus'></i> Kinerja Utama";
}

if($id_kegiatan_harian_jenis == 2) {
    $modalTitle = 'Kinerja Harian Tambahan Bulan '.Helper::getBulanLengkap($bulan).' '.Session::getTahun();
    $buttonTitle = "<i class='fa fa-plus'></i> Kinerja Tambahan";
}

$datetime = DateTime::createFromFormat('Y-n-d', Session::getTahun().'-'.$bulan.'-01');

$query = KegiatanTahunan::find();
$query->andWhere([
    'kegiatan_tahunan.id_pegawai' => Session::getIdPegawai(),
    'kegiatan_tahunan.id_kegiatan_status' => KegiatanStatus::SETUJU,
    'kegiatan_tahunan.id_kegiatan_tahunan_jenis' => $id_kegiatan_harian_jenis,
    'kegiatan_tahunan.id_kegiatan_tahunan_versi' => 2
]);
$query->joinWith(['manyKegiatanBulanan', 'instansiPegawai']);
$query->andWhere(['kegiatan_bulanan.bulan'=>$datetime->format('n')]);
$query->andWhere('kegiatan_bulanan.target IS NOT NULL');
$query->andWhere(['instansi_pegawai.status_plt' => 0]);

$allKegiatanTahunan = $query->all();

$query2 = KegiatanTahunan::find();
$query2->andWhere([
    'kegiatan_tahunan.id_pegawai' => Session::getIdPegawai(),
    'kegiatan_tahunan.id_kegiatan_status' => KegiatanStatus::SETUJU,
    'kegiatan_tahunan.id_kegiatan_tahunan_versi' => 2
]);
$query2->joinWith(['manyKegiatanBulanan', 'instansiPegawai']);
$query2->andWhere(['kegiatan_bulanan.bulan'=>$datetime->format('n')]);
$query2->andWhere('kegiatan_bulanan.target IS NOT NULL');
$query2->andWhere(['instansi_pegawai.status_plt' => 1]);

$allKegiatanTahunanPlt = $query2->all();

?>

<?php Modal::begin([
    'header' => "<h4>$modalTitle</h4>",
    'id' => $id_modal,
    'size' => Modal::SIZE_LARGE,
    'toggleButton' => [
        'tag'=>'a',
        'label' => $buttonTitle,
        'class'=>'btn btn-primary btn-flat'
    ],
]); ?>

<?php Pjax::begin(['timeout' => 10000]); ?>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th style="text-align:center; width:10px;">No</th>
            <th style="text-align:center;">Kinerja Tahunan</th>
            <th style="width:50px;"></th>
        </tr>
    </thead>
    <?php $no=1; foreach ($allKegiatanTahunan as $kegiatanTahunan) { ?>

        <?= $this->render('_tr-modal-kinerja-tahunan', [
            'kegiatanTahunan' => $kegiatanTahunan,
            'bulan' => $bulan,
            'tanggal' => $tanggal,
            'id_kegiatan_harian_jenis' => @$id_kegiatan_harian_jenis,
            'no' => $no++,
            'padding' => 10,
        ]) ?>
    <?php } ?>

    <?php if ($id_kegiatan_harian_jenis == 2) {
        foreach ($allKegiatanTahunanPlt as $kegiatanTahunan) {
            echo $this->render('_tr-modal-kinerja-tahunan', [
                'kegiatanTahunan' => $kegiatanTahunan,
                'bulan' => $bulan,
                'tanggal' => $tanggal,
                'id_kegiatan_harian_jenis' => @$id_kegiatan_harian_jenis,
                'no' => $no++,
                'padding' => 10,
                'status_plt' => 1,
            ]);
        }
    } ?>
</table>

<?php Pjax::end(); ?>
<?php Modal::end(); ?>
