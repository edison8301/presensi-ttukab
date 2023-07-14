<?php

/* @var $this \yii\web\View */

/* @var $directoryAsset false|string */

use app\components\Session;
use app\models\User;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $directoryAsset false|string */
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?= Html::img('images/logo.png', ['class' => 'img', 'alt' => 'User Image']); ?>
            </div>
            <div class="pull-left info">
                <p><?= User::getUsernameBySession() ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <?php if (User::isAdmin()) { ?>
            <?= $this->render('//layouts/_menu-admin') ?>
        <?php } ?>

        <?php if (User::isInstansi()) { ?>
            <?= $this->render('//layouts/_menu-instansi') ?>
        <?php } ?>

        <?php if (User::isPegawai()) { ?>
            <?= $this->render('//layouts/_menu-pegawai') ?>
        <?php } ?>

        <?php if (User::isVerifikator()) { ?>
            <?= $this->render('//layouts/_menu-verifikator') ?>
        <?php } ?>

        <?php if (User::isGrup()) { ?>
            <?= $this->render('//layouts/_menu-grup') ?>
        <?php } ?>

        <?php if (User::isMapping()) { ?>
            <?= $this->render('//layouts/_menu-mapping') ?>
        <?php } ?>

        <?php if (User::isAdminInstansi()) { ?>
            <?= $this->render('//layouts/_menu-admin-instansi') ?>
        <?php } ?>

        <?php if (User::isAdminIki()) { ?>
            <?= $this->render('//layouts/_menu-admin-iki') ?>
        <?php } ?>

        <?php if (User::isOperatorAbsen()) { ?>
            <?= $this->render('//layouts/_menu-operator-absen') ?>
        <?php } ?>

        <?php if (Session::isOperatorStruktur()) { ?>
            <?= $this->render('//layouts/_menu-operator-struktur') ?>
        <?php } ?>

        <?php if (Session::isPemeriksaAbsensi()) { ?>
            <?= $this->render('//layouts/_menu-pemeriksa-absensi') ?>
        <?php } ?>

        <?php if (Session::isPemeriksaKinerja()) { ?>
            <?= $this->render('//layouts/_menu-pemeriksa-kinerja') ?>
        <?php } ?>

        <?php if (Session::isPemeriksaIki()) { ?>
            <?= $this->render('//layouts/_menu-pemeriksa-iki') ?>
        <?php } ?>

        <?php if (Session::isMappingRpjmd()) { ?>
            <?= $this->render('//layouts/_menu-mapping-rpjmd') ?>
        <?php } ?>

    </section>

</aside>
