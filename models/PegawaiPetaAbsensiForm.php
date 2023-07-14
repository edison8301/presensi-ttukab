<?php

namespace app\models;

use app\components\Helper;
use app\components\Session;
use yii\base\Model;

class PegawaiPetaAbsensiForm extends Model {

    public $id_instansi;

    public $id_peta;

    public $tanggal;

    public $bulan;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_instansi', 'id_peta', 'tanggal', 'bulan'], 'safe'],
        ];
    }

    public function setAttribute()
    {
        if ($this->id_peta == null) {
            $queryPeta = \app\models\Peta::find();
            $queryPeta->andWhere('id_instansi is null AND id_pegawai is null');

            $peta = $queryPeta->one();

            $this->id_peta = $peta->id;
        }

        if ($this->bulan == null) {
            $this->bulan = date('n');
        }

        if ($this->tanggal == null) {
            $this->tanggal = date('Y-m-d');
        }
    }

    public function getPeta()
    {
        return Peta::findOne($this->id_peta);
    }

    public function getInstansi()
    {
        return Instansi::findOne($this->id_instansi);
    }

    public function getDate()
    {
        $date = \Datetime::createFromFormat('Y-n-d', Session::getTahun(). '-' . $this->bulan. '-01');
        return $date;
    }

    public function getBulanLengkapTahun()
    {
        return Helper::getBulanLengkap($this->bulan) . ' ' . Session::getTahun();
    }

}

?>