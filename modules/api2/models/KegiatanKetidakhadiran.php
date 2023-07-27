<?php


namespace app\modules\api2\models;

use app\components\Helper;
use Yii;
use yii\helpers\Url;

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
            'jenis' => strval(@$this->kegiatanKetidakhadiranJenis->nama),
            'penjelasan' => strval($this->penjelasan),
            'checktime' => strval($this->checktime),
            'waktu_cek' => $this->getWaktuCek(),
            'foto_pendukung' => strval($this->foto_pendukung),
            'url_foto_pendukung' => $this->getUrlFotoPendukung(),
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

    public function getUrlFotoPendukung()
    {
        $url = Url::base(true);
        $url .= '/uploads/foto-pendukung/';
        $url .= $this->foto_pendukung;

        $headers = get_headers($url, 1);

        if (str_contains($headers[0], '200') == false) {
            return Url::base(true) . '/images/image-not-found.png';
        }

        return $url;
    }

    public function getWaktuCek()
    {
        if ($this->checktime == null) {
            return null;
        }

        $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->checktime);
        $string = Helper::getTanggal($datetime->format('Y-m-d'));
        $string .= ', ' . $datetime->format('H:i:s');

        return $string;
    }

}
