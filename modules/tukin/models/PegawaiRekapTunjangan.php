<?php

namespace app\modules\tukin\models;

use Yii;

/**
 * This is the model class for table "pegawai_rekap_tunjangan".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $id_instansi
 * @property int $bulan
 * @property string $tahun
 * @property double $potongan_total
 *
 * @property PegawaiRekapAbsensi $pegawaiRekapAbsensi
 * @property PegawaiRekapKinerja $pegawaiRekapKinerja
 * @property Instansi $instansi
 * @property Pegawai $pegawai
 * @property Jabatan $jabatan
 * @property KelasJabatan $kelasJabatan
 * @property int|float $persenKinerja
 * @property int|float $persenAbsensi
 * @property float $potonganTotal
 */
class PegawaiRekapTunjangan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_rekap_tunjangan';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'id_instansi', 'bulan', 'tahun'], 'required'],
            [['id', 'id_pegawai', 'id_instansi', 'bulan'], 'integer'],
            [['tahun'], 'safe'],
            [['potongan_total'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Id Pegawai',
            'id_instansi' => 'Id Instansi',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'potongan_total' => 'Potongan Total',
        ];
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai'])
            ->inverseOf('pegawaiRekapTunjangan');
    }

    public function getJabatan()
    {
        return $this->hasOne(Jabatan::class, ['id' => 'id_jabatan'])
            ->via('pegawai');
    }

    public function getKelasJabatan()
    {
        return $this->hasOne(KelasJabatan::class, ['kelas_jabatan' => 'kelas_jabatan'])
            ->via('jabatan');
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi']);
    }

    public function getPegawaiRekapAbsensi()
    {
        return $this->hasOne(PegawaiRekapAbsensi::class, [
            'id_pegawai' => 'id_pegawai',
            'bulan' => 'bulan',
            'tahun' => 'tahun',
        ]);
    }

    public function getPegawaiRekapKinerja()
    {
        return $this->hasOne(PegawaiRekapKinerja::class, [
            'id_pegawai' => 'id_pegawai',
            'bulan' => 'bulan',
            'tahun' => 'tahun',
        ]);
    }

    public function getPotonganTotal()
    {
        return (@$this->pegawaiRekapKinerja->getPotonganTotal() * 0.8)
            + (@$this->pegawaiRekapAbsensi->getPotonganTotal() * 0.1)
            + $this->getPotonganSerapanAnggaran() * 0.1;
    }

    public function getPersenKinerja()
    {
        return $this->findOrCreatePegawaiRekapKinerja() !== null
            ? $this->findOrCreatePegawaiRekapKinerja()->getPersen()
            : 0;
    }

    public function getPersenAbsensi()
    {
        return $this->findOrCreatePegawaiRekapAbsensi() !== null
            ? $this->findOrCreatePegawaiRekapAbsensi()->getPersen()
            : 100;
    }

    public function getPersenSerapanAnggaran()
    {
        return $this->instansi->getSerapanAnggaranBulan($this->bulan);
    }

    public function getPotonganSerapanAnggaran()
    {
        return $this->instansi->getPotonganSerapanAnggaranBulan($this->bulan);
    }

    public function findOrCreatePegawaiRekapKinerja()
    {
        if ($this->pegawaiRekapKinerja === null) {
            $new = new PegawaiRekapKinerja([
                'id_pegawai' => $this->id_pegawai,
                'bulan' => $this->bulan,
                'tahun' => $this->tahun,
            ]);
            $new->save();
            return $new;
        }
        return $this->pegawaiRekapKinerja;
    }

    public function findOrCreatePegawaiRekapAbsensi()
    {
        if ($this->pegawaiRekapAbsensi === null) {
            $new = new PegawaiRekapAbsensi([
                'id_pegawai' => $this->id_pegawai,
                'bulan' => $this->bulan,
                'tahun' => $this->tahun,
            ]);
            $new->save();
            return $new;
        }
        return $this->pegawaiRekapAbsensi;
    }
}
