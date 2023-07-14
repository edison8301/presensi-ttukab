<?php

namespace app\components\presensi;

class PresensiHari extends BasePresensi
{
    public $tanggal;

    public $presensiBulan;

    public $shiftKerja;

    /**
     * @var DateTime
     */
    protected $_date;

    /**
     * @return DateTime
     */
    public function getDate()
    {
        if($this->_date !== null) {
            return $this->_date;
        }

        $this->_date = DateTime::createFromFormat('Y-m-d', $this->tanggal);

        return $this->_date;
    }

    public function execute()
    {
        if ($this->tanggal === null) {
            throw new InvalidConfigException('The "tanggal" property must be set.');
        }

        if ($this->presensiBulan === null) {
            throw new InvalidConfigException('The "presensiBulan" property must be set.');
        }

        if ($this->shiftKerja === null) {
            throw new InvalidConfigException('The "shiftKerja" property must be set.');
        }

    }

}
