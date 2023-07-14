<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11/02/2019
 * Time: 21:03
 */

use app\models\User;
use app\modules\kinerja\models\KegiatanStatus;
use yii\helpers\Html;

?>


<?php if ($searchModel->mode == 'pegawai') { ?>
    <?php /* Html::a('<i class="fa fa-plus"></i> Tambah Kegiatan Utama', [
        'create',
        'id_pegawai'=>User::getIdPegawai(),
        'nomor_skp'=>$searchModel->nomor_skp
    ], ['class' => 'btn btn-success btn-flat']); */ ?>
    <?= $this->render('_modal-skp'); ?>
<?php } ?>

<?php //echo Html::a('<i class="fa fa-print"></i> Export Kegiatan', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-primary btn-flat']); ?>

<?php if (User::isAdmin()) {
    echo Html::submitButton('<i class="fa fa-refresh"></i> Ubah Menjadi Konsep', [
        'class' => 'btn btn-info btn-flat',
        'data-confirm' => 'Yakin akan mengubah status kegiatan yang dipilih menjadi konsep?',
        'value' => 'yii1'
    ]);
} else {
    echo Html::submitButton('<i class="fa fa-send-o"></i> Kirim Kegiatan', [
        'class' => 'btn btn-warning btn-flat',
        'data-confirm' => 'Yakin akan kirim kegiatan yang dipilih?',
        'value' => 'yii1'
    ]);
} ?>
