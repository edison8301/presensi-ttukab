<?php

use yii\helpers\Html;
use app\models\User;
/* @var $this yii\web\View */

$this->title = 'Dashboard Tahun ' . Yii::$app->session->get('tahun', date('Y'));

echo $this->render('_filter', ['filter' => $filter]);

if (User::getIdInstansi() != null) 
{
    echo $this->render('_pegawai', ['filter' => $filter]);
}

if (User::getIdInstansi() == null) 
{
    echo $this->render('_instansi', ['filter' => $filter]);
}
