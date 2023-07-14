<?php

namespace app\models;

use yii\base\Model;
use yii2mod\query\ArrayQuery;
use yii\helpers\Html;

class PegawaiPetaAbsensiReport extends Model {

    public $id_pegawai;

    public $id_instansi;

    public $id_peta;

    public $bulan;

    public $tahun;

    private $_pegawai;

    private $_instansi;

    private $_peta;

    private $_date;

    private $_arrayCheckinout;

    public function getPegawai()
    {
        if ($this->_pegawai !== null) {
            return $this->_pegawai;
        }
        
        $pegawai = Pegawai::findOne($this->id_pegawai);

        $this->_pegawai = $pegawai;
        return $this->_pegawai; 
    }

    public function getInstansi()
    {
        if ($this->_instansi !== null) {
            return $this->_instansi;
        }
        
        $instansi = Instansi::findOne($this->id_instansi);

        $this->_instansi = $instansi;
        return $this->_instansi; 
    }

    public function getPeta()
    {
        if ($this->_peta !== null) {
            return $this->_peta;
        }
        
        $peta = Peta::findOne($this->id_peta);

        $this->_peta = $peta;
        return $this->_peta; 
    }

    public function getDate()
    {
        if ($this->_date !== null) {
            return $this->_date;
        }
        
        $date = \DateTime::createFromFormat('Y-n-d', $this->tahun . '-' . $this->bulan. '-01');

        $this->_date = $date;

        return $this->_date; 
    }

    public function getArrayCheckinout()
    {
        if ($this->_arrayCheckinout !== null) {
            return $this->_arrayCheckinout;
        }

        $checktime_awal = $this->date->format('Y-m-01 00:00:00');
        $checktime_akhir = $this->date->format('Y-m-t 23:59:59');
    
        $query = $this->pegawai->getManyCheckinout();
        $query->andWhere('checktime >= :checktime_awal AND checktime <= :checktime_akhir', [
            ':checktime_awal' => $checktime_awal,
            ':checktime_akhir' => $checktime_akhir,
        ]);

        $this->_arrayCheckinout = $query->all();

        return $this->_arrayCheckinout; 
    }

    public function getStringCheckinout(array $params = [])
    {
        $tanggal = $params['tanggal'];

        $datetimeParams = \DateTime::createFromFormat('Y-m-d', $tanggal);

        $query = new ArrayQuery();
        $query->from($this->getArrayCheckinout());
        $query->andWhere(['>=', 'checktime', $datetimeParams->format('Y-m-d 00:00:00')]);
        $query->andWhere(['<=', 'checktime', $datetimeParams->format('Y-m-d 23:59:59')]);

        $string = '';

        foreach ($query->all() as $checkinout) {

            $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $checkinout->checktime);

            $x = floatval($checkinout->latitude) - floatval($this->peta->latitude);
            $y = floatval($checkinout->longitude) - floatval($this->peta->longitude);

            $r = sqrt($x*$x + $y*$y);

            $batas = $this->peta->jarak * 0.00000909;

            if ($datetime !== false AND $r <= $batas) {

                $time = $datetime->format('H:i:s');

                if (@$params['link'] !== false) {
                    $time = Html::a($datetime->format('H:i:s'), [
                        '/iclock/checkinout/view-peta',
                        'id' => $checkinout->id,
                    ]);
                }

                $string .= $time  . '<br/>';
            }

        }

        return rtrim($string, ", ");
    }

    public function getCountCheckinout(array $params = [])
    {
        $query = new ArrayQuery();
        $query->from($this->getArrayCheckinout());

        if (@$params['tanggal'] == null) {
            $query->andWhere(['>=', 'checktime', $this->date->format('Y-m-01 00:00:00')]);
            $query->andWhere(['<=', 'checktime', $this->date->format('Y-m-t 23:59:59')]);
        }

        if (@$params['tanggal'] != null) {
            $tanggal = $params['tanggal'];
            $datetime = \DateTime::createFromFormat('Y-m-d', $tanggal);

            $query->andWhere(['>=', 'checktime', $datetime->format('Y-m-d 00:00:00')]);
            $query->andWhere(['<=', 'checktime', $datetime->format('Y-m-d 23:59:59')]);
        }

        $jumlah = '';

        foreach ($query->all() as $checkinout) {

            $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $checkinout->checktime);

            $x = floatval($checkinout->latitude) - floatval($this->peta->latitude);
            $y = floatval($checkinout->longitude) - floatval($this->peta->longitude);

            $r = sqrt($x*$x + $y*$y);

            $batas = $this->peta->jarak * 0.00000909;

            if ($datetime !== false AND $r <= $batas) {
                $jumlah++;
            }

        }

        return $jumlah;
    }

}

?>