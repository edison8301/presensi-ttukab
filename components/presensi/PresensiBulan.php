<?php

namespace app\components\presensi;

use app\models\Pegawai;
use yii\base\InvalidConfigException;

/**
 * @property Pegawai $pegawai
 */
class PresensiBulan extends BasePresensi
{
    /**
     * @var DateTime
     */
    protected $_date;

    /**
     * @var int
     */
    public $tahun;

    /**
     * @var int
     */
    public $bulan;

    /**
     * @var Pegawai
     */
    public $pegawai;

    /**
     * @var PresensiHari[]
     */
    private $_arrayPresensiHari;

    /**
     * @throws InvalidConfigException
     */
    public function execute()
    {
        if ($this->validBulan() == false) {
            throw new InvalidConfigException('Bulan harus diantara 1 - 12');
        }

        if ($this->pegawai === null) {
            throw new InvalidConfigException('The "pegawai" property must be set.');
        }

        if ($this->tahun === null) {
            throw new InvalidConfigException('The "tahun" property must be set.');
        }

        $this->setArrayPresensiHari();
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        if($this->_date !== null) {
            return $this->_date;
        }

        $this->_date = DateTime::createFromFormat('Y-n', $this->tahun.'-'.$this->bulan);

        return $this->_date;
    }

    /**
     * @return bool
     */
    protected function validBulan(): bool
    {
        return in_array($this->bulan, range(1, 12));
    }

    /**
     * @return void
     */
    public function setArrayPresensiHari()
    {
        $date = $this->getDate();
        $end = $date->format('t');

        $start = 1;

        for ($i = $start; $i <= $end; $i++) {

            $hari = sprintf('%02d', $i);
            $tanggal =  $date->format('Y-m').'-'.$hari;

            $shiftKerja = $this->pegawai->findShiftKerja(['tanggal'=>$tanggal]);

            $presensiHari = new PresensiHari([
                'tanggal' => $tanggal,
                'presensiBulan' => $this,
                'shiftKerja' => $shiftKerja
            ]);

            $presensiHari->execute();
            $this->_arrayPresensiHari["$i"] = $presensiHari;
        }
        unset($dateI, $date, $end);
    }

    /**
     * @return PresensiHari[]
     */
    public function getArrayPresensiHari(): array
    {
        if($this->_arrayPresensiHari === null) {
            $this->setArrayPresensiHari();
        }

        return $this->_arrayPresensiHari;
    }
}
