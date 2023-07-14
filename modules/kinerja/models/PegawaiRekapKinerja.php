<?php

namespace app\modules\kinerja\models;

use app\models\Instansi;
use app\models\Pegawai;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "pegawai_rekap_kinerja".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $id_instansi
 * @property int $bulan
 * @property double $potongan_skp
 * @property double $potongan_ckhp
 * @property double $potongan_total
 * @property string $waktu_buat
 * @property string $waktu_update
 * @property int $tahun
 * @property float $progres
 *
 * @property Instansi $instansi
 * @property Pegawai $pegawai
 * @property KegiatanTahunan[] $manyKegiatanTahunan
 * @property KegiatanBulanan[] $manyKegiatanBulanan
 */
class PegawaiRekapKinerja extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pegawai_rekap_kinerja';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'tahun', 'bulan'], 'required'],
            [['id_pegawai', 'id_instansi'], 'integer'],
            [['potongan_skp', 'potongan_ckhp', 'potongan_total', 'tahun', 'progres'], 'number'],
            [['waktu_buat', 'waktu_update'], 'safe'],
            [['bulan'], 'integer'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'waktu_buat',
                'updatedAtAttribute' => 'waktu_update',
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Pegawai',
            'id_instansi' => 'Instansi',
            'bulan' => 'Bulan',
            'potongan_skp' => 'Potongan Skp',
            'potongan_ckhp' => 'Potongan Ckhp',
            'potongan_total' => 'Potongan Total',
            'waktu_buat' => 'Waktu Buat',
            'waktu_update' => 'Waktu Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManyKegiatanTahunan()
    {
        return $this->hasMany(KegiatanTahunan::class, ['id_pegawai' => 'id_pegawai'])
            ->andWhere(['id_kegiatan_status'=>1])
            ->tahun();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManyKegiatanBulanan()
    {
        return $this->hasMany(KegiatanBulanan::class, ['id_kegiatan_tahunan' => 'id'])
            ->via('manyKegiatanTahunan')
            ->andWhere(['bulan' => $this->bulan])
            ->andWhere(['IS NOT', 'kegiatan_bulanan.target', null]);
    }

    /**
     * @return int
     */
    public function coutKegiatanBulanan($params=[])
    {
        $query = $this->getManyKegiatanBulanan();
        $query->joinWith(['kegiatanTahunan']);
        $query->andFilterWhere(['kegiatan_tahunan.id_kegiatan_tahunan_versi' => @$params['id_kegiatan_tahunan_versi']]);
        return $query->count();
    }

    public function setPotonganTotal()
    {
        $this->setProgres();
        $this->potongan_total = 100 - $this->getProgres();
    }

    public function setProgres()
    {
        if ($this->manyKegiatanBulanan !== []) {
            $this->progres = array_sum(
                    array_map(
                        function (KegiatanBulanan $kegiatanBulanan) {
                            return $kegiatanBulanan->getPersen();
                        },
                        $this->manyKegiatanBulanan
                    )
                ) / $this->coutKegiatanBulanan();
        } else {
            $this->progres = 0;
        }
    }

    public function updateProgres()
    {
        $this->setProgres();
        $this->save();
    }

    public function getProgres()
    {
        return number_format($this->progres, 2);
    }

    public static function query($params=[])
    {
        $query = static::find();
        $query->andFilterWhere([
            'id_pegawai'=>@$params['id_pegawai'],
            'bulan' => @$params['bulan'],
            'tahun' => @$params['tahun']
        ]);

        return $query;
    }

    public static function findOrCreate($params=[])
    {
        $query = static::query($params);
        $model = $query->one();

        if($model===null) {
            $model = new PegawaiRekapKinerja();
            $model->id_pegawai = @$params['id_pegawai'];
            $model->bulan = @$params['bulan'];
            $model->tahun = @$params['tahun'];

            if($model->save()!=true) {
                print_r($model->getErrors());
                die();
            }

            $model->updateProgres();
        }

        return $model;

    }

}
