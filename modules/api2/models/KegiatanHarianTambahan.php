<?php


namespace app\modules\api2\models;


class KegiatanHarianTambahan extends \app\modules\kinerja\models\KegiatanHarianTambahan
{
    public static function getQueryByParams($params=[])
    {
        $query = static::find();

        return $query;
    }

    public function restJson()
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
        ];
    }

    public static function restList($params=[])
    {
        $query = static::getQueryByParams($params);

        $output = [];
        foreach ($query->all() as $kegiatanTambahan) {
            $output[] = $kegiatanTambahan->restJson();
        }

        return $output;
    }
}
