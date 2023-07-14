<?php

namespace app\modules\kinerja\models;

use app\components\Session;
use app\models\Jabatan;
use DateTime;
use Yii;
use app\components\Helper;
use app\models\Catatan;
use app\models\InstansiPegawai;
use app\models\Pegawai;
use app\models\User;
use app\modules\kinerja\models\KegiatanRiwayatHarian;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "kegiatan_harian".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $id_kegiatan_harian_jenis
 * @property int $id_kegiatan_tahunan
 * @property int $id_kegiatan_harian_tambahan
 * @property int $id_instansi_pegawai
 * @property string $tanggal
 * @property string $uraian
 * @property int $kuantitas
 * @property string $satuan
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property string $berkas
 * @property string $id_kegiatan_status
 * @property int $id_pegawai_penyetuju
 * @property string $keterangan_tolak
 * @property string $waktu_disetujui
 * @property int $status_hapus
 * @property string $waktu_dihapus
 * @property KegiatanTahunan $kegiatanTahunan
 * @property KegiatanBulanan $kegiatanBulanan
 * @see KegiatanHarian::getPegawai()
 * @property Pegawai $pegawai
 * @property int $id_kegiatan_harian_versi
 * @property Datetime $waktu_dibuat
 * @property string $realisasi
 */
class KegiatanHarian extends \app\components\RangeActiveRecord
{
    const SCENARIO_UPDATE_STATUS = 'update_status';
    const SCENARIO_KEGIATAN_SKP = 'kegiatan_skp';
    const SCENARIO_KEGIATAN_TAMBAHAN = 'kegiatan_tambahan';

    const CKHP_TANPA_KETERANGAN = 1;
    const CKHP_HARI_LIBUR = 2;
    const CKHP_KETIDAKHADIRAN_CUTI = 3;
    const CKHP_KETIDAKHADIRAN_SAKIT = 4;
    const CKHP_KETIDAKHADIRAN_TUBEL = 5;
    const CKHP_ISI = 6;

    const UTAMA = 1;
    const TAMBAHAN = 2;

