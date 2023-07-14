<?php

use app\components\Session;
use app\models\User;
use app\modules\kinerja\models\KegiatanStatus;
use yii\helpers\Html;

/* @var $this yii\web\View */

?>


<?php if ($searchModel->mode == 'pegawai') { ?>
    <?php if (Session::isKinerjaPP30Aktif() == true) { ?>
        <?= $this->render('_modal-skp-v2'); ?>
    <?php } ?>

    <?php if (Session::isKinerjaPP30Aktif() == false) { ?>
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Data', null, [
            'class' => 'btn btn-flat btn-success',
            'onclick' => 'alert("Kinerja PP 30/2019 sudah tidak digunakan, silahkan gunakan Permenpan 6 Tahun 2022")'
        ]) ?>
    <?php } ?>
<?php } ?>

<?php //echo Html::a('<i class="fa fa-print"></i> Export Kegiatan', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-primary btn-flat']); ?>

<?php if (User::isAdmin()) {
    echo Html::submitButton('<i class="fa fa-refresh"></i> Ubah Menjadi Konsep', [
        'class' => 'btn btn-info btn-flat',
        'data-confirm' => 'Yakin akan mengubah status kegiatan yang dipilih menjadi konsep?',
        'value' => 'yii1'
    ]);
} else {
    echo Html::submitButton('<i class="fa fa-send-o"></i> Kirim Kinerja Tahunan', [
        'class' => 'btn btn-warning btn-flat',
        'data-confirm' => 'Yakin akan kirim kegiatan yang dipilih?',
        'value' => 'yii1'
    ]);
} ?>
