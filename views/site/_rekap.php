<?php

use app\models\User;
use yii\helpers\Url;
use app\components\Helper;

/* @var $this yii\web\View */
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Rekap Anggaran Tahun <?= Yii::$app->session->get('tahun', date('Y')) ?></h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-lg-3">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <p>Anggaran Induk</p>
                        <h3>
                            Rp
                            <?php
                            if (User::isAdmin()) {
                                print Helper::rp(Anggaran::getSumPaguThisYear(),0);
                            } else {
                                print Helper::rp(Pagu::getJumlah(Yii::$app->session->get('tahun',date('Y')),Yii::$app->user->identity->id_opd),0);
                            } ?>
                        </h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money"></i>
                    </div>
                    <a href="<?= Url::to(['anggaran/index']) ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <p>Realisasi</p>
                        <h3>
                            Rp
                            <?php
                            if (User::isAdmin()) {
                                print Helper::rp(Anggaran::getSumRealisasiThisYear(),0);
                            } else {
                                print Helper::rp(Realisasi::getJumlahSampaiBulan(Yii::$app->session->get('tahun',date('Y')),date('m'),Yii::$app->user->identity->id_opd));
                            } ?>
                        </h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <a href="<?= Url::to(['anggaran/index']) ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="small-box bg-green">
                    <div class="inner">
                        <p>Sisa</p>
                        <h3>
                            Rp
                            <?php
                            if (User::isAdmin()) {
                                print Helper::rp(Anggaran::getSelisihRealisasiPagu(),0);
                            } else {
                                print Realisasi::getPersenPerPaguSampaiBulan(Yii::$app->session->get('tahun',date('Y')),date('m'),Yii::$app->user->identity->id_opd).'%';
                            } ?>
                        </h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-book"></i>
                    </div>
                    <a href="<?= Url::to(['anggaran/index']) ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="small-box bg-red">
                    <div class="inner">
                        <p>Persen Sisa</p>
                        <h3>
                            <?php
                            if (User::isAdmin()) {
                                print Anggaran::getSelisihRealisasiPaguPersen(Yii::$app->session->get('tahun',date('Y')),date('m'));
                            } else {
                                print Realisasi::getPersenPerPaguSampaiBulan(Yii::$app->session->get('tahun',date('Y')),date('m'),Yii::$app->user->identity->id_opd).'%';
                            } ?>
                        </h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-book"></i>
                    </div>
                    <a href="<?= Url::to(['anggaran/index']) ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
