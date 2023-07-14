<?php

namespace app\modules\absensi\models;

use app\modules\tukin\models\InstansiPegawai;
use Yii;
use app\models\Instansi;
use app\models\Pegawai;

/**
 * This is the model class for table "instansi_rekap_absensi".
 *
 * @property int $id
 * @property int $id_instansi
 * @property int $bulan
 * @property string $tahun
 * @property string $persen_potongan_total
 * @property string $persen_potongan_fingerprint
 * @property string $persen_potongan_kegiatan
 * @property string $waktu_diperbarui
 */
class InstansiRekapAbsensi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $nama_instansi;

    public static function tableName()
    {
        return 'instansi_rekap_absensi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_instansi', 'bulan', 'tahun'], 'required'],
            [['id_instansi', 'bulan'], 'integer'],
            [['tahun', 'waktu_diperbarui'], 'safe'],
            [['persen_potongan_total', 'persen_potongan_fingerprint', 'persen_potongan_kegiatan'], 'number'],
            [['persen_hadir', 'persen_tidak_hadir', 'persen_tanpa_keterangan'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_instansi' => 'Id Instansi',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'persen_potongan_total' => 'Persen Potongan Total',
            'persen_potongan_fingerprint' => 'Persen Potongan Fingerprint',
            'persen_potongan_kegiatan' => 'Persen Potongan Kegiatan',
            'waktu_diperbarui' => 'Waktu Diperbarui',
        ];
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::className(),['id'=>'id_instansi']);
    }

    public function queryPegawaiRekapAbsensi()
    {
        $query = PegawaiRekapAbsensi::find();
        $query->andWhere([
            'id_instansi'=>$this->id_instansi,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun
        ]);

        return $query;
    }

    public function refresh($force = 1)
    {
        $datetime = \DateTime::createFromFormat('Y-n-d', $this->tahun.'-'.$this->bulan.'-01');

        $query = \app\models\InstansiPegawai::find();
        $query->andWhere(['id_instansi' => $this->id_instansi]);
        $query->berlaku($datetime->format('Y-m-15'));
        $query->with('pegawai');

        if($force == 1) {
            foreach($query->all() as $data) {
                if($data->pegawai !== null) {
                    $data->pegawai->updatePegawaiRekapAbsensi($this->bulan);
                }
            }
        }

        $query = $this->queryPegawaiRekapAbsensi();

        $this->persen_potongan_kegiatan = $query->average('persen_potongan_kegiatan');
        $this->persen_potongan_fingerprint = $query->average('persen_potongan_fingerprint');
        $this->persen_potongan_total = $query->average('persen_potongan_total');
        $this->persen_hadir = $query->average('persen_hadir');
        $this->persen_tidak_hadir = $query->average('persen_tidak_hadir');
        $this->persen_tanpa_keterangan = $query->average('persen_tanpa_keterangan');
        $this->waktu_diperbarui = date('Y-m-d H:i:s');

        if($this->save() == false) {
            print_r($this->getErrors());
            die;
        }
    }

    public function findAllPegawai()
    {
        $query = Pegawai::find();

        $query->andWhere('status_hapus IS NULL');
        $query->andWhere([
            'id_instansi'=>$this->id_instansi
        ]);

        return $query->all();
    }
}