    public $waktu;
    public $mode = 'pegawai';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kegiatan_harian';
    }

    public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'status_hapus' => true
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return KegiatanHarianQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new KegiatanHarianQuery(get_called_class());
        $query->aktif();
        return $query;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'tanggal', 'uraian','jam_mulai','jam_selesai', 'id_instansi_pegawai'], 'required'],
            [['id_pegawai', 'id_kegiatan_tahunan', 'id_kegiatan_status', 'kuantitas',
                'id_pegawai_penyetuju','id_kegiatan_harian_jenis','id_kegiatan_harian_tambahan'
            ], 'integer'],
            ['status_hapus', 'boolean'],
            [['tanggal', 'jam_mulai', 'jam_selesai', 'waktu_disetujui','satuan','waktu_dihapus'], 'safe'],
            [['uraian', 'berkas'], 'string', 'max' => 255],
            // [['id_kegiatan_tahunan', 'tanggal'], 'unique', 'targetAttribute' => ['id_kegiatan_tahunan', 'tanggal']],
            ['id_kegiatan_status', 'default', 'value' => KegiatanStatus::KONSEP],
            [['id_kegiatan_tahunan'], 'exist', 'skipOnError' => true, 'targetClass' => KegiatanTahunan::className(), 'targetAttribute' => ['id_kegiatan_tahunan' => 'id']],
            // [['tanggal'], 'validasiRentang'],
            [['id_kegiatan_tahunan'],'required','on'=>self::SCENARIO_KEGIATAN_SKP],
            [['kuantitas'], 'required', 'when' => function($model) {
                return $model->id_kegiatan_harian_jenis == 1 AND $model->id_kegiatan_harian_versi == 1;
            }],
            [['realisasi_kuantitas'], 'required', 'when' => function($model) {
                if (Session::getTahun() >= 2022) {
                    return false;
                }

                return $model->id_kegiatan_harian_versi == 2;
            }],
            [['id_kegiatan_harian_tambahan'],'required','on'=>self::SCENARIO_KEGIATAN_TAMBAHAN],
            [['keterangan_tolak'], 'safe'],
            [['id_kegiatan_harian_versi'], 'integer'],
            [['realisasi_kuantitas', 'realisasi_kualitas', 'realisasi_waktu', 'realisasi_biaya'], 'number'],
            ['uraian', 'validateUraian'],
            [['realisasi'], 'required', 'when' => function(KegiatanHarian $data) {
                return $data->id_kegiatan_harian_versi == 3;
            }],
            [['realisasi'], 'number'],
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
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_kegiatan_tahunan' => 'Kegiatan SKP',
            'id_instansi_pegawai' => 'Instansi',
            'id_kegiatan_harian_tambahan' => 'Kegiatan Tambahan',
            'tanggal' => 'Tanggal',
            'uraian' => 'Uraian',
            'kuantitas' => 'Kuantitas',
            'jam_mulai' => 'Jam Mulai',
            'hariTanggal' => 'Hari/Tanggal',
            'jam_selesai' => 'Jam Selesai',
            'berkas' => 'Berkas',
            'id_kegiatan_harian_jenis'=>'Jenis Kegiatan',
            'id_kegiatan_status' => 'Status',
            'id_pegawai_penyetuju' => 'Id Pegawai Penyetuju',
            'waktu_disetujui' => 'Waktu Disetujui',
        ];
    }

    public function setIdKegiatanStatus($id_kegiatan_status)
    {
        $this->id_kegiatan_status = $id_kegiatan_status;
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getInstansiPegawaiViaPegawai()
    {
        return $this->hasOne(InstansiPegawai::class,['id_pegawai'=>'id_pegawai']);
    }

    public function getJabatan()
    {
        return $this->hasOne(Jabatan::class,['id'=>'id_jabatan'])
            ->via('instansiPegawaiViaPegawai');
    }

    public function getKegiatanTahunan()
    {
        return $this->hasOne(KegiatanTahunan::className(), ['id' => 'id_kegiatan_tahunan']);
    }

    public function getKegiatanHarianTambahan()
    {
        return $this->hasOne(KegiatanHarianTambahan::className(), ['id' => 'id_kegiatan_harian_tambahan']);
    }

    public function getKegiatanHarianJenis()
    {
        return $this->hasOne(KegiatanHarianJenis::className(), ['id' => 'id_kegiatan_harian_jenis']);
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::className(), ['id' => 'id_pegawai']);
    }

    public function getTanggal()
    {
        return $this->tanggal;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKegiatanStatus()
    {
        return $this->hasOne(KegiatanStatus::className(), ['id' => 'id_kegiatan_status']);
    }

    public function getAllKegiatanRiwayat()
    {
        return $this->hasMany(KegiatanRiwayatHarian::class, ['id_kegiatan' => 'id']);
    }

    public function getInstansiPegawai()
    {
        return $this->hasOne(InstansiPegawai::class, ['id' => 'id_instansi_pegawai']);
    }

    public function getManyCatatan()
    {
        return $this->hasMany(Catatan::class, ['id_kegiatan_harian' => 'id']);
    }

    public function findAllCatatan()
    {
        $query = $this->getManyCatatan();
        $query->orderBy(['waktu_buat' => SORT_ASC]);
        return $query->all();
    }

    public function getJamMulai()
    {
        return date('H:i', strtotime($this->jam_mulai));
    }

    public function getJamSelesai()
    {
        return date('H:i', strtotime($this->jam_selesai));
    }

    public function getWaktu()
    {
        return $this->getJamMulai() . ' - ' . $this->getJamSelesai();
    }

    public function loadDefaultAttributes()
    {
        if ($this->isNewRecord && User::isPegawai()) {
            /* @var $pegawai Pegawai */
            $pegawai = Yii::$app->user->identity->pegawai;
            if ($pegawai->getInstansiPegawaiBerlaku() !== null) {
                $this->id_instansi_pegawai = $pegawai->getInstansiPegawaiBerlaku()->id;
            }
        }
        if (!$this->isNewRecord && $this->getIsKegiatanSkp()) {
            $this->id_instansi_pegawai = @$this->kegiatanTahunan->id_instansi_pegawai;

            $pegawai = Pegawai::findOne($this->id_pegawai);
            if ($this->id_instansi_pegawai == null && $pegawai != null) {
                $this->id_instansi_pegawai = $pegawai->getIdInstansiBerlaku($this->tanggal);
            }
        }
        return;
    }

    public function getIdInstansiPegawaiBerlaku()
    {
        if (!$this->isNewRecord && $this->instansiPegawai !== null) {
            return $this->instansiPegawai->id;
        }
        return null;
    }

    /**
     * Ambil nama satuan dari kegiatan tahunan
     * @return string
     */
    public function getSatuanKuantitas()
    {
        return $this->getRelationField("kegiatanTahunan", "satuan_kuantitas");
    }

    /**
     * Ambil kuantitas dengan nama satuan
     * @return string
     */
    public function getKuantitasSatuan()
    {
        /*
        if ($this->getIsKegiatanSkp()) {
            return $this->kuantitas . ' ' . $this->getSatuanKuantitas();
        }
        */
        return $this->kuantitas . ' '.$this->satuan;

    }

    public function getRelationField($relation, $field)
    {
        return $this->$relation !== null ? $this->$relation->$field : null;
    }

    public function getIsKegiatanSubordinat()
    {
        return $this->isNewRecord ? false : $this->pegawai->kode_pegawai_atasan == Yii::$app->user->identity->kode_pegawai;
    }

    public function accessKodeKegiatanStatus()
    {
        if (User::isAdmin() OR $this->getIsKegiatanSubordinat()) {
            return true;
        }
        return false;
    }

    public function getHariTanggal()
    {
        if($this->tanggal!=null) {
            return Helper::getHari($this->tanggal) . ', ' . Helper::getTanggalSingkat($this->tanggal);
        } elseif($this->bulan!=null) {
            return Helper::getBulanLengkap($this->bulan).' '.User::getTahun();
        } else {
            return User::getTahun();
        }

    }

    public function accessSetPeriksa()
    {
        if(User::isPegawai() AND $this->id_pegawai != User::getIdPegawai()) {
            return false;
        }

        if($this->id_kegiatan_status==KegiatanStatus::KONSEP) {
            if (User::isPegawai()) {
                return true;
            }

            return false;
        }

        if($this->id_kegiatan_status==KegiatanStatus::TOLAK) {
            if (User::isPegawai()) {
                return true;
            }

            return false;
        }

        return false;
    }

    public function accessSetSetuju()
    {
        if (User::isPegawai() AND $this->id_pegawai == User::getIdPegawai()) {
            return false;
        }

        if ($this->id_kegiatan_status != KegiatanStatus::PERIKSA) {
            return false;
        }

        if(Session::isPemeriksaKinerja()) {
            return false;
        }

        return true;
    }

    public function accessSetKonsep()
    {
        if (User::isPegawai() AND $this->id_pegawai == User::getIdPegawai()) {
            return false;
        }

        if ($this->id_kegiatan_status != KegiatanStatus::SETUJU
            AND $this->id_kegiatan_status != KegiatanStatus::TOLAK
        ) {
            return false;
        }

        if(Session::isPemeriksaKinerja()) {
            return false;
        }

        return true;
    }

    public function accessSetTolak()
    {
        if (User::isPegawai() AND $this->id_pegawai == User::getIdPegawai())
            return false;

        if ($this->id_kegiatan_status!=KegiatanStatus::PERIKSA) {
            return false;
        }

        if(Session::isPemeriksaKinerja()) {
            return false;
        }

        return true;
    }

    public function accessUpdate()
    {
        if(User::isAdmin()) {
            return true;
        }
        if(User::isPegawai() AND $this->id_pegawai != User::getIdPegawai()) {
            return false;
        }
        if($this->id_kegiatan_status==KegiatanStatus::KONSEP) {
            return true;
        }
        if($this->id_kegiatan_status==KegiatanStatus::TOLAK) {
            return true;
        }

        return false;
    }

    public function accessDelete()
    {
        if(User::isAdmin()) {
            return true;
        }
        if(User::isPegawai() AND $this->id_pegawai != User::getIdPegawai()) {
            return false;
        }
        if($this->id_kegiatan_status==KegiatanStatus::KONSEP) {
            return true;
        }
        if($this->id_kegiatan_status==KegiatanStatus::TOLAK) {
            return true;
        }

        return false;
    }

    public static function accessCreate()
    {
        if(Session::isPemeriksaKinerja()) {
            return false;
        }

        return true;
    }

    public function accessCreateSub()
    {
        return (int) $this->id_kegiatan_status === KegiatanStatus::KONSEP;
    }

    public function getKegiatanBulanan()
    {
        $bulan = date('n', strtotime($this->tanggal));

        return $this->hasOne(KegiatanBulanan::class, ['id_kegiatan_tahunan' => 'id_kegiatan_tahunan'])
                    ->andWhere(['bulan'=>$bulan]);
    }

    public function getListKegiatanTahunan($params=[])
    {
        if (User::isPegawai()) {
            $this->id_pegawai = User::getIdPegawai();
        }

        if ($this->tanggal == null) {
            $this->tanggal = date('Y-m-d');
        }

        $target_is_not_null = true;
        if (@$params['target_is_not_null'] != null) {
            $target_is_not_null = @$params['target_is_not_null'];
        }

        $datetime = \DateTime::createFromFormat('Y-m-d', $this->tanggal);

        $query = KegiatanTahunan::find();
        $query->joinWith(['manyKegiatanBulanan', 'instansiPegawaiSkp']);
        $query->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_jenis' => $this->id_kegiatan_harian_jenis]);
        $query->andWhere(['kegiatan_bulanan.bulan'=> $datetime->format('n')]);
        if ($target_is_not_null == true) {
            $query->andWhere('kegiatan_bulanan.target IS NOT NULL');
        }
        $query->andWhere([
            'kegiatan_tahunan.id_kegiatan_status' => KegiatanStatus::SETUJU,
            'kegiatan_tahunan.id_pegawai' => $this->id_pegawai,
            'kegiatan_tahunan.tahun' => User::getTahun(),
        ])
        ->aktif();

        $query->andFilterWhere(['kegiatan_tahunan.id_kegiatan_tahunan_versi' => @$params['id_kegiatan_tahunan_versi']]);

        return ArrayHelper::map($query->all(),'id','nama');
    }

    public function getListKegiatanBulanan()
    {
        $bulan = date('n', strtotime($this->tanggal));

        $query = KegiatanBulanan::find();
        $query->joinWith(['kegiatanTahunan']);

        if (User::isPegawai()) {
            $this->id_pegawai = User::getIdPegawai();
        }

        $query->andFilterWhere(['kegiatan_tahunan.id_pegawai'=>$this->id_pegawai]);

        $query->andWhere('kegiatan_bulanan.target IS NOT NULL');
        $query->andWhere([
            'bulan'=>$bulan,
            'kegiatan_tahunan.id_kegiatan_status'=>KegiatanStatus::SETUJU,
            'kegiatan_tahunan.tahun'=>User::getTahun()
        ]);

        $list = [];

        foreach ($query->all() as $data) {
            $list[$data->id_kegiatan_tahunan] = $data->kegiatanTahunan->nama. ' ('.$data->kegiatanTahunan->getTextInduk().')';
        }

        return $list;
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }
        KegiatanRiwayatHarian::createRiwayat($this, RiwayatJenis::DELETE);
        return true;
    }

    public function beforeSoftDelete()
    {
        KegiatanRiwayatHarian::createRiwayat($this, RiwayatJenis::DELETE);
        $this->waktu_dihapus = date('Y-m-d H:i:s');
        return true;
    }

    public function getNamaKegiatanTahunan()
    {
        if (!empty($this->kegiatanTahunan)) {
            return $this->kegiatanTahunan->nama;
        }

        if ($this->id_kegiatan_tahunan == null) {
            return "Kegiatan Tambahan";
        }
    }

    public function afterSave($insert,$changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->scenario === self::SCENARIO_UPDATE_STATUS) {
            KegiatanRiwayatHarian::createRiwayat($this, RiwayatJenis::SUNTING, $this->id_kegiatan_status);
            return true;
        }
        if ($insert) {
            KegiatanRiwayatHarian::createRiwayat($this, RiwayatJenis::TAMBAH);
        } else {
            KegiatanRiwayatHarian::createRiwayat($this, RiwayatJenis::SUNTING);
        }
        return true;
    }

    public function getIsKegiatanSkp()
    {
        return $this->isKegiatanSkp();
    }

    public function isKegiatanSkp()
    {
        return (int) $this->id_kegiatan_harian_jenis === KegiatanHarianJenis::KEGIATAN_SKP;
    }

    public function getIsKegiatanTambahan()
    {
        return (int) $this->id_kegiatan_harian_jenis === KegiatanHarianJenis::KEGIATAN_TAMBAHAN;
    }

    public function getIsKegiatanDisetujui()
    {
        return (int) $this->id_kegiatan_status === KegiatanStatus::SETUJU;
    }

    public function getIsKegiatanKonsep()
    {
        return (int) $this->id_kegiatan_status === KegiatanStatus::KONSEP;
    }

    public function getIsKegiatanPeriksa()
    {
        return (int) $this->id_kegiatan_status === KegiatanStatus::PERIKSA;
    }

    public function getIsKegiatanDitolak()
    {
        return (int) $this->id_kegiatan_status === KegiatanStatus::TOLAK;
    }

    public function validasiRentang($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ((User::isPegawai() || User::isInstansi()) && !$this->getIsInRange(date('Y-m-d'))) {
                $this->addError($attribute, 'Batas penginputan ckhp maksimal 5 (Lima) hari kerja dari sebelumnya.');
            }
        }
    }

    public function validateKegiatanHarianJenis()
    {
        $this->addError('id_kegiatan_tahunan','Err');
        if($this->id_kegiatan_harian_jenis==1 AND $this->id_kegiatan_tahunan==null) {
            $this->addError('id_kegiatan_tahunan','Kegiatan SKP tidak boleh kosong');
        }

        if($this->id_kegiatan_harian_jenis==2 AND $this->id_kegiatan_harian_tambahan==null) {
            $this->addError('id_kegiatan_harian_tambahan','Kegiatan tambahan tidak boleh kosong');
        }
    }

    public function setScenarioKegiatan()
    {
        if($this->id_kegiatan_harian_jenis==1) {
            $this->setScenario('kegiatan_skp');
        }

        if($this->id_kegiatan_harian_jenis==2) {
            $this->setScenario('kegiatan_tambahan');
        }
    }

    /**
     * @return InstansiPegawaiSkp
     */
    public function getInstansiPegawaiSkpFromTanggal()
    {
        $query = InstansiPegawaiSkp::find();
        $query->joinWith(['instansiPegawai']);

        $query->andWhere([
            'instansi_pegawai.id_pegawai'=>$this->id_pegawai
        ]);

        $query->andWhere('instansi_pegawai_skp.tanggal_mulai <= :tanggal AND
            instansi_pegawai_skp.tanggal_selesai >= :tanggal',[
            ':tanggal'=>$this->tanggal
        ]);

        $query->orderBy(['instansi_pegawai.tanggal_mulai' => SORT_DESC]);

        return $query->one();
    }

    public function getNomorSkp()
    {
        $model = $this->getInstansiPegawaiSkpFromTanggal();

        if($model!=null) {
            return $model->nomor;
        }
    }

    public function getNomorSkpLengkap()
    {
        $model = $this->getInstansiPegawaiSkpFromTanggal();

        if($model!=null) {
            return $model->nomor.' : '.$model->getNamaJabatan().' - '.$model->getNamaInstansi();
        }
    }

    public function getIdInstansiPegawai()
    {
        $model = $this->getInstansiPegawaiSkpFromTanggal();

        if($model!=null) {
            return $model->id_instansi_pegawai;
        }

        return null;

    }

    public function getTahun()
    {
        if($this->tanggal==null) {
            return null;
        }

        $date = DateTime::createFromFormat('Y-m-d',$this->tanggal);
        return $date->format('Y');

    }

    public function updateRealisasiKegiatanBulanan()
    {
        if($this->isKegiatanSkp() == false) {
            return false;
        }

        if($this->id_kegiatan_status != KegiatanStatus::SETUJU) {
            return false;
        }

        if($this->kegiatanBulanan === null) {
            return false;
        }

        $this->kegiatanBulanan->updateRealisasi();
    }

    public function getNamaKegiatanHarianJenis()
    {
        if($this->id_kegiatan_harian_jenis == 1) {
            return "Kinerja Utama";
        }

        if($this->id_kegiatan_harian_jenis == 2) {
            return "Kinerja Tambahan";
        }
    }

    public function getKeteranganTolak()
    {
        if($this->id_kegiatan_status != KegiatanStatus::TOLAK) {
            return;
        }

        if($this->keterangan_tolak == null) {
            return;
        }

        return "(Ket: $this->keterangan_tolak)";
    }

    public static function redirectV3()
    {
        $force = Yii::$app->request->get('force');
        if ($force == true) {
            return false;
        }

        $url = Yii::$app->request->url;
        $url = str_replace('v2', 'v3', $url);

        return Yii::$app->controller->redirect($url);
    }

    public function updateTanggal()
    {
        $tanggal = $this->tanggal;
        if ($tanggal == null) {
            $tanggal = date('Y-m-d');
        }

        $datetime = \DateTime::createFromFormat('Y-m-d', $tanggal);

        if ($this->isDiskresi() == true
            OR $datetime->format('Y-m') == '2022-04'
            OR $datetime->format('Y-m') == '2022-05'
        ) {
            $this->tanggal = $tanggal;
            return $this->tanggal;
        }

        $this->tanggal = date('Y-m-d');
        return $this->tanggal;
    }

    public function setWaktuDibuat()
    {
        $this->waktu_dibuat = date('Y-m-d H:i:s');
        return $this->waktu_dibuat;
    }

    public function isDiskresi(): bool
    {
        $query = KegiatanHarianDiskresi::find();
        $query->andWhere(['id_pegawai' => $this->id_pegawai]);
        $query->andWhere(['tanggal' => $this->tanggal]);

        $model = $query->one();

        if ($model == null) {
            return false;
        }

        return true;
    }

    public function getStringWaktuDibuat()
    {
        $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->waktu_dibuat);
        if ($datetime == false) {
            return null;
        }

        $tanggal = $datetime->format('Y-m-d');
        $waktu = $datetime->format('H:i:s');

        return Helper::getTanggal($tanggal) . ', ' . $waktu;
    }

    public function validateUraian()
    {
        if ($this->isAttributeChanged('uraian') == false) {
            return true;
        }

        $words = ['apel', 'aple', 'senam', 'senan', 'upacara', 'olahraga'];

        foreach ($words as $word) {
            $uraian = strtolower($this->uraian);

            if (strpos($uraian, $word) !== false) {
                $this->addError('uraian', 'Kata/Kalimat "' . strtoupper($word) . '" tidak diperbolehkan');
            }
        }

        if (strlen($this->uraian) < 20) {
            $this->addError('uraian', 'Uraian minimal 20 karakter');
        }
    }

    public function canVerifikasi()
    {
        $instansiPegawai = $this->instansiPegawai;

        if ($this->id_kegiatan_status == KegiatanStatus::KONSEP
            OR $this->id_kegiatan_status == KegiatanStatus::TOLAK
        ) {

            if (Session::isAdmin()) {
                return true;
            }

            if (Session::isPegawai() AND $this->id_pegawai == Session::getIdPegawai()) {
                return true;
            }

        }

        if ($this->id_kegiatan_status == KegiatanStatus::PERIKSA) {

            if (Session::isAdmin()) {
                return true;
            }

            if (Session::isPegawai()
                AND in_array(@$instansiPegawai->jabatan->id_induk, User::getIdJabatanBerlaku())
            ) {
                return true;
            }
        }

        return false;
    }
}
