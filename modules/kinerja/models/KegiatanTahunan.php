<?php

namespace app\modules\kinerja\models;

use app\components\Helper;
use app\components\Session;
use app\models\InstansiPegawai;
use app\models\Jabatan;
use app\models\Catatan;
use app\models\Eselon;
use app\models\Instansi;
use app\models\InstansiJenis;
use app\models\KegiatanTahunanFungsional;
use app\models\Pegawai;
use app\models\RefKegiatanStatus;
use app\models\RpjmdIndikatorFungsional;
use app\models\RpjmdKegiatanIndikator;
use app\models\RpjmdProgramIndikator;
use app\models\RpjmdSasaranIndikator;
use app\models\RpjmdSubkegiatanIndikator;
use app\models\User;
use dosamigos\chartjs\ChartJs;
use yii\db\ActiveQuery;
use yii2mod\query\ArrayQuery;
use DateTime;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "kegiatan_tahunan".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $id_pegawai_penyetuju
 * @property int $id_instansi_pegawai
 * @property int $id_induk [int(11)]
 * @property int $id_kegiatan_tahunan_versi
 * @property int $id_kegiatan_tahunan_jenis
 * @property string $nama
 * @property string $satuan_kuantitas
 * @property string $satuan_kualitas
 * @property string $satuan_waktu
 * @property string $satuan_biaya
 * @property string $target_kuantitas
 * @property int $target_waktu
 * @property string $waktu_dibuat
 * @property string $waktu_disetujui
 * @property string $tahun [year(4)]
 * @property string $indikator_kuantitas
 * @property string $indikator_kualitas
 * @property string $indikator_waktu
 * @property string $indikator_biaya
 * @property string $target_biaya [decimal(20)]
 * @property string $target_angka_kredit [decimal(7,4)]
 * @property int $realisasi_kuantitas [int(11)]
 * @property int $realisasi_waktu [int(11)]
 * @property string $realisasi_angka_kredit [decimal(7,4)]
 * @property string $realisasi_biaya [decimal(20)]
 * @property string $id_kegiatan_status [varchar(11)]
 * @property int $status_hapus [int(11)]
 * @property Pegawai $pegawai
 * @property bool $isBulanMelebihi
 * @property InstansiPegawai $instansiPegawai
 * @property int $realisasi
 * @property KegiatanHarian[] $allKegiatanHarian
 * @property KegiatanRiwayat[] $allKegiatanRiwayat
 * @property KegiatanTahunan[] $manySub
 * @property bool $isDisetujui
 * @property string $averagePersenRealisasiPerBulan
 * @property mixed $labelIdKegiatanStatus
 * @property mixed $idKegiatanStatus
 * @property mixed $kegiatanBulananCount
 * @property mixed $kegiatanHarianCount
 * @property string $averagePersenRealisasi
 * @property bool $isDitolak
 * @property string $textInduk
 * @property mixed $manyKegiatanBulanan
 * @property mixed $buttonDropdown
 * @property bool $isBulanKurang
 * @property mixed $encodeNama
 * @property KegiatanStatus $kegiatanStatus
 * @property \yii\db\ActiveQuery $namaPegawai
 * @property string $targetWaktu
 * @property string $targetSatuan
 * @property bool $isKegiatanSubordinat
 * @property mixed $induk
 * @property bool $isKonsep
 * @property string $jumlahKuantitasRealisasiSatuan
 * @property bool $isDiverifikasi
 * @property mixed $jumlahKuantitasRealisasi
 * @property float|int $totalRealisasi
 * @property float|int $totalTarget
 * @property Pegawai $pegawaiPenyetuju
 * @property KegiatanHarian[] $_allKegiatanHarian
 * @method softDelete() dari SoftDeleteBehavior
 * @property InstansiPegawaiSkp $instansiPegawaiSkp
 * @property int $id_instansi_pegawai_skp
 * @property int $id_kegiatan_tahunan_atasan
 * @property int $status_kegiatan_tahunan_atasan
 * @property KegiatanTahunan $kegiatanTahunanAtasan
 * @see KegiatanTahunan::getManyKegiatanHarian()
 * @property KegiatanHarian[] $manyKegiatanHarian
 * @property int $id_kegiatan_rhk
 * @property int $id_kegiatan_aspek
 * @property string $satuan
 * @property int $target
 * @see KegiatanTahunan::getKegiatanRhk()
 * @property KegiatanRhk $kegiatanRhk
 * @property KegiatanAspek $kegiatanAspek
 * @see KegiatanTahunan::getLinkUpdateV3Icon()
 * @property string $linkUpdateV3Icon
 * @property string $perspektif
 */
class KegiatanTahunan extends \yii\db\ActiveRecord
{
    const SCENARIO_UPDATE_STATUS = 'update_status';

    const UTAMA = 1;
    const TAMBAHAN = 2;

    public $nama_pegawai;
    public $nomor_skp;
    public $mode = 'pegawai';

    private $_allKegiatanHarian;

