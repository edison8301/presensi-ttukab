<?php

use app\models\Instansi;
use app\models\User;

/* @var $this yii\web\View */
/* @var $allOpd Instansi[] */


$this->title = 'Aplikasi E-Budgeting - Kabupaten Puncak';

?>
<?= $this->render('_rekap',['model'=>$model]) ?>

<?php if (User::isAdmin()) {
    print $this->render('_tabel_opd',['allOpd'=>$allOpd]);
} ?>

