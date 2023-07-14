<?php

namespace app\modules\kinerja\models;

use Yii;

/**
 * This is the ActiveQuery class for [[KegiatanHarian]].
 *
 * @see KegiatanHarian
 */
class KegiatanHarianQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function aktif($state = true)
    {
        return $this->andWhere(['{{%kegiatan_harian}}.status_hapus' => !$state]);
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
                ->joinWith('kegiatanTahunan.pegawai')
                ->andWhere(['pegawai.kode_pegawai_atasan' => Yii::$app->user->identity->kode_pegawai]);
        }
        return $this;
    }

    /**
     * @inheritdoc
     * @return KegiatanHarian[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return KegiatanHarian|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function setuju()
    {
        return $this->andWhere(['id_kegiatan_status' => KegiatanStatus::SETUJU]);
    }

    public function konsep()
    {
        return $this->andWhere(['id_kegiatan_status' => KegiatanStatus::KONSEP]);
    }

    public function periksa()
    {
        return $this->andWhere(['id_kegiatan_status' => KegiatanStatus::PERIKSA]);
    }

    public function tolak()
    {
        return $this->andWhere(['id_kegiatan_status' => KegiatanStatus::TOLAK]);
    }

    public function filterByTanggal($tanggal)
    {
        return $this->andWhere(['tanggal'=>$tanggal]);
    }

    public function filterByBulan($bulan = null,$tahun = null)
    {
        if($bulan === null) {
            $bulan = date('n');
        }

        if($tahun === null) {
            $tahun = date('Y');
        }

        $date = \DateTime::createFromFormat('Y-n-d',$tahun.'-'.$bulan.'-01');

        return $this->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir',[
            ':tanggal_awal'=>$date->format('Y-m-01'),
            ':tanggal_akhir' => $date->format('Y-m-t')
        ]);
    }

    public function filterByTahun($tahun)
    {

        $date = \DateTime::createFromFormat('Y',$tahun);

        return $this->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir',[
            ':tanggal_awal'=>$date->format('Y-01-01'),
            ':tanggal_akhir' => $date->format('Y-12-31')
        ]);
    }
}
