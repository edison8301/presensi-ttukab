<?php

$this->title = "Rekap Absensi";

?>


<?= Yii::$app->controller->renderPartial('_filter'); ?>

<?php if(\app\models\User::getKodeInstansi()==null) { ?>
<?= Yii::$app->controller->renderPartial('_instansi'); ?>
<?php } ?>

<?php if(\app\models\User::getKodeInstansi()!=null) { ?>
<?= Yii::$app->controller->renderPartial('_pegawai'); ?>
<?php } ?>