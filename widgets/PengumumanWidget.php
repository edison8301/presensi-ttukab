<?php

namespace app\widgets;

use Yii;
use app\components\Helper;
use app\models\JadwalPenginputan;
use app\models\User;
use yii\base\Widget;
use yii\bootstrap\Alert;
use yii\helpers\Html;

class PengumumanWidget extends Widget
{
    /**
     * @var \app\models\Pengumuman
     */
    public $model;

    public $demo = false;

    public $demoAs = 'pegawai';

    protected static $_template = [
        'pegawai' => 'getNamaPegawai',
        'instansi' => 'getNamaInstansi',
        'pegawai/instansi' => 'getNamaPegawaiOrInstansi',
    ];

    public function init()
    {
    }

    public function run()
    {
        $this->renderAlert();
    }

    protected function initAlert()
    {
        echo Html::beginTag('div', ['class' => 'row']);
        echo Html::beginTag('div', ['class' => 'col-sm-12']);
        echo Html::beginTag('div', ['class' => 'alert alert-info fade-in']);
    }

    protected function endAlert()
    {
        echo Html::endTag('div');
        echo Html::endTag('div');
        echo Html::endTag('div');
    }

    protected function renderHeader()
    {
        echo Html::tag('i', null, ['class' => 'fa fa-warning']);
        echo " " . $this->model->judul;
    }

    protected function renderContent()
    {
        echo Html::beginTag('div', ['class' => 'marquee']);
        echo Html::beginTag('marquee');
            echo Html::tag('p', $this->getText(), ['style' => 'font-family: Arial; font-weight: bold; font-size: 13pt']);
        echo Html::endTag('marquee');
        echo Html::endTag('div');
    }

    protected function renderAlert()
    {
        $this->initAlert();
        $this->renderHeader();
        $this->renderContent();
        $this->endAlert();
    }

    protected function getText()
    {
        $text = $this->model->teks;
        foreach (static::$_template as $key => $value) {
            $text = str_ireplace('{' . $key . '}', call_user_func([get_called_class(), $value], $this->demo), $text);
        }
        return $text;
    }

    protected static function getNamaPegawai($demo)
    {
        if ($demo) {
            return $demo === 'pegawai' ? 'BAMBANG PURWANTO' : null;
        }
        if (User::isAdmin()) {
            return ucwords(Yii::$app->user->identity->username);
        }
        return User::isPegawai() ? Yii::$app->user->identity->pegawai->nama : null;
    }


    protected static function getNamaInstansi($demo)
    {
        if ($demo) {
            return $demo === 'instansi' ? 'BADAN KEPEGAWAIAN DAERAH' : null;
        }
        return User::isInstansi() ? Yii::$app->user->identity->instansi->nama : null;
    }

    protected static function getNamaPegawaiOrInstansi($demo)
    {
        return ($return = static::getNamaPegawai($demo)) !== null ? $return : static::getNamaInstansi($demo);
    }
}
