<?php

namespace app\modules\tukin\models;

use DateTime;
use Yii;
use yii\helpers\ArrayHelper;
use yii2mod\query\ArrayQuery;

/**
 * This is the model class for table "instansi".
 *
 * @property int $id
 * @property int $id_induk
 * @property int $id_instansi_jenis
 * @property string $nama
 * @property string $singkatan
 * @property string $alamat
 * @property string $telepon
 * @property Jabatan[] $manyJabatanKepala
 * @property InstansiJenis $instansiJenis
 * @property Jabatan $manyJabatan
 * @property string $email
 * @property bool $status_koordinator
 *
 * @property mixed $listInstansiFilter
 * @property InstansiSerapanAnggaran[] $manyInstansiSerapanAnggaranTahun
 * @property InstansiSerapanAnggaran[] $manyInstansiSerapanAnggaran
 * @property InstansiKordinatif[] $manyInstansiKordinatif
 * @property Instansi[] $manySubInstansi
 * @property InstansiKordinatif $instansiKordinasiAktif
 */
class Instansi extends \app\models\Instansi
{

    public function getManySubInstansi()
    {
        return $this->hasMany(Instansi::class, ['id_induk' => 'id']);
    }

    public function getInstansiJenis()
    {
        return $this->hasOne(InstansiJenis::class, ['id' => 'id_instansi_jenis']);
    }

    public static function getList()
    {
        $query = static::find();
        if (User::isMapping()) {
            $query->andWhere(['in', 'id', \app\models\User::getListIdInstansi()]);
        }
        return ArrayHelper::map($query->all(), 'id', 'nama');
    }

    public function getListInstansiFilter()
    {
        $ids = array_keys($this->getManySubInstansi()->select('id')->indexBy('id')->asArray()->all());
        $ids[] = $this->id;

        return ArrayHelper::map(static::find()->andWhere(['in', 'id', $ids])->all(), 'id', 'nama');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManyInstansiSerapanAnggaran()
    {
        return $this->hasMany(InstansiSerapanAnggaran::class, ['id_instansi' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManyInstansiSerapanAnggaranTahun()
    {
        return $this->getManyInstansiSerapanAnggaran()
            ->andWhere(['tahun' => User::getTahun()]);
    }

    /**
     * @return InstansiSerapanAnggaran[]
     */
    public function findOrCreateSerapanAnggaranTahun()
    {
        if (count($this->manyInstansiSerapanAnggaranTahun) !== 12) {
            $return = [];
            for ($i = 1; $i <= 12; $i++) {
                $new = new InstansiSerapanAnggaran([
                    'id_instansi' => $this->id,
                    'tahun' => User::getTahun(),
                    'bulan' => $i
                ]);
                $new->save();
                $return[] = $new;
            }
            unset($new);
            return $return;
        }
        return $this->manyInstansiSerapanAnggaranTahun;
    }

    /**
     * @param $bulan
     * @return float|int
     */
    public function getSerapanAnggaranBulan($bulan)
    {
        return (new ArrayQuery(['from' => $this->findOrCreateSerapanAnggaranTahun()]))
            ->andWhere(['bulan' => $bulan])
            ->one()
            ->serapan;
    }

    /**
     * @param $bulan
     * @return float|int
     */
    public function getPotonganSerapanAnggaranBulan($bulan)
    {
        return 100 - $this->getSerapanAnggaranBulan($bulan);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManyInstansiKordinatif()
    {
        return $this->hasMany(InstansiKordinatif::class, ['id_instansi' => 'id']);
    }

    public function getHasKordinatifAktif($bulan = null)
    {
        return $this->getInstansiKordinasiAktif($bulan) !== false;
    }

    /**
     * @param null $bulan
     * @return InstansiKordinatif
     */
    public function getInstansiKordinasiAktif($bulan = null)
    {
        $bulan !== null or $bulan = date('m');
        $date = new DateTime(User::getTahun() . "-$bulan-01");
        $query = new ArrayQuery(['from' => $this->manyInstansiKordinatif]);
        $query->andWhere(['<=', 'tanggal_berlaku_mulai', $date->format('Y-m-01')])
            ->andWhere(['>=', 'tanggal_berlaku_mulai', $date->format('Y-m-01')]);
        return $query->one();
    }
}
