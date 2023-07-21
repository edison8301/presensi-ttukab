<?php


namespace app\modules\api2\models;

use app\components\Helper;

class KegiatanKetidakhadiran extends \app\models\KegiatanKetidakhadiran
{
    public static function getQueryByParams($params=[])
    {
        $query = static::find();

        if (@$params['nip'] !== null) {
            $query->andWhere(['nip' => $params['nip']]);
        }

        $query->orderBy(['checktime' => SORT_DESC]);

        return $query;
    }

    public function restJson()
    {
        return [
            'id' => strval($this->id),
            'nip' => strval($this->nip),
            'id_kegiatan_ketidakhadiran_jenis' => strval($this->id_kegiatan_ketidakhadiran_jenis),
            'penjelasan' => strval($this->penjelasan),
            'checktime' => strval($this->checktime),
            'foto_pendukung' => strval($this->foto_pendukung),
            'url_foto_pendukung' => null
        ];
    }

    public static function restApiIndex($params=[])
    {
        $query = static::getQueryByParams($params);

        if (@$params['limit'] != null) {
            $query->limit(@$params['limit']);
        }

        $output = [];
        foreach ($query->all() as $data) {
            $output[] = $data->restJson();
        }

        return $output;
    }

}