    public $butir_kegiatan_jf;
    public $output_jf;
    public $angka_kredit_jf;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kegiatan_tahunan';
    }

    public function rules()
    {
        return [
            [['tahun', 'id_pegawai', 'nama'], 'required'],
            [['id_instansi_pegawai'],  'required', 'message' => 'SKP tahun berjalan belum di-generate, silahkan klik tombol refresh SKP pada menu Daftar SKP'],
            [['id_pegawai', 'id_kegiatan_status', 'id_induk', 'realisasi_waktu', 'id_pegawai_penyetuju',
                'realisasi_kuantitas', 'status_hapus', 'id_instansi_pegawai'],
                'integer', 'message' => '{attribute} harus berupa angka',
            ],
            [['waktu_dibuat', 'waktu_disetujui'], 'safe'],
            ['id_kegiatan_status', 'default', 'value' => 2],
            [['nama'], 'string', 'max' => 500],
            [['id_kegiatan_tahunan_versi', 'id_kegiatan_tahunan_jenis'], 'integer'],
            [['target_angka_kredit'], 'number'],
            [['satuan_kuantitas'], 'string', 'max' => 255],
            [['id_pegawai'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::class, 'targetAttribute' => ['id_pegawai' => 'id']],
            [['id_pegawai_penyetuju'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::class, 'targetAttribute' => ['id_pegawai_penyetuju' => 'id']],
            [['nomor_skp'],'safe'],
            [['id_kegiatan_tahunan_atasan'], 'integer'],
            [['id_kegiatan_tahunan_atasan'], 'required', 'when' => function(KegiatanTahunan $model) {
                return $model->validateKegiatanTahunanAtasan() AND $model->isVisibleIdKegiatanTahunanAtasan();
                // return $model->id_kegiatan_tahunan_versi == 2 AND $model->id_kegiatan_tahunan_jenis == 1;
            },'message' => 'Kinerja atasan tidak boleh kosong. Silahkan hubungi atasan Anda'],
            [['status_kegiatan_tahunan_atasan'], 'integer'],
            [['id_instansi_pegawai_skp'], 'integer'],
            [['status_plt'], 'integer'],
            [['indikator_kuantitas'], 'safe'],
            [['indikator_kuantitas'], 'required', 'when' => function(KegiatanTahunan $model) {
                return $model->id_kegiatan_tahunan_versi == 2;
            }],
            [['indikator_kualitas', 'indikator_waktu', 'indikator_biaya'], 'safe'],
            [['target_kuantitas', 'satuan_kuantitas'], 'required', 'when' => function (KegiatanTahunan $data) {
                return $data->id_kegiatan_tahunan_versi != 3;
            }, 'message' => '{attribute} tidak boleh kosong'],
            [['target_kuantitas', 'target_kualitas',
                'target_waktu', 'target_biaya'], 'number', 'message' => '{attribute} harus berupa angka'],
            [['satuan_kualitas', 'satuan_waktu', 'satuan_biaya', 'butir_kegiatan', 'output',
                'target_angka_kredit', 'keterangan_tolak'], 'safe'],
            [['butir_kegiatan_jf', 'output_jf', 'angka_kredit_jf'], 'safe'],
            [['id_rpjmd_sasaran_indikator', 'id_rpjmd_program_indikator',
                'id_rpjmd_kegiatan_indikator', 'id_rpjmd_subkegiatan_indikator',
                'id_rpjmd_indikator_fungsional'], 'integer'],
            // VERSI 3 (Permenpan 7 Tahun 2022)
            [['id_kegiatan_aspek'], 'required', 'when' => function (KegiatanTahunan $data) {
               return $data->id_kegiatan_tahunan_versi == 3 && $data->isJpt() == false;
            }],
            [['target', 'satuan'], 'required', 'when' => function (KegiatanTahunan $data) {
                return $data->id_kegiatan_tahunan_versi == 3;
            }],
            [['perspektif'], 'required', 'when' => function (KegiatanTahunan $data) {
                return $data->id_kegiatan_tahunan_versi == 3 && $data->isJpt() == true;
            }],
            [['id_kegiatan_aspek'], 'integer'],
            [['target'], 'number'],
            [['satuan', 'perspektif'], 'string'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPDATE_STATUS] = ['id_kegiatan_status'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     * @return KegiatanTahunanQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new KegiatanTahunanQuery(get_called_class());
        $query->aktif();
        return $query;
    }

    public static function parentFind()
    {
        return parent::find();
    }

    public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'status_hapus' => true,
                ],
            ],
            'timestampBehavior' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'waktu_dibuat',
                'updatedAtAttribute' => null,
                'value' => function ($event) {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    public function afterSoftDelete()
    {
        foreach ($this->manySub as $sub) {
            $sub->softDelete();
        }

        return true;
        //return parent::afterSoftDelete();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Pegawai',
            'id_instansi_pegawai' => 'Id Instansi Pegawai',
            'nama' => $this->id_kegiatan_tahunan_versi != 3 ? 'Nama Kegiatan' : 'Indikator',
            'satuan_kuantitas' => 'Satuan Hasil',
            'target_kuantitas' => 'Target Kuantitas',
            'target_waktu' => 'Target Waktu',
            'id_pegawai_penyetuju' => 'Pegawai Penyetuju',
            'id_kegiatan_status' => 'Status',
            'waktu_dibuat' => 'Waktu Dibuat',
            'waktu_disetujui' => 'Waktu Disetujui',
            'nomor_skp' => 'Nomor SKP',
            'namaNipPegawai' => 'Nama Pegawai',
            'id_kegiatan_tahunan_atasan' => 'Dukungan Kegiatan Atasan',
            'id_rpjmd_program_indikator' => 'Indikator Program Renstra',
            'id_rpjmd_sasaran_indikator' => 'Indikator Sasaran Renstra',
            'id_rpjmd_kegiatan_indikator' => 'Indikator Kegiatan Renstra',
            'id_rpjmd_subkegiatan_indikator' => 'Indikator Sub Kegiatan Renstra',
            'id_rpjmd_indikator_fungsional' => 'Indikator Fungsional Renstra',
            'id_kegiatan_rhk' => 'Rencana Hasil Kerja',
            'id_kegiatan_aspek' => 'Aspek',
        ];
    }

    public function getKegiatanRhk()
    {
        return $this->hasOne(KegiatanRhk::class, ['id' => 'id_kegiatan_rhk']);
    }

    public function getKegiatanAspek()
    {
        return $this->hasOne(KegiatanAspek::class, ['id' => 'id_kegiatan_aspek']);
    }

    public function getInstansiPegawai()
    {
        return $this->hasOne(InstansiPegawai::class, ['id' => 'id_instansi_pegawai'])
            ->inverseOf('manyKegiatanTahunan');
    }

    public function getJabatan()
    {
        return $this->hasOne(Jabatan::class, ['id' => 'id_jabatan'])
            ->via('instansiPegawai');
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi'])
            ->via('instansiPegawai');
    }

    public function getInstansiPegawaiSkp()
    {
        return $this->hasOne(InstansiPegawaiSkp::class,[
           'id_instansi_pegawai'=>'id_instansi_pegawai',
           'tahun'=>'tahun'
        ]);
    }

    public function getRpjmdSasaranIndikator()
    {
        return $this->hasOne(RpjmdSasaranIndikator::class, ['id' => 'id_rpjmd_sasaran_indikator']);
    }

    public function getRpjmdProgramIndikator()
    {
        return $this->hasOne(RpjmdProgramIndikator::class, ['id' => 'id_rpjmd_program_indikator']);
    }

    public function getRpjmdKegiatanIndikator()
    {
        return $this->hasOne(RpjmdKegiatanIndikator::class, ['id' => 'id_rpjmd_kegiatan_indikator']);
    }

    public function getRpjmdSubkegiatanIndikator()
    {
        return $this->hasOne(RpjmdSubkegiatanIndikator::class, ['id' => 'id_rpjmd_subkegiatan_indikator']);
    }

    public function getRpjmdIndikatorFungsional()
    {
        return $this->hasOne(RpjmdIndikatorFungsional::class, ['id' => 'id_rpjmd_indikator_fungsional']);
    }

    public function getManyKegiatanBulan()
    {
        return $this->hasMany(KegiatanBulanan::class,['id_kegiatan_tahunan'=>'id']);
    }

    public function getManyKegiatanTriwulan()
    {
        return $this->hasMany(KegiatanTriwulan::class,['id_kegiatan_tahunan'=>'id']);
    }

    /**
     * @return string
     */
    public function getNamaPegawai()
    {
        return @$this->pegawai->nama;
    }

    /**
     * @return string
     */
    public function getNamaNipPegawai()
    {
        return @$this->pegawai->nama.' ('.@$this->pegawai->nip.')';
    }

    public function getManyKegiatanBulanan()
    {
        return $this->hasMany(KegiatanBulanan::class, ['id_kegiatan_tahunan' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getManySub()
    {
        return $this->hasMany(KegiatanTahunan::class, ['id_induk' => 'id'])
            ->with('kegiatanStatus');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManyKegiatanHarian()
    {
        return $this->hasMany(KegiatanHarian::class, ['id_kegiatan_tahunan' => 'id']);
    }

    public function getAllKegiatanRiwayat()
    {
        return $this->hasMany(KegiatanRiwayatTahunan::class, ['id_kegiatan' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }

    public function getInduk()
    {
        return $this->hasOne(KegiatanTahunan::class, ['id' => 'id_induk']);
    }

    public function getKegiatanTahunanAtasan()
    {
        return $this->hasOne(KegiatanTahunan::class, ['id' => 'id_kegiatan_tahunan_atasan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiPenyetuju()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai_penyetuju']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKegiatanStatus()
    {
        return $this->hasOne(KegiatanStatus::class, ['id' => 'id_kegiatan_status']);
    }

    public function getManyCatatan()
    {
        return $this->hasMany(Catatan::class, ['id_kegiatan_tahunan' => 'id']);
    }

    public function getManyKegiatanTahunanFungsional()
    {
        return $this->hasMany(KegiatanTahunanFungsional::class, ['id_kegiatan_tahunan' => 'id']);
    }

    public function getOneKegiatanTahunanFungsional()
    {
        return $this->hasOne(KegiatanTahunanFungsional::class, ['id_kegiatan_tahunan' => 'id']);
    }

    public function getEncodeNama()
    {
        return Html::encode($this->nama);
    }

    public function setIdKegiatanStatus($id_kegiatan_status)
    {
        $this->id_kegiatan_status = $id_kegiatan_status;
    }

    public function getKegiatanBulananCount()
    {
        return count($this->manyKegiatanBulanan);
    }

    public function getKegiatanHarianCount()
    {
        return count($this->allKegiatanHarian);
    }

    public function getTargetKuantitas()
    {
        return $this->target_kuantitas.' '.$this->satuan_kuantitas;
    }

    public function getTargetWaktu()
    {
        return "$this->target_waktu Bulan";
    }

    public function getRelationField($relation, $field)
    {
        return $this->$relation !== null ? $this->$relation->$field : null;
    }

    public function getIsDisetujui()
    {
        return (int) $this->id_kegiatan_status === RefKegiatanStatus::DISETUJUI;
    }

    public function getIsKonsep()
    {
        return (int) $this->id_kegiatan_status === RefKegiatanStatus::KONSEP;
    }

    public function getIsDiverifikasi()
    {
        return (int) $this->id_kegiatan_status === RefKegiatanStatus::DIVERIFIKASI;
    }

    public function getIsDitolak()
    {
        return (int) $this->id_kegiatan_status === RefKegiatanStatus::DITOLAK;
    }

    public static function getListJson($id_instansi_pegawai = null, $map = false, $tanggal = null)
    {
        $query = static::find()
            ->setuju()
            ->aktif()
            ->andFilterWhere(['id_pegawai' => User::getIdPegawai()])
            ->andWhere(['>=', 'tahun', 2018])
            ->andFilterWhere(['id_instansi_pegawai' => $id_instansi_pegawai]);
        if ($tanggal !== null) {
            $date = new DateTime($tanggal);
            $query->andWhere(['tahun' => $date->format('Y')]);
            unset($date);
        }

        if ($map === true) {
            //return ArrayHelper::map($query->all(), 'id', 'nama', 'tahun');
            return self::getListHirarki($query->all());
        }

        $list = [];
        foreach ($query->all() as $model) {
            $list[$model->tahun][] = ['id' => $model->id, 'name' => $model->nama];
        }
        return $list;
    }

    public function setIdInstansiPegawaiDefault()
    {
        if ($this->pegawai !== null) {
            $instansiPegawai = $this->pegawai->getInstansiPegawaiBerlaku();
            $this->id_instansi_pegawai = @$instansiPegawai->id;
            return true;
        }
        return false;
    }

    public function getTargetSatuan()
    {
        return "$this->target_kuantitas $this->satuan_kuantitas";
    }

    public function loadDefaultAttributes()
    {
        $this->tahun = User::getTahun();
        return;
    }

    public function getJumlahKuantitasRealisasi()
    {
        return $this
            ->getManyKegiatanHarian()
            ->sum('kuantitas');
    }

    public function getAveragePersenRealisasi()
    {
        if ($this->target_kuantitas !== null) {
            return Yii::$app->formatter->asPercent($this->getJumlahKuantitasRealisasi() / $this->target_kuantitas, 2);
        }
        return 'N/A';
    }

    public function getJumlahKuantitasRealisasiSatuan()
    {
        return $this->getJumlahKuantitasRealisasi() . ' ' . $this->satuan_kuantitas;
    }

    public function getIsBulanMelebihi()
    {
        return $this->getKegiatanBulananCount() > $this->target_waktu;
    }

    public function getIsBulanKurang()
    {
        return $this->getKegiatanBulananCount() < $this->target_waktu;
    }

    public function getIsKegiatanSubordinat()
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return $this->isNewRecord ? false : $this->pegawai->kode_pegawai_atasan == Yii::$app->user->identity->kode_pegawai;
    }

    public function accessIdKegiatanStatus()
    {
        if (User::isAdmin()) {
            return true;
        }
        return false;
    }

    public function generateKegiatanBulanan()
    {
        Yii::$app->db->createCommand()->batchInsert('kegiatan_bulanan',[
            'id_kegiatan_tahunan','bulan'
        ],[
            [$this->id,1],
            [$this->id,2],
            [$this->id,3],
            [$this->id,4],
            [$this->id,5],
            [$this->id,6],
            [$this->id,7],
            [$this->id,8],
            [$this->id,9],
            [$this->id,10],
            [$this->id,11],
            [$this->id,12],

        ])->execute();
    }

    public function generateKegiatanTriwulan()
    {
        // Yii::$app->db->createCommand()->batchInsert('kegiatan_triwulan',[
        //     'id_kegiatan_tahunan','periode'
        // ],[
        //     [$this->id,1],
        //     [$this->id,2],
        //     [$this->id,3],
        //     [$this->id,4],

        // ])->execute();

        for ($i=1; $i <= 4; $i++) {
            $kegiatanTriwulan = new KegiatanTriwulan();
            $kegiatanTriwulan->id_kegiatan_tahunan = $this->id;
            $kegiatanTriwulan->tahun = Session::getTahun();
            $kegiatanTriwulan->id_pegawai = Session::getIdPegawai();
            $kegiatanTriwulan->periode = $i;
            $kegiatanTriwulan->save();
        }
    }

    public function findKegiatanBulananByBulan($bulan)
    {
        $model = KegiatanBulanan::findOne([
            'id_kegiatan_tahunan' => $this->id,
            'bulan' => $bulan,
        ]);

        return $model;
    }

    /**
     * @var bool apakah model memiliki kegiatan bulan yang baru dibuat
     */
    protected $_hasKegiatanBulanBaru = false;
    protected $_hasKegiatanTriwulanBaru = false;

    /**
     * Mencari atau membuat kegiatan bulan jika tidak ditemukan.
     * @param $bulan
     * @return KegiatanBulanan
     */
    public function findOrCreateKegiatanBulan($bulan)
    {
        $manyKegiatanBulanan = $this->manyKegiatanBulanan;

        if(count($manyKegiatanBulanan)==0) {
            $this->generateKegiatanBulanan();
            $manyKegiatanBulanan = $this->getManyKegiatanBulanan()->all();
        }

        $query = new ArrayQuery(['from' => $manyKegiatanBulanan]);
        $model = $query->andWhere(['bulan' => $bulan])->one();
        if ($model === false) {
            $this->_hasKegiatanBulanBaru = true;
            $model = new KegiatanBulanan([
                'id_kegiatan_tahunan' => $this->id,
                'bulan' => $bulan
            ]);
            $model->save();
        }

        return $model;
    }

    public function findOrCreateKegiatanTriwulan($triwulan)
    {
        if($this->manyKegiatanTriwulan == null){
            $this->generateKegiatanTriwulan();
        }

        $query = new ArrayQuery(['from' => $this->manyKegiatanTriwulan]);
        $model = $query->andWhere(['periode' => $triwulan])->one();
        if ($model === false) {
            $this->_hasKegiatanTriwulanBaru = true;
            $model = new KegiatanTriwulan([
                'id_kegiatan_tahunan' => $this->id,
                'periode' => $triwulan
            ]);
            $model->save();
        }

        return $model;
    }

    public function getAllKegiatanHarian()
    {
        if($this->_allKegiatanHarian === null) {
            $this->_allKegiatanHarian = $this->manyKegiatanHarian;
        }

        return $this->_allKegiatanHarian;
    }

    /**
     * @param array $params
     * @return ArrayQuery
     */
    public function getArrayQueryKegiatanHarian($params=[])
    {

        $params['tanggal_awal'] = null;
        $params['tanggal_akhir'] = null;

        if(@$params['bulan'] != null) {
            $bulan = @$params['bulan'];
            $date = DateTime::createFromFormat('Y-n-d',$this->tahun."-".$bulan.'-01');
            @$params['tanggal_awal'] = $date->format('Y-m-01');
            @$params['tanggal_akhir'] = $date->format('Y-m-t');

        }

        $query = new ArrayQuery();
        $query->from($this->getAllKegiatanHarian());
        $query->andFilterWhere([
            'id_kegiatan_status' => @$params['id_kegiatan_status']
        ]);

        $query->andFilterWhere(['>=','tanggal',@$params['tanggal_awal']]);
        $query->andFilterWhere(['<=','tanggal',@$params['tanggal_akhir']]);

        return $query;
    }

    public function countKegiatanHarian($params=[])
    {
        $query = $this->getArrayQueryKegiatanHarian($params);
        return $query->count();
    }

    /**
     * @return float|int Total Realisasi dari kegiatan bulanan
     */
    public function getTotalRealisasi($params=[])
    {
        @$params['id_kegiatan_status'] = KegiatanStatus::SETUJU;

        $arrayQuery = $this->getArrayQueryKegiatanHarian($params);

        $total = 0;
        foreach($arrayQuery->all() as $data) {

            if ($this->id_kegiatan_tahunan_versi == 1) {
                $total = $total + $data->kuantitas;
            }

            $attribute = @$params['attribute'];
            if ($this->id_kegiatan_tahunan_versi == 2 AND $attribute != null) {
                $total = $total + $data->$attribute;
            }

            if ($this->id_kegiatan_tahunan_versi == 3) {
                $total = $total + doubleval($data->realisasi);
            }
        }

        return $total;
    }

    /**
     * @return float|int Total Realisasi dari kegiatan bulanan
     */
    public function getTotalTarget($params = [])
    {
        $attribute = @$params['attribute'];
        if($attribute == null) {
            $attribute = 'target';
        }

        if ($this->_hasKegiatanBulanBaru) {
            return $this->getManyKegiatanBulanan()->sum('kegiatan_bulanan.' . $attribute);
        }
        return array_sum(
            array_map(
                function (KegiatanBulanan $kegiatanBulanan) use ($attribute) {
                    return $kegiatanBulanan->$attribute;
                },
                $this->manyKegiatanBulanan
            )
        );
    }

    public function accessSetKonsep()
    {
        if (User::isPegawai() == true AND in_array(@$this->instansiPegawai->jabatan->id_induk, User::getIdJabatanBerlaku())) {
            return true;
        }

        if (User::isAdmin() == true) {
            return true;
        }

        return false;
    }

    public function accessSetPeriksa()
    {
        if (User::isPegawai() == true AND $this->id_pegawai == User::getIdPegawai()) {
            if ($this->id_kegiatan_status == KegiatanStatus::KONSEP) {
                return true;
            }

            if ($this->id_kegiatan_status == KegiatanStatus::TOLAK) {
                return true;
            }
        }

        if (User::isAdmin() == true) {
            if ($this->id_kegiatan_status == KegiatanStatus::KONSEP) {
                return true;
            }

            if ($this->id_kegiatan_status == KegiatanStatus::TOLAK) {
                return true;
            }
        }

        return false;
    }

    public function accessSetSetuju()
    {
        if (User::isAdmin() AND
            $this->id_kegiatan_status == KegiatanStatus::PERIKSA
        ) {
            return true;
        }

        if (User::isPegawai() AND
            $this->id_pegawai != User::getIdPegawai() AND
            $this->id_kegiatan_status == KegiatanStatus::PERIKSA
        ) {
            return true;
        }

        return false;
    }

    public function accessSetTolak()
    {
        if ($this->id_kegiatan_status != KegiatanStatus::PERIKSA) {
            return false;
        }

        if (User::isPegawai() and $this->id_pegawai == User::getIdPegawai()) {
            return false;
        }

        if(Session::isPemeriksaKinerja()) {
            return false;
        }

        return true;
    }

    public function accessView()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isPegawai() AND $this->id_pegawai == User::getIdPegawai()) {
            return true;
        }

        if(Session::isPemeriksaKinerja()) {
            return true;
        }

        return false;

    }

    public function accessUpdate()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (Session::isMappingRpjmd()) {
            return true;
        }

        if (User::isPegawai() AND $this->id_pegawai == User::getIdPegawai()) {

            if ($this->id_kegiatan_status == KegiatanStatus::KONSEP) {
                return true;
            }

            if ($this->id_kegiatan_status == KegiatanStatus::TOLAK) {
                return true;
            }
        }

        return false;

    }

    public function accessUpdateIdKegiatanTahunanAtasan()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isPegawai()
            AND $this->id_pegawai == User::getIdPegawai()
            AND $this->status_kegiatan_tahunan_atasan == 1
            AND @$this->kegiatanTahunanAtasan == null
        ) {
            return true;
        }

        return false;

    }

    public function accessDelete()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isPegawai() AND $this->id_pegawai == User::getIdPegawai()) {

            if ($this->id_kegiatan_status == KegiatanStatus::KONSEP) {
                return true;
            }

            if ($this->id_kegiatan_status == KegiatanStatus::TOLAK) {
                return true;
            }
        }

        return false;

    }

    public static function accessCreate()
    {
        return true;
    }

    public function accessCreateSub()
    {
        if ($this->id_kegiatan_status != KegiatanStatus::KONSEP) {
            return false;
        }

        return true;
    }

    public function getTextInduk()
    {
        if ($this->id_induk === null) {
            return 'Utama';
        }

        return 'Tahapan';
    }

    public static function queryIndukByIdPegawai($id_pegawai)
    {
        $query = KegiatanTahunan::find()
            ->induk()
            ->andWhere(['id_pegawai' => $id_pegawai])
            ->aktif();

        return $query;
    }

    /**
     * @param $id_pegawai
     * @param null $id_instansi_pegawai
     * @param null $bulan
     * @return KegiatanTahunan[]
     */
    public static function allIndukByIdPegawai($id_pegawai, $id_instansi_pegawai = null, $bulan = null)
    {
        $query = static::find()
            ->aktif()
            ->tahun()
            ->with('kegiatanStatus', 'manySub', 'manyKegiatanBulanan','manyKegiatanHarian')
            ->andFilterWhere(['id_instansi_pegawai' => $id_instansi_pegawai]);
        if ($bulan !== null and in_array($bulan, range(1, 12))) {
            $query->joinWith('manyKegiatanBulanan')
                ->andWhere(['kegiatan_bulanan.bulan' => $bulan])
                ->andWhere(['IS NOT', 'kegiatan_bulanan.target', null])
                ->orderBy(['id_induk' => SORT_ASC]);
        } else {
            $query->induk();
        }
        return $query->all();
    }

    public function getButtonDropdown()
    {

        return ButtonDropdown::widget([
            'label' => '',
            'options' => ['class' => 'btn btn-xs btn-primary btn-flat'],
            'dropdown' => [
                'encodeLabels' => false,
                'items' => [
                    ['label' => '<i class="fa fa-plus"></i> Tambah Tahapan', 'url' => ['kegiatan-tahunan/create', 'id_pegawai' => User::getIdPegawai(), 'id_induk' => $this->id], 'visible' => $this->accessCreateSub()],
                    ['label' => '<i class="fa fa-pencil"></i> Ubah', 'url' => ['kegiatan-tahunan/update', 'id' => $this->id], 'visible' => $this->accessUpdate()],
                    ['label' => '<i class="fa fa-trash"></i> Hapus', 'url' => ['kegiatan-tahunan/delete', 'id' => $this->id], 'visible' => $this->accessDelete()],
                ],
            ],
        ]);
    }

    public function getButtonDropdownV2()
    {
        return ButtonDropdown::widget([
            'label' => '',
            'options' => ['class' => 'btn btn-xs btn-primary btn-flat'],
            'dropdown' => [
                'encodeLabels' => false,
                'items' => [
                    ['label' => '<i class="fa fa-plus"></i> Tambah Tahapan', 'url' => ['kegiatan-tahunan/create-v2', 'id_pegawai' => User::getIdPegawai(), 'id_induk' => $this->id], 'visible' => $this->accessCreateSub()],
                    ['label' => '<i class="fa fa-pencil"></i> Ubah', 'url' => ['kegiatan-tahunan/update-v2', 'id' => $this->id], 'visible' => $this->accessUpdate()],
                    ['label' => '<i class="fa fa-trash"></i> Hapus', 'url' => ['kegiatan-tahunan/delete', 'id' => $this->id], 'visible' => $this->accessDelete()],
                ],
            ],
        ]);
    }

    public function getLabelIdKegiatanStatus()
    {
        return @$this->kegiatanStatus->getLabel();
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        KegiatanRiwayatTahunan::createRiwayat($this, RiwayatJenis::DELETE);
        foreach ($this->getManySub()->all() as $sub) {
            $sub->softDelete();
        }

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        foreach ($this->getManySub()->all() as $sub) {
            $sub->id_kegiatan_status = $this->id_kegiatan_status;
            $sub->save();
        }

        /*
        if ($this->scenario === self::SCENARIO_UPDATE_STATUS) {
            KegiatanRiwayatTahunan::createRiwayat($this, RiwayatJenis::SUNTING, $this->id_kegiatan_status);
            return true;
        }
        if ($insert) {
            KegiatanRiwayatTahunan::createRiwayat($this, RiwayatJenis::TAMBAH);
        } else {
            KegiatanRiwayatTahunan::createRiwayat($this, RiwayatJenis::SUNTING);
        }
        */

        return true;
    }

    /**
     * @return integer
     */
    public function countSub()
    {
        return $this->getManySub()->count();
    }

    public static function getList($params=[])
    {
        //Inisialisasi variabel dari $params
        $id_pegawai = @$params['id_pegawai'];
        $id_instansi_pegawai = @$params['id_instansi_pegawai'];
        $tahun = @$params['tahun'];
        $id_kegiatan_status = @$params['id_kegiatan_status'];

        // Kalau User Pegawai, set id_pegawai dari session
        if (User::isPegawai()) {
            $id_pegawai = User::getIdPegawai();
        }

        $id = null;

        if(@$params['tanggal'] != null) {
            $datetime = DateTime::createFromFormat('Y-m-d', @$params['tanggal']);
            $queryBulan = KegiatanBulanan::find();
            $queryBulan->joinWith(['kegiatanTahunan']);
            $queryBulan->andFilterWhere([
                'kegiatan_bulanan.bulan' => $datetime->format('n'),
                'kegiatan_tahunan.id_instansi_pegawai'=> $id_instansi_pegawai,
                'kegiatan_tahunan.tahun'=> $tahun,
                'kegiatan_tahunan.id_kegiatan_status'=> $id_kegiatan_status,
            ]);

            $queryBulan->andWhere('target IS NOT NULL');

            $id = $queryBulan->select('id_kegiatan_tahunan')->column();
        }

        // Mulai Query
        $query = KegiatanTahunan::find();
        $query->joinWith(['instansiPegawai']);

        $query->andFilterWhere([
            'kegiatan_tahunan.id' => $id,
            'kegiatan_tahunan.id_instansi_pegawai'=> $id_instansi_pegawai,
            'kegiatan_tahunan.tahun'=> $tahun,
            'kegiatan_tahunan.id_kegiatan_status'=> $id_kegiatan_status,
            'instansi_pegawai.id_pegawai'=>$id_pegawai
        ]);

        // Kalau hirarki==1 return kegiatan yang sudah tersruktur kegiatan
        // tahapan di bawah kegiatan utama

        if(@$params['hirarki']==true) {
            return self::getListHirarki($query->all());
        }

        return ArrayHelper::map($query->all(), 'id', 'nama');


    }

    public function hasTarget($params = [])
    {
        $tanggal = date('Y-m-d');

        if(@$params['tanggal'] != null) {
            $tanggal = @$params['tanggal'];
        }

        $datetime = DateTime::createFromFormat('Y-m-d', $tanggal);

        $query = KegiatanBulanan::find();
        $query->andWhere([
            'id_kegiatan_tahunan' => $this->id,
            'bulan' => $datetime->format('n')
        ]);
        $query->andWhere('target IS NOT NULL');

        if($query->count() != 0) {
            return true;
        }

        return false;
    }

    public static function getListBaru($params=[])
    {
        //Inisialisasi variabel dari $params
        $id_pegawai = @$params['id_pegawai'];
        $id_instansi_pegawai = @$params['id_instansi_pegawai'];
        $tahun = @$params['tahun'];
        $id_kegiatan_status = @$params['id_kegiatan_status'];
        $id_kegiatan_tahunan_versi = @$params['id_kegiatan_tahunan_versi'];

        // Kalau User Pegawai, set id_pegawai dari session
        if (User::isPegawai()) {
            $id_pegawai = User::getIdPegawai();
        }

        // Mulai Query
        $query = KegiatanTahunan::find();
        $query->joinWith(['instansiPegawai']);

        $query->andFilterWhere([
            'kegiatan_tahunan.id_instansi_pegawai' => $id_instansi_pegawai,
            'kegiatan_tahunan.tahun' => $tahun,
            'kegiatan_tahunan.id_kegiatan_status' => $id_kegiatan_status,
            'kegiatan_tahunan.id_kegiatan_tahunan_versi' => $id_kegiatan_tahunan_versi,
            'instansi_pegawai.id_pegawai' => $id_pegawai,
        ]);

        $query->andWhere('id_induk IS NULL');

        $list = [];
        foreach($query->all() as $data)
        {
            $subHasTarget = false;
            foreach ($data->findAllSub() as $sub) {
                if($sub->hasTarget($params) == true) {
                    $subHasTarget = true;
                }
            }

            if($data->hasTarget($params) == true OR $subHasTarget == true) {
                $target = '';
                if($data->hasTarget($params) == false) {
                    $target = '(Tanpa Target)';
                }
                $list[$data->id] = trim($data->nama. ' '. $target);
                foreach ($data->findAllSub() as $sub) {
                    if($sub->hasTarget($params) == true) {
                        $list[$sub->id] = '- '. $sub->nama;
                    }
                }
            }
        }

        return $list;

    }

    /**
     * @param \app\modules\kinerja\models\KegiatanTahunan[] $kegiatanTahunanArray
     * @return array
     */
    public static function getListHirarki($kegiatanTahunanArray)
    {
        $arrayQuery = new ArrayQuery();
        $arrayQuery->from($kegiatanTahunanArray);
        $arrayQuery = $arrayQuery->andWhere(['id_induk'=>null]);

        $list = [];
        foreach($arrayQuery->all() as $data) {
            $list[$data->id] = $data->nama;

            $arrayQueryTahap = new ArrayQuery();
            $arrayQueryTahap->from($kegiatanTahunanArray);
            $arrayQueryTahap->andWhere(['id_induk'=>$data->id]);

            foreach($arrayQueryTahap->all() as $subdata) {
                $list[$subdata->id] = "-- ".$subdata->nama;
            }
        }

        return $list;
    }

    public function setIdInstansiPegawai($params = [])
    {
        if($this->id_induk!=null) {
            $this->id_instansi_pegawai = @$this->induk->id_instansi_pegawai;
            $this->id_instansi_pegawai_skp = @$this->induk->id_instansi_pegawai_skp;
        }

        if(@$params['id_instansi_pegawai'] != null) {
            $this->id_instansi_pegawai = @$params['id_instansi_pegawai'];
            return;
        }

        if($this->id_induk==null) {
            $query = InstansiPegawaiSkp::find();
            $query->joinWith(['instansiPegawai']);
            $query->andWhere([
                'instansi_pegawai.id_pegawai'=>$this->id_pegawai,
                'instansi_pegawai_skp.tahun'=>$this->tahun,
                'instansi_pegawai_skp.nomor'=>$this->nomor_skp
            ]);

            $model = $query->one();

            if($model !== null) {
                $this->id_instansi_pegawai = @$model->id_instansi_pegawai;
                return;
            }

            if($this->pegawai === null) {
                return;
            }

            $this->id_instansi_pegawai = $this->pegawai->getIdInstansiBerlaku();

        }
    }

    public function getNomorSkpLengkap()
    {
        if($this->id_instansi_pegawai==null) {
            $this->setIdInstansiPegawai();
        }

        $model = $this->instansiPegawaiSkp;

        if($model!=null) {
            return $model->nomor.' : '.$model->getNamaJabatan().' - '.$model->getNamaInstansi();
        }

        return '-';
    }

    public function getNamaKegiatanInduk()
    {
        return @$this->induk->nama;
    }

    public function setNomorSkp()
    {
        $this->nomor_skp = @$this->instansiPegawaiSkp->nomor;
    }

    public function countKegiatanHarianSetuju()
    {
        $query = new ArrayQuery();
        $query->from($this->manyKegiatanHarian);
        $query->andWhere([
            'id_kegiatan_status'=>KegiatanStatus::SETUJU
        ]);

        return $query->count();

    }

    public static function query($params=[])
    {
        $query = KegiatanTahunan::find();
        $query->andFilterWhere([
            'id_pegawai'=>@$params['id_pegawai'],
            'tahun'=>@$params['tahun'],
            'id_kegiatan_status'=>@$params['id_kegiatan_status']
        ]);

        return $query;
    }

    public static function arrayId(array $params=[])
    {
        $query = static::query($params);
        $query->select('id');
        return $query->column();
    }

    public function getListKegiatanTahunan($params = [])
    {
        if($this->pegawai === null) {
            return [];
        }

        $instansiPegawai = $this->instansiPegawai;

        if($instansiPegawai === null) {
            return [];
        }

        $query = KegiatanTahunan::find();

        $query->andWhere([
            'id_instansi_pegawai' => $instansiPegawai->id,
            'tahun' => $this->tahun,
            'id_kegiatan_status' => KegiatanStatus::SETUJU
        ]);

        if(@$params['id_kegiatan_tahunan_versi'] != null) {
            $query->andWhere([
                'id_kegiatan_tahunan_versi' => @$params['id_kegiatan_tahunan_versi']
            ]);
        } else {
            $query->andWhere([
                'id_kegiatan_tahunan_versi' => 1
            ]);
        }

        $query->andWhere('id_induk IS NULL');

        $all = $query->all();

        $list = [];
        foreach($all as $data) {
            $list[$data->id] = '- '.$data->nama;
        }

        return $list;
    }

    public function getListKegiatanTahunanAtasan($params = [])
    {
        if($this->pegawai === null) {
            return [];
        }

        $instansiPegawai = $this->instansiPegawai;

        if($instansiPegawai === null) {
            return [];
        }

        $instansiPegawaiAtasan = $instansiPegawai->getInstansiPegawaiAtasan()->one();

        if($instansiPegawaiAtasan === null) {
            return [];
        }

        $query = KegiatanTahunan::find();

        $query->andWhere([
            'id_instansi_pegawai' => $instansiPegawaiAtasan->id,
            'tahun' => $this->tahun,
            'id_kegiatan_status' => KegiatanStatus::SETUJU
        ]);

        if(@$params['id_kegiatan_tahunan_versi'] != null) {
            $query->andWhere([
                'id_kegiatan_tahunan_versi' => @$params['id_kegiatan_tahunan_versi']
            ]);
        } else {
            $query->andWhere([
                'id_kegiatan_tahunan_versi' => 1
            ]);
        }

        $query->andWhere('id_induk IS NULL');

        $all = $query->all();

        $list = [];
        foreach($all as $data) {
            $list[$data->id] = '- '.$data->nama. ' (Utama)';

            foreach($data->findAllSub() as $sub) {
                $list[$sub->id] = '- - ' . $sub->nama. ' (Tahapan)';
            }
        }

        return $list;
    }

    public function findAllSub()
    {
        return $this->getManySub()->all();
    }

    public function isVisibleStatusKegiatanTahunanAtasan()
    {
        if (@$this->instansiPegawai->jabatan->id_jenis_jabatan == 2) {
            return true;
        }

        return false;

    }

    public function isVisibleIdKegiatanTahunanAtasan()
    {
        if(Session::getTahun() <= 2020) {
            return false;
        }

        if($this->id_induk != null) {
            return false;
        }

        return true;


    }

    public function getSumTargetKegiatanBulanan()
    {
        return $this->getManyKegiatanBulanan()->sum('kegiatan_bulanan.target');
    }

    public function validateTargetPerBulan()
    {
        $i_awal = 1;

        if($this->instansiPegawai->tanggal_mulai >= Session::getTahun().'-01-01') {
            $datetime = DateTime::createFromFormat('Y-m-d', $this->instansiPegawai->tanggal_mulai);
            if($datetime !== false) {
                $i_awal = $datetime->format('n');
            }
        }

        if ($this->id_kegiatan_tahunan_versi == 2 AND Session::getTahun() == 2021) {
            $i_awal = 7;
        }

        for($i = $i_awal; $i <= 12; $i++) {
            $queryBulan = KegiatanBulanan::find();
            $queryBulan->joinWith(['kegiatanTahunan']);
            $queryBulan->andWhere([
                'kegiatan_tahunan.tahun' => $this->tahun,
                'kegiatan_tahunan.id_instansi_pegawai' => $this->id_instansi_pegawai,
                'kegiatan_bulanan.bulan' => $i
            ]);

            $target = $queryBulan->sum('kegiatan_bulanan.target');

            if($target == 0) {
                return $i;
            }
        }

        return 0;
    }

    public function getLinkDeleteIcon()
    {
        if ($this->canUpdate() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-trash"></i>', [
            '/kinerja/kegiatan-tahunan/delete',
            'id' => $this->id
        ],[
            'data-method' => 'post',
            'data-confirm' => 'Yakin akan menghapus data?'
        ]);
    }

    public function validateKegiatanTahunanAtasan()
    {
        if($this->id_kegiatan_tahunan_versi == 2 AND $this->id_kegiatan_tahunan_jenis == 1) {
            $jabatan = $this->instansiPegawai->jabatan;
            $eselon = $jabatan->eselon;

            // KHUSUS DINAS PENGHUBUNG DENGAN JABATAN SEBAGAI KEPALA BADAN
            if($this->instansiPegawai->id_instansi == 7 AND $this->instansiPegawai->id_jabatan == 793) {
                return false;
            }

            if($eselon->non_eselon == 1) {
                return true;
            }

            $data = explode('-', $eselon->nama);
            $nilai = $data[0];

            if($nilai == 'I' OR $nilai == 'II') {
                return false;
            }

            return true;
        }

        return false;
    }

    public static function getArrayIdVersi2()
    {
        $query = KegiatanTahunan::find();
        $query->andWhere(['id_kegiatan_tahunan_versi' => 2]);
        $query->select('id');
        return $query->column();
    }

    public function getKeteranganTolak()
    {
        if($this->id_kegiatan_status != KegiatanStatus::TOLAK) {
            return;
        }

        if($this->keterangan_tolak == null) {
            return;
        }

        return "<br/>(Ket: $this->keterangan_tolak)";
    }

    public function findAllCatatan()
    {
        $query = $this->getManyCatatan();
        $query->orderBy(['waktu_buat' => SORT_ASC]);
        return $query->all();
    }

    public function generateKegiatanTahunanFungsional()
    {
        if($this->id_kegiatan_tahunan_versi == 1) {
            return false;
        }

        $data1 = $this->butir_kegiatan_jf;
        $data2 = $this->output_jf;
        $data3 = $this->angka_kredit_jf;

        $jumlah = count($data1);

        for ($i=0; $i<$jumlah; $i++) {

            if($data1[$i] != null
                OR $data2[$i] != null
                OR $data3[$i] != null
            ) {
                $model = new KegiatanTahunanFungsional();
                $model->id_kegiatan_tahunan = $this->id;
                $model->butir_kegiatan = @$data1[$i];
                $model->output = @$data2[$i];
                $model->angka_kredit = @$data3[$i];
                if(!$model->save()) {
                    print_r($model->getErrors());die;
                }
            }
        }
    }

    public function setArrayFungsional()
    {
        foreach ($this->manyKegiatanTahunanFungsional as $data) {
            $this->butir_kegiatan_jf[] = $data->butir_kegiatan;
            $this->output_jf[] = $data->output;
            $this->angka_kredit_jf[] = $data->angka_kredit;
        }

        return true;
    }

    public function updateKegiatanTahunanFungsional()
    {
        KegiatanTahunanFungsional::deleteAll([
            'id_kegiatan_tahunan' => $this->id
        ]);

        $this->generateKegiatanTahunanFungsional();
        return true;
    }

    public function isVisibleRpjmdSasaranIndikator()
    {
        if ($this->id_instansi_pegawai == null) {
            $this->setIdInstansiPegawai();
        }

        $eselon = @$this->instansiPegawai->jabatan->eselon;

        if ($eselon == null) {
            return false;
        }

        $arrayEselon = [
            Eselon::ESELON_IIA,
            Eselon::ESELON_IIB
        ];

        return in_array($eselon->id, $arrayEselon);
    }

    public function isVisibleRpjmdProgramIndikator()
    {
        if ($this->id_instansi_pegawai == null) {
            $this->setIdInstansiPegawai();
        }

        $eselon = @$this->instansiPegawai->jabatan->eselon;

        if ($eselon == null) {
            return false;
        }

        $arrayEselon = [
            Eselon::ESELON_IIIA,
            Eselon::ESELON_IIIB
        ];

        return in_array($eselon->id, $arrayEselon);
    }

    public function isVisibleRpjmdKegiatanIndikator()
    {
        if ($this->id_instansi_pegawai == null) {
            $this->setIdInstansiPegawai();
        }

        $eselon = @$this->instansiPegawai->jabatan->eselon;

        if ($eselon == null) {
            return false;
        }

        $arrayEselon = [
            Eselon::ESELON_IVA,
            Eselon::ESELON_IVB
        ];

        return in_array($eselon->id, $arrayEselon);
    }

    public function isVisibleRpjmdSubKegiatanIndikator()
    {
        if ($this->id_instansi_pegawai == null) {
            $this->setIdInstansiPegawai();
        }

        $jabatan = @$this->instansiPegawai->jabatan;

        if ($jabatan == null) {
            return false;
        }

        if ($jabatan->id_jenis_jabatan == Jabatan::PELAKSANA) {
            return true;
        }

        return false;
    }

    public function isVisibleRpjmdIndikatorFungsional()
    {
        if ($this->id_instansi_pegawai == null) {
            $this->setIdInstansiPegawai();
        }

        $jabatan = @$this->instansiPegawai->jabatan;

        if ($jabatan == null) {
            return false;
        }

        if ($jabatan->id_jenis_jabatan == Jabatan::FUNGSIONAL) {
            return true;
        }

        return false;
    }

    public function isMappingRpjmd()
    {
        if (@$this->rpjmdSasaranIndikator !== null) {
            return true;
        }

        if (@$this->rpjmdProgramIndikator !== null) {
            return true;
        }

        if (@$this->rpjmdKegiatanIndikator !== null) {
            return true;
        }

        if (@$this->rpjmdSubkegiatanIndikator !== null) {
            return true;
        }

        if (@$this->rpjmdIndikatorFungsional !== null) {
            return true;
        }

        return false;
    }

    public function getLabelMappingRpjmd()
    {
        if ($this->isMappingRpjmd() == false) {
            return '';
        }

        return Html::tag('label', '<i class="fa fa-check"></i>', [
            'class' => 'label label-success',
            'data-toggle' => 'tooltip',
            'title' => 'Telah dikaitkan dengan indikator',
            'style' => 'cursor:pointer;'
        ]);
    }

    public function getIdInstansiRpjmd()
    {
        if (@$this->instansi->id_instansi_jenis == InstansiJenis::UPTD
            AND $this->tahun <= 2022
        ) {
            return $this->instansi->id_induk;
        }

        return $this->instansiPegawai->id_instansi;
    }

    public function getDariBulanTarget()
    {
        if($this->id_kegiatan_tahunan_versi == 1 AND Session::getTahun() == 2021) {
            return 1;
        }

        if($this->id_kegiatan_tahunan_versi == 2 AND Session::getTahun() == 2021) {
            return 7;
        }

        $datetime = DateTime::createFromFormat('Y-m-d', $this->instansiPegawai->tanggal_mulai);
        if ($datetime != false) {
            if ($datetime->format('Y') <= Session::getTahun()) {
                return 1;
            }

            if ($datetime->format('d') >= 15) {
                $datetime->modify('+1 month');
            }

            return $datetime->format('n');
        }

        return 1;
    }

    public function getSampaiBulanTarget()
    {
        if($this->id_kegiatan_tahunan_versi == 1 AND Session::getTahun() == 2021) {
            return 6;
        }

        if (@$this->instansiPegawai->tanggal_selesai == 9999-12-31) {
            return 12;
        }

        $datetime = DateTime::createFromFormat('Y-m-d', @$this->instansiPegawai->tanggal_selesai);
        if ($datetime != false) {
            return $datetime->format('n');
        }

        return 12;
    }

    public function getTotalPersenRealisasi()
    {
        $query = $this->getManyKegiatanBulan();

        $persen = $query->average('persen_realisasi');

        if ($persen > 100) {
            $persen = 100;
        }

        return $persen;
    }

    public function getLinkViewV3Icon()
    {
        return Html::a('<i class="fa fa-eye"></i>', [
            '/kinerja/kegiatan-tahunan/view-v3',
            'id' => $this->id,
        ], [
            'data-toggle' => 'tooltip',
            'title' => 'Lihat',
        ]);
    }

    public function getLinkUpdateV3Icon()
    {
        if ($this->canUpdate() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-pencil"></i>', [
            '/kinerja/kegiatan-tahunan/update-v3',
            'id' => $this->id,
        ], [
            'data-toggle' => 'tooltip',
            'title' => 'Ubah Indikator',
        ]);
    }

    public function getLinkPeriksaV3Icon()
    {
        if ($this->canUpdate() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-send-o"></i>',[
            '/kinerja/kegiatan-tahunan/set-periksa-v3',
            'id' => $this->id,
        ],[
            'data-toggle' => 'tooltip',
            'title' => 'Periksa',
            'onclick' => 'return confirm("Yakin akan mengirim data untuk diperiksa?");',
        ]);
    }

    public function canUpdate()
    {
        if (User::isAdmin() == true) {
            if ($this->id_kegiatan_status == KegiatanStatus::KONSEP) {
                return true;
            }

            if ($this->id_kegiatan_status == KegiatanStatus::TOLAK) {
                return true;
            }
        }

        if (Session::isPegawai() == true AND $this->id_pegawai == Session::getIdPegawai()) {
            if ($this->id_kegiatan_status == KegiatanStatus::KONSEP) {
                return true;
            }

            if ($this->id_kegiatan_status == KegiatanStatus::TOLAK) {
                return true;
            }
        }

        return false;
    }

    public function getLinkSetujuIcon()
    {
        if ($this->accessSetSetuju() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-check"></i>',[
            '/kinerja/kegiatan-tahunan/set-setuju',
            'id' => $this->id
        ], [
            'data-toggle' => 'tooltip',
            'title' => 'Setujui',
            'onclick' => 'return confirm("Yakin akan menyetujui data?");',
        ]);
    }

    public function getLinkTolakIcon()
    {
        if ($this->accessSetTolak() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-remove"></i>',[
            '/kinerja/kegiatan-tahunan/tolak',
            'id' => $this->id,
        ], [
            'data-toggle' => 'tooltip',
            'title' => 'Tolak',
        ]);
    }

    public function getLinkKonsepIcon()
    {
        if ($this->accessSetKonsep() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-refresh"></i>',[
            'kegiatan-tahunan/set-konsep',
            'id' => $this->id,
        ], [
            'data-toggle' => 'tooltip',
            'title' => 'Kembalikan Ke Konsep',
            'onclick' => 'return confirm("Yakin akan mengembalikan data menjadi konsep?");',
        ]);
    }

    public function getLinkViewCatatanIcon()
    {
        return Html::a('<i class="fa fa-comment"></i>', [
            'kegiatan-tahunan/view-catatan',
            'id' => $this->id,
        ], [
            'data-toggle' => 'tooltip',
            'title' => 'Lihat Catatan',
        ]);
    }

    public function loadAttributeFromKegiatanRhk()
    {
        $this->tahun = @$this->kegiatanRhk->tahun;
        $this->id_pegawai = @$this->kegiatanRhk->id_pegawai;
        $this->id_instansi_pegawai = @$this->kegiatanRhk->id_instansi_pegawai;
        $this->id_instansi_pegawai_skp = @$this->kegiatanRhk->id_instansi_pegawai_skp;
        $this->id_kegiatan_tahunan_jenis = @$this->kegiatanRhk->id_kegiatan_rhk_jenis;

        return true;
    }

    public function isJpt()
    {
        if (@$this->instansiPegawai == null) {
            return false;
        }

        return $this->instansiPegawai->isJpt();
    }

    public function getNamaIndikatorRenstra()
    {
        if ($this->isVisibleRpjmdSasaranIndikator()) {
            return @$this->rpjmdSasaranIndikator->namaLengkap;
        }

        if ($this->isVisibleRpjmdProgramIndikator()) {
            return @$this->rpjmdProgramIndikator->namaLengkap;
        }

        if ($this->isVisibleRpjmdKegiatanIndikator()) {
            return @$this->rpjmdKegiatanIndikator->namaLengkap;
        }

        if ($this->isVisibleRpjmdSubKegiatanIndikator()) {
            return @$this->rpjmdSubkegiatanIndikator->namaLengkap;
        }

        if ($this->isVisibleRpjmdIndikatorFungsional()) {
            return @$this->rpjmdIndikatorFungsional->namaLengkap;
        }

        return null;
    }

    public static function getListPerspektif()
    {
        return [
            'Penerima Layanan' => 'Penerima Layanan',
            'Penguatan Internal' => 'Penguatan Internal',
            'Anggaran' => 'Anggaran',
            'Proses Bisnis' => 'Proses Bisnis',
        ];
    }

    public function setPerspektif()
    {
        if (is_array($this->perspektif) == false) {
            return false;
        }

        $this->perspektif = implode(';', $this->perspektif);
        return true;
    }

    public function setArrayPerspektif()
    {
        $this->perspektif = explode(';', $this->perspektif);

        return $this->perspektif;
    }

    /**
     * @return KegiatanTahunan[]|array
     */
    public function findAllSubFromKegiatanRhk()
    {
        $query = KegiatanTahunan::find();
        $query->joinWith(['kegiatanRhk']);
        $query->andWhere(['kegiatan_rhk.id_induk' => $this->id_kegiatan_rhk]);

        return $query->all();
    }

    public function getKurvaKinerjaBulanan() {
        $labels = [];
        $targetData = [];
        $realData = [];
        $sumReal = 0;
        $sumTarget = 0;
    
        for ($i = 1; $i <= 12; $i++) {
            $kegiatanBulanan = $this->findOrCreateKegiatanBulan($i);
            $labels[] = Helper::getBulanLengkap($i);
            $sumTarget = $this->getTotalTarget();
            $sumReal += $kegiatanBulanan->realisasi;
            $targetData[] = $sumTarget;
            $realData[] = $sumReal;
        }
    
        $data = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Target',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.1)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'pointBackgroundColor' => 'rgba(255, 99, 132, 1)',
                    'pointBorderColor' => '#fff',
                    'data' => $targetData
                ],
                [
                    'label' => 'Realisasi',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'pointBackgroundColor' => 'rgba(54, 162, 235, 1)',
                    'pointBorderColor' => '#fff',
                    'data' => $realData
                ],
            ],
        ];

        echo ChartJs::widget([
            'type' => 'line',
            'options' => [
                'height' => 100,
                'width' => 400,
            ],
            'data' => $data,
        ]);
    
        return $data;
    }
    

}
