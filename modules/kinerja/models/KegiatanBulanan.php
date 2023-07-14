<?php

namespace app\modules\kinerja\models;

use app\components\Session;
use app\models\Jabatan;
use app\models\InstansiPegawai;
use app\models\KegiatanTahunanVersi;
use Yii;
use app\components\Helper;
use kartik\editable\Editable;
use yii\helpers\Html;
use app\models\User;
use yii\validators\Validator;
use function date;

/**
 * This is the model class for table "kegiatan_bulanan".
 *
 * @property int $id
 * @property int $id_kegiatan_tahunan
 * @property int $bulan
 * @property int $target [int(11)]
 * @property int $target_kualitas
 * @property int $target_waktu
 * @property int $target_biaya
 * @property int $realisasi [int(11)]
 * @property int $realisasi_kualitas
 * @property int $realisasi_waktu
 * @property int $realisasi_biaya
 * @property mixed $editableTarget
 * @property float|int $persen
 * @property string $labelPersenRealisasi
 * @property float|int $totalRealisasiBulan
 * @property KegiatanHarian[] $manyKegiatanHarian
 * @property string $namaKegiatan
 * @property KegiatanHarian[] $allKegiatanHarian
 * @property mixed $totalRealisasi
 * @property string $targetSatuan
 * @property mixed $satuanKuantitas
 * @property float|int $persenRealisasi
 * @property string $namaBulanSingkat
 * @property string $namaBulan
 * @see KegiatanBulanan::getKegiatanTahunan()
 * @property KegiatanTahunan $kegiatanTahunan
 * @property InstansiPegawaiSkp instansiPegawaiSkp
 */
