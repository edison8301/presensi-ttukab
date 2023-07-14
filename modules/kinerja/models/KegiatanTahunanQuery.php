<?php

namespace app\modules\kinerja\models;

use Yii;

/**
 * This is the ActiveQuery class for [[KegiatanTahunan]].
 *
 * @see KegiatanTahunan
 */
class KegiatanTahunanQuery extends \yii\db\ActiveQuery
{
    public function aktif()
    {
        return $this->andWhere(['kegiatan_tahunan.status_hapus' => 0]);
    }

    public function tahun($tahun = null)
    {
        if($tahun==null) {
            $tahun = \app\models\User::getTahun();
        }

        return $this->andWhere(['kegiatan_tahunan.tahun' => $tahun]);
    }

    public function byPegawaiSession()
    {
        if (User::isPegawai()) {
            return $this->andWhere(['id_pegawai' => Yii::$app->session->get('id_pegawai')]);
        }
        return $this;
    }

    public function subordinat()
    {
        if (User::isPegawai()) {
            return $this
                ->joinWith('pegawai')
                ->andWhere(['pegawai.kode_pegawai_atasan' => Yii::$app->user->identity->kode_pegawai]);
        }
        return $this;
    }

    public function induk()
    {
        return $this->andWhere(['kegiatan_tahunan.id_induk' => null]);
    }

    public function setuju()
    {
        return $this->andWhere(['kegiatan_tahunan.id_kegiatan_status' => KegiatanStatus::SETUJU]);
    }

    public function konsep()
    {
        return $this->andWhere(['kegiatan_tahunan.id_kegiatan_status' => KegiatanStatus::KONSEP]);
    }

    public function periksa()
    {
        return $this->andWhere(['kegiatan_tahunan.id_kegiatan_status' => KegiatanStatus::PERIKSA]);
    }

    public function tolak()
    {
        return $this->andWhere(['kegiatan_tahunan.id_kegiatan_status' => KegiatanStatus::TOLAK]);
    }

    /**
     * @inheritdoc
     * @return KegiatanTahunan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return KegiatanTahunan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
