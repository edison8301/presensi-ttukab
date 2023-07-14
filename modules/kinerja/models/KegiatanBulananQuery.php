<?php

namespace app\modules\kinerja\models;

use Yii;
use app\models\User;

/**
 * This is the ActiveQuery class for [[KegiatanBulanan]].
 *
 * @see KegiatanBulanan
 */
class KegiatanBulananQuery extends \yii\db\ActiveQuery
{
    public static function find($subordinat = false)
    {
        return parent::find();
    }

    public function byPegawaiSession()
    {
        if (User::isPegawai()) {
            return $this
                ->joinWith('kegiatanTahunan')
                ->andWhere(['kegiatan_tahunan.id_pegawai' => Yii::$app->session->get('id_pegawai')]);
        }
        return $this;
    }

    public function subordinat()
    {
        if (User::isPegawai()) {
            return $this
                ->joinWith(['kegiatanTahunan', 'kegiatanTahunan.pegawai'])
                ->andWhere(['pegawai.kode_pegawai_atasan' => Yii::$app->user->identity->kode_pegawai]);
        }
        return $this;
    }

    public function bulanIni()
    {
        return $this->andWhere(['bulan' => date('m')]);
    }

    public function bulan($bulan)
    {
        return $this->andWhere(['bulan' => $bulan]);
    }

    /**
     * @inheritdoc
     * @return KegiatanBulanan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return KegiatanBulanan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