class KegiatanBulanan extends \yii\db\ActiveRecord
{
    private $_totalRealisasi;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kegiatan_bulanan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_kegiatan_tahunan'], 'required'],
            [['id_kegiatan_tahunan', 'bulan'], 'integer'],
            //[['id_kegiatan_tahunan', 'bulan'], 'unique', 'targetAttribute' => ['id_kegiatan_tahunan', 'bulan']],
            [['id_kegiatan_tahunan'], 'exist', 'skipOnError' => true, 'targetClass' => KegiatanTahunan::class, 'targetAttribute' => ['id_kegiatan_tahunan' => 'id']],
            [['target', 'realisasi'], 'default', 'value' => null, 'isEmpty' => function ($value) {
                /** @see \yii\validators\Validator::isEmpty() */
                return $value === null || $value === [] || $value === '' || (double) $value === 0;
            }],
            [['target'], 'validasiJadwal'],
            [['target', 'target_kualitas', 'target_waktu', 'target_biaya'], 'number'],
            [['realisasi', 'realisasi_kualitas', 'realisasi_biaya', 'realisasi_waktu'], 'number'],
        ];
    }

    public function validasiJadwal($attribute, $params, $validator)
    {
        if(Session::isAdmin()) {
            return true;
        }

        if($this->kegiatanTahunan->id_kegiatan_status == 2) {
            return true;
        }

        $query  = PegawaiUbah::find();
        $query->andWhere(['id_pegawai'=>$this->kegiatanTahunan->id_pegawai]);
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
            ':tanggal' => date('Y-m-d')
        ]);

        if($query->one()!==null) {
            return true;
        }

        if (!$this->hasErrors() && $this->kegiatanTahunan !== null) {
            $jadwal = JadwalKegiatanBulan::findOrCreate($this->bulan, $this->kegiatanTahunan->tahun);
            if ($jadwal !== null && date('Y-m-d') > $jadwal->tanggal) {
                $this->addError($attribute, 'Tidak dapat menginput target karena melewati jadwal penginputan ( '
                    . Helper::getTanggal($jadwal->tanggal)
                    . ' )');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_kegiatan_tahunan' => 'Kegiatan Tahunan',
            'bulan' => 'Bulan',
            'target_kuantitas' => 'Target Kuantitas',
        ];
    }

    /**
     * @inheritdoc
     * @return KegiatanBulananQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new KegiatanBulananQuery(get_called_class());
        //$query->joinWith(['kegiatanTahunan']);
        //$query->andWhere(['kegiatan_tahunan.status_hapus' => 0]);
        return $query;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKegiatanTahunan()
    {
        return $this->hasOne(KegiatanTahunan::class, ['id' => 'id_kegiatan_tahunan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstansiPegawai()
    {
        return $this->hasOne(InstansiPegawai::class, ['id' => 'id_instansi_pegawai'])
            ->via('kegiatanTahunan');
    }

    public function getJabatan()
    {
        return $this->hasOne(Jabatan::class,['id'=>'id_jabatan'])
            ->via('instansiPegawai');
    }

    public function getInstansiPegawaiSkp()
    {
        return $this->hasOne(InstansiPegawaiSkp::class,[
            'id_instansi_pegawai'=>'id_instansi_pegawai',
            'tahun'=>'tahun'
        ])->via('kegiatanTahunan');
    }

    public function loadDefaultAttributes()
    {
        $this->bulan = date('m');
    }

    public function getManyKegiatanHarian()
    {
        return $this->hasMany(KegiatanHarian::class, ['id_kegiatan_tahunan' => 'id_kegiatan_tahunan'])
            ->aktif()
            ->andWhere([
                'between',
                'tanggal',
                date('Y-m-01', strtotime(User::getTahun() . '-' . sprintf('%02d', $this->bulan) . '-01')),
                date('Y-m-t', strtotime(User::getTahun() . '-' . sprintf('%02d', $this->bulan) . '-01'))
            ]);
    }

    public function getAllKegiatanHarianV2()
    {
        $query = $this->getManyKegiatanHarian();
        $query->andWhere(['id_kegiatan_harian_versi' => 2]);

        return $query->all();
    }

    public function getAllKegiatanHarianV3()
    {
        $query = $this->getManyKegiatanHarian();
        $query->andWhere(['id_kegiatan_harian_versi' => 3]);

        return $query->all();
    }

    public function getAllKegiatanHarian()
    {
        return $this->getManyKegiatanHarian();
    }

    public function getNamaBulan()
    {
        return Helper::getBulanLengkap($this->bulan);
    }

    public function getNamaBulanSingkat()
    {
        return Helper::getBulanSingkat($this->bulan);
    }

    public function getTargetSatuan()
    {
        return $this->target . ' ' . $this->getSatuanKuantitas();
    }

    public function getNamaKegiatan()
    {
        return $this->getRelationField("kegiatanTahunan", "nama");
    }

    public function getSatuanKuantitas()
    {
        return @$this->kegiatanTahunan->satuan_kuantitas;
    }

    public function getRelationField($relation, $field)
    {
        return $this->$relation !== null ? $this->$relation->$field : '';
    }

    /**
     * @param $params['attribute'] app\modules\kinerja\KegiatanHarian
     */
    public function getTotalRealisasiBulan($params=[])
    {
        return ($realisasi = $this->getTotalRealisasi($params)) !== null ? $realisasi : 0;
    }

    /**
     * @param $params['attribute'] app\modules\kinerja\KegiatanHarian
     */
    public function getPersen()
    {
        if ($this->kegiatanTahunan->id_kegiatan_tahunan_versi == 2) {
            return $this->getPersenV2();
        }

        $realisasi = $this->getTotalRealisasiBulan() / $this->target * 100;
        return $realisasi >= 100 ? 100 : number_format($realisasi, 2);
    }

    public function getPersenV2()
    {
        $realisasiKuantitas = $this->getTotalRealisasiBulan([
            'attribute' => 'realisasi_kuantitas',
        ]);
        $realisasiKualitas = $this->getTotalRealisasiBulan([
            'attribute' => 'realisasi_kualitas',
        ]);
        $realisasiWaktu = $this->getTotalRealisasiBulan([
            'attribute' => 'realisasi_waktu',
        ]);
        $realisasiBiaya = $this->getTotalRealisasiBulan([
            'attribute' => 'realisasi_biaya',
        ]);

        $totalPersenRealisasi = 0;
        $jumlah = 0;

        if($this->target != null AND $this->target != 0) {
            $totalPersenRealisasi += Helper::persen($realisasiKuantitas,$this->target);
            $jumlah++;
        }
        /* */
        if($this->target_kualitas != null AND $this->target_kualitas != 0) {
            $totalPersenRealisasi += Helper::persen($realisasiKualitas,$this->target_kualitas);
            $jumlah++;
        }
        /* */
        if($this->target_waktu != null AND $this->target_waktu != 0) {
            $totalPersenRealisasi += Helper::persen($realisasiWaktu,$this->target_waktu);
            $jumlah++;
        }
        /* */
        if($this->target_biaya != null AND $this->target_biaya != 0) {
            $totalPersenRealisasi += Helper::persen($realisasiBiaya,$this->target_biaya);
            $jumlah++;
        }

        $realisasi = $totalPersenRealisasi / $jumlah;
        return $realisasi >= 100 ? 100 : number_format($realisasi, 2);
    }

    /**
     * @param $params['attribute'] app\modules\kinerja\KegiatanHarian
     */
    public function getPersenPerAttributeV2($params=[])
    {
        $attribute = @$params['attribute'];
        $targetAttribute = 'target'; // default

        if ($attribute == null) return number_format(0, 2);

        if ($attribute == 'realisasi_kuantitas') {
            $targetAttribute = 'target';
        } elseif ($attribute == 'realisasi_kualitas') {
            $targetAttribute = 'target_kualitas';
        } elseif ($attribute == 'realisasi_waktu') {
            $targetAttribute = 'target_waktu';
        } elseif ($attribute == 'realisasi_biaya') {
            $targetAttribute = 'target_biaya';
        }

        if ($this->$targetAttribute == null OR $this->$targetAttribute == 0) {
            return number_format(0, 2);
        }

        $realisasi = $this->getTotalRealisasiBulan($params) / $this->$targetAttribute * 100;
        return $realisasi >= 100 ? 100 : number_format($realisasi, 2);
    }

    public function getTotalRealisasi($params=[])
    {
        if ($this->kegiatanTahunan->id_kegiatan_tahunan_versi == 2) {
            return $this->getTotalRealisasiV2($params);
        }

        if ($this->_totalRealisasi === null ) {
            $this->_totalRealisasi = (User::getTahun() === '2018' && (int) $this->bulan !== 12) ?
                $this->realisasi :
                array_sum(
                    array_map(
                        function (KegiatanHarian $row) {
                            if ($row->getIsKegiatanDisetujui()) {
                                return $row->kuantitas;
                            }
                            return 0;
                        },
                        $this->allKegiatanHarian
                    )
                );
        }
        return $this->_totalRealisasi;
    }

    public function getTotalRealisasiV2($params=[])
    {
        $totalRealisasi = (User::getTahun() === '2018' && (int) $this->bulan !== 12) ?
            $this->realisasi :
            array_sum(
                array_map(
                    function (KegiatanHarian $row) use ($params) {
                        if ($row->getIsKegiatanDisetujui()) {

                            $attribute = @$params['attribute'];
                            if ($row->id_kegiatan_harian_versi == 2 && $attribute != null) {
                                return $row->$attribute;
                            }

                        }

                        return 0;
                    },
                    $this->allKegiatanHarian
                )
            );

        return $totalRealisasi;
    }

    /*
    public function getManyKegiatanHarian()
    {
        return $this->hasMany(KegiatanHarian::className(), [
            'id_kegiatan_tahunan' => 'id_kegiatan_tahunan',
        ]);
    }
    */


    public function getRealisasi()
    {
        $tahun = $this->kegiatanTahunan->tahun;
        $bulan = $this->bulan;

        $time = strtotime($tahun.'-'.$bulan);
        $query = $this->getManyKegiatanHarian();
        $query->andWhere(['id_kegiatan_status'=>1]);
        $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir',[
            ':tanggal_awal'=>date('Y-m-01', $time),
            ':tanggal_akhir'=>date('Y-m-t', $time),
        ]);

        return $query->sum('kuantitas');
    }

    public function getPersenRealisasi()
    {
        $persenRealisasi = 0;

        if ($this->target != null) {
            $persenRealisasi = $this->realisasi / $this->target * 100;

            if($persenRealisasi > 100) {
                $persenRealisasi = 100;
            }
        }

        return $persenRealisasi;
    }

    public function accessUpdate()
    {
        $datetime = \DateTime::createFromFormat('Y-n', $this->kegiatanTahunan->tahun . '-' . $this->bulan);

        if ($this->kegiatanTahunan->id_kegiatan_tahunan_versi == KegiatanTahunanVersi::PP_30_TAHUN_2O19) {

            if ($datetime !== false AND $datetime->format('Y-m') >= '2023-02') {
                return false;
            }
        }

        return true;
    }

    public function updateRealisasi()
    {
        if($this->target==null) {
            return null;
        }

        if($this->kegiatanTahunan===null) {
            return false;
        }

        if($this->kegiatanTahunan->id_kegiatan_tahunan_versi == 2) {
            return $this->updateRealisasiV2();
        }

        $realisasi = $this->kegiatanTahunan->getTotalRealisasi(['bulan'=>$this->bulan]);
        $persenRealisasi = Helper::persen($realisasi, $this->target);

        if($persenRealisasi > 100) {
            $persenRealisasi = 100;
        }

        return $this->updateAttributes([
            'realisasi' => $realisasi,
            'persen_realisasi' => $persenRealisasi
        ]);
    }

    public function updateRealisasiV2()
    {
        $realisasiKuantitas = $this->kegiatanTahunan->getTotalRealisasi([
            'bulan' => $this->bulan,
            'attribute' => 'realisasi_kuantitas',
        ]);
        $realisasiKualitas = $this->kegiatanTahunan->getTotalRealisasi([
            'bulan' => $this->bulan,
            'attribute' => 'realisasi_kualitas',
        ]);
        $realisasiWaktu = $this->kegiatanTahunan->getTotalRealisasi([
            'bulan' => $this->bulan,
            'attribute' => 'realisasi_waktu',
        ]);
        $realisasiBiaya = $this->kegiatanTahunan->getTotalRealisasi([
            'bulan' => $this->bulan,
            'attribute' => 'realisasi_biaya',
        ]);

        $totalPersenRealisasi = 0;
        $jumlah = 0;

        if($this->target != null AND $this->target != 0) {
            $totalPersenRealisasi += Helper::persen($realisasiKuantitas,$this->target);
            $jumlah++;
        }
        /* */
        if($this->target_kualitas != null AND $this->target_kualitas != 0) {
            $totalPersenRealisasi += Helper::persen($realisasiKualitas,$this->target_kualitas);
            $jumlah++;
        }
        /* */
        if($this->target_waktu != null AND $this->target_waktu != 0) {
            $totalPersenRealisasi += Helper::persen($realisasiWaktu,$this->target_waktu);
            $jumlah++;
        }
        /* */
        if($this->target_biaya != null AND $this->target_biaya != 0) {
            $totalPersenRealisasi += Helper::persen($realisasiBiaya,$this->target_biaya);
            $jumlah++;
        }

        $persenRealisasi = $totalPersenRealisasi / $jumlah;

        if($persenRealisasi > 100) {
            $persenRealisasi = 100;
        }

        return $this->updateAttributes([
            'realisasi' => $realisasiKuantitas,
            'realisasi_kualitas' => $realisasiKualitas,
            'realisasi_waktu' => $realisasiWaktu,
            'realisasi_biaya' => $realisasiBiaya,
            'persen_realisasi' => $persenRealisasi
        ]);
    }

    public function getLinkUpdateRealisasiIcon()
    {
        return Html::a('<i class="fa fa-refresh"></i>',[
            '/kinerja/kegiatan-bulanan/update-realisasi',
            'id'=>$this->id
        ]);
    }

    public function getNamaNipPegawai()
    {
        if($this->kegiatanTahunan!=null) {
            return $this->kegiatanTahunan->getNamaNipPegawai();
        }
    }

    public function getNomorSkpLengkap()
    {
        if($this->kegiatanTahunan!=null) {
            return $this->kegiatanTahunan->getNomorSkpLengkap();
        }
    }

    public function getLabelStatusKegiatanTahunan()
    {
        return @$this->kegiatanTahunan->labelIdKegiatanStatus;
    }

    public static function query($params=[])
    {
        $query = KegiatanBulanan::find();
        $query->joinWith(['kegiatanTahunan', 'instansiPegawai']);

        $query->andFilterWhere([
            'kegiatan_bulanan.id_kegiatan_tahunan' => @$params['id_kegiatan_tahunan'],
            'kegiatan_bulanan.bulan' => @$params['bulan'],
            'kegiatan_tahunan.id_pegawai' => @$params['kegiatan_tahunan.id_pegawai'],
            'kegiatan_tahunan.tahun' => @$params['kegiatan_tahunan.tahun'],
            'kegiatan_tahunan.id_kegiatan_status' => @$params['kegiatan_tahunan.id_kegiatan_status'],
            'kegiatan_tahunan.status_plt'=>@$params['kegiatan_tahunan.status_plt'],
            'kegiatan_tahunan.status_hapus'=>@$params['kegiatan_tahunan.status_hapus'],
            'kegiatan_tahunan.id_kegiatan_tahunan_versi' => @$params['kegiatan_tahunan.id_kegiatan_tahunan_versi'],
        ]);

        if(@$params['target'] == 'IS NOT NULL') {
            $query->andWhere('kegiatan_bulanan.target IS NOT NULL');
        }

        if(@$params['target_is_not_null'] != null) {
            $query->andWhere('kegiatan_bulanan.target IS NOT NULL');
        }

        return $query;
    }

    public static function average($params=[])
    {
        $query = KegiatanBulanan::query($params);
        return $query->average(@$params['attribute']);
    }

    public static function findAll($params=[])
    {
        $query = KegiatanBulanan::query($params);
        return $query->all();
    }

    public function getTargetSatuanKualitas()
    {
        $target = $this->target_kualitas ?? 0;
        $satuan = @$this->kegiatanTahunan->satuan_kualitas;
        return "$target $satuan";
    }

    public function getTargetSatuanBiaya()
    {
        $target = $this->target_biaya ?? 0;
        $satuan = @$this->kegiatanTahunan->satuan_biaya;
        return "$target $satuan";
    }

    public function getTargetSatuanWaktu()
    {
        $target = $this->target_waktu ?? 0;
        $satuan = @$this->kegiatanTahunan->satuan_waktu;
        return "$target $satuan";
    }

    public function getPersenRealisasiKuantitas()
    {
        return Helper::persen($this->target, $this->realisasi);
    }

    public function getPersenRealisasiKualitas()
    {
        return Helper::persen($this->target_kualitas, $this->realisasi_kualitas);
    }

    public function getPersenRealisasiWaktu()
    {
        return Helper::persen($this->target_waktu, $this->realisasi_waktu);
    }

    public function getPersenRealisasiBiaya()
    {
        return Helper::persen($this->target_biaya, $this->realisasi_biaya);
    }

    public function canUpdate()
    {
        $datetime = \DateTime::createFromFormat('Y-n', Session::getTahun().'-'.$this->bulan);
        if ($datetime->format('Y-m') <= '2021-07') {
            return false;
        }

        if (Session::getTahun() >= 2021
            AND $this->instansiPegawai->tanggal_mulai >= $datetime->format('Y-m-15')
        ) {
            return false;
        }

        return true;
    }

    public function canUpdateV3()
    {
        if (Session::isAdmin()) {
            return true;
        }

        if (Session::isPegawai() == true
            AND @$this->kegiatanTahunan->id_pegawai == Session::getIdPegawai()
        ) {

            if (@$this->kegiatanTahunan->id_kegiatan_status == KegiatanStatus::KONSEP) {
                return true;
            }

            if (@$this->kegiatanTahunan->id_kegiatan_status == KegiatanStatus::TOLAK) {
                return true;
            }

        }

        return false;
    }

    public function getEditable(array $params = [])
    {
        $attribute = $params['attribute'];

        if (@$params['status_kunci'] === true) {
            return $this->$attribute;
        }

        return Editable::widget([
            'model' => $this,
            'value' => $this->$attribute,
            'name' => $attribute,
            'valueIfNull' =>  '...',
            'header' => ucwords($attribute),
            'formOptions' => ['action' => ['kegiatan-bulanan/editable-update']],
            'beforeInput' => Html::hiddenInput('editableKey', $this->id),
            'inputType' => Editable::INPUT_TEXT,
            'placement' => 'top',
            'options' => ['placeholder' => 'Input ' . ucwords($attribute) . '...'],
            //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
        ]);
    }
}
