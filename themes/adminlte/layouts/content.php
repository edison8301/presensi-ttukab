<?php
use app\models\Pengumuman;
use app\models\User;
use app\widgets\PengumumanWidget;
use dmstr\widgets\Alert;
use yii\widgets\Breadcrumbs;

?>
<div class="content-wrapper">
    <section class="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <h1>
                <?php
                if ($this->title !== null) {
                    echo \yii\helpers\Html::encode($this->title);
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } ?>
            </h1>
        <?php } ?>

        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>

    <section class="content">
        <?php if(\app\components\Config::pengumumanAktif()==true) { ?>
            <?php foreach (Pengumuman::getAllPengumumanAktif() as $pengumuman) { ?>
                <?= PengumumanWidget::widget(['model' => $pengumuman]); ?>
            <?php } ?>
        <?php } ?>

        <?= Alert::widget() ?>
        <?= $content ?>
    </section>

</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.0
    </div>
    <strong>Copyright &copy; <?= date('Y'); ?> <a href="#" target="_blank">Kabupaten Timor Tengah Utara</a>.</strong> Seluruh Hak Cipta Dilindungi
</footer>
