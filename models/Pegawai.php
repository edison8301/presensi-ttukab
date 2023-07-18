<?php

namespace app\models;

use app\components\Config;
use app\models\User;
use app\modules\absensi\models\KetidakhadiranPanjang;
use app\modules\absensi\models\PegawaiAbsensiManual;
use app\modules\absensi\models\PegawaiDispensasiJenis;
use app\modules\kinerja\models\KegiatanHarianDiskresi;
use app\modules\tunjangan\models\JabatanTunjanganGolongan;
use DateTime;
use Yii;
use app\components\Helper;
use app\components\Session;
use app\components\TunjanganBulan;
use app\modules\absensi\models\Absensi;
use app\modules\absensi\models\HukumanDisiplin;
use app\modules\absensi\models\InstansiAbsensiManual;
use app\modules\absensi\models\JamKerja;
use app\modules\absensi\models\Ketidakhadiran;
use app\modules\absensi\models\KetidakhadiranJamKerja;
use app\modules\absensi\models\KetidakhadiranJenis;
use app\modules\absensi\models\KetidakhadiranKegiatanJenis;
use app\modules\absensi\models\KetidakhadiranPanjangJenis;
use app\modules\absensi\models\KetidakhadiranPanjangStatus;
use app\modules\absensi\models\PegawaiDispensasi;
use app\modules\absensi\models\PegawaiRekapAbsensi;
use app\modules\absensi\models\PegawaiShiftKerja;
use app\modules\absensi\models\PresensiCeklis;
use app\modules\absensi\models\ShiftKerja;
use app\modules\iclock\models\Checkinout;
use app\modules\iclock\models\Devcmds;
use app\modules\iclock\models\Userinfo;
use app\modules\kinerja\models\InstansiPegawaiSkp;
use app\modules\kinerja\models\KegiatanBulanan;
use app\modules\kinerja\models\KegiatanHarian;
use app\modules\kinerja\models\KegiatanStatus;
use app\modules\kinerja\models\KegiatanTahunan;
use app\modules\kinerja\models\KegiatanRhk;
use app\modules\kinerja\models\PegawaiRekapKinerja;
use app\modules\tukin\models\Jabatan;
use app\modules\tunjangan\models\JabatanGolongan;
use kartik\editable\Editable;
use yii\base\InvalidConfigException;
use yii2mod\query\ArrayQuery;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\httpclient\Client;

/**
 * This is the model class for table "pegawai".
 *
 * @property integer $id
 * @property string $nama
 * @property string $nip
 * @property integer $id_instansi
 * @property integer $id_jabatan
 * @property integer $id_atasan
 * @property string $gender
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string $alamat
 * @property string $telepon
 * @property string $email
 * @property string $foto
 * @property string $grade
 * @property int $id_golongan [int(11)]
 * @property int $id_instansi_pegawai_bak [int(11)]
 * @property bool $status_batas_pengajuan [tinyint(1)]
 * @property string $nama_jabatan [varchar(255)]
 * @property string $gelar_depan [varchar(255)]
 * @property string $gelar_belakang [varchar(255)]
 * @property int $id_eselon [int(2)]
 * @property string $eselon_bak [varchar(255)]
 * @property int $id_pegawai_status [int(11)]
 * @property string $status_hapus [datetime]
 * @property int $jumlah_userinfo [int(11)]
 * @property bool status_absen_ceklis
 * @property int $jenis_absen_ceklis
 * @property int $status_admin_mesin_absensi
 * @property mixed $isEselonIII
 * @property mixed $user
 * @property null $checktimeTerakhir
 * @property string $ceklisSkp
 * @property mixed $allPegawaiMutasi
 * @property \yii\db\ActiveQuery $manyKegiatanTahunan
 * @property mixed $manyKetidakhadiran
 * @property mixed $hasSkpDisetujui
 * @property InstansiPegawai[] $allInstansiPegawai
 * @property string $bulanLengkapTahun
 * @property int $potonganSkp
 * @property mixed $checkinoutTerakhir
 * @property array $arrayUserid
 * @property mixed $listAtasan
 * @property string $masa
 * @property mixed $idUser
 * @property mixed $manyKetidakhadiranKegiatan
 * @property mixed $manyUserinfo
 * @property int $pokokTunjangan
 * @property Jabatan $jabatan
 * @property mixed $allIdInstansiPegawai
 * @property mixed $instansiPegawai
 * @property mixed $pegawaiDispensasi
 * @property mixed $manyCheckinout
 * @property mixed $instansiMutasiNama
 * @property bool $isEselon
 * @property mixed $pegawaiInstansi
 * @property mixed $manyKetidakhadiranPanjang
 * @property string $namaNip
 * @property mixed $manyKetidakhadiranJamKerja
 * @property mixed $eselon
 * @property mixed $isEselonII
 * @property string $textChecktimeTerakhir
 * @property bool $isBatasPengajuan
 * @property mixed $encodeNama
 * @property Instansi $instansi
 * @property bool $isEselonV
 * @property mixed $hasSkp
 * @property mixed $hukumanDisiplin
 * @property mixed $manyKegiatanHarian
 * @property mixed $manyPegawaiShiftKerja
 * @property mixed $isEselonIV
 * @property mixed $namaInstansi
 * @property mixed $isEselonI
 * @property mixed $allCheckinout
 * @property mixed $atasan
 * @property mixed $manyKegiatanHarianTahun
 * @property int $jumlah_checkinout [int(11)]
 * @property PegawaiRekapKinerja manyPegawaiRekapKinerja
 * @property Pegawai $pegawai
 * @property KegiatanHarian[] $_manyKegiatanHarian
 * @property InstansiPegawai[] $manyInstansiPegawai
 * @property string $namaPegawaiAtasan
 * @property null $linkIconUserSetPassword
 * @property mixed $manyInstansiPegawaiSkp
 * @property null|string $linkIconDelete
 * @property string $namaJabatanAtasan
 * @property bool $isAbsenCeklis
 * @property string $nipFormat
 * @property null|string $linkIconUpdate
 * @property null|string $linkIconView
 * @property null $linkButtonUpdate
 * @property InstansiPegawai $instansiPegawaiBerlaku dari magic method getInstansiPegawaiBerlaku()
 * @property mixed|int $jumlahKetidakhadiranKegiatanTanpaKeteranganSelainSidak
 * @property float|int $persenPotonganKetidakhadiranKegiatanTanpaKeteranganSidak
 * @property mixed $labelGolongan
 * @property float|int $persenPotonganPulangAwalKeluarKerjaSemuaInterval
 * @property float|int $persenPotonganPresensi
 * @property mixed $allPegawaiGolongan
 * @property mixed $manyKegiatanBulanan
 * @property mixed|int $jumlahPulangAwalKeluarKerjaSemuaInterval
 * @property mixed $jabatanTukin
 * @property float|int $persenPotonganKetidakhadiranKegiatanTanpaKeteranganSelainSidak
 * @property mixed $jabatanGolongan
 * @property bool $isAbsensiManual
 * @see Pegawai::getPegawaiGolongan()
 * @property PegawaiGolongan $pegawaiGolongan
 * @property mixed $jumlahKetidakhadiranKegiatanTanpaKeteranganSidak
 * @property mixed $manyPegawaiGolongan
 * @property mixed $manyPegawaiRekapAbsensi
 * @property string $kodePresensi
 * @property mixed $listIdInstansiPegawai
 * @property float|int $persenPotonganTidakHadirTanpaKeterangan
 * @property mixed $onePegawaiRekapKinerja
 * @property null|string $linkIconUpdateGolongan
 * @property TunjanganBulan $tunjanganBulan
 * @see Pegawai::getPendidikan()
 * @property Pendidikan $pendidikan
 * @see Pegawai::getGolongan()
 * @property Golongan $golongan
 * @see Pegawai::getPegawaiGolonganBerlaku()
 * @property PegawaiGolongan $pegawaiGolonganBerlaku
 * @property InstansiPegawai $instansiPegawaiBerlakuPlt
 * @property int $id_pegawai_jenis
 */
class Pegawai extends \yii\db\ActiveRecord
{
    public $_hari = [];
    public $_ketidakhadiran = [];
    public $_ketidakhadiran_jam_kerja = [];
    public $_ketidakhadiran_panjang = [];
    public $_checkinout = [];
    public $_checkinout_dalam_kantor = [];
    public $_checkinout_luar_kantor = [];
    public $_jam_kerja = [];
    public $_pegawai_shift_kerja = [];
    public $_potongan_bulan_fingerprint = 0;
    public $_potongan_bulan_kegiatan = 0;
    public $_potongan_bulan_total = 0;
    public $_potongan_bulan = 0;
    public $_potongan_hari = 0;
    public $_potongan_hari_keterangan = "";
    public $_potongan_jam_kerja = 0;
    public $_status_hari_tanpa_keterangan = false;
    public $_potongan_jam_kerja_keterangan = "";
    public $_hari_kerja = 0;
    public $_hari_hadir = 0;
    public $_hari_tidak_hadir = 0;
    public $_hari_libur = [];
    public $_hari_ketidakhadiran = [];
    public $_jumlah_ketidakhadiran_kegiatan_tanpa_keterangan = [];
    public $_hari_tanpa_keterangan = 0;
    public $_userid = [];
    public $_jumlah_ketidakhadiran_jam_tanpa_keterangan;
    public $tahun;
    protected $_ketidakhadiran_bulan = [];
    protected $_ketidakhadiran_panjang_bulan = [];
    protected $_skp = [];
    protected $_potongan_ckhp = [];
    protected $_pegawai_dispensasi;
    protected $_has_dispensasi;
    protected $_instansi_mutasi;
    protected $_jumlah_terlambat_masuk_kerja_interval = [];
    protected $_jumlah_terlambat_masuk_istirahat_interval = [];
    protected $_jumlah_pulang_awal_keluar_kerja_interval = [];
    protected $_jumlah_pulang_awal_keluar_kerja = 0;

    /*
     *
     * */
    public $kode_presensi;

    /**
     * array $_potongan_displin dengan index bulan (1 - 12)
     */
    protected $_potongan_disiplin = [];
    private $_manyKegiatanHarian;
    /**
     * @var mixed|null
     */
    private $_jam_absensi;
    /**
     * @var int|mixed|null
     */
    private $_menit_telat;

    public $platform = 'website';

    /**
     * @var KegiatanHarian[]
     */
    public $_kegiatan_harian = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pegawai';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama', 'nip', 'id_instansi'], 'required'],
            [
                [
                    'id_instansi', 'id_jabatan', 'id_atasan', 'id_pegawai_status', 'id_golongan',
                    'jumlah_userinfo', 'jumlah_checkinout', 'id_eselon', 'jenis_absen_ceklis',
                    'id_pegawai_jenis',
                ],
                'integer',
            ],
            [['jenis_absen_ceklis'], 'required', 'when' => function (self $row) {
                return $row->getIsAbsensiManual();
            }],
            [['tanggal_lahir', 'status_admin_mesin_absensi'], 'safe'],
            [['alamat'], 'string'],
            [['nama', 'gender', 'tempat_lahir', 'telepon', 'email'], 'string', 'max' => 1000],
            [['nip'], 'string', 'max' => 50],
            [['nama_jabatan', 'gelar_depan', 'gelar_belakang'], 'string', 'max' => 255],
            [['foto'], 'string', 'max' => 200],
            [['grade'], 'string', 'max' => 10],
            [['nip'], 'unique'],
            [['status_batas_pengajuan', 'status_absen_ceklis'], 'boolean'],
            [['kode_golongan'], 'safe'],
            [['tmt_jabatan', 'tmt_golongan'], 'safe'],
            [['id_pendidikan'], 'safe'],
            [['nik', 'status_update_nik'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'nip' => 'NIP',
            'id_instansi' => 'Instansi',
            'id_jabatan' => 'Jabatan',
            'nama_jabatan' => 'Jabatan',
            'id_atasan' => 'Atasan',
            'gender' => 'Gender',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'alamat' => 'Alamat',
            'telepon' => 'Telepon',
            'email' => 'Email',
            'foto' => 'Foto',
            'grade' => 'Grade',
            'id_pegawai_status' => 'Status',
            'id_golongan' => 'Golongan',
            'id_eselon' => 'Eselon',
            'status_batas_pengajuan' => 'Batas Pengajuan',
            'status_absen_ceklis' => 'Absensi Manual',
            'jenis_absen_ceklis' => 'Jenis Shift Kerja',
            'id_pegawai_jenis' => 'Jenis Pegawai',
        ];
    }

    /**
     * @inheritdoc
     * @return PegawaiQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new PegawaiQuery(get_called_class());
        $query->aktif();
        return $query;
    }

    /**
     * @return array|\yii\db\ActiveRecord|null|Userinfo
     */
    public function getOneUserInfo()
    {
        return $this->getManyUserinfo()->orderBy(['userid' => SORT_DESC])->one();
    }

    public function getKodePresensi()
    {
        /* @var $query Userinfo */
        $query = $this->getOneUserInfo();

        if ($query !== null) {
            return $query->userid;
        }

        return null;
    }

    public function getManyKegiatanHarianTahun()
    {
        $tahun = User::getTahun();
        return $this
            ->getManyKegiatanHarian()
            ->andWhere(['between', 'tanggal', date("$tahun-m-01"), "$tahun-12-01"]);
    }

    public function getManyKegiatanHarian()
    {
        return $this->hasMany(KegiatanHarian::class, ['id_pegawai' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManyPegawaiGolongan()
    {
        return $this->hasMany(PegawaiGolongan::class, ['id_pegawai' => 'id']);
    }

    public function getManyKegiatanBulanan()
    {
        return $this->hasMany(KegiatanBulanan::class, ['id_pegawai' => 'id'])
            ->via('manyKegiatanTahunan');
    }

    public function getGolongan()
    {
        return @$this->pegawaiGolongan->golongan;
        //return $this->hasOne(Golongan::class, ['id' => 'id_golongan']);
    }

    public function getPendidikan()
    {
        return $this->hasOne(Pendidikan::class, ['id' => 'id_pendidikan']);
    }

    public function getPegawaiGolongan()
    {
        return $this->hasOne(PegawaiGolongan::class, ['id_pegawai' => 'id'])
            ->orderBy(['tanggal_selesai' => SORT_DESC]);
    }

    public function getPegawaiJenis()
    {
        return $this->hasOne(PegawaiJenis::class, ['id' => 'id_pegawai_jenis']);
    }

    public static function getListBawahan($params = [])
    {
        $query = InstansiPegawai::find();
        $query->filterByBulanTahun(@$params['bulan'], @$params['tahun']);

        $query->joinWith(['jabatan']);
        $query->andWhere([
            'jabatan.id_induk' => User::getIdJabatanBerlaku([
                'bulan' => @$params['bulan'],
                'tahun' => @$params['tahun']
            ])
        ]);

        $query->joinWith(['pegawai']);
        $query->orderBy(['pegawai.nama' => SORT_ASC]);


        $list = [];
        foreach ($query->all() as $data) {
            $list[$data->id_pegawai] = $data->pegawai->nama . ' (' . $data->pegawai->nip . ')';
        }

        return $list;
    }

    /**
     * Jika Pegawai non eselon tetapi golongan di atas IV, Maka atasan adalah golongan 3 seinstansi atau
     * golongan 2 dari bebas instansi.
     * Jika Eselon III-b ke atas, atasan hanya ambil dari instansi terkait
     * Selain itu atasan bebas selama pegawai memiliki jabatan eselon lebih tinggi
     *
     * @param $id_eselon eselon pegawai
     * @param $id_instansi instansi pegawai
     * @param $id_golongan golongan pegawai
     * @param bool $map apakah fungsi direturn menggunakan ArrayHelper::map() untuk dropdown / select2 tradisional atau
     * digunakan pada depdrop
     * @return array
     */
    public static function getListJson($id_eselon, $id_instansi, $id_golongan, $map = false)
    {
        if ((int)$id_eselon === Eselon::NON_ESELON && $id_golongan > 12) {
            $query = static::find()
                ->where('id_eselon < 5 OR (id_eselon = 5 AND id_instansi = :id_instansi)', [':id_instansi' => $id_instansi]);
        } elseif ($id_eselon > 5) {
            $query = static::find()->andWhere(['<', 'id_eselon', $id_eselon]);
            $instansi = Instansi::findOne($id_instansi);
            if ($instansi->getIsUptdHasInduk()) {
                $query->andWhere(['in', 'id_instansi', [$id_instansi, $instansi->id_induk]]);
            } else {
                $query->andWhere(['id_instansi' => $id_instansi]);
            }
        } else {
            $query = static::find()->andWhere(['<', 'id_eselon', $id_eselon]);
        }
        if ($map === true) {
            return ArrayHelper::map($query->all(), 'id', 'namaNip');
        }
        $list = [];
        foreach ($query->all() as $pegawai) {
            $list[] = ['id' => $pegawai->id, 'name' => $pegawai->namaNip];
        }
        return $list;
    }

    public static function accessIndex()
    {
        if (User::isAdmin()) {
            return true;
        }

        return false;
    }

    public static function getListShiftKerja()
    {
        return [
            38 => 'Shift Kerja Guru',
            39 => 'Shift Kerja Staff TU',
        ];
    }

    public static function accessCreate()
    {
        if (User::isAdmin()) {
            return true;
        }

        return false;
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::className(), ['id' => 'id_instansi'])
            ->inverseOf('manyPegawai');
    }

    public function getJabatan()
    {
        return $this->hasOne(Jabatan::className(), ['id' => 'id_jabatan']);
    }

    public function getAtasan()
    {
        return $this->hasOne(static::className(), ['id' => 'id_atasan']);
    }

    public function getManyInstansiPegawai()
    {
        return $this->hasMany(InstansiPegawai::class, ['id_pegawai' => 'id']);
    }

    public function getManyInstansiPegawaiSkp()
    {
        return $this->hasMany(InstansiPegawaiSkp::class, ['id_instansi_pegawai' => 'id'])
            ->via('manyInstansiPegawai');
    }

    public function getCountPegawaiGolongan()
    {
        return $this->getManyPegawaiGolongan()->count();
    }

    public function getAllIdInstansiPegawai()
    {
        return $this->getAllInstansiPegawai()
            ->select('id_instansi')
            ->groupBy('id_instansi')
            ->column();
    }

    public function getAllInstansiPegawai()
    {
        return $this->hasMany(InstansiPegawai::className(), ['id_pegawai' => 'id'])
            ->orderBy(['tanggal_berlaku' => SORT_ASC]);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id_pegawai' => 'id']);
    }

    public function getNamaNip()
    {
        return $this->nama . ' - ' . $this->nip;
    }

    public function getNamaInstansi($params = [])
    {
        $bulan = @$params['bulan'];
        $tahun = @$params['tahun'];

        if ($bulan == null) {
            $bulan = date('n');
        }

        if ($tahun == null) {
            $tahun = date('Y');
        }

        $date = DateTime::createFromFormat('Y-n-d', $tahun . '-' . $bulan . '-01');

        $instansiPegawai = $this->getInstansiPegawaiBerlaku($date->format('Y-m-15'));

        if ($instansiPegawai != null) {
            return $instansiPegawai->instansi->nama;
        }
    }

    /**
     * @param null $tanggal
     * @return InstansiPegawai
     */
    public function getInstansiPegawaiBerlaku($tanggal = null)
    {
        return $this->getInstansiPegawai()
            ->berlaku($tanggal)
            ->andWhere(['status_plt' => 0])
            ->one();
    }

    /**
     * @param null $tanggal
     * @return InstansiPegawai
     */
    public function getInstansiPegawaiBerlakuPlt($tanggal = null)
    {
        return $this->getInstansiPegawai()
            ->berlaku($tanggal)
            ->andWhere(['status_plt' => 1])
            ->one();
    }

    /**
     * @return InstansiPegawaiQuery
     */
    public function getInstansiPegawai()
    {
        return $this->hasOne(InstansiPegawai::class, ['id_pegawai' => 'id'])
            ->berlaku();
    }

    /**
     * @return bool
     */

    private $_is_absensi_manual;

    public function getIsAbsensiManual($params=[])
    {
        if ($this->_is_absensi_manual !== null) {
            return $this->_is_absensi_manual;
        }

        $bulan = @$params['bulan'];

        $status_manual = false;

        $pegawaiAbsensiManual = $this->getPegawaiAbsensiManualBerlaku([
            'bulan' => @$params['bulan'],
        ]);

        if ($pegawaiAbsensiManual !== null) {
            $is_absensi_manual = $pegawaiAbsensiManual->status == PegawaiAbsensiManual::AKTIF;
            $this->_is_absensi_manual = $is_absensi_manual;
            return $this->_is_absensi_manual;
        }

        $id_instansi = $this->getIdInstansiBerlaku($bulan);

        if ($id_instansi !== null) {
            $status_manual = InstansiAbsensiManual::isManual([
                'id_instansi' => $id_instansi,
                'bulan' => $bulan,
            ]);
        }

        $this->_is_absensi_manual = $status_manual;

        return $this->_is_absensi_manual;
    }

    public function getPegawaiAbsensiManualBerlaku($params=[])
    {
        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        if ($tahun == null) {
            $tahun = Session::getTahun();
        }

        if ($bulan == null) {
            $bulan = date('m');
        }

        $datetime = \DateTime::createFromFormat('Y-m-d', $tahun . '-' . $bulan . '-01');

        $query = PegawaiAbsensiManual::find();
        $query->andWhere(['id_pegawai' => $this->id]);
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
            ':tanggal' => $datetime->format('Y-m-15')
        ]);

        return $query->one();
    }

    public static function getList($params = [], $bawahan = false, $withNip = true)
    {
        $query = static::find();

        if ($bawahan === true) {
            $query->joinWith(['allInstansiPegawai'])
                ->andWhere(['instansi_pegawai.id_atasan' => User::getIdPegawai()]);
        }

        if (User::isPegawai() and $bawahan !== true) {
            $query->andWhere(['id' => User::getIdPegawai()]);
        }

        if (User::isInstansi() || User::isAdminInstansi() || User::isOperatorAbsen()) {
            $query->joinWith(['allInstansiPegawai'])
                ->andWhere(['instansi_pegawai.id_instansi' => User::getIdInstansi()]);
        }

        if (User::isVerifikator()) {
            $query->joinWith(['allInstansiPegawai']);
            $query->andWhere(['instansi_pegawai.id_instansi' => User::getListIdInstansi()]);
        }

        if (isset($params['id_instansi'])) {
            $query->joinWith(['allInstansiPegawai']);
            $query->andWhere(['instansi_pegawai.id_instansi' => $params['id_instansi']]);
        }
        $attribute = $withNip ? 'namaNip' : 'nama';
        return ArrayHelper::map($query->all(), 'id', $attribute);
    }

    public function getNamaJabatan($params = [])
    {
        $bulan = @$params['bulan'];
        $tahun = @$params['tahun'];

        if ($bulan == null) {
            $bulan = date('n');
        }

        if ($tahun == null) {
            $tahun = date('Y');
        }

        $date = DateTime::createFromFormat('Y-n-d', $tahun . '-' . $bulan . '-01');

        $instansiPegawai = $this->getInstansiPegawaiBerlaku($date->format('Y-m-15'));

        if ($instansiPegawai != null) {
            return $instansiPegawai->jabatan->getNamaJabatan([
                'status_plt' => $instansiPegawai->status_plt == 1
            ]);
        }

        return null;
    }

    public function getRelationField($relation, $field)
    {
        return $this->$relation !== null ? $this->$relation->$field : null;
    }

    public function createUser()
    {
        $user = $this->user;
        if ($user === null) {
            $user = new User;
            $user->username = $this->nip;
            $user->id_pegawai = $this->id;
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->nip);
            $user->id_user_role = UserRole::PEGAWAI;

            if (!$user->save()) {
                print_r($user->getErrors());
                die();
            }
        }
        return $user;
    }

    public function getEncodeNama()
    {
        return Html::encode($this->nama);
    }

    public function getListAtasan()
    {
        $query = static::find()
            ->andWhere(['<', 'id_eselon', $this->id_eselon]);
        if (!$this->getIsNewRecord()) {
            $query->andWhere(['!=', 'id', $this->id]);
        }
        if (!$this->getIsEselonII()) {
            $query->andWhere(['id_instansi' => $this->id_instansi]);
        }
        return ArrayHelper::map($query->all(), 'id', 'namaNip');
    }

    public function getIsEselonII()
    {
        return in_array($this->id_eselon, Eselon::$eselon_ii);
    }

    public function findAllAbsensi($params = [])
    {

        $query = $this->queryAbsensi($params);
        $query->orderBy(['jam_absensi' => SORT_ASC]);

        return $query->all();
    }

    public function queryAbsensi($params = [])
    {
        $query = \app\modules\absensi\models\Absensi::find();
        $query->andWhere(['id_pegawai' => $this->kode_absensi]);

        if (!empty($params['tahun'])) {
            $tahun = $params['tahun'];
            $bulan_awal = 1;
            $bulan_akhir = 12;

            if (!empty($params['bulan'])) {
                $bulan_awal = $params['bulan'];
                $bulan_akhir = $params['bulan'];
            }

            $query->andWhere('tanggal_absensi >= :tanggal_absensi_awal AND tanggal_absensi <= :tanggal_absensi_akhir', [
                ':tanggal_absensi_awal' => $tahun . '-' . $bulan_awal . '-01',
                ':tanggal_absensi_akhir' => $tahun . '-' . $bulan_akhir . '-31',
            ]);
        }

        if (!empty($params['tanggal'])) {
            $tanggal = $params['tanggal'];

            $query->andWhere('tanggal_absensi = :tanggal_absensi', [
                ':tanggal_absensi' => $tanggal,
            ]);
        }

        return $query;
    }

    public function findOneAbsensi($params = [])
    {
        $query = $this->queryAbsensi($params);
        $query->orderBy(['jam_absensi' => SORT_ASC]);

        return $query->all();
    }

    public function getJumlahHadir($params = [])
    {
        $query = $this->queryAbsensi($params);

        $query->groupBy(['tanggal_absensi']);

        return $query->count();
    }

    public function getJumlahAbsensi($params = [])
    {
        $query = $this->queryAbsensi($params);

        return $query->count();
    }

    public function getJumlahKeterangan($params = [])
    {
        $query = $this->queryKeterangan($params);

        return $query->count();
    }

    public function queryKeterangan($params = [])
    {
        $query = \app\modules\absensi\models\Keterangan::find();
        $query->andWhere(['nip' => $this->nip]);

        if (!empty($params['tahun'])) {
            $tahun = $params['tahun'];
            $bulan_awal = 1;
            $bulan_akhir = 12;

            if (!empty($params['bulan'])) {
                $bulan_awal = $params['bulan'];
                $bulan_akhir = $params['bulan'];
            }

            $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir', [
                ':tanggal_awal' => $tahun . '-' . $bulan_awal . '-01',
                ':tanggal_akhir' => $tahun . '-' . $bulan_akhir . '-31',
            ]);
        }

        if (!empty($params['tanggal'])) {
            $tanggal = $params['tanggal'];

            $query->andWhere('tanggal = :tanggal', [
                ':tanggal' => $tanggal,
            ]);
        }

        if (!empty($params['jenis'])) {
            $jenis = $params['jenis'];

            $query->andWhere('id_keterangan_jenis = :jenis', [
                ':jenis' => $jenis,
            ]);
        }

        return $query;
    }

    public function getJumlahDinasLuar($params)
    {
        $params['jenis'] = \app\modules\absensi\models\KeteranganJenis::DINAS_LUAR;

        $query = $this->queryKeterangan($params);

        return $query->count();
    }

    public function getJumlahSakit($params)
    {
        $params['jenis'] = \app\modules\absensi\models\KeteranganJenis::SAKIT;

        $query = $this->queryKeterangan($params);

        return $query->count();
    }

    public function getJumlahIzin($params)
    {
        $params['jenis'] = \app\modules\absensi\models\KeteranganJenis::IZIN;

        $query = $this->queryKeterangan($params);

        return $query->count();
    }

    public function getJumlahCuti($params)
    {
        $params['jenis'] = \app\modules\absensi\models\KeteranganJenis::CUTI;

        $query = $this->queryKeterangan($params);

        return $query->count();
    }

    public function getPokokTunjangan()
    {
        $model = \app\modules\kinerja\models\GradeTunjangan::findOne(['id' => $this->grade]);

        if ($model !== null) {
            return $model->tunjangan;
        } else {
            return 0;
        }
    }

    public function findTunjanganKinerja($params = [])
    {
        return $this->findTunjangan('kinerja', $params);
    }

    public function findTunjangan($jenis, $params = [])
    {
        $bulan = \app\models\User::getBulan();
        $tahun = \app\models\User::getTahun();

        if (!empty($params['bulan'])) {
            $bulan = $params['bulan'];
        }

        if (!empty($params['tahun'])) {
            $bulan = $params['tahun'];
        }

        $query = \app\modules\kinerja\models\UserTunjangan::find();

        $query->andWhere([
            'nip' => $this->nip,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'jenis' => $jenis,
        ]);

        $model = $query->one();

        if ($model === null) {
            $model = new \app\modules\kinerja\models\UserTunjangan;
            $model->nip = $this->nip;
            $model->bulan = $bulan;
            $model->tahun = $tahun;
            $model->jenis = $jenis;

            $model->save();
        }

        return $model;
    }

    public function findTunjanganAbsensi($params = [])
    {
        return $this->findTunjangan('absensi', $params);
    }

    public function findAllJamKerja($tanggal)
    {
        $dateTime = date_create($tanggal);

        $query = \app\modules\absensi\models\JamKerja::find();
        $query->andWhere($dateTime->format('N'));

        return $query->all();
    }

    public function getJamAbsensi($tanggal, $jamKerja)
    {
        $query = $this->queryAbsensi(['tanggal' => $tanggal]);
        $query->andWhere('jam_absensi >= :jam_mulai_pindai AND jam_absensi <= :jam_selesai_pindai', [
            ':jam_mulai_pindai' => $jamKerja->jam_mulai_pindai,
            ':jam_selesai_pindai' => $jamKerja->jam_selesai_pindai,
        ]);

        $model = $query->one();

        $this->_jam_absensi = null;

        if ($model !== null) {
            $this->_jam_absensi = $model->jam_absensi;
        }

        return $this->_jam_absensi;
    }

    public function getMenitTelat($tanggal, $jamKerja)
    {
        $jamAbsensi = $this->_jam_absensi;

        if ($jamAbsensi == null) {
            $this->_menit_telat = 15;

            return $this->_menit_telat;

            /*
        if ($jamKerja->jenis == 1)
        $jamAbsensi = $jamKerja->jam_selesai_pindai;

        if ($jamKerja->jenis == 2)
        $jamAbsensi = $jamKerja->jam_mulai_pindai;
         */
        }

        if ($jamAbsensi >= $jamKerja->jam_mulai_normal and $jamAbsensi <= $jamKerja->jam_selesai_normal) {
            $this->_menit_telat = 0;

            return $this->_menit_telat;
        } else {

            if ($jamAbsensi < $jamKerja->jam_mulai_normal) {
                $interval = date_diff(date_create($jamKerja->jam_mulai_normal), date_create($jamAbsensi));
            }

            if ($jamAbsensi > $jamKerja->jam_selesai_normal) {
                $interval = date_diff(date_create($jamKerja->jam_selesai_normal), date_create($jamAbsensi));
            }

            //$interval = date_diff(date_create("09:30:00"),date_create("09:11:00"));

            $this->_menit_telat = $interval->format('%i');

            return $this->_menit_telat;
        }
    }

    public function getPersenPotongan($tanggal, $jamKerja)
    {
        $menitTelat = $this->_menit_telat;

        if ($menitTelat > 15) {
            return 45;
        } else {
            return $menitTelat * 3;
        }
    }

    /**
     * @param array $params 1.bulan 2.tahun
     */
    public function findAbsensiRekap($params = [])
    {
        // default values
        $bulan = \app\models\User::getBulan();
        $tahun = \app\models\User::getTahun();
        if (!empty($params['bulan'])) {
            $bulan = $params['bulan'];
        }
        if (!empty($params['tahun'])) {
            $bulan = $params['tahun'];
        }
        $rekap = $this->getAllPegawaiRekapAbsensi($bulan)->one();
        if ($rekap === null) {
            return $this->createPegawaiRekapAbsensi($bulan);
        }
        return $rekap;
    }

    /**
     * @param null $bulan
     * @param null $tahun ini untuk eksekusi via cmd
     * @return \yii\db\ActiveQuery
     */
    public function getAllPegawaiRekapAbsensi($bulan = null, $tahun = null)
    {
        if ($tahun == null) {
            $tahun = User::getTahun();
        }

        return $this->hasMany(PegawaiRekapAbsensi::class, ['id_pegawai' => 'id'])
            ->andWhere(['tahun' => $tahun])
            ->andFilterWhere(['bulan' => $bulan]);
    }

    protected function createPegawaiRekapAbsensi($bulan)
    {
        $model = new PegawaiRekapAbsensi();
        $model->nip = $this->nip;
        $model->bulan = $bulan;
        $model->tahun = User::getTahun();
        $model->save();

        return $model;
    }

    public function getAllCheckinout()
    {
        return $this
            ->hasMany(Checkinout::className(), ['userid' => 'userid'])
            ->via('userinfo');
    }

    public function countCheckinoutByTanggal($tanggal)
    {

        $params = [
            'checktime_awal' => $tanggal . ' 00:00:00',
            'checktime_akhir' => $tanggal . ' 23:59:59',
        ];

        return $this->countCheckinout($params);
    }

    public function countCheckinout($params = [])
    {
        $query = $this->queryCheckinout($params);
        return $query->count();
    }

    public function queryCheckinout($params = [])
    {

        $query = $this->getManyCheckinout();

        if (isset($params['userid'])) {
            $query = Checkinout::find();
            $query->andWhere(['userid' => $params['userid']]);
        }

        if (isset($params['checktime_awal'])) {
            $query->andWhere('checktime >= :checktime_awal', [
                ':checktime_awal' => $params['checktime_awal'],
            ]);
        }

        if (isset($params['checktime_akhir'])) {
            $query->andWhere('checktime <= :checktime_akhir', [
                ':checktime_akhir' => $params['checktime_akhir'],
            ]);
        }

        return $query;
    }

    public function getManyCheckinout()
    {
        return $this->hasMany(Checkinout::className(), ['userid' => 'userid'])
            ->via('manyUserinfo');
    }

    public function countTemplate()
    {
        $query = $this->queryTemplate();
        return $query->count();
    }

    public function queryTemplate()
    {
        $query = \app\modules\iclock\models\Template::find();
        $query->joinWith(['userinfo']);
        $query->andWhere([
            'userinfo.badgenumber' => $this->nip,
        ]);

        return $query;
    }

    public function allTemplate()
    {
        $query = $this->queryTemplate();
        return $query->all();
    }

    public function getTextChecktimeTerakhir()
    {
        $checktimeTerakhir = $this->getChecktimeTerakhir();

        if ($checktimeTerakhir != null) {
            return Helper::getSelisihWaktu($checktimeTerakhir) . " Lalu<br>" . $checktimeTerakhir;
        } else {
            return "";
        }
    }

    public function getChecktimeTerakhir()
    {
        $model = $this->getCheckinoutTerakhir();

        if ($model !== null) {
            return $model->checktime;
        } else {
            return null;
        }
    }

    public function getCheckinoutTerakhir()
    {
        $query = $this->getManyCheckinout();
        $query->orderBy(['checktime' => SORT_DESC]);
        return $query->one();
    }

    /**
     * @return string
     * @see findAllCheckinout();
     */
    public function getStringChecktime(DateTime $date = null, $jamKerja = null)
    {
        $suffix = null;

        if ($this->getIsPegawaiDispensasi($date->format('Y-m-d'))) {
            $suffix = "Dispensasi";
        }
        
        if ($date->format('Y-m-d') > date('Y-m-d')) {
            return $suffix;
        }

        $shiftKerja = $this->findShiftKerja(['tanggal' => $date->format('Y-m-d')]);

        //Hari Libur
        if (in_array($date->format('Y-m-d'), $this->_hari_libur) && $shiftKerja->getIsLiburNasional()) {
            if ($shiftKerja->countJamKerja(JamKerja::HARI_LIBUR) == 0) {
                return "Hari Libur";
            }
        }

        //Jika tidak ada jam kerja pada tanggal dihitung, maka potongan hari = 0
        if ($shiftKerja->countJamKerja($date->format('N')) == 0) {
            return "Tidak Ada Jam Kerja";
        }

        /*$queryArray = new ArrayQuery();
        $queryArray->from($this->_ketidakhadiran_panjang);
        $queryArray->where([
            'id_pegawai' => $this->id,
        ]);

        $ketidakhadiranPanjangArray = $queryArray->one();*/


        $query = new ArrayQuery;
        $query->from($this->_ketidakhadiran_panjang);
        $query->andWhere(['id_pegawai' => $this->id])
            ->andWhere(['<=', 'tanggal_mulai', $date->format('Y-m-d')])
            ->andWhere(['>=', 'tanggal_selesai', $date->format('Y-m-d')]);
        $query->andWhere(['id_ketidakhadiran_panjang_status' => KetidakhadiranPanjangStatus::SETUJU]);

        $ketidakhadiranPanjang = $query->one();

        if ($ketidakhadiranPanjang !== false) {
            return @$ketidakhadiranPanjang->ketidakhadiranPanjangJenis->nama;
        }

        $checktime_awal = $date->format('Y-m-d 00:00:00');
        $checktime_akhir = $date->format('Y-m-d 23:59:59');

        if ($jamKerja != null) {
            $date1 = $jamKerja->getDateMulaiHitung($date);
            //$date1 = $jamKerja->getDateMulaiHitung($date)->modify('-15 minute');
            $checktime_awal = $date1->format("Y-m-d H:i:s");
            $date2 = $jamKerja->getDateSelesaiHitung($date);
            //$date2 = $jamKerja->getDateSelesaiHitung($date)->modify('+15 minute');
            $checktime_akhir = $date2->format("Y-m-d H:i:s");

            if ($checktime_awal > date('Y-m-d H:i:s')) {
                return "";
            }
        }

        $queryArray = new ArrayQuery();
        $queryArray->from($this->_checkinout);
        $queryArray->where([
            'between', 'checktime', $checktime_awal, $checktime_akhir,
        ]);

        $jumlahCheckinout = $queryArray->count();

        $queryArray = new ArrayQuery();
        $queryArray->from($this->_checkinout);
        $queryArray->where([
            'between', 'checktime', $checktime_awal, $checktime_akhir,
        ]);

        $checkinoutArray = $queryArray->all();

        /*
        $checkinout = $this->findAllCheckinout([
        'checktime_awal'=>$checktime_awal,
        'checktime_akhir'=>$checktime_akhir,
        'userid'=>$this->_userid
        ]);
         */

        $presensi = ArrayHelper::getColumn($checkinoutArray, function ($element) {
            $label = Helper::getJamMenitDetik($element['checktime']);

            if ($element['status_lokasi_kantor'] == Checkinout::LUAR_KANTOR) {
                $label = $label .' (Luar Kantor)';
            }

            return $label;
        });
        /*
        $presensi = ArrayHelper::getColumn($checkinout, function ($element) {
        $label =  Helper::getJamMenitDetik($element['checktime']);
        return $label;
        });
         */

        $output = implode(', ', $presensi);

        if ($output == null) {
            $output = "Tanpa Keterangan";
            if ($this->getIsAbsensiManual(['bulan' => $date->format('n')]) && $jamKerja !== null) {
                $output = Editable::widget([
                    'model' => $this,
                    'value' => null,
                    'name' => 'id',
                    'valueIfNull' => 'Tidak Hadir',
                    'header' => 'Input Absensi',
                    'formOptions' => ['action' => ['/iclock/checkinout/editable-update']],
                    'beforeInput' => function ($form, $widget) use ($jamKerja, $date) {
                        echo Html::hiddenInput('editableKey', $this->id);
                        echo Html::hiddenInput('editableJamKerjaId', $jamKerja->id);
                        echo Html::hiddenInput('editableTanggal', $date->format('Y-m-d'));
                    },
                    'inputType' => Editable::INPUT_DROPDOWN_LIST,
                    'data' => [0 => 'Tidak Hadir', 1 => 'Hadir'],
                    'placement' => 'top',
                    'options' => ['placeholder' => 'Input Absensi'],
                    //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                ]);
            }
        } elseif ($this->getIsAbsensiManual(['bulan' => $date->format('n')]) && $jamKerja !== null) {
            $output = Editable::widget([
                'model' => $this,
                'value' => 'Hadir',
                'name' => 'id',
                'valueIfNull' => 'Tidak Hadir',
                'header' => 'Input Absensi',
                'formOptions' => ['action' => ['/iclock/checkinout/editable-update']],
                'beforeInput' => function ($form, $widget) use ($jamKerja, $date) {
                    echo Html::hiddenInput('editableKey', $this->id);
                    echo Html::hiddenInput('editableJamKerjaId', $jamKerja->id);
                    echo Html::hiddenInput('editableTanggal', $date->format('Y-m-d'));
                },
                'inputType' => Editable::INPUT_DROPDOWN_LIST,
                'data' => [0 => 'Tidak Hadir', 1 => 'Hadir'],
                'placement' => 'top',
                'options' => ['placeholder' => 'Input Absensi'],
                //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
            ]);
        }

        return $output . ($suffix !== null ? ' - ' . $suffix : null);
    }

    public function findShiftKerja($params = [])
    {
        $tanggal = date('Y-m-d');

        if (isset($params['tanggal'])) {
            $tanggal = $params['tanggal'];
        }

        if (@$params['bulan'] == null) {
            $datetime = \DateTime::createFromFormat('Y-m-d', $tanggal);
            $params['bulan'] = $datetime->format('n');
        }

        if ($this->getIsAbsensiManual(['bulan' => @$params['bulan']]) == true) {
            return $this->findShiftKerjaManual();
        }

        $model = $this->getPegawaiShiftKerjaBerlaku($tanggal);
        if ($model !== false and $model->shiftKerja !== null) {
            return $model->shiftKerja->getConditionalShiftKerja($tanggal);
        }
        return ShiftKerja::getDefault()->getConditionalShiftKerja($tanggal);
    }

    public function findShiftKerjaManual()
    {
        static $shiftKerja;
        if ($shiftKerja === null) {
            $shiftKerja = ShiftKerja::findOne(38);
        }
        return $shiftKerja;
    }

    public function getPegawaiShiftKerjaBerlaku($tanggal)
    {
        $query = new ArrayQuery();
        return $query->from($this->manyPegawaiShiftKerja)
            ->andWhere(['<=', 'tanggal_berlaku', $tanggal])
            ->orderBy(['tanggal_berlaku' => SORT_DESC])
            ->one();
    }

    public function getIsPegawaiDispensasi($tanggal)
    {
        if ($this->_has_dispensasi === null) {
            $date = new DateTime($tanggal);
            /* $dispensasi = $this
                ->getPegawaiDispensasi()
                ->andWhere(['>=', 'tanggal_mulai', "{$date->format('Y')}-01-01"])
                ->asArray()
                ->indexBy('tanggal_mulai')
                ->all(); */

            $dispensasi = $this
                ->getPegawaiDispensasi()
                ->andWhere('tanggal_mulai <= :tanggal_akhir AND tanggal_akhir >= :tanggal_awal', [
                    ':tanggal_awal' => $date->format('Y-m-01'),
                    ':tanggal_akhir' => $date->format('Y-m-t'),
                ])
                ->andWhere(['id_pegawai_dispensasi_jenis' => [
                    PegawaiDispensasiJenis::FULL,
                    PegawaiDispensasiJenis::ABSENSI
                ]])
                ->asArray()
                ->indexBy('tanggal_mulai')
                ->all();

            if ($dispensasi !== []) {
                $this->_pegawai_dispensasi = $dispensasi;
                $this->_has_dispensasi = true;
            } else {
                $this->_has_dispensasi = false;
            }
        }
        if ($this->_has_dispensasi === true) {
            if ($this->_pegawai_dispensasi !== []) {
                $hasil = array_filter(
                    $this->_pegawai_dispensasi,
                    function ($data) use ($tanggal) {
                        return $data['tanggal_mulai'] <= $tanggal and $data['tanggal_akhir'] >= $tanggal;
                    }
                );
                if (count($hasil) !== 0) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getIsPegawaiDispensasiCkhp($tanggal)
    {
        $query = $this->getPegawaiDispensasi();
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_akhir >= :tanggal', [
            ':tanggal' => $tanggal,
        ]);
        $query->andWhere(['id_pegawai_dispensasi_jenis' => [
            PegawaiDispensasiJenis::FULL,
            PegawaiDispensasiJenis::CKHP
        ]]);

        return $query->count() > 0;
    }

    public function getPegawaiDispensasi()
    {
        return $this->hasMany(PegawaiDispensasi::class, ['id_pegawai' => 'id'])->inverseOf('pegawai');
    }

    /**
     * @return boolean
     * @see findAllCheckinout()
     */
    public function isTepatWaktu($params = [], DateTime $date = null)
    {
        /**
         * Jika hari adalah hari yang dihitung
         * @todo verifikasi dan integrasikan hari hari libur secara dinamis
         */
        if ($date->format('Y-m-d H:i:s') >= date('Y-m-d H:i:s') and !in_array($date->format('N'), [6, 7])) {
            return true;
        }

        if ($params instanceof \app\modules\absensi\models\JamKerja) {
            $jam_mulai = $params->getJamMinimalAbsensi($date);
            $jam_selesai = $params->getJamMaksimalAbsensi($date);
            foreach ($this->findAllCheckinout($params, $date) as $data) {
                if ($data->checktime >= $jam_mulai && $data->checktime < $jam_selesai) {
                    return true;
                }
            }
        } else {
            foreach ($this->findAllCheckinout($params, $date) as $data) {
                if ($data->checktime >= $params['checktime_awal'] && $data->checktime <= $params['checktime_akhir']) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param array $params
     * @return Object
     */
    public function findAllCheckinout($params = [])
    {
        $query = $this->queryCheckinout($params);

        $query->orderBy(['checktime' => SORT_ASC]);

        return $query->all();
    }

    public function updatePegawaiRekapAbsensi($bulan, $tahun = null)
    {
        $rekap = $this->findOrCreatePegawaiRekapAbsensi($bulan, $tahun);

        if ($rekap->status_kunci == 1) {
            return $rekap;
        }

        if ($this->getIsAbsensiManual(['bulan' => $bulan])) {
            return $this->getPresensiCeklis($bulan)->updatePegawaiRekapAbsensi();
        }

        $this->getPotonganBulan($bulan, $tahun);

        $rekap->jumlah_hari_kerja = $this->_hari_kerja;
        $rekap->jumlah_hadir = $this->_hari_hadir;
        $rekap->jumlah_tidak_hadir = $this->_hari_tidak_hadir;
        $rekap->jumlah_izin = @$this->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_IZIN];
        $rekap->jumlah_sakit = @$this->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_SAKIT];
        $rekap->jumlah_cuti = @$this->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_CUTI];
        $rekap->jumlah_tugas_belajar = @$this->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_TUGAS_BELAJAR];
        $rekap->jumlah_dinas_luar = @$this->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_DINAS_LUAR];
        $rekap->jumlah_tugas_kedinasan = @$this->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_TUGAS_KEDINASAN];
        $rekap->jumlah_diklat = @$this->_hari_ketidakhadiran[KetidakhadiranPanjangJenis::KETIDAKHADIRAN_DIKLAT];
        $rekap->jumlah_alasan_teknis = @$this->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_ALASAN_TEKNIS];
        $rekap->jumlah_tidak_hadir_upacara = @$this->_jumlah_ketidakhadiran_kegiatan_tanpa_keterangan[Absensi::KETIDAKHADIRAN_KEGIATAN_UPACARA];
        $rekap->jumlah_tidak_hadir_senam = @$this->_jumlah_ketidakhadiran_kegiatan_tanpa_keterangan[Absensi::KETIDAKHADIRAN_KEGIATAN_SENAM];
        $rekap->jumlah_tidak_hadir_apel_pagi = @$this->_jumlah_ketidakhadiran_kegiatan_tanpa_keterangan[Absensi::KETIDAKHADIRAN_KEGIATAN_APEL_PAGI];
        $rekap->jumlah_tidak_hadir_apel_sore = @$this->_jumlah_ketidakhadiran_kegiatan_tanpa_keterangan[Absensi::KETIDAKHADIRAN_KEGIATAN_APEL_SORE];
        $rekap->jumlah_tidak_hadir_sidak = @$this->_jumlah_ketidakhadiran_kegiatan_tanpa_keterangan[Absensi::KETIDAKHADIRAN_KEGIATAN_SIDAK];
        $rekap->jumlah_tanpa_keterangan = $this->_hari_tanpa_keterangan;
        $rekap->persen_potongan_fingerprint = $this->_potongan_bulan_fingerprint;
        $rekap->persen_potongan_kegiatan = $this->_potongan_bulan_kegiatan;
        $rekap->persen_potongan_total = $this->_potongan_bulan;
        $rekap->waktu_diperbarui = date('Y-m-d H:i:s');
        $rekap->jumlah_ketidakhadiran_jam_tanpa_keterangan = $this->_jumlah_ketidakhadiran_jam_tanpa_keterangan;

        $rekap->persen_hadir = 0;
        $rekap->persen_tidak_hadir = 100;
        $rekap->persen_tanpa_keterangan = 100;

        if ($this->_hari_kerja != 0) {
            $rekap->persen_hadir = round($this->_hari_hadir / $this->_hari_kerja * 100, 2);
            $rekap->persen_tidak_hadir = round($this->_hari_tidak_hadir / $this->_hari_kerja * 100, 2);
            $rekap->persen_tanpa_keterangan = round($this->_hari_tanpa_keterangan / $this->_hari_kerja * 100, 2);
        }

        //$rekap->save(false);
        $rekap->save();

        return $rekap;
    }

    public function findOrCreatePegawaiTunjangan($params = [])
    {
        $bulan = @$params['bulan'];
        $tahun = @$params['tahun'];

        if ($tahun == null) {
            $tahun = Session::getTahun();
        }

        if ($bulan == null) {
            $bulan = date('n');
        }

        return PegawaiTunjangan::findOrCreate([
            'id_pegawai' => $this->id,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
    }

    public function findOrCreatePegawaiRekapAbsensi($bulan, $tahun = null)
    {
        if ($tahun == null) {
            $tahun = User::getTahun();
        }

        $query = PegawaiRekapAbsensi::find();
        $query->andWhere([
            'id_pegawai' => $this->id,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);

        $query->orderBy(['id' => SORT_DESC]);

        $rekapAbsensi = $query->one();

        /*
        $allPegawaiRekapAbsensi = $this->getAllPegawaiRekapAbsensi($bulan, $tahun)->all();
        $query = new ArrayQuery(['from' => $allPegawaiRekapAbsensi]);
        $rekapAbsensi = $query->andWhere(['id_instansi' => $this->getIdInstansiBerlaku($bulan, $tahun)])
            ->one();
        */

        if ($rekapAbsensi === null) {
            $rekapAbsensi = new \app\modules\absensi\models\PegawaiRekapAbsensi;
            $rekapAbsensi->bulan = $bulan;
            $rekapAbsensi->tahun = $tahun;
            $rekapAbsensi->id_pegawai = $this->id;
            $rekapAbsensi->id_golongan = $this->id_golongan;
            $rekapAbsensi->id_instansi = $this->getIdInstansiBerlaku($bulan, $tahun);

            if ($rekapAbsensi->save() == false) {
                print_r($rekapAbsensi->getErrors());
                die();
            }
        }

        return $rekapAbsensi;
    }

    public function getIdInstansiBerlaku($bulan = null, $tahun = null)
    {
        if ($bulan == null) {
            $bulan = date('n');
        }

        if ($tahun == null) {
            $tahun = date('Y');
        }

        $datetime = DateTime::createFromFormat('Y-n-d', $tahun . '-' . $bulan . '-01');
        $tanggal = $datetime->format('Y-m-15');
        $instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);

        if ($instansiPegawai !== null) {
            return $instansiPegawai->id_instansi;
        }

        return null;
    }

    /**
     * @param $bulan
     * @return float|int
     */
    public function getPotonganBulan($bulan, $tahun = null)
    {
        if ($tahun == null) {
            $tahun = User::getTahun();
        }

        $this->_potongan_bulan = 0;
        $this->_hari_kerja = 0;
        $this->_hari_tanpa_keterangan = 0;
        $this->_hari_tidak_hadir = 0;
        $this->_hari_ketidakhadiran = [
            Absensi::KETIDAKHADIRAN_IZIN => 0,
            Absensi::KETIDAKHADIRAN_SAKIT => 0,
            Absensi::KETIDAKHADIRAN_CUTI => 0,
            Absensi::KETIDAKHADIRAN_TUGAS_BELAJAR => 0,
            Absensi::KETIDAKHADIRAN_DINAS_LUAR => 0,
            Absensi::KETIDAKHADIRAN_TUGAS_KEDINASAN => 0,
            Absensi::KETIDAKHADIRAN_ALASAN_TEKNIS => 0,
            KetidakhadiranPanjangJenis::KETIDAKHADIRAN_DIKLAT => 0,
        ];

        $this->_jumlah_ketidakhadiran_kegiatan_tanpa_keterangan = [
            Absensi::KETIDAKHADIRAN_KEGIATAN_APEL_PAGI => 0,
            Absensi::KETIDAKHADIRAN_KEGIATAN_APEL_SORE => 0,
            Absensi::KETIDAKHADIRAN_KEGIATAN_UPACARA => 0,
            Absensi::KETIDAKHADIRAN_KEGIATAN_SENAM => 0,
            Absensi::KETIDAKHADIRAN_KEGIATAN_SIDAK => 0,
        ];

        $this->_jumlah_ketidakhadiran_jam_tanpa_keterangan = 0;

        $this->_jumlah_terlambat_masuk_kerja_interval = [
            Absensi::INTERVAL_1_SD_15 => 0,
            Absensi::INTERVAL_16_SD_30 => 0,
            Absensi::INTERVAL_31_KE_ATAS => 0,
            Absensi::INTERVAL_TIDAK_PRESENSI => 0
        ];

        $this->_jumlah_terlambat_masuk_istirahat_interval = [
            Absensi::INTERVAL_1_SD_15 => 0,
            Absensi::INTERVAL_16_SD_30 => 0,
            Absensi::INTERVAL_31_KE_ATAS => 0,
            Absensi::INTERVAL_TIDAK_PRESENSI => 0
        ];

        $this->_jumlah_pulang_awal_keluar_kerja_interval = [
            Absensi::INTERVAL_1_SD_15 => 0,
            Absensi::INTERVAL_16_SD_30 => 0,
            Absensi::INTERVAL_31_KE_ATAS => 0,
            Absensi::INTERVAL_TIDAK_PRESENSI => 0
        ];

        if ($this->getIsAbsensiManual(['bulan' => $bulan])) {
            return $this->getPresensiCeklis($bulan)->updatePegawaiRekapAbsensi();
        }

        //Load array ketidakhadiran, userid, dan hari libur
        $this->_ketidakhadiran = $this->getArrayKetidakhadiran(['bulan' => $bulan, 'tahun' => $tahun], false);
        $this->_ketidakhadiran_jam_kerja = $this->getArrayKetidakhadiranJamKerja(['bulan' => $bulan, 'tahun' => $tahun]);
        $this->_ketidakhadiran_panjang = $this->getArrayKetidakhadiranPanjang(['bulan' => $bulan, 'tahun' => $tahun]);
        $this->_checkinout = $this->getArrayCheckinout(['bulan' => $bulan, 'tahun' => $tahun]);
        $this->_checkinout_dalam_kantor = $this->getArrayCheckinoutDalamKantor();
        $this->_checkinout_luar_kantor = $this->getArrayCheckinoutLuarKantor();
        $this->_userid = $this->getArrayUserid();
        $this->_pegawai_shift_kerja = $this->getArrayPegawaiShiftKerja(['bulan' => $bulan, 'tahun' => $tahun]);
        $this->_hari_libur = $this->getArrayHariLibur(['bulan' => $bulan, 'tahun' => $tahun]);

        $date = date_create($tahun . '-' . $bulan . '-01');

        //Hitung dan simpan potongan kegiatan
        if ($this->getIsPegawaiDispensasi($date->format('Y-m-d'))) {
            $this->_potongan_bulan_kegiatan = 0;
        } else {
            $this->_potongan_bulan_kegiatan = $this->getPotonganBulanKegiatan($bulan, $tahun);
        }

        //Hitung potongan bulan fingerprint yang merupakan penjumlahan dari potongan hari
        $potongan = 0;
        $end = $date->format('t');
        for ($i = 1; $i <= $end; $i++) {
            $potongan += $this->getPotonganHari($date);
            $date->modify('+1 day');
        }

        $this->_potongan_bulan_fingerprint = $potongan;

        if ($this->_potongan_bulan_fingerprint > 100) {
            $this->_potongan_bulan_fingerprint = 100;
        }

        //Potongan bulan total = potongan bulan fingerprint + potongan bulan kegiatan
        $potonganBulan = 0;
        $potonganBulan += $this->_potongan_bulan_fingerprint;
        $potonganBulan += $this->_potongan_bulan_kegiatan;

        if (Config::PERHITUNGAN_PERGUB_2022) {
            $potonganBulan += $this->getPotonganAtribut([
                'bulan' => $bulan,
                'tahun' => $tahun,
            ]);
        }

        $this->_potongan_bulan = $potonganBulan;

        if ($this->_potongan_bulan > 100) {
            $this->_potongan_bulan = 100;
        }

        /*if ($this->getIsPegawaiDispensasi($date->format('Y-m-d'))) {
            $this->_hari_hadir = $this->_hari_kerja;
        } */
        $this->_hari_hadir = $this->_hari_kerja - $this->_hari_tidak_hadir;
        // $this->_hari_hadir = $this->_hari_kerja - $this->_hari_tidak_hadir + $this->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_DINAS_LUAR];


        return $this->_potongan_bulan;
    }

    public function getArrayKetidakhadiran($params = [], $asArray = true)
    {
        $query = $this->getManyKetidakhadiran();

        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        if ($tahun == null) {
            $tahun = User::getTahun();
        }

        $tanggal_awal = $tahun . '-01-01';
        $tanggal_akhir = $tahun . '-12-31';

        if ($bulan != null) {
            $date = date_create($tahun . '-' . $bulan);
            $tanggal_awal = $date->format('Y-m-01');
            $tanggal_akhir = $date->format('Y-m-t');
        }

        $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir', [
            ':tanggal_awal' => $tanggal_awal,
            ':tanggal_akhir' => $tanggal_akhir,
        ]);
        if ($asArray === true) {
            $this->_ketidakhadiran = $query->indexBy('tanggal')->asArray()->all();
        } else {
            $this->_ketidakhadiran = $query->indexBy('tanggal')->all();
        }

        return $this->_ketidakhadiran;

        /*
    $this->_hari_libur = ArrayHelper::getColumn($ketidakhadiran,'tanggal');
    return $this->_hari_libur;
     */
    }

    public function getManyKetidakhadiran()
    {
        return $this->hasMany(Ketidakhadiran::className(), ['id_pegawai' => 'id'])
            ->andWhere('status_hapus IS NULL');
    }

    public function getArrayKetidakhadiranJamKerja($params = [])
    {
        $query = $this->getManyKetidakhadiranJamKerja();

        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        if ($tahun == null) {
            $tahun = User::getTahun();
        }

        $tanggal_awal = $tahun . '-01-01';
        $tanggal_akhir = $tahun . '-12-31';

        if ($bulan != null) {
            $date = date_create($tahun . '-' . $bulan);
            $tanggal_awal = $date->format('Y-m-01');
            $tanggal_akhir = $date->format('Y-m-t');
        }

        $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir', [
            ':tanggal_awal' => $tanggal_awal,
            ':tanggal_akhir' => $tanggal_akhir,
        ]);
        $query->with(['ketidakhadiranJamKerjaStatus', 'ketidakhadiranJamKerjaJenis']);

        $this->_ketidakhadiran_jam_kerja = $query->all();
        return $this->_ketidakhadiran_jam_kerja;

        /*
    $this->_hari_libur = ArrayHelper::getColumn($ketidakhadiran,'tanggal');
    return $this->_hari_libur;
     */
    }

    public function getManyKetidakhadiranJamKerja()
    {
        return $this->hasMany(KetidakhadiranJamKerja::className(), ['id_pegawai' => 'id'])
            ->andWhere('status_hapus IS NULL');
    }

    public function getArrayKetidakhadiranPanjang($params = [], $asArray = false)
    {
        $query = $this->getManyKetidakhadiranPanjang();
        $query->with(['ketidakhadiranPanjangJenis', 'ketidakhadiranPanjangStatus']);

        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        if ($tahun == null) {
            $tahun = User::getTahun();
        }

        $tanggal_awal = $tahun . '-01-01';
        $tanggal_akhir = $tahun . '-12-31';

        if ($bulan != null) {
            $date = date_create($tahun . '-' . $bulan);
            $tanggal_awal = $date->format('Y-m-01');
            $tanggal_akhir = $date->format('Y-m-t');
        }

        $query->andWhere('status_hapus IS NULL');
        $query->andWhere([
            'id_ketidakhadiran_panjang_status' => KetidakhadiranPanjangStatus::SETUJU,
        ]);

        $query->andWhere('
            (tanggal_mulai <= :tanggal_awal AND (tanggal_selesai >= :tanggal_awal AND tanggal_selesai <= :tanggal_akhir)) OR
            (tanggal_mulai >= :tanggal_awal AND tanggal_selesai <= :tanggal_akhir) OR
            ((tanggal_mulai >= :tanggal_awal AND tanggal_mulai <= :tanggal_akhir) AND tanggal_selesai >= :tanggal_akhir) OR
            (tanggal_mulai <= :tanggal_awal AND tanggal_selesai >= :tanggal_akhir)
            ', [
            ':tanggal_awal' => $tanggal_awal,
            ':tanggal_akhir' => $tanggal_akhir,
        ]);

        if ($asArray === true) {
            $this->_ketidakhadiran_panjang = $query->asArray()->all();
        } else {
            $this->_ketidakhadiran_panjang = $query->all();
        }

        return $this->_ketidakhadiran_panjang;

        /*
    $this->_hari_libur = ArrayHelper::getColumn($ketidakhadiran,'tanggal');
    return $this->_hari_libur;
     */
    }

    public function getArrayCheckinout($params = [])
    {
        $query = $this->getManyCheckinout();

        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        if ($tahun == null) {
            $tahun = User::getTahun();
        }

        if ($bulan != null) {
            $date = date_create($tahun . '-' . $bulan);
            $checktime_awal = $date->format('Y-m-01 00:00:00');
            $date->modify('+1 month');
            $checktime_akhir = $date->format('Y-m-01 23:59:59');
        }

        $query->andWhere('checktime >= :checktime_awal AND checktime <= :checktime_akhir', [
            ':checktime_awal' => $checktime_awal,
            ':checktime_akhir' => $checktime_akhir,
        ]);

        $this->_checkinout = $query->asArray()->all();
        return $this->_checkinout;
    }

    public function getArrayCheckinoutDalamKantor()
    {
        $query = new ArrayQuery();
        $query->from($this->_checkinout);
        $query->andWhere([
            'status_lokasi_kantor' => Checkinout::DALAM_KANTOR
        ]);

        $this->_checkinout_dalam_kantor = $query->all();
        return $this->_checkinout_dalam_kantor;
    }

    public function getArrayCheckinoutLuarKantor()
    {
        $query = new ArrayQuery();
        $query->from($this->_checkinout);
        $query->andWhere([
            'status_lokasi_kantor' => Checkinout::LUAR_KANTOR
        ]);

        $this->_checkinout_luar_kantor = $query->all();
        return $this->_checkinout_luar_kantor;
    }

    public function getArrayUserid()
    {
        $manyUserinfo = $this->getManyUserinfo()->select('userid')->asArray()->all();
        $this->_userid = ArrayHelper::getColumn($manyUserinfo, 'userid');
        $this->_userid = $manyUserinfo;
        return $this->_userid;
    }

    public function getManyUserinfo()
    {
        return $this->hasMany(Userinfo::className(), ['badgenumber' => 'nip']);
    }

    public function getArrayPegawaiShiftKerja($params = [])
    {
        $query = $this->getManyPegawaiShiftKerja();

        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        if ($tahun == null) {
            $tahun = User::getTahun();
        }

        $tanggal = $tahun . '-12-31';

        if (isset($params['bulan'])) {
            $date = date_create($tahun . '-' . $bulan);
            $tanggal = $date->format('Y-m-t');
        }

        $query->andWhere('tanggal_berlaku <= :tanggal', [
            ':tanggal' => $tanggal,
        ]);

        $query->orderBy(['tanggal_berlaku' => SORT_DESC]);

        $query->limit(31);

        $this->_pegawai_shift_kerja = $query->asArray()->all();
        return $this->_pegawai_shift_kerja;
    }

    public function getManyPegawaiShiftKerja()
    {
        return $this->hasMany(PegawaiShiftKerja::className(), ['id_pegawai' => 'id'])
            ->andWhere('status_hapus IS NULL')
            ->indexBy('tanggal_berlaku');
    }

    public function getArrayHariLibur($params = [])
    {
        $query = \app\modules\absensi\models\HariLibur::find();

        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        if ($tahun == null) {
            $tahun = User::getTahun();
        }

        $tanggal_awal = $tahun . '-01-01';
        $tanggal_akhir = $tahun . '-12-31';

        if ($bulan != null) {
            $date = date_create($tahun . '-' . $bulan);
            $tanggal_awal = $date->format('Y-m-01');
            $tanggal_akhir = $date->format('Y-m-t');
        }

        $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir', [
            ':tanggal_awal' => $tanggal_awal,
            ':tanggal_akhir' => $tanggal_akhir,
        ]);

        $hariLibur = $query->select('tanggal')->asArray()->all();
        $this->_hari_libur = ArrayHelper::getColumn($hariLibur, 'tanggal');

        return $this->_hari_libur;
    }

    /*public function calculatePotongan($bulan)
    {
    $date = date_create(date('Y-m-d', strtotime(User::getTahun() . "-$bulan-01")));
    $potongan = 0;
    $shiftKerja = ShiftKerja::findOne(1);
    $end = $date->format('t');
    for ($i = 1; $i <= $end; $i++) {
    if (in_array($date->format('N'), [6, 7])) {
    $date->modify('+1 day');
    continue;
    }
    foreach ($shiftKerja->findAllJamKerja($date->format('N')) as $jamKerja) {
    $potongan += $this->getPotongan($jamKerja, $date);
    }
    $date->modify('+1 day');
    }
    $rekap = $this->findAbsensiRekap($bulan);
    $rekap->jumlah_persen_potongan = $potongan;
    $rekap->save();
    return $rekap;
    }*/

    public function getPotonganBulanKegiatan($bulan, $tahun = null)
    {
        $total_jumlah = 0;

        foreach (KetidakhadiranKegiatanJenis::find()->all() as $data) {

            $jumlah = $this->countKetidakhadiranKegiatanPotongan([
                'id_ketidakhadiran_kegiatan_jenis' => $data->id,
                'id_ketidakhadiran_kegiatan_keterangan' => Absensi::KETIDAKHADIRAN_KEGIATAN_TANPA_KETERANGAN,
                'bulan' => $bulan,
                'tahun' => $tahun
            ]);

            $this->_jumlah_ketidakhadiran_kegiatan_tanpa_keterangan[$data->id] = $jumlah;
            $total_jumlah = $total_jumlah + $jumlah;
        }

        return 2.5 * $total_jumlah;
    }

    /**
     * Menghitung ketidakhadiran kegiatan yang masuk
     * ke perhitungan potongan
     */
    public function countKetidakhadiranKegiatanPotongan($params = [])
    {
        $query = $this->queryKetidakhadiranKegiatan($params);
        $i = 0;
        foreach ($query->all() as $kegiatan) {
            if ($this->getIsPegawaiDispensasi($kegiatan->tanggal)) {
                continue;
            }
            if ($this->_ketidakhadiran_panjang !== []) {
                $izin = array_filter(
                    $this->_ketidakhadiran_panjang,
                    function ($data) use ($kegiatan) {
                        return $data['tanggal_mulai'] <= $kegiatan->tanggal and $data['tanggal_selesai'] >= $kegiatan->tanggal;
                    }
                );
                if (count($izin) !== 0) {
                    continue;
                }
            }
            if (isset($this->_ketidakhadiran[$kegiatan->tanggal]) && (int)$this->_ketidakhadiran[$kegiatan->tanggal]['id_ketidakhadiran_status'] === Absensi::KETIDAKHADIRAN_SETUJU) {
                continue;
            }
            $i++;
        }
        return $i;
    }

    public function queryKetidakhadiranKegiatan($params = [])
    {
        $query = $this->getManyKetidakhadiranKegiatan();

        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        if ($tahun == null) {
            $tahun = User::getTahun();
        }

        $tanggal_awal = $tahun . '-01-01';
        $tanggal_akhir = $tahun . '-12-31';

        if ($bulan != null) {
            $date = date_create($tahun . '-' . $bulan);
            $tanggal_awal = $date->format('Y-m-01');
            $tanggal_akhir = $date->format('Y-m-t');
        }

        $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir', [
            ':tanggal_awal' => $tanggal_awal,
            ':tanggal_akhir' => $tanggal_akhir,
        ]);

        if (isset($params['id_ketidakhadiran_kegiatan_jenis'])) {
            $query->andWhere([
                'id_ketidakhadiran_kegiatan_jenis' => $params['id_ketidakhadiran_kegiatan_jenis'],
            ]);
        }

        if (isset($params['id_ketidakhadiran_kegiatan_keterangan'])) {
            $query->andWhere([
                'id_ketidakhadiran_kegiatan_keterangan' => Absensi::KETIDAKHADIRAN_KEGIATAN_TANPA_KETERANGAN,
            ]);
        }

        return $query;
    }

    public function getManyKetidakhadiranKegiatan()
    {
        return $this->hasMany(\app\modules\absensi\models\KetidakhadiranKegiatan::className(), ['id_pegawai' => 'id'])
            ->andWhere('status_hapus IS NULL');
    }

    /*public function findShiftKerjaByTanggalFromArray($tanggal = null)
    {
    if ($tanggal == null) {
    $tanggal = date('Y-m-d');
    }

    $queryArray = new ArrayQuery();
    $queryArray->from($this->_pegawai_shift_kerja);
    $queryArray->where(['<=', 'tanggal_berlaku', $tanggal]);

    $queryArray->orderBy(['tanggal_berlaku' => SORT_DESC]);

    $pegawaiShiftKerjaArray = $queryArray->one();

    if ($pegawaiShiftKerjaArray != null) {
    $shiftKerja = ShiftKerja::findOne($pegawaiShiftKerjaArray['id_shift_kerja']);

    if ($shiftKerja !== null) {
    return $shiftKerja;
    }
    }

    return ShiftKerja::findOne(1);
    }*/

    public function getPotonganHari($date, $shiftKerja = null)
    {
        $tanggal = $date->format('Y-m-d');

        $this->_hari[$tanggal] = [];
        $this->_hari[$tanggal]['uraian'] = "Tanpa Keterangan";
        $this->_jam_kerja[$tanggal] = [];


        //$shiftKerja = $this->findShiftKerjaByTanggalFromArray($tanggal);
        $shiftKerja = $this->findShiftKerja(['tanggal' => $tanggal]);
        $this->_potongan_hari = $shiftKerja->hari_kerja * Absensi::getPotonganTanpaKeterangan($date);

        foreach ($shiftKerja->manyJamKerja as $jamKerja) {
            $this->_jam_kerja[$tanggal][$jamKerja->id]['_potongan_jam_kerja'] = 0;
            $this->_jam_kerja[$tanggal][$jamKerja->id]['uraian'] = "Tanpa Keterangan";
        }

        //Jika tanggal dihitung lebih besar dari tanggal hari ini, maka potongan tidak/belum dihitung
        if ($date->format('Y-m-d') > date('Y-m-d')) {
            $this->_potongan_hari = 0;
            $this->_hari[$tanggal]['uraian'] = "";
            $this->_hari[$tanggal]['_potongan_hari'] = $this->_potongan_hari;
            return $this->_potongan_hari;
        }

        //Jika tidak ada jam kerja pada tanggal dihitung, maka potongan hari = 0
        if ($shiftKerja->countJamKerja($date->format('N')) == 0) {
            $this->_potongan_hari = 0;
            $this->_hari[$tanggal]['uraian'] = "Tidak Ada Jam Kerja";
            $this->_hari[$tanggal]['_potongan_hari'] = $this->_potongan_hari;
            return $this->_potongan_hari;
        }

        //Jika hari libur dan tidak ada jam kerja di hari libur, maka potongan hari = 0
        if (in_array($date->format('Y-m-d'), $this->_hari_libur) && $shiftKerja->getIsLiburNasional()) {
            if ($shiftKerja->countJamKerja(JamKerja::HARI_LIBUR) == 0) {
                $this->_potongan_hari = $shiftKerja->countJamKerja(JamKerja::HARI_LIBUR);
                $this->_hari[$tanggal]['uraian'] = "Hari Libur";
                $this->_hari[$tanggal]['_potongan_hari'] = $this->_potongan_hari;
                return $this->_potongan_hari;
            }
        }

        //Mulai hitung hari kerja
        $this->_hari_kerja += $shiftKerja->hari_kerja;

        $dispensasi = $this->getIsPegawaiDispensasi($tanggal);

        $queryArray = new ArrayQuery();
        $queryArray->from($this->_ketidakhadiran_panjang);
        $queryArray->where([
            'id_pegawai' => $this->id,
        ]);

        $ketidakhadiranPanjangArray = $queryArray->one();

        if ($ketidakhadiranPanjangArray != null) {
            $query = new ArrayQuery();
            $query->from($this->getManyKetidakhadiranPanjang()->all());
            $query->andWhere(['<=', 'tanggal_mulai', $date->format('Y-m-d')])
                ->andWhere(['>=', 'tanggal_selesai', $date->format('Y-m-d')])
                ->andWhere(['id_ketidakhadiran_panjang_status' => KetidakhadiranPanjangStatus::SETUJU]);

            $ketidakhadiranPanjang = $query->one();

            if ($ketidakhadiranPanjang !== false) {
                $this->_potongan_hari = 0;
                $this->_hari[$tanggal]['_potongan_hari'] = $this->_potongan_hari;
                $id_ketidakhadiran_jenis = @$ketidakhadiranPanjang->ketidakhadiranPanjangJenis->id_ketidakhadiran_jenis;

                if ($id_ketidakhadiran_jenis != null) {

                    //Jika tidak hadir != tugas kedinasann, maka tidak hadir ++
                    if (!in_array($ketidakhadiranPanjang->id_ketidakhadiran_panjang_jenis, [KetidakhadiranPanjangJenis::KETIDAKHADIRAN_TUGAS_KEDINASAN, KetidakhadiranPanjangJenis::KETIDAKHADIRAN_ALASAN_TEKNIS])) {
                        $this->_hari_ketidakhadiran[$id_ketidakhadiran_jenis] += $shiftKerja->hari_kerja;
                        $this->_hari_tidak_hadir += $shiftKerja->hari_kerja;
                    }
                }
                if ($ketidakhadiranPanjang->getIsDiklat()) {
                    $this->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_TUGAS_KEDINASAN] -= $shiftKerja->hari_kerja;
                    $this->_hari_ketidakhadiran[KetidakhadiranPanjangJenis::KETIDAKHADIRAN_DIKLAT]++;
                }

                if ($dispensasi === true) {
                    $this->_potongan_hari = 0;
                    $this->_hari[$tanggal]['uraian'] .= " - Dispensasi";
                    $this->_hari[$tanggal]['_potongan_hari'] = $this->_potongan_hari;
                    return $this->_potongan_hari;
                } else {
                    return $this->_potongan_hari;
                }
            }
        }

        $queryArray = new ArrayQuery();
        $queryArray->from($this->_ketidakhadiran);
        $queryArray->where([
            'tanggal' => $date->format('Y-m-d'),
            //'id_jam_kerja'=>"0",
            'id_ketidakhadiran_status' => 1,
        ]);

        $ketidakhadiranArray = $queryArray->one();


        if ($ketidakhadiranArray != null) {
            $this->_potongan_hari = 0;
            $this->_hari[$tanggal]['_potongan_hari'] = $this->_potongan_hari;
            $id_ketidakhadiran_jenis = $ketidakhadiranArray['id_ketidakhadiran_jenis'];
            $this->_hari_ketidakhadiran[$id_ketidakhadiran_jenis]++;

            //Jika tidak hadir != tugas kedinasann, maka tidak hadir ++
            if ($id_ketidakhadiran_jenis != 6) {
                $this->_hari_tidak_hadir += $shiftKerja->hari_kerja;
            }

            if ($dispensasi === true) {
                $this->_potongan_hari = 0;
                $this->_hari[$tanggal]['uraian'] .= "- Dispensasi";
                $this->_hari[$tanggal]['_potongan_hari'] = $this->_potongan_hari;
                return $this->_potongan_hari;
            } else {
                return $this->_potongan_hari;
            }
        }

        if ($dispensasi === true) {
            $this->_potongan_hari = 0;
            $this->_hari[$tanggal]['uraian'] .= "- Dispensasi";
            $this->_hari[$tanggal]['_potongan_hari'] = $this->_potongan_hari;
            return $this->_potongan_hari;
        }

        $this->_status_hari_tanpa_keterangan = true;

        //Cek ada kehadiran atau tidak
        foreach ($shiftKerja->findAllJamKerja($date->format('N')) as $jamKerja) {
            $queryArray = new ArrayQuery();
            $queryArray->from($this->_checkinout);
            $queryArray->where([
                'between', 'checktime',
                $jamKerja->getDateMulaiHitung($date)->format('Y-m-d H:i:s'),
                $jamKerja->getDateSelesaiHitung($date)->format('Y-m-d H:i:s'),
            ]);

            $jumlahCheckinout = $queryArray->count();

            /* $queryArrayLuarKantor = new ArrayQuery();
            $queryArrayLuarKantor->from($this->_checkinout_luar_kantor);
            $queryArrayLuarKantor->where([
                'between', 'checktime',
                $jamKerja->getDateMulaiHitung($date)->format('Y-m-d H:i:s'),
                $jamKerja->getDateSelesaiHitung($date)->format('Y-m-d H:i:s'),
            ]);

            $jumlahCheckinoutLuarKantor = $queryArrayLuarKantor->count();
            */

            if ($jumlahCheckinout != 0) {
                $this->_status_hari_tanpa_keterangan = false;
            }

            $queryKetidakhadiranJamKerja = new ArrayQuery();
            $queryKetidakhadiranJamKerja->from($this->getArrayKetidakhadiranJamKerja());
            $queryKetidakhadiranJamKerja->where([
                'id_pegawai' => $this->id,
                'id_jam_kerja' => $jamKerja->id,
                'tanggal' => $date->format('Y-m-d'),
                'id_ketidakhadiran_jam_kerja_status' => 1
            ]);

            $jumlahKetidakhadiranJamKerja = $queryKetidakhadiranJamKerja->count();

            if ($jumlahKetidakhadiranJamKerja != 0) {
                $this->_status_hari_tanpa_keterangan = false;
            }
        }

        if ($this->_status_hari_tanpa_keterangan == true) {
            $this->_hari_tanpa_keterangan += $shiftKerja->hari_kerja;
            $this->_hari_tidak_hadir += $shiftKerja->hari_kerja;
            $potongan = $shiftKerja->hari_kerja * Absensi::getPotonganTanpaKeterangan($date);

            $this->_potongan_hari = 12;
            $this->_hari[$tanggal]['_potongan_hari'] = $this->_potongan_hari;
            return $this->_potongan_hari;
        }

        $potongan = 0;
        $count = 0;
        foreach ($shiftKerja->findAllJamKerja($date->format('N')) as $jamKerja) {
            $potongan += $this->getPotonganJamKerja($date, $jamKerja);
            $count++;
        }

        $this->_potongan_hari = $potongan;
        $this->_hari[$tanggal]['_potongan_hari'] = $this->_potongan_hari;
        return $this->_potongan_hari;
    }

    public function getManyKetidakhadiranPanjang()
    {
        return $this->hasMany(\app\modules\absensi\models\KetidakhadiranPanjang::className(), ['id_pegawai' => 'id'])
            ->andWhere('status_hapus IS NULL');
    }

    /**
     * Ambil potongan berdasarkan jamkerja dan tanggal, bukan potongan total bulan ini
     * @return float
     * @see findAllCheckinout();
     */
    public function getPotonganJamKerja($date = null, $jamKerja)
    {
        $tanggal = $date->format('Y-m-d');
        $this->_potongan_jam_kerja = 0;
        $this->_potongan_jam_kerja_keterangan = "";

        //Cek ketidakhadiran
        $queryArray = new ArrayQuery();
        $queryArray->from($this->_ketidakhadiran_jam_kerja);
        $queryArray->where([
            'tanggal' => $date->format('Y-m-d'),
            'id_jam_kerja' => $jamKerja->id,
            'id_ketidakhadiran_jam_kerja_status' => 1,
        ]);

        $ketidakhadiranJamKerjaArray = $queryArray->one();

        if ($ketidakhadiranJamKerjaArray != null) {
            $this->_potongan_jam_kerja = 0;
            $this->_status_hari_tanpa_keterangan = false;
            $this->_jam_kerja[$tanggal][$jamKerja->id]['_potongan_jam_kerja'] = $this->_potongan_jam_kerja;
            return $this->_potongan_jam_kerja;
        }

        if ($this->_potongan_hari == 0) {
            $this->_potongan_jam_kerja = 0;
            $this->_status_hari_tanpa_keterangan = false;
            $this->_jam_kerja[$tanggal][$jamKerja->id]['_potongan_jam_kerja'] = $this->_potongan_jam_kerja;
            return $this->_potongan_jam_kerja;
        }

        $dateMulai = $jamKerja->getDateMulaiAbsensi($date);
        $dateSelesai = $jamKerja->getDateSelesaiAbsensi($date);
        // $dateMulai = date_create($tanggal . ' ' . $jamKerja->jam_minimal_absensi);
        // $dateSelesai = date_create($tanggal . ' ' . $jamKerja->jam_maksimal_absensi);

        if ($dateMulai->format("Y-m-d H:i:s") > date('Y-m-d H:i:s')) {
            $this->_potongan_jam_kerja = 0;
            $this->_status_hari_tanpa_keterangan = false;
            $this->_jam_kerja[$tanggal][$jamKerja->id]['_potongan_jam_kerja'] = $this->_potongan_jam_kerja;
            return $this->_potongan_jam_kerja;
        }

        //Periksa data absensi (checkinout) apakah tepat waktu atau tidak
        $queryArrayTepatWaktu = new ArrayQuery();
        $queryArrayTepatWaktu->from($this->_checkinout);
        $queryArrayTepatWaktu->where([
            'between', 'checktime',
            $jamKerja->getDateMulaiAbsensi($date)->format('Y-m-d H:i:s'),
            $jamKerja->getDateSelesaiAbsensi($date)->format('Y-m-d H:i:s'),
        ]);

        $jumlahCheckinout = $queryArrayTepatWaktu->count();

        //Jika absensi tepat waktu, maka potongan jam kerja = 0
        if ($jumlahCheckinout != 0) {
            $this->_potongan_jam_kerja = 0;
            $this->_jam_kerja[$tanggal][$jamKerja->id]['_potongan_jam_kerja'] = $this->_potongan_jam_kerja;
            $this->_status_hari_tanpa_keterangan = false;

            $presensi = ArrayHelper::getColumn($queryArrayTepatWaktu->all(), function ($element) {
                $label = Helper::getJamMenitDetik($element['checktime']);
                return $label;
            });

            /*
            $presensi = ArrayHelper::getColumn($queryTepatWaktu->all(), function ($element) {
            $label =  Helper::getJamMenitDetik($element['checktime']);
            return $label;
            });
             */

            $this->_jam_kerja[$tanggal][$jamKerja->id]['uraian'] = implode(', ', $presensi);
            return $this->_potongan_jam_kerja;
        }

        //Jika absensi tidak tepat waktu
        $queryArray = new ArrayQuery();
        $queryArray->from($this->_checkinout);
        $queryArray->where([
            'between', 'checktime',
            $jamKerja->getDateMulaiHitung($date)->format('Y-m-d H:i:s'),
            $jamKerja->getDateSelesaiHitung($date)->format('Y-m-d H:i:s'),
        ]);

        $jumlahCheckinout = $queryArray->count();

        //Tidak ada presensi jam kerja potongan 4%
        if ($jumlahCheckinout == 0) {
            $this->_jumlah_ketidakhadiran_jam_tanpa_keterangan++;
            $this->_potongan_jam_kerja = 4;
            $this->_jam_kerja[$tanggal][$jamKerja->id]['_potongan_jam_kerja'] = $this->_potongan_jam_kerja;

            if (@$jamKerja->nama == 'Masuk Kerja') {
                $this->_jumlah_terlambat_masuk_kerja_interval[Absensi::INTERVAL_TIDAK_PRESENSI] += 1;
            }

            if (@$jamKerja->nama == 'Masuk Istirahat') {
                $this->_jumlah_terlambat_masuk_istirahat_interval[Absensi::INTERVAL_TIDAK_PRESENSI] += 1;
            }

            if (@$jamKerja->nama == 'Keluar Kerja' or @$jamKerja->nama == 'Pulang Kerja') {
                $this->_jumlah_pulang_awal_keluar_kerja_interval[Absensi::INTERVAL_TIDAK_PRESENSI] += 1;
            }

            return $this->_potongan_jam_kerja;
        }


        //Cek keterlambatan maksimal 15 menit
        $dateMulai = $jamKerja->getDateMulaiAbsensi($date);
        $dateSelesai = $jamKerja->getDateSelesaiAbsensi($date);

        $dateMulai->modify('-15 minute');
        $dateSelesai->modify('+15 minute');

        $queryArray = new ArrayQuery();
        $queryArray->from($this->_checkinout);
        $queryArray->where([
            'between', 'checktime', $dateMulai->format('Y-m-d H:i:s'), $dateSelesai->format('Y-m-d H:i:s'),
        ]);

        $jumlahCheckinout = $queryArray->count();

        //Jika telat, maka potongan = 1
        if ($jumlahCheckinout != 0) {
            $this->_potongan_jam_kerja = 1;
            $this->_status_hari_tanpa_keterangan = false;
            $this->_jam_kerja[$tanggal][$jamKerja->id]['_potongan_jam_kerja'] = $this->_potongan_jam_kerja;

            if (@$jamKerja->nama == 'Masuk Kerja') {
                $this->_jumlah_terlambat_masuk_kerja_interval[Absensi::INTERVAL_1_SD_15] += 1;
            }

            if (@$jamKerja->nama == 'Masuk Istirahat') {
                $this->_jumlah_terlambat_masuk_istirahat_interval[Absensi::INTERVAL_1_SD_15] += 1;
            }

            if (@$jamKerja->nama == 'Keluar Kerja') {
                $this->_jumlah_pulang_awal_keluar_kerja_interval[Absensi::INTERVAL_1_SD_15] += 1;
            }

            return $this->_potongan_jam_kerja;
        }

        //Cek keterlambatan maksimal 30 menit
        $dateMulai = $jamKerja->getDateMulaiAbsensi($date);
        $dateSelesai = $jamKerja->getDateSelesaiAbsensi($date);

        $dateMulai->modify('-30 minute');
        $dateSelesai->modify('+30 minute');

        $queryArray = new ArrayQuery();
        $queryArray->from($this->_checkinout);
        $queryArray->where([
            'between', 'checktime', $dateMulai->format('Y-m-d H:i:s'), $dateSelesai->format('Y-m-d H:i:s'),
        ]);

        $jumlahCheckinout = $queryArray->count();

        //Jika $jumlahCheckinout != 0 artinya pegawai terlambat sd 30 menit
        if ($jumlahCheckinout != 0) {
            $this->_potongan_jam_kerja = 2;
            $this->_status_hari_tanpa_keterangan = false;
            $this->_jam_kerja[$tanggal][$jamKerja->id]['_potongan_jam_kerja'] = $this->_potongan_jam_kerja;

            if (@$jamKerja->nama == 'Masuk Kerja') {
                $this->_jumlah_terlambat_masuk_kerja_interval[Absensi::INTERVAL_16_SD_30] += 1;
            }

            if (@$jamKerja->nama == 'Masuk Istirahat') {
                $this->_jumlah_terlambat_masuk_istirahat_interval[Absensi::INTERVAL_16_SD_30] += 1;
            }

            if (@$jamKerja->nama == 'Keluar Kerja') {
                $this->_jumlah_pulang_awal_keluar_kerja_interval[Absensi::INTERVAL_16_SD_30] += 1;
            }

            return $this->_potongan_jam_kerja;
        }

        //Jika $jumlahCheckinout == 0 artinya pegawai absen di atas 30 menit
        //Untuk cek tidak ada data checkinout sudah dilakukan di atas
        if ($jumlahCheckinout == 0) {
            $this->_potongan_jam_kerja = 2.5;

            if (@$jamKerja->nama == 'Masuk Kerja') {
                $this->_jumlah_terlambat_masuk_kerja_interval[Absensi::INTERVAL_31_KE_ATAS] += 1;
            }

            if (@$jamKerja->nama == 'Masuk Istirahat') {
                $this->_jumlah_terlambat_masuk_istirahat_interval[Absensi::INTERVAL_31_KE_ATAS] += 1;
            }

            if (@$jamKerja->nama == 'Keluar Kerja') {
                $this->_jumlah_pulang_awal_keluar_kerja_interval[Absensi::INTERVAL_31_KE_ATAS] += 1;
            }

            return $this->_potongan_jam_kerja;
        }

        /*
        //Cek keterlambatan lewat 30 menit
        $dateMulai = $jamKerja->getDateMulaiAbsensi($date);
        $dateSelesai = $jamKerja->getDateSelesaiAbsensi($date);

        $queryArray = new ArrayQuery();
        $queryArray->from($this->_checkinout);
        $queryArray->where([
            'between', 'checktime', $dateMulai->format('Y-m-d H:i:s'), $dateSelesai->format('Y-m-d H:i:s'),
        ]);

        $jumlahCheckinout = $queryArray->count();

        //Jika telat, maka potongan = 2.5
        if ($jumlahCheckinout != 0) {
            $this->_potongan_jam_kerja = 2.5;
            $this->_status_hari_tanpa_keterangan = false;
            $this->_jam_kerja[$tanggal][$jamKerja->id]['_potongan_jam_kerja'] = $this->_potongan_jam_kerja;

            if(@$jamKerja->nama=='Masuk Kerja') {
                $this->_jumlah_terlambat_masuk_kerja_interval[Absensi::INTERVAL_31_KE_ATAS] += 1;
            }

            if(@$jamKerja->nama=='Masuk Istirahat') {
                $this->_jumlah_terlambat_masuk_istirahat_interval[Absensi::INTERVAL_31_KE_ATAS] += 1;
            }

            if(@$jamKerja->nama=='Keluar Kerja') {
                $this->_jumlah_pulang_awal_keluar_kerja_interval[Absensi::INTERVAL_31_KE_ATAS] += 1;
            }

            return $this->_potongan_jam_kerja;
        }
        */


        //Cek keterlambatan maksimal 15 menit
        /*
        $dateMulai->modify('-15 minute');
        $dateSelesai->modify('+15 minute');

        $queryArray = new ArrayQuery();
        $queryArray->from($this->_checkinout);
        $queryArray->where([
            'between', 'checktime', $dateMulai->format('Y-m-d H:i:s'), $dateSelesai->format('Y-m-d H:i:s'),
        ]);

        $jumlahCheckinout = $queryArray->count();

        //Jika telat, maka potongan = 1
        if ($jumlahCheckinout != 0) {
            $this->_potongan_jam_kerja = 1;
            $this->_status_hari_tanpa_keterangan = false;
            $this->_jam_kerja[$tanggal][$jamKerja->id]['_potongan_jam_kerja'] = $this->_potongan_jam_kerja;

            return $this->_potongan_jam_kerja;
        }

        $queryArray = new ArrayQuery();
        $queryArray->from($this->_checkinout);
        $queryArray->where([
            'between', 'checktime',
            $jamKerja->getDateMulaiHitung($date)->format('Y-m-d H:i:s'),
            $jamKerja->getDateSelesaiHitung($date)->format('Y-m-d H:i:s'),
            // $tanggal . ' ' . $jamKerja->jam_mulai_hitung,
            // $tanggal . ' ' . $jamKerja->jam_selesai_hitung,
        ]);

        $jumlahCheckinout = $queryArray->count();

        //Potongan lebih dari 15 menit : 4%
        if ($jumlahCheckinout != 0) {
            $this->_potongan_jam_kerja = 4;
            $this->_jam_kerja[$tanggal][$jamKerja->id]['_potongan_jam_kerja'] = $this->_potongan_jam_kerja;
            return $this->_potongan_jam_kerja;
        }

        //Potongan tidak absen jam kerja : 4%
        if ($jumlahCheckinout == 0) {
            $this->_jumlah_ketidakhadiran_jam_tanpa_keterangan++;
            $this->_potongan_jam_kerja = 4;
            $this->_jam_kerja[$tanggal][$jamKerja->id]['_potongan_jam_kerja'] = $this->_potongan_jam_kerja;
            return $this->_potongan_jam_kerja;
        }

        return $this->_potongan_jam_kerja;
        */
    }

    public function countKetidakhadiranKegiatan($params = [])
    {
        return $this->queryKetidakhadiranKegiatan($params)->count();
    }

    public function findAllKetidakhadiranKegiatan($params = [])
    {
        $query = $this->queryKetidakhadiranKegiatan($params);
        //$query->with(['ketidakhadiranKegiatanJenis','ketidakhadiranKegiatanKeterangan']);
        $query->orderBy(['tanggal' => SORT_ASC]);

        return $query->all();
    }

    public function countKegiatanTahunan($params = [])
    {
        $query = $this->getManyKegiatanTahunan();
        $query->andFilterWhere(['id_kegiatan_tahunan_versi' => @$params['id_kegiatan_tahunan_versi']]);
        return $query->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManyKegiatanTahunan()
    {
        return $this->hasMany(KegiatanTahunan::className(), ['id_pegawai' => 'id']);
    }

    public function getManyKegiatanRhk()
    {
        return $this->hasMany(KegiatanRhk::class, ['id_pegawai' => 'id']);
    }

    public function accessViewPegawaiShiftKerja()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator()) {
            return true;
        }

        if (User::isInstansi() and $this->id_instansi = User::getIdInstansi()) {
            return true;
        }

        if (User::isAdminInstansi() and $this->id_instansi = User::getIdInstansi()) {
            return true;
        }

        if (User::isGrup() and in_array($this->id, User::getListIdPegawai())) {
            return true;
        }

        return false;
    }

    public function accessCreatePegawaiShiftKerja()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator()) {
            return true;
        }

        if (User::isInstansi() and $this->id_instansi = User::getIdInstansi()) {
            return true;
        }

        if (User::isAdminInstansi() and $this->id_instansi = User::getIdInstansi()) {
            return true;
        }

        if (User::isGrup() and in_array($this->id, User::getListIdPegawai())) {
            return true;
        }

        return false;
    }

    public function getNamaShiftKerja($params = [])
    {
        $shiftKerja = $this->findShiftKerja($params);
        return $shiftKerja->nama;
    }

    public function getButtonDropdownKetidakhadiran($date, $jamKerja = null)
    {
        $ketidakhadiran = $this->findKetidakhadiran([
            'tanggal' => $date->format('Y-m-d'),
            'id_jam_kerja' => ($jamKerja ? $jamKerja->id : "IS NULL"),
        ]);

        return ButtonDropdown::widget([
            'label' => '',
            'options' => ['class' => 'btn-xs btn-success btn-flat btn-ketidakhadiran'],
            'dropdown' => [
                'options' => ['class' => 'dropdown-menu-left'],
                'encodeLabels' => false,
                'items' => [
                    ['label' => '<i class="fa fa-pencil"></i> Ajukan Keterangan Tidak Hadir', 'url' => [
                        '/absensi/ketidakhadiran/create',
                        'id_pegawai' => $this->id,
                        'tanggal' => $date->format('Y-m-d'),
                        'id_jam_kerja' => ($jamKerja ? $jamKerja->id : "0"),
                    ], 'visible' => ($ketidakhadiran ? false : true)],
                    ['label' => '<i class="fa fa-eye"></i> Lihat Keterangan Tidak Hadir', 'url' => [
                        '/absensi/ketidakhadiran/view',
                        'id' => $ketidakhadiran ? $ketidakhadiran->id : "",
                    ], 'visible' => ($ketidakhadiran ? true : false)],
                ],
            ],
        ]);
    }

    public function findKetidakhadiran($params = [])
    {
        $query = $this->queryKetidakhadiran($params);

        return $query->one();
    }

    public function queryKetidakhadiran($params = [])
    {
        $query = $this->getManyKetidakhadiran();
        //$query->with(['ketidakhadiranJenis','ketidakhadiranStatus']);

        if (isset($params['bulan'])) {
            $date = date_create(User::getTahun() . '-' . $params['bulan']);
            $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir', [
                ':tanggal_awal' => $date->format('Y-m-01'),
                ':tanggal_akhir' => $date->format('Y-m-t'),
            ]);
        }

        if (isset($params['tanggal'])) {
            $query->andWhere(['tanggal' => $params['tanggal']]);
        }

        if (isset($params['id_jam_kerja'])) {
            $query->andWhere(['id_jam_kerja' => $params['id_jam_kerja']]);
        }

        if (isset($params['id_ketidakhadiran_jenis'])) {
            $query->andWhere(['id_ketidakhadiran_jenis' => $params['id_ketidakhadiran_jenis']]);
        }

        if (isset($params['id_ketidakhadiran_status'])) {
            $query->andWhere(['id_ketidakhadiran_status' => $params['id_ketidakhadiran_status']]);
        }

        return $query;
    }

    public function getLabelKetidakhadiran($date, $jamKerja = null)
    {
        $output = "";

        $queryPanjang = new ArrayQuery;
        $queryPanjang->from($this->_ketidakhadiran_panjang);
        $queryPanjang->andWhere(['id_pegawai' => $this->id])
            ->andWhere(['<=', 'tanggal_mulai', $date->format('Y-m-d')])
            ->andWhere(['>=', 'tanggal_selesai', $date->format('Y-m-d')]);
        $queryPanjang->andWhere(['id_ketidakhadiran_panjang_status' => KetidakhadiranPanjangStatus::SETUJU]);

        $ketidakhadiranPanjang = $queryPanjang->one();

        if ($ketidakhadiranPanjang != null) {
            return "";
        }

        $queryArray = new ArrayQuery();
        $queryArray->from($this->_ketidakhadiran);
        $queryArray->where([
            'tanggal' => $date->format('Y-m-d'),
            'id_jam_kerja' => ($jamKerja ? $jamKerja->id : "0"),
        ]);

        $ketidakhadiranArray = $queryArray->one();

        /*
        $ketidakhadiran = $this->findKetidakhadiran([
        'tanggal'=>$date->format('Y-m-d'),
        'id_jam_kerja'=>($jamKerja ? $jamKerja->id : "0")
        ]);
         */

        if ($ketidakhadiranArray != null) {
            $ketidakhadiran = $ketidakhadiranArray;

            $output = @$ketidakhadiran->ketidakhadiranJenis->getLabelNama() . ' ';
            $output .= @$ketidakhadiran->ketidakhadiranStatus->getLabelNama();
        }

        return $output;
    }

    public function getLabelKetidakhadiranJamKerja($date, $jamKerja = null)
    {
        $output = "";

        $queryArray = new ArrayQuery();
        $queryArray->from($this->_ketidakhadiran_jam_kerja);
        $queryArray->where([
            'tanggal' => $date->format('Y-m-d'),
            'id_jam_kerja' => ($jamKerja ? $jamKerja->id : "0"),
        ]);

        $ketidakhadiranJamKerjaArray = $queryArray->one();

        /*
        $ketidakhadiran = $this->findKetidakhadiran([
        'tanggal'=>$date->format('Y-m-d'),
        'id_jam_kerja'=>($jamKerja ? $jamKerja->id : "0")
        ]);
         */

        if ($ketidakhadiranJamKerjaArray != null) {
            $ketidakhadiranJamKerja = $ketidakhadiranJamKerjaArray;
            if ($ketidakhadiranJamKerja->ketidakhadiranJamKerjaJenis !== null) {
                $output = @$ketidakhadiranJamKerja->ketidakhadiranJamKerjaJenis->getLabelNama() . ' ';
                $output .= @$ketidakhadiranJamKerja->ketidakhadiranJamKerjaStatus->getLabelNama();
            } else {
                $output = '-';
            }
        }

        return $output;
    }

    public function getLinkKetidakhadiran($date, $jamKerja = null, $stringChecktime = null)
    {
        if ($this->getIsPegawaiDispensasi($date->format('Y-m-d')) and $stringChecktime !== null) {
            switch ($stringChecktime) {
                case 'Hari Libur':
                    return "";
                    break;
                case 'Tidak Ada Jam Kerja':
                    return "";
                    break;
                default:
                    break;
            }
        }

        if (User::isPegawai()) {
            return "";
        }

        $query = new ArrayQuery;
        $query->from($this->_ketidakhadiran_panjang);
        $query->andWhere(['id_pegawai' => $this->id])
            ->andWhere(['<=', 'tanggal_mulai', $date->format('Y-m-d')])
            ->andWhere(['>=', 'tanggal_selesai', $date->format('Y-m-d')]);
        $query->andWhere(['id_ketidakhadiran_panjang_status' => KetidakhadiranPanjangStatus::SETUJU]);

        $ketidakhadiranPanjang = $query->one();
        if ($ketidakhadiranPanjang !== false and !in_array($stringChecktime, ['Hari Libur', 'Tidak Ada Jam Kerja'])) {
            if ($this->platform == 'mobile') {
                return "";
            }
            return Html::a(
                '<i class="fa fa-eye"></i>',
                ['ketidakhadiran-panjang/view', 'id' => $ketidakhadiranPanjang->id],
                ['data-toggle' => 'tooltip', 'title' => 'Lihat Detail Ketidakhadiran Panjang']
            );
        }

        $queryArray = new ArrayQuery();
        $queryArray->from($this->_ketidakhadiran);
        $queryArray->where([
            'tanggal' => $date->format('Y-m-d'),
            'id_jam_kerja' => ($jamKerja ? $jamKerja->id : "0"),
            //'id_ketidakhadiran_status'=>1
        ]);

        $ketidakhadiranArray = $queryArray->one();

        /*
        $ketidakhadiran = $this->findKetidakhadiran([
        'tanggal'=>$date->format('Y-m-d'),
        'id_jam_kerja'=>($jamKerja ? $jamKerja->id : "0")
        ]);
         */
        if (!$this->getIsPegawaiDispensasi($date->format('Y-m-d'))) {
            if ($jamKerja == null and $this->_potongan_hari == 0 and $ketidakhadiranArray == null) {
                return "";
            }
            if ($jamKerja != null and $this->_potongan_jam_kerja == 0 and $ketidakhadiranArray == null) {
                return "";
            }
            /*if ($jamKerja != null  and $ketidakhadiranArray == null) {
                $output = Html::a('<i class="fa fa-plus"></i>', [
                    '/absensi/ketidakhadiran/create',
                    'id_pegawai' => $this->id,
                    'tanggal' => $date->format('Y-m-d'),
                    'id_jam_kerja' => ($jamKerja ? $jamKerja->id : "0"),
                ], ['data-toggle' => 'tooltip', 'title' => 'Ajukan Keterangan']) . ' ';

                return $output;
            }*/
        }

        if ($this->platform == 'mobile') {
            return "";
        }

        $output = "";

        if ($ketidakhadiranArray == null and $this->getIsPegawaiDispensasi($date->format('Y-m-d'))) {
            $output = Html::a('<i class="fa fa-plus"></i>', [
                '/absensi/ketidakhadiran/create',
                'id_pegawai' => $this->id,
                'tanggal' => $date->format('Y-m-d'),
                'id_jam_kerja' => ($jamKerja ? $jamKerja->id : "0"),
            ], ['data-toggle' => 'tooltip', 'title' => 'Ajukan Keterangan']) . ' ';

            return $output;
        }

        if ($ketidakhadiranArray != null) {

            $ketidakhadiran = $ketidakhadiranArray;

            $output .= Html::a('<i class="fa fa-eye"></i>', [
                '/absensi/ketidakhadiran/view',
                'id' => $ketidakhadiran->id,
            ], ['data-toggle' => 'tooltip', 'title' => 'Lihat Keterangan']) . ' ';

            if ($ketidakhadiran->accessUpdate()) {
                $output .= Html::a('<i class="fa fa-pencil"></i>', [
                    '/absensi/ketidakhadiran/update',
                    'id' => $ketidakhadiran->id,
                ], ['data-toggle' => 'tooltip', 'title' => 'Ubah Keterangan']) . ' ';
            }

            if ($ketidakhadiran->accessSetSetuju()) {
                $output .= Html::a('<i class="fa fa-check"></i>', [
                    '/absensi/ketidakhadiran/set-setuju',
                    'id' => $ketidakhadiran->id,
                ], ['data-toggle' => 'tooltip', 'title' => 'Setujui Pengajuan', 'onclick' => 'return confirm("Yakin akan menyetujui pengajuan?");']) . ' ';
            }

            if ($ketidakhadiran->accessSetTolak()) {
                $output .= Html::a('<i class="fa fa-remove"></i>', [
                    '/absensi/ketidakhadiran/set-tolak',
                    'id' => $ketidakhadiran->id,
                ], ['data-toggle' => 'tooltip', 'title' => 'Tolak Pengajuan', 'onclick' => 'return confirm("Yakin akan menolak pengajuan?");']) . ' ';
            }

            if ($ketidakhadiran->accessDelete()) {
                $output .= Html::a(
                    '<i class="fa fa-trash"></i>',
                    [
                        '/absensi/ketidakhadiran/delete',
                        'id' => $ketidakhadiran->id,
                    ],
                    [
                        'data' => [
                            'confirm' => 'Yakin akan menghapus ketidak hadiran?',
                            'method' => 'post',
                            'toggle' => 'tooltip',
                        ],
                        'title' => 'Hapus Keterangan',
                    ]
                ) . ' ';
            }
        }

        return trim($output);
    }

    public function getLinkKetidakhadiranJamKerja($date, $jamKerja, $stringChecktime = null)
    {

        if (User::isPegawai()) {
            return "";
        }

        if (Session::isPemeriksaAbsensi()) {
            return "";
        }

        $queryArray = new ArrayQuery();
        $queryArray->from($this->_ketidakhadiran_jam_kerja);
        $queryArray->where([
            'tanggal' => $date->format('Y-m-d'),
            'id_jam_kerja' => $jamKerja->id,
            //'id_ketidakhadiran_status'=>1
        ]);

        $ketidakhadiranJamKerjaArray = $queryArray->one();

        /*
        $ketidakhadiran = $this->findKetidakhadiran([
        'tanggal'=>$date->format('Y-m-d'),
        'id_jam_kerja'=>($jamKerja ? $jamKerja->id : "0")
        ]);
         */

        if ($jamKerja == null and $this->_potongan_hari == 0 and $ketidakhadiranJamKerjaArray == null) {
            return "";
        }

        if ($jamKerja != null and $this->_potongan_jam_kerja == 0 and $ketidakhadiranJamKerjaArray == null and !$this->getIsPegawaiDispensasi($date->format('Y-m-d'))) {
            return "";
        }

        if ($this->getIsPegawaiDispensasi($date->format("Y-m-d")) and $stringChecktime !== null) {
            switch ($stringChecktime) {
                case 'Hari Libur':
                    return "";
                    break;
                case 'Tidak Ada Jam Kerja':
                    return "";
                    break;
                default:
                    break;
            }
        }

        if ($this->platform == 'mobile') {
            return "";
        }

        $output = "";

        if ($ketidakhadiranJamKerjaArray == null or $this->getIsPegawaiDispensasi($date->format('Y-m-d'))) {
            $output = Html::a('<i class="fa fa-plus"></i>', [
                '/absensi/ketidakhadiran-jam-kerja/create',
                'id_pegawai' => $this->id,
                'tanggal' => $date->format('Y-m-d'),
                'id_jam_kerja' => ($jamKerja ? $jamKerja->id : "0"),
            ], ['data-toggle' => 'tooltip', 'title' => 'Ajukan Keterangan']) . ' ';

            return $output;
        }

        if ($ketidakhadiranJamKerjaArray != null) {

            $ketidakhadiranJamKerja = $ketidakhadiranJamKerjaArray;

            $output .= Html::a('<i class="fa fa-eye"></i>', [
                '/absensi/ketidakhadiran-jam-kerja/view',
                'id' => $ketidakhadiranJamKerja->id,
            ], ['data-toggle' => 'tooltip', 'title' => 'Lihat Keterangan']) . ' ';

            if ($ketidakhadiranJamKerja->accessUpdate()) {
                $output .= Html::a('<i class="fa fa-pencil"></i>', [
                    '/absensi/ketidakhadiran-jam-kerja/update',
                    'id' => $ketidakhadiranJamKerja->id,
                ], ['data-toggle' => 'tooltip', 'title' => 'Ubah Keterangan']) . ' ';
            }

            if ($ketidakhadiranJamKerja->accessSetSetuju()) {
                $output .= Html::a('<i class="fa fa-check"></i>', [
                    '/absensi/ketidakhadiran-jam-kerja/set-setuju',
                    'id' => $ketidakhadiranJamKerja->id,
                ], ['data-toggle' => 'tooltip', 'title' => 'Setujui Pengajuan', 'onclick' => 'return confirm("Yakin akan menyetujui pengajuan?");']) . ' ';
            }

            if ($ketidakhadiranJamKerja->accessSetTolak()) {
                $output .= Html::a('<i class="fa fa-remove"></i>', [
                    '/absensi/ketidakhadiran-jam-kerja/set-tolak',
                    'id' => $ketidakhadiranJamKerja->id,
                ], ['data-toggle' => 'tooltip', 'title' => 'Tolak Pengajuan', 'onclick' => 'return confirm("Yakin akan menolak pengajuan?");']) . ' ';
            }

            if ($ketidakhadiranJamKerja->accessDelete()) {
                $output .= Html::a(
                    '<i class="fa fa-trash"></i>',
                    [
                        '/absensi/ketidakhadiran-jam-kerja/delete',
                        'id' => $ketidakhadiranJamKerja->id,
                    ],
                    [
                        'data' => [
                            'confirm' => 'Yakin akan menghapus ketidak hadiran?',
                            'method' => 'post',
                            'toggle' => 'tooltip',
                        ],
                        'title' => 'Hapus Keterangan',
                    ]
                ) . ' ';
            }
        }

        return trim($output);
    }

    public function countKetidakhadiran($params = [])
    {
        $query = $this->queryKetidakhadiran($params);

        return $query->count();
    }

    public function countKetidakhadiranSetuju($params = [])
    {
        $query = $this->queryKetidakhadiran($params);

        $query->andWhere(['id_ketidakhadiran_status' => 1]);

        return $query->count();
    }

    public function findAllKetidakhadiran($params)
    {
        $query = $this->queryKetidakhadiran($params);

        return $query->all();
    }

    public function findAllPegawaiMutasi()
    {
        return $this->allPegawaiMutasi;
    }

    public function getBulanLengkapTahun()
    {
        $output = User::getTahun();

        if ($this->bulan != null) {
            $output = Helper::getBulanLengkap($this->bulan) . ' ' . User::getTahun();
        }

        return $output;
    }

    /**
     * Mengirim user ke mesin
     * @param string $sn serial number mesin
     * @param boolean $sendFP untuk kirim fingerprint atau tidak
     */
    public function kirimUser($sn, $sendFP = true)
    {
        if ($this->countUserinfo() == 1) {
            $userinfo = $this->manyUserinfo[0];
            $cmdContent = sprintf("DATA USER PIN=%s\tName=%s\tPri=%d\tCard=170601044\tTZ=0\tGrp=1", $this->nip, $this->nama, $userinfo->Privilege);
            $cmd = new Devcmds([
                'SN_id' => $sn,
                'CmdContent' => $cmdContent,
                'CmdCommitTime' => date('Y-m-d H:i:s'),
            ]);
            $cmd->save();
            if ($sendFP === true) {
                foreach ($userinfo->templates as $template) {
                    $cmdContent = sprintf("DATA FP PIN=%s\tFID=%d\tSize=%d\tValid=%d\tTMP=%s", $this->nip, $template->FingerID, StringHelper::byteLength($template->Template), $template->Valid, $template->Template);
                    $cmd = new Devcmds([
                        'SN_id' => $sn,
                        'CmdContent' => $cmdContent,
                        'CmdCommitTime' => date('Y-m-d H:i:s'),
                    ]);
                    $cmd->save();
                }
            }
        } else {
            // @todo handle jika userinfo ada banyak
        }
    }

    public function countUserinfo()
    {
        $query = $this->getManyUserinfo();
        return $query->count();
    }

    public function countKegiatanHarian($params = [])
    {
        $query = $this
            ->queryKegiatanHarian($params)
            ->aktif();
        return $query->count();
    }

    public function queryKegiatanHarian($params = [])
    {
        $query = $this->getManyKegiatanHarian();

        if (isset($params['tanggal'])) {
            $query->andWhere(['tanggal' => $params['tanggal']]);
        }

        return $query;
    }

    public function countUser()
    {
        $query = User::find();
        $query->andWhere([
            'id_pegawai' => $this->id,
            'id_user_role' => UserRole::PEGAWAI,
        ]);

        return $query->count();
    }

    public function getMasa()
    {
        $masa = '';

        $masa = User::getTahun();

        if ($this->bulan != null) {
            $masa = Helper::getBulanLengkap($this->bulan) . ' ' . User::getTahun();
        }

        if ($this->tanggal != null) {
            $masa = Helper::getHariTanggal($this->tanggal);
        }

        return $masa;
    }

    public function getEselon()
    {
        return $this->hasOne(Eselon::class, ['id' => 'id_eselon']);
    }

    public function getIsEselon()
    {
        return $this->id_eselon !== Eselon::NON_ESELON;
    }

    public function getIsEselonI()
    {
        return in_array($this->id_eselon, Eselon::$eselon_i);
    }

    public function getIsEselonIII()
    {
        return in_array($this->id_eselon, Eselon::$eselon_iii);
    }

    public function getIsEselonIV()
    {
        return in_array($this->id_eselon, Eselon::$eselon_iv);
    }

    public function getIsEselonV()
    {
        return (int)$this->id_eselon === Eselon::ESELON_VA;
    }

    public function getInstansiPegawaiBerlakuEager($tanggal = null)
    {
        $query = new ArrayQuery();
        $query->from($this->allInstansiPegawai);
        $query->andWhere(['>=', 'tanggal_berlaku', $tanggal]);
        return $query->one();
    }

    public function getHasMutasi($tahun = null)
    {
        return $this
            ->getInstansiPegawaiTahun($tahun)
            ->count() > 1;
    }

    public function getInstansiPegawaiTahun($tahun = null)
    {
        if ($tahun === null) {
            $tahun = User::getTahun();
        }
        return $this->getAllInstansiPegawai()
            ->andWhere(['tahun' => $tahun]);
    }

    public function getListInstansiPegawaiTahun($tahun = null)
    {
        return ArrayHelper::map($this->getAllInstansiPegawaiTahun($tahun), 'id', function ($model) {
            return $model->nama_jabatan . ' - ' . @$model->instansi->nama;
        });
    }

    public function getAllInstansiPegawaiTahun($tahun = null)
    {
        return $this
            ->getInstansiPegawaiTahun($tahun)
            ->all();
    }

    public function getHukumanDisiplin()
    {
        return $this->hasMany(HukumanDisiplin::class, ['id_pegawai' => 'id'])
            ->aktif();
    }

    /**
     * @param $bulan
     * @param $id_hukuman_disiplin_jenis
     * @return float|int
     */
    public function getPersenPotonganHukumanDisiplinJenis($bulan, $id_hukuman_disiplin_jenis)
    {
        return array_sum(
            array_map(
                function (HukumanDisiplin $hukumanDisiplin) {
                    return $hukumanDisiplin->getPotongan();
                },
                $this->getManyHukumanDisiplin($bulan, $id_hukuman_disiplin_jenis)
            )
        );
    }

    /**
     * @param int $bulan
     * @param int $id_hukuman_disiplin_jenis
     * @return HukumanDisiplin[]
     */
    public function getManyHukumanDisiplin($bulan = null, $id_hukuman_disiplin_jenis = null)
    {
        $query = new ArrayQuery(['from' => $this->hukumanDisiplin]);
        return $query->andFilterWhere([
            'bulan' => $bulan,
            'id_hukuman_disiplin_jenis' => $id_hukuman_disiplin_jenis
        ])
            ->all();
    }

    public function getPotonganHukumanDisiplin($bulan)
    {
        $bulan = (int)$bulan;
        if (!isset($this->_potongan_disiplin[$bulan])) {
            /** @var HukumanDisiplin[] $disiplin */
            $disiplin = $this->getManyHukumanDisiplin($bulan);
            $potongan = 0;
            foreach ($disiplin as $data) {
                $potongan += $data->getPotongan();
            }
            if ($potongan > 100) {
                $potongan = 100;
            }
            $this->_potongan_disiplin[$bulan] = $potongan;
        }

        return $this->_potongan_disiplin[$bulan];
    }

    public function getKetidakhadiranBulan($bulan)
    {
        if (!isset($this->_ketidakhadiran_bulan[$bulan])) {
            $this->_ketidakhadiran_bulan[$bulan] = $this->getArrayKetidakhadiran(['bulan' => $bulan], false);
        }
        return $this->_ketidakhadiran_bulan[$bulan];
    }

    public function getKetidakhadiranPanjangBulan($bulan)
    {
        if (!isset($this->_ketidakhadiran_panjang_bulan[$bulan])) {
            $this->_ketidakhadiran_panjang_bulan[$bulan] = $this->getArrayKetidakhadiranPanjang(['bulan' => $bulan], false);
        }
        return $this->_ketidakhadiran_panjang_bulan[$bulan];
    }

    public function getClassCeklis($tanggal)
    {
        static $allowed = [
            KegiatanHarian::CKHP_HARI_LIBUR,
            KegiatanHarian::CKHP_KETIDAKHADIRAN_CUTI,
            KegiatanHarian::CKHP_KETIDAKHADIRAN_SAKIT,
            KegiatanHarian::CKHP_KETIDAKHADIRAN_TUBEL,
            KetidakhadiranPanjangJenis::KETIDAKHADIRAN_ALASAN_KHUSUS
        ];
        $status = $this->getStatusWajibCkhp($tanggal);
        if (in_array($status, $allowed)) {
            return 'bg-gray';
        }
        return null;
    }

    public function getStatusWajibCkhp($tanggal)
    {
        if (!$tanggal instanceof DateTime) {
            $date = new DateTime($tanggal);
        } else {
            $date = $tanggal;
        }
        if ($this->getIsHariLibur($date)) {
            return KegiatanHarian::CKHP_HARI_LIBUR;
        }
        if (($ketidakhadiran = $this->getIsKetidakhadiranTidakKegiatan($date))) {
            switch ($ketidakhadiran) {
                case KetidakhadiranJenis::SAKIT:
                    return KegiatanHarian::CKHP_KETIDAKHADIRAN_CUTI;
                case KetidakhadiranJenis::CUTI:
                    return KegiatanHarian::CKHP_KETIDAKHADIRAN_SAKIT;
                case KetidakhadiranJenis::TUGAS_BELAJAR:
                    return KegiatanHarian::CKHP_KETIDAKHADIRAN_TUBEL;
                case KetidakhadiranPanjangJenis::KETIDAKHADIRAN_ALASAN_KHUSUS;
                    return KetidakhadiranPanjangJenis::KETIDAKHADIRAN_ALASAN_KHUSUS;
                    break;
            }
        }
        if ($this->getHasCkhp($tanggal)) {
            return KegiatanHarian::CKHP_ISI;
        }
        return KegiatanHarian::CKHP_TANPA_KETERANGAN;
    }

    public function getIsHariLibur($date)
    {
        /*if ((int) $date->format('N') === 6 OR (int) $date->format('N') === 7) {
        return true;
        }
        return HariLibur::getIsLibur($date->format('Y-m-d'));*/

        $shift = $this->getShiftKerjaAktif($date->format('Y-m-d'));
        return !$shift->getHasJamKerja($date);
    }

    /**
     * @param $tanggal
     * @return ShiftKerja
     */
    public function getShiftKerjaAktif($tanggal)
    {
        if ($this->getIsAbsensiManual()) {
            return $this->findShiftKerjaManual();
        }
        $query = new ArrayQuery();
        $query->from($this->manyPegawaiShiftKerja);
        $query->andWhere(['<=', 'tanggal_berlaku', $tanggal])
            ->orderBy(['tanggal_berlaku' => SORT_DESC]);
        $result = $query->one();
        if ($result !== false) {
            if ($result->shiftKerja !== null) {
                return $result->shiftKerja;
            }
        }
        return ShiftKerja::getDefault();
    }

    public function getIsKetidakhadiranTidakKegiatan(DateTime $date)
    {
        $bulan = $date->format('m');
        $tanggal = $date->format('Y-m-d');
        if (isset($this->manyKetidakhadiran[$tanggal])) {
            return $this->manyKetidakhadiran[$tanggal]->getIsWajibCkhp();
        }
        if ($this->manyKetidakhadiranPanjang !== null) {
            foreach ($this->manyKetidakhadiranPanjang as $ketidakhadiranPanjang) {
                if (($return = $ketidakhadiranPanjang->getIsWajibCkhp($tanggal)) !== false) {
                    return $return;
                }
            }
        }
        return false;
    }

    public function getHasCkhp($tanggal)
    {
        // return count($this->manyKegiatanHarian);
        // return $this->getManyKegiatanHarian()->andWhere(['tanggal' => $tanggal])->count();
        if ($tanggal instanceof DateTime) {
            $tahun = $tanggal->format("Y");
        } else {
            $tahun = substr($tanggal, 0, 4);
        }
        $query = new ArrayQuery(['from' => $this->manyKegiatanHarian]);
        return $query->andWhere(['tanggal' => $tanggal, 'id_kegiatan_status' => KegiatanStatus::SETUJU])->count();
    }

    public function getCeklis($tanggal)
    {
        $status = $this->getStatusWajibCkhp($tanggal);
        $ceklis = '&#x2714;';
        switch ($status) {
            case KegiatanHarian::CKHP_HARI_LIBUR:
                $teks = 'Hari Libur';
                $ceklis = 'L';
                break;
            case KegiatanHarian::CKHP_KETIDAKHADIRAN_CUTI:
                $teks = 'Cuti';
                $ceklis = 'C';
                break;
            case KegiatanHarian::CKHP_KETIDAKHADIRAN_SAKIT:
                $teks = 'Cuti';
                $ceklis = 'C';
                // $teks = 'Sakit';
                // $ceklis = 'S';
                break;
            case KegiatanHarian::CKHP_KETIDAKHADIRAN_TUBEL:
                $teks = 'Tubel';
                $ceklis = 'TB';
                break;
            case KetidakhadiranPanjangJenis::KETIDAKHADIRAN_ALASAN_KHUSUS;
                $teks = 'Alasan Khusus';
                $ceklis = '-';
                break;
            default:
                $teks = '';
                break;
        }
        return !$this->getIsWajibCkhp($tanggal) ?
            "<span data-toggle=\"tooltip\" title=\"$teks\">$ceklis</span>" :
            "<span data-toggle=\"tooltip\" title=\"$teks\">&times;</span>";
    }

    public function getIsWajibCkhp($tanggal)
    {
        return (int)$this->getStatusWajibCkhp($tanggal) === KegiatanHarian::CKHP_TANPA_KETERANGAN;
    }

    public function getHasSkpDisetujui($tanggal = null)
    {
        if ($tanggal !== null) {
            $date = new DateTime($tanggal);
            $tahun = $date->format('Y');
        } else {
            $tahun = User::getTahun();
        }
        $query = new ArrayQuery();
        $query->from($this->manyKegiatanTahunan);
        $query->andWhere(['tahun' => $tahun])
            ->andWhere(['id_kegiatan_status' => KegiatanStatus::SETUJU]);
        return $query->count();
    }

    public function getCeklisSkp($tahun = null, $bulan = null)
    {
        if ($tahun == null) {
            $tahun = date('Y');
        }

        if ($bulan == null) {
            $bulan = date('n');
        }

        $datetime = DateTime::createFromFormat('Y-n-d', $tahun . '-' . $bulan . '-01');
        $tanggal = $datetime->format('Y-m-15');

        if ($this->getIsPegawaiDispensasi($tanggal) == true) {
            return "&#x2714;";
        }

        if ($this->getStatusTubel($tahun, $bulan) == true) {
            return "&#x2714;";
        }

        if ($this->getIsGuru($tahun, $bulan) == true) {
            return "&#x2714;";
        }

        return $this->getHasSkp(['tahun' => $tahun, 'bulan' => $bulan]) ? "&#x2714;" : "&times;";

        if ($this->getHasSkp(['tahun'=>$tahun])) {
            return '<i class="fa fa-check"></i>';
        } else {
            return '<i class="fa fa-remove"></i>';
        };
    }

    public function getHasSkp($params = [])
    {
        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        if ($tahun === null) {
            $tahun = Session::getTahun();
        }

        if($bulan === null) {
            $bulan = date('n');
        }

        if (!isset($this->_skp[$tahun])) {
            $query = new ArrayQuery();
            $query->from($this->manyKegiatanTahunan);
            $query->andWhere(['tahun' => $tahun]);

            $datetime = DateTime::createFromFormat('Y-n-d',$tahun.'-'.$bulan.'-01');

            if ($datetime->format('Y-m') < '2021-07') {
                $query->andWhere(['id_kegiatan_tahunan_versi' => 1]);
            }

            if ($datetime->format('Y-m') >= '2021-07'
                AND $datetime->format('Y-m') <= '2022-12'
            ) {
                $query->andWhere(['id_kegiatan_tahunan_versi' => 2]);
            }

            if ($datetime->format('Y-m') >= '2023-01') {
                $query->andWhere(['id_kegiatan_tahunan_versi' => 3]);
            }

            $this->_skp[$tahun] = $query->count();

        }

        return $this->_skp[$tahun];
    }

    /**
     * @param $tanggal
     * @return int
     */
    public function getCountKegiatanHarian($params = [])
    {
        $query = $this->getArrayQueryKegiatanHarian($params);
        return $query->count();
    }

    /**
     * @param array $params
     * @return ArrayQuery
     */
    public function getArrayQueryKegiatanHarian($params = [])
    {
        $query = new ArrayQuery();
        $query->from($this->findAllKegiatanHarian());
        $query->andFilterWhere([
            'tanggal' => @$params['tanggal'],
            'id_kegiatan_status' => @$params['id_kegiatan_status']
        ]);

        return $query;
    }

    /**
     * @param $tanggal
     * @return int
     */
    public function getCountKegiatanHarianV2($params = [])
    {
        $query = $this->getArrayQueryKegiatanHarianV2($params);
        return $query->count();
    }

    /**
     * @param array $params
     * @return ArrayQuery
     */
    public function getArrayQueryKegiatanHarianV2($params = [])
    {
        $query = $this->getManyKegiatanHarian();
        $query->joinWith(['kegiatanTahunan']);
        $query->andFilterWhere([
            'kegiatan_harian.tanggal' => @$params['tanggal'],
            'kegiatan_harian.id_kegiatan_status' => @$params['id_kegiatan_status'],
            'kegiatan_tahunan.id_kegiatan_tahunan_versi' => @$params['id_kegiatan_tahunan_versi'],
        ]);

        return $query;
    }

    public function findAllKegiatanHarian()
    {
        if ($this->_manyKegiatanHarian == null) {
            $this->_manyKegiatanHarian = $this->getManyKegiatanHarian()->all();
        }

        return $this->_manyKegiatanHarian;
    }

    public function createInstansiPegawai()
    {
        $instansiPegawai = new InstansiPegawai([
            'id_pegawai' => $this->id,
            'id_instansi' => $this->id_instansi,
            'id_atasan' => $this->id_atasan,
            'id_golongan' => $this->id_golongan,
            'id_eselon' => $this->id_eselon,
            'nama_jabatan' => $this->nama_jabatan,
            'tanggal_berlaku' => date(User::getTahun() . '-01-01'),
        ]);
        $instansiPegawai->save(false);
    }

    public function getIsBatasPengajuan()
    {
        return (int)$this->status_batas_pengajuan === 1;
    }

    public function getManyPegawaiRekapAbsensi()
    {
        return $this->hasMany(PegawaiRekapAbsensi::class, ['id_pegawai' => 'id']);
    }

    public function getQueryPegawaiRekapAbsensi($params = [])
    {
        $query = $this->getManyPegawaiRekapAbsensi();
        $query->andWhere([
            'bulan' => @$params['bulan'],
            'tahun' => @$params['tahun']
        ]);
        return $query;
    }

    public function getCountPegawaiRekapAbsensi($params = [])
    {
        $query = $this->getQueryPegawaiRekapAbsensi($params);
        return $query->count();
    }

    public function findAllPegawaiRekapAbsensi($params = [])
    {
        $query = $this->getQueryPegawaiRekapAbsensi($params);
        return $query->all();
    }

    public function getManyPegawaiRekapKinerja()
    {
        return $this->hasMany(PegawaiRekapKinerja::class, ['id_pegawai' => 'id'])
            ->orderBy(['bulan' => SORT_ASC]);
    }

    public function getOnePegawaiRekapKinerja()
    {
        return $this->hasOne(PegawaiRekapKinerja::class, ['id_pegawai' => 'id'])
            ->orderBy(['bulan' => SORT_ASC]);
    }

    /**
     * @return PegawaiRekapKinerja[]
     */
    public function findOrCreateAllPegawaiRekapKinerja()
    {
        $rekapKinerja = $this->manyPegawaiRekapKinerja;
        if (count($rekapKinerja) < 12) {
            $rekapKinerja = [];
            for ($i = 1; $i <= 12; $i++) {
                $find = $this->getPegawaiRekapKinerja($i);
                if ($find === false) {
                    $rekapKinerja[] = $this->updatePegawaiRekapKinerja($i);
                } else {
                    $rekapKinerja[] = $find;
                }
            }
        }
        return $rekapKinerja;
    }

    /**
     * @param $bulan
     * @return PegawaiRekapKinerja
     */
    public function getPegawaiRekapKinerja($bulan)
    {
        $query = new ArrayQuery(['from' => $this->manyPegawaiRekapKinerja]);
        return $query->andWhere(['bulan' => $bulan])->one();
    }

    public function updatePegawaiRekapKinerja($bulan)
    {
        $pegawaiRekapKinerja = $this->getPegawaiRekapKinerja($bulan);
        if ($pegawaiRekapKinerja === false) {
            $pegawaiRekapKinerja = new PegawaiRekapKinerja([
                'bulan' => $bulan,
                'id_pegawai' => $this->id,
                'id_instansi' => $this->id_instansi,
                'potongan_skp' => $this->getPotonganSkp(),
                'potongan_ckhp' => $this->getPotonganCkhpTotal($bulan),
                'potongan_total' => $this->getPotonganKegiatanTotal($bulan),
                'tahun' => User::getTahun(),
            ]);
            $pegawaiRekapKinerja->save();
        } else {
            $pegawaiRekapKinerja->potongan_skp = $this->getPotonganSkp();
            $pegawaiRekapKinerja->potongan_ckhp = $this->getPotonganCkhpTotal($bulan);
            $pegawaiRekapKinerja->potongan_total = $this->getPotonganKegiatanTotal($bulan);
        }
        $pegawaiRekapKinerja->setPotonganTotal();
        $pegawaiRekapKinerja->save(false);
        return $pegawaiRekapKinerja;
    }

    public $_status_tubel;

    public function getStatusTubel($tahun = null, $bulan = null)
    {
        if ($this->_status_tubel != null) {
            return $this->_status_tubel;
        }

        if ($tahun == null) {
            $tahun = Session::getTahun();
        }

        if ($bulan == null) {
            $bulan = date('n');
        }

        $datetime = DateTime::createFromFormat('Y-n-d', $tahun . '-' . $bulan . '-01');

        $tanggal = $datetime->format('Y-m-15');

        $queryTubel = KetidakhadiranPanjang::find();
        $queryTubel->andWhere(['id_pegawai' => $this->id]);
        $queryTubel->andWhere([
            'id_ketidakhadiran_panjang_jenis' => 5
        ]);
        $queryTubel->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal', [
            ':tanggal' => $tanggal
        ]);
        $tubel = $queryTubel->one();

        if ($tubel !== null) {
            $this->_status_tubel = true;
        } else {
            $this->_status_tubel = false;
        }

        return $this->_status_tubel;
    }

    public function getPotonganSkp($tahun = null, $bulan = null)
    {
        if ($tahun == null) {
            $tahun = date('Y');
        }

        if ($bulan == null) {
            $bulan = date('n');
        }

        if ($this->_status_tubel == true) {
            return 0;
        }

        $bulanOk = $bulan;
        if ($bulanOk < 10 and strlen(strval($bulanOk)) == 1) {
            $bulanOk = '0' . $bulan;
        }

        if ($this->getIsPegawaiDispensasi($tahun . '-' . $bulanOk . '-15') == true) {
            return 0;
        }

        if ($this->getStatusTubel($tahun, $bulan) == true) {
            return 0;
        }

        if ($this->getIsGuru($tahun, $bulan) == true) {
            return 0;
        }

        if ($this->getHasSkp(['tahun'=>$tahun])) {
            return 0;
        }

        return 50;
    }

    public function getRealisasiRkb($tahun, $bulan)
    {
        return 50;
    }

    public function getPotonganRkb($tahun, $bulan)
    {
        return 10;
    }

    public function getPotonganKinerja($tahun, $bulan)
    {
        return 5;
    }

    public function getPotonganCkhpTotal($bulan, $tahun = null)
    {
        if ($tahun === null) {
            $tahun = User::getTahun();
        }
        if (!isset($this->_potongan_ckhp[$bulan])) {
            if (!$this->getHasSkp()) {
                $this->_potongan_ckhp[$bulan] = 0;
            }

            $bantu = $tahun . '-' . $bulan . '-01';
            $date = new DateTime($bantu);
            $total = 0;
            for ($i = 1; $i <= $date->format('t'); $i++) {
                $total += $this->getPotonganCkhp($date->format("Y-m-" . sprintf("%02d", $i)));
            }
            $this->_potongan_ckhp[$bulan] = $total;
        }
        return $this->_potongan_ckhp[$bulan];
    }

    public function getPotonganCkhp($tanggal)
    {
        return $this->getIsWajibCkhp($tanggal) ? 1 : 0;
    }

    public function getPotonganKegiatanTotal($bulan, $tahun = null)
    {
        return $this->getHasSkp(['tahun'=>$tahun]) ? $this->getPotonganSkp($tahun) + $this->getPotonganCkhpTotal($bulan) : 50;
    }

    public function findOrCreateUserInfo()
    {
        if ((int)$this->countUserinfo() === 0) {
            $new = new Userinfo([
                'badgenumber' => $this->nip,
                'DelTag' => 0,
                'name' => $this->nama,
            ]);
            $new->save(false);
            return $new;
        }

        return $this->manyUserinfo[0];
    }

    public function findAllInstansiPegawai()
    {
        $query = $this->getManyInstansiPegawai()
            ->aktif()
            ->orderBy(['tanggal_berlaku' => SORT_DESC]);

        return $query->all();
    }

    public function getNamaJabatanAtasan()
    {
        if (@$this->jabatan->jabatanInduk->nama != null) {
            return @$this->jabatan->jabatanInduk->nama;
        }

        return "Belum Dimapping";
    }

    public function getNamaPegawaiAtasan()
    {
        if (@$this->jabatan->jabatanInduk->id != null) {
            $query = Pegawai::find();
            $query->andWhere([
                'id_jabatan' => @$this->jabatan->jabatanInduk->id
            ]);

            $list = [];
            foreach ($query->all() as $pegawai) {
                $list[] = $pegawai->nama;
            }

            return implode(", ", $list);
        }

        return "Belum Ada Pemangku";
    }

    /**
     * @param $tahun
     */
    public function refreshInstansiPegawaiSkp($params = [])
    {
        $tahun = User::getTahun();
        if (@$params['tahun'] != null) {
            $tahun = @$params['tahun'];
        }

        $query = $this->getManyInstansiPegawai();
        $query->filterByTahun($tahun);

        $urutan = 1;
        $urutanPlt = 1;

        $arrayIdInstansiPegawai = [];
        foreach ($query->all() as $instansiPegawai) {
            $arrayIdInstansiPegawai[] = $instansiPegawai->id;

            if ($instansiPegawai->status_plt == 0) {
                $instansiPegawai->updateInstansiPegawaiSkp([
                    'tahun' => $tahun,
                    'urutan' => $urutan,
                    'status_plt' => 0
                ]);

                $urutan++;
            }

            if ($instansiPegawai->status_plt == 1) {
                $instansiPegawai->updateInstansiPegawaiSkp([
                    'tahun' => $tahun,
                    'urutan' => $urutanPlt,
                    'status_plt' => 1
                ]);

                $urutanPlt++;
            }
        }

        if (count($arrayIdInstansiPegawai) != 0) {
            $allInstansiPegawaiSkp = InstansiPegawaiSkp::find()
                ->joinWith(['instansiPegawai'])
                ->andWhere(['instansi_pegawai_skp.tahun' => $tahun])
                ->andWhere(['instansi_pegawai.id_pegawai' => $this->id])
                ->andWhere(['not in', 'instansi_pegawai_skp.id_instansi_pegawai', $arrayIdInstansiPegawai])
                ->all();

            foreach ($allInstansiPegawaiSkp as $instansiPegawaiSkp) {
                $instansiPegawaiSkp->softDelete();
            }
        }
    }

    public function getLinkButtonUpdate()
    {
        if ($this->accessUpdate() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-pencil"></i> Sunting Instansi', [
            'update', 'id' => $this->id
        ], ['class' => 'btn btn-success btn-flat']);
    }

    public function accessUpdate()
    {
        if (User::isAdmin()) {
            return true;
        }

        return false;
    }

    public function getLinkIconView()
    {
        if ($this->accessView() == false) {
            return null;
        }

        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', [
            '/pegawai/view',
            'id' => $this->id
        ], [
            'data-toggle' => 'tooltip',
            'title' => 'View'
        ]) . ' ';
    }

    public function accessView()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator()) {
            return true;
        }

        if (User::isMapping()) {
            return true;
        }

        return false;
    }

    public function getLinkIconUpdate()
    {
        if ($this->accessUpdate() == false) {
            return null;
        }

        return Html::a('<i class="glyphicon glyphicon-pencil"></i>', [
            '/pegawai/update',
            'id' => $this->id
        ], [
            'data-toggle' => 'tooltip',
            'title' => 'Ubah'
        ]) . ' ';
    }

    public function getLinkIconUpdateGolongan()
    {
        if ($this->accessUpdate() == false) {
            return null;
        }

        return Html::a('<i class="glyphicon glyphicon-pencil"></i>', [
            '/pegawai/view',
            'id' => $this->id
        ], [
            'data-toggle' => 'tooltip',
            'title' => 'Ubah'
        ]) . ' ';
    }

    public function getLinkIconDelete()
    {
        if ($this->accessDelete() == false) {
            return null;
        }

        return Html::a('<i class="glyphicon glyphicon-trash"></i>', [
            '/pegawai/delete',
            'id' => $this->id
        ], [
            'data-method' => 'post',
            'data-confirm' => 'Yakin akan menghapus data?',
            'data-toggle' => 'tooltip',
            'title' => 'Hapus'
        ]) . ' ';
    }

    public function accessDelete()
    {
        if (User::isAdmin()) {
            return true;
        }

        return false;
    }

    public function getLinkIconUserSetPassword()
    {
        if ($this->accessUserSetPassword() == false) {
            return null;
        }

        return Html::a('<i class="glyphicon glyphicon-lock"></i>', [
            '/user/set-password',
            'id' => $this->getIdUser()
        ], ['data-toggle' => 'tooltip', 'title' => 'Set Password']);
    }

    public function accessUserSetPassword()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator()) {
            return true;
        }

        return false;
    }

    public function getIdUser()
    {
        $model = User::findOne([
            'id_pegawai' => $this->id,
            'id_user_role' => UserRole::PEGAWAI,
        ]);

        if ($model === null) {
            $model = new User;
            $model->username = $this->nip;
            $model->id_pegawai = $this->id;
            $model->id_user_role = UserRole::PEGAWAI;
            $model->password = Yii::$app->getSecurity()->generatePasswordHash($this->nip);

            if (!$model->save()) {
                //print_r($model->getErrors());
                //die();
            }
        }

        return $model->id;
    }

    public function countInstansiPegawai()
    {
        return count($this->manyInstansiPegawai);
    }

    public function getNipFormat()
    {
        return substr($this->nip, 0, 8)
            . ' '
            . substr($this->nip, 8, 6)
            . ' '
            . substr($this->nip, 14, 1)
            . ' '
            . substr($this->nip, 15);
    }

    /**
     * @var PresensiCeklis[]
     */
    protected $_presensiCeklis = [];

    /**
     * @param $bulan
     * @return PresensiCeklis
     */
    public function getPresensiCeklis($bulan)
    {
        if (!isset($this->_presensiCeklis[$bulan])) {
            $this->_presensiCeklis[$bulan] = new PresensiCeklis(['pegawai' => $this, 'bulan' => $bulan]);
        }
        return $this->_presensiCeklis[$bulan];
    }

    public function getListIdInstansiPegawai()
    {
        $query = $this->getManyInstansiPegawai();
        $query->select('id');
        return $query->column();
    }

    public function getQueryInstansiPegawaiSkp($params = [])
    {
        $query = $this->getManyInstansiPegawaiSkp();
        $query->andFilterWhere([
            'instansi_pegawai_skp.tahun' => @$params['tahun']
        ]);

        return $query;
    }

    public function getCountInstansiPegawaiSkp($params = [])
    {
        $query = $this->getQueryInstansiPegawaiSkp($params);

        return $query->count();
    }

    public function findAllInstansiPegawaiSkp($params = [])
    {
        $query = $this->getQueryInstansiPegawaiSkp($params);

        return $query->all();
    }

    public function getJumlahKetidakhadiranKegiatanTanpaKeteranganSidak()
    {
        $bantu = $this->_jumlah_ketidakhadiran_kegiatan_tanpa_keterangan;
        return @$bantu[Absensi::KETIDAKHADIRAN_KEGIATAN_SIDAK];
    }

    public function getPersenPotonganKetidakhadiranKegiatanTanpaKeteranganSidak()
    {
        return $this->getJumlahKetidakhadiranKegiatanTanpaKeteranganSidak()
            * 4;
    }

    public function getJumlahKetidakhadiranKegiatanTanpaKeteranganSelainSidak()
    {
        $bantu = $this->_jumlah_ketidakhadiran_kegiatan_tanpa_keterangan;

        $jumlah = 0;
        $jumlah += @$bantu[Absensi::KETIDAKHADIRAN_KEGIATAN_APEL_PAGI];
        $jumlah += @$bantu[Absensi::KETIDAKHADIRAN_KEGIATAN_APEL_SORE];
        $jumlah += @$bantu[Absensi::KETIDAKHADIRAN_KEGIATAN_UPACARA];
        $jumlah += @$bantu[Absensi::KETIDAKHADIRAN_KEGIATAN_SENAM];

        return $jumlah;
    }

    public function getPersenPotonganKetidakhadiranKegiatanTanpaKeteranganSelainSidak()
    {
        return $this->getJumlahKetidakhadiranKegiatanTanpaKeteranganSelainSidak()
            * 4;
    }


    public function getJumlahTerlambatMasukKerjaInterval($jenis)
    {
        return @$this->_jumlah_terlambat_masuk_kerja_interval[$jenis];
    }

    public function getPersenPotonganTerlambatMasukKerjaInterval($jenis)
    {
        return $this->getJumlahTerlambatMasukKerjaInterval($jenis)
            * $this->getPersenPotonganInterval($jenis);
    }

    public function getJumlahTerlambatMasukIstirahatInterval($jenis)
    {
        return @$this->_jumlah_terlambat_masuk_istirahat_interval[$jenis];
    }

    public function getPersenPotonganTerlambatMasukIstirahatInterval($jenis)
    {
        return $this->getJumlahTerlambatMasukIstirahatInterval($jenis)
            * $this->getPersenPotonganInterval($jenis);
    }

    public function getJumlahPulangAwalKeluarKerja($jenis)
    {
        return @$this->_jumlah_pulang_awal_keluar_kerja_interval[$jenis];
    }

    public function getPersenPotonganPulangAwalKeluarKerjaInterval($jenis)
    {
        return $this->getJumlahPulangAwalKeluarKerja($jenis)
            * $this->getPersenPotonganInterval($jenis);
    }

    public function getPersenPotonganInterval($jenis)
    {
        if ($jenis == Absensi::INTERVAL_1_SD_15) {
            return 1;
        }

        if ($jenis == Absensi::INTERVAL_16_SD_30) {
            return 2;
        }

        if ($jenis == Absensi::INTERVAL_31_KE_ATAS) {
            return 2.5;
        }

        if ($jenis == Absensi::INTERVAL_TIDAK_PRESENSI) {
            return 4;
        }
    }

    public function getJumlahPulangAwalKeluarKerjaSemuaInterval()
    {
        $jumlah = 0;
        $jumlah += $this->getJumlahPulangAwalKeluarKerja(Absensi::INTERVAL_1_SD_15);
        $jumlah += $this->getJumlahPulangAwalKeluarKerja(Absensi::INTERVAL_16_SD_30);
        $jumlah += $this->getJumlahPulangAwalKeluarKerja(Absensi::INTERVAL_16_SD_30);

        return $jumlah;
    }

    public function getPersenPotonganPulangAwalKeluarKerjaSemuaInterval()
    {
        return $this->getJumlahPulangAwalKeluarKerjaSemuaInterval() * 1;
    }

    public function getPersenPotonganTidakHadirTanpaKeterangan()
    {
        return $this->_hari_tanpa_keterangan * 12;
    }

    public function getPersenPotonganPresensi($bulan = null, $params = [])
    {
        if (@$params['tukin'] == true) {
            $pegawaiRekapAbsensi = $this->findOrCreatePegawaiRekapAbsensi($bulan);
            return @$pegawaiRekapAbsensi->persen_potongan_total;
        }

        $jumlah = 0;
        $jumlah += $this->getPersenPotonganTidakHadirTanpaKeterangan();

        $jumlah += $this->getPersenPotonganTerlambatMasukKerjaInterval(Absensi::INTERVAL_1_SD_15);
        $jumlah += $this->getPersenPotonganTerlambatMasukKerjaInterval(Absensi::INTERVAL_16_SD_30);
        $jumlah += $this->getPersenPotonganTerlambatMasukKerjaInterval(Absensi::INTERVAL_31_KE_ATAS);
        $jumlah += $this->getPersenPotonganTerlambatMasukKerjaInterval(Absensi::INTERVAL_TIDAK_PRESENSI);

        $jumlah += $this->getPersenPotonganTerlambatMasukIstirahatInterval(Absensi::INTERVAL_1_SD_15);
        $jumlah += $this->getPersenPotonganTerlambatMasukIstirahatInterval(Absensi::INTERVAL_16_SD_30);
        $jumlah += $this->getPersenPotonganTerlambatMasukIstirahatInterval(Absensi::INTERVAL_31_KE_ATAS);
        $jumlah += $this->getPersenPotonganTerlambatMasukIstirahatInterval(Absensi::INTERVAL_TIDAK_PRESENSI);

        $jumlah += $this->getPersenPotonganPulangAwalKeluarKerjaSemuaInterval();
        $jumlah += $this->getPersenPotonganPulangAwalKeluarKerjaInterval(Absensi::INTERVAL_TIDAK_PRESENSI);

        $jumlah += $this->getPersenPotonganKetidakhadiranKegiatanTanpaKeteranganSelainSidak();
        $jumlah += $this->getPersenPotonganKetidakhadiranKegiatanTanpaKeteranganSidak();

        if ($jumlah > 100) {
            $jumlah = 100;
        }

        return $jumlah;
    }

    public function getAllPegawaiGolongan()
    {
        return $this->getManyPegawaiGolongan()
            ->orderBy(['tanggal_mulai' => SORT_ASC])
            ->all();
    }


    public function getLabelGolongan()
    {
        if ($this->pegawaiGolongan !== null) {
            return Html::tag("span", $this->pegawaiGolongan->golongan, ['class' => 'label label-success']);
        }
        return Html::a("<span class='label label-danger'><i class='fa fa-warning'></i> Belum Ditentukan", ['/pegawai/view', 'id' => $this->id], ['data-toggle' => 'tooltip', 'title' => 'Golongan belum diset : berpengaruh pada besaran TPP']);
    }

    public function getJabatanTunjanganGolongan($bulan = null, $tahun = null)
    {
        $pegawaiGolongan = $this->getPegawaiGolonganBerlaku([
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);

        if (in_array(@$pegawaiGolongan->id_golongan, Golongan::arrayGolonganI())) {
            return JabatanTunjanganGolongan::I;
        }
        if (in_array(@$pegawaiGolongan->id_golongan, Golongan::arrayGolonganIi())) {
            return JabatanTunjanganGolongan::II;
        }
        if (in_array(@$pegawaiGolongan->id_golongan, Golongan::arrayGolonganIii())) {
            return JabatanTunjanganGolongan::III;
        }
        if (in_array(@$pegawaiGolongan->id_golongan, Golongan::arrayGolonganIv())) {
            return JabatanTunjanganGolongan::IV;
        }

        if (@$pegawaiGolongan->id_golongan == Golongan::V) {
            return JabatanTunjanganGolongan::V;
        }

        if (@$pegawaiGolongan->id_golongan == Golongan::IX) {
            return JabatanTunjanganGolongan::IX;
        }

        if (@$pegawaiGolongan->id_golongan == Golongan::X) {
            return JabatanTunjanganGolongan::X;
        }

        if (@$pegawaiGolongan->id_golongan == Golongan::VII) {
            return JabatanTunjanganGolongan::VII;
        }
    }

    /**
     * @param array $params
     * @return PegawaiGolongan
     */
    public function getPegawaiGolonganBerlaku($params = [])
    {
        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        if ($tahun == null) {
            $tahun = Session::getTahun();
        }

        if ($bulan == null) {
            $bulan = date('n');
        }

        $datetime = DateTime::createFromFormat('Y-n-d', $tahun . '-' . $bulan . '-01');
        $tanggal = $datetime->format('Y-m-15');

        /*
        if ($bulan < 10) {
            $bulan = '0' . $bulan;
        }

        $tanggal = $tahun . '-' . $bulan . '-15';
        */

        if (@$params['tanggal'] != null) {
            $tanggal = @$params['tanggal'];
        }

        $query = PegawaiGolongan::find();
        $query->andWhere(['id_pegawai' => $this->id]);
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal', [
            ':tanggal' => $tanggal
        ]);

        $query->with('golongan');

        return $query->one();
    }

    public function getNamaPegawaiGolonganBerlaku($params = [])
    {
        $pegawaiGolongan = $this->getPegawaiGolonganBerlaku($params);

        if ($pegawaiGolongan !== null) {
            return $pegawaiGolongan->golongan->golongan;
        }
    }

    public $_is_direktur_utama;
    public function getIsDirekturUtama(string $tahun, string $bulan)
    {
        if ($this->_is_direktur_utama != null) {
            return $this->_is_direktur_utama;
        }

        $datetime = DateTime::createFromFormat('Y-n-d', $tahun . '-' . $bulan . '-01');
        $tanggal = $datetime->format('Y-m-15');

        $instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);
        $nama_jabatan = @$instansiPegawai->nama_jabatan;

        $this->_is_direktur_utama = false;

        if (substr($nama_jabatan,0,8) == 'Direktur') {
            $this->_is_direktur_utama = true;
        }

        return $this->_is_direktur_utama;
    }

    public $_is_tugas_belajar;
    public function getIsTugasBelajar(string $tahun, string $bulan)
    {
        if ($this->_is_tugas_belajar != null) {
            return $this->_is_tugas_belajar;
        }

        $datetime = DateTime::createFromFormat('Y-n-d', $tahun . '-' . $bulan . '-01');
        $tanggal = $datetime->format('Y-m-15');

        $instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);
        $nama_jabatan = @$instansiPegawai->nama_jabatan;

        $this->_is_tugas_belajar = false;

        if (substr($nama_jabatan,0,13) == 'Tugas Belajar') {
            $this->_is_tugas_belajar = true;
        }

        return $this->_is_tugas_belajar;
    }

    public $_is_guru;

    public function getIsGuru(string $tahun, string $bulan)
    {
        if ($this->_is_guru != null) {
            return $this->_is_guru;
        }

        $datetime = DateTime::createFromFormat('Y-n-d', $tahun . '-' . $bulan . '-01');
        $tanggal = $datetime->format('Y-m-15');

        $instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);

        $nama_jabatan = @$instansiPegawai->nama_jabatan;

        if (
            substr($nama_jabatan, 0, 4) == 'Guru'
            or substr($nama_jabatan, 0, 10) == 'Calon Guru'
            or substr($nama_jabatan, 0, 15) == 'Tenaga Pendidik'
            or substr($nama_jabatan, 0, 14) == 'Kepala Sekolah'
        ) {
            $this->_is_guru = true;
        } else {
            $this->_is_guru = false;
        }

        return $this->_is_guru;
    }

    private $_is_dokter_spesialis;

    public function getIsDokterSpesialis($params = [])
    {
        if ($this->_is_dokter_spesialis != null) {
            return $this->_is_dokter_spesialis;
        }

        $tahun = @$params['tahun'];
        if ($tahun == null) {
            $tahun = Session::getTahun();
        }

        $bulan = @$params['bulan'];
        if ($bulan == null) {
            $bulan = date('n');
        }

        $datetime = DateTime::createFromFormat('Y-n-d', $tahun . '-' . $bulan . '-01');
        $tanggal = $datetime->format('Y-m-15');

        $instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);

        $this->_is_dokter_spesialis = false;

        if (
            substr(@$instansiPegawai->nama_jabatan, 0, 6) == 'Dokter'
            or substr(@$instansiPegawai->nama_jabatan, 0, 8) == 'Direktur'
        ) {
            if (
                strpos(@$instansiPegawai->nama_jabatan, 'Spesialis') !== false
                and strpos(@$instansiPegawai->nama_jabatan, 'Subspesialis') === false
            ) {
                $this->_is_dokter_spesialis = true;
            }
        }

        return $this->_is_dokter_spesialis;
    }

    private $_is_dokter_subspesialis;

    public function getIsDokterSubspesialis($params = [])
    {
        if ($this->_is_dokter_subspesialis != null) {
            return $this->_is_dokter_subspesialis;
        }

        $tahun = @$params['tahun'];
        if ($tahun == null) {
            $tahun = Session::getTahun();
        }

        $bulan = @$params['bulan'];
        if ($bulan == null) {
            $bulan = date('n');
        }

        $datetime = DateTime::createFromFormat('Y-n-d', $tahun . '-' . $bulan . '-01');
        $tanggal = $datetime->format('Y-m-15');

        $instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);

        $this->_is_dokter_subspesialis = false;

        if (
            substr(@$instansiPegawai->nama_jabatan, 0, 6) == 'Dokter'
            or substr(@$instansiPegawai->nama_jabatan, 0, 8) == 'Direktur'
        ) {
            if (strpos(@$instansiPegawai->nama_jabatan, 'Subspesialis') !== false) {
                $this->_is_dokter_subspesialis = true;
            }
        }

        return $this->_is_dokter_subspesialis;
    }

    public function getKelasJabatan(): ?int
    {
        $instansiPegawai = @$this->instansiPegawaiBerlaku;
        $instansiPegawaiPlt = @$this->instansiPegawaiBerlakuPlt;

        if ($instansiPegawaiPlt != null) {
            if (@$instansiPegawai->jabatan->kelas_jabatan < @$instansiPegawaiPlt->jabatan->kelas_jabatan) {
                return @$instansiPegawaiPlt->jabatan->kelas_jabatan;
            }
        }

        return @$instansiPegawai->jabatan->kelas_jabatan;
    }

    public function getEselonJabatan(): ?string
    {
        $instansiPegawai = @$this->instansiPegawaiBerlaku;
        $instansiPegawaiPlt = @$this->instansiPegawaiBerlakuPlt;

        if ($instansiPegawaiPlt != null) {
            if (@$instansiPegawai->jabatan->kelas_jabatan < @$instansiPegawaiPlt->jabatan->kelas_jabatan) {
                return $instansiPegawaiPlt->getEselonJabatan();
            }
        }

        if ($instansiPegawai == null) {
            return null;
        }

        return $instansiPegawai->getEselonJabatan();
    }

    public static function findNamaByNip($nip)
    {
        $model = Pegawai::findOne(['nip' => $nip]);
        if ($model === null) {
            return null;
        }

        return $model->nama;
    }

    /**
     * @return InstansiPegawaiSkp
     */
    public function getInstansiPegawaiSkpFromTanggal($tanggal=null)
    {
        if($tanggal ==  null) {
            $tanggal = date('Y-m-d');
        }

        $query = InstansiPegawaiSkp::find();
        $query->joinWith(['instansiPegawai']);

        $query->andWhere([
            'instansi_pegawai.id_pegawai' => $this->id
        ]);

        $query->andWhere('instansi_pegawai_skp.tanggal_mulai <= :tanggal AND
            instansi_pegawai_skp.tanggal_selesai >= :tanggal',[
            ':tanggal' => $tanggal
        ]);

        return $query->one();
    }

    /**
     * @param array $params
     * @return array
     */
    public function getArrayIdBawahan($params = []): array
    {
        $instansiPegawai = $this->getInstansiPegawaiBerlaku();

        if($instansiPegawai === null) {
            return ['-'];
        }

        if($instansiPegawai->jabatan === null) {
            return ['-'];
        }

        $arrayIdJabatanBawahan = $instansiPegawai->jabatan->getArrayIdBawahan($params);

        if(count($arrayIdJabatanBawahan) == 0) {
            return ['-'];
        }

        $query = InstansiPegawai::find();
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
            ':tanggal' => date('Y-m-d')
        ]);

        $query->andWhere(['id_jabatan' => $arrayIdJabatanBawahan]);

        $query->select('id_pegawai');

        return $query->column();
    }

    /**
     * Fungsi untuk mendapatkan array KegiatanHarian
     *
     * $params['tanggal']
     * $params['bulan']
     *
     * @param $params
     * @return KegiatanHarian[]|array
     */
    public function getArrayKegiatanHarian($params=[])
    {
        if ($this->_kegiatan_harian !== null) {
            return $this->_kegiatan_harian;
        }

        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        if ($tahun == null) {
            $tahun = User::getTahun();
        }

        $datetime = \DateTime::createFromFormat('Y-m', $tahun . '-' . $bulan);

        $query = KegiatanHarian::find();
        $query->andWhere(['id_pegawai' => $this->id]);
        $query->andWhere('tanggal <= :tanggal_mulai AND tanggal >= :tanggal_selesai', [
            ':tanggal_mulai' => $datetime->format('Y-m-01'),
            ':tanggal_selesai' => $datetime->format('Y-m-t'),
        ]);
        $query->andWhere('id_kegiatan_harian_versi >= 2');

        $this->_kegiatan_harian = $query->all();

        return $this->_kegiatan_harian;
    }

    public $_arrayKegiatanHarianBulan;
    public function getArrayKegiatanHarianBulan(array $params)
    {
        if ($this->_arrayKegiatanHarianBulan !== null) {
            return $this->_arrayKegiatanHarianBulan;
        }

        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        if ($tahun == null) {
            $tahun = Session::getTahun();
        }

        if ($bulan == null) {
            $bulan = date('n');
        }

        $datetime = \DateTime::createFromFormat('Y-n-d', $tahun . '-' . $bulan . '-01');
        $tanggal_awal = $datetime->format('Y-m-01');
        $tanggal_akhir = $datetime->format('Y-m-t');

        $query = KegiatanHarian::find();
        $query->andWhere(['id_pegawai' => $this->id]);
        $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir', [
            ':tanggal_awal' => $tanggal_awal,
            ':tanggal_akhir' => $tanggal_akhir,
        ]);
        $query->andWhere('id_kegiatan_harian_versi >= 2');

        $this->_arrayKegiatanHarianBulan = $query->all();
        return $this->_arrayKegiatanHarianBulan;
    }

    /**
     * $params['tanggal']
     *
     * @param array $params
     * @return bool
     */
    public function isPegawaiTidakWajibCkhp(array $params): bool
    {
        $pegawaiTukin = \app\modules\tukin\models\Pegawai::findOne($this->id);
        $tanggal = $params['tanggal'];

        $datetime = \DateTime::createFromFormat('Y-m-d', $tanggal);
        $bulan = $datetime->format('n');
        $tahun = $datetime->format('Y');

        $listData = [
            '198806052010011003' => '2022-06-01',
            '198907232019022005' => '2022-06-01',
            '198803132010011002' => '2022-06-01',
            '199511302019021008' => '2022-06-01',
        ];

        if (@$listData[$this->nip] == $tanggal) {
            return true;
        }

        // KHUSUS TANGGAL 2022-07-04 TIDAK ADA POTONGAN CKHP
        if ($tanggal == '2022-07-04' OR $tanggal == '2023-01-10') {
            return true;
        }

        if ($pegawaiTukin->getPegawaiTugasBelajar(['bulan' => $bulan, 'tahun' => $tahun]) != null) {
            return true;
        }

        if ($datetime->format('n') <= 6) {
            if ($this->getIsTugasBelajar($tahun, $bulan)) {
                return true;
            }
        }

        if ($this->getIsGuru($tahun, $bulan)) {
            return true;
        }

        if ($this->getIsDirekturUtama($tahun, $bulan)) {
            return true;
        }

        if($this->getIsDokterSpesialis(['bulan'=>$bulan,'tahun'=>$tahun]) == true) {
            return true;
        }

        if($this->getIsDokterSubspesialis(['bulan'=>$bulan,'tahun'=>$tahun]) == true) {
            return true;
        }

        return false;
    }

    /**
     * $params['tanggal']
     *
     * @param array $params
     * @return bool
     */
    public $_has_kegiatan_harian;
    public function hasKegiatanHarian(array $params): bool
    {
        $tanggal = @$params['tanggal'];

        if (@$this->_has_kegiatan_harian[$tanggal] !== null) {
            return $this->_has_kegiatan_harian[$tanggal];
        }

        if ($this->isPegawaiTidakWajibCkhp($params) == true) {
            $this->_has_kegiatan_harian[$tanggal] = true;
            return $this->_has_kegiatan_harian[$tanggal];
        }

        $datetime = \DateTime::createFromFormat('Y-m-d', $tanggal);
        $query = new ArrayQuery();
        $query->from($this->getArrayKegiatanHarianBulan([
            'tahun' => $datetime->format('Y'),
            'bulan' => $datetime->format('m'),
        ]));
        $query->andWhere(['tanggal' => $tanggal]);

        $queryNull = new ArrayQuery(['from' => $query->all()]);
        $queryNull->andWhere(['waktu_dibuat' => null]);
        if ($queryNull->count() > 0) {
            $this->_has_kegiatan_harian[$tanggal] = true;
            return $this->_has_kegiatan_harian[$tanggal];
        }

        if ($query->count() != 0) {
            /* @var $model KegiatanHarian */
            $model = $query->one();
            if ($model->isDiskresi() == true) {
                $this->_has_kegiatan_harian[$tanggal] = true;
                return $this->_has_kegiatan_harian[$tanggal];
            }
        }

        $query->andWhere(['>=', 'waktu_dibuat', $datetime->format('Y-m-d 06:00:00')]);
        $query->andWhere(['<=', 'waktu_dibuat', $datetime->format('Y-m-d 23:59:59')]);

        $this->_has_kegiatan_harian[$tanggal] = $query->count() > 0;
        return $this->_has_kegiatan_harian[$tanggal];
    }

    /**
     * Fungsi untuk memeriksa apakah pegawai memiliki hari kerja atau tidak
     *
     * $params['tanggal']<br/>
     * $params['bulan']
     *
     * @param array $params
     * @return bool
     */
    public $_is_hari_kerja;
    public function isHariKerja(array $params): bool
    {
        $tanggal = @$params['tanggal'];

        if (@$this->_is_hari_kerja[$tanggal] !== null) {
            return $this->_is_hari_kerja[$tanggal];
        }

        $shiftKerja = $this->findShiftKerja(['tanggal' => $tanggal]);

        $datetime = \DateTime::createFromFormat('Y-m-d', $tanggal);

        if ($tanggal < '2022-06-01') {
            $this->_is_hari_kerja[$tanggal] = false;
            return $this->_is_hari_kerja[$tanggal];
        }

        if ($this->isTanggalMulaiPengisianCkhp($params) == false) {
            $this->_is_hari_kerja[$tanggal] = false;
            return $this->_is_hari_kerja[$tanggal];
        }

        if ($tanggal > date('Y-m-d')) {
            $this->_is_hari_kerja[$tanggal] = false;
            return $this->_is_hari_kerja[$tanggal];
        }

        if ($this->isHariLibur($params) && $shiftKerja->getIsLiburNasional()) {
            $this->_is_hari_kerja[$tanggal] = false;
            return $this->_is_hari_kerja[$tanggal];
        }

        // Jika tidak ada jam kerja pada tanggal
        if ($shiftKerja->countJamKerja($datetime->format('N')) == 0) {
            $this->_is_hari_kerja[$tanggal] = false;
            return $this->_is_hari_kerja[$tanggal];
        }

        if ($this->hasKetidakhadiranPanjang(['tanggal' => $tanggal])) {
            $this->_is_hari_kerja[$tanggal] = false;
            return $this->_is_hari_kerja[$tanggal];
        }

        $isPegawaiDispensasi = $this->isPegawaiDispensasi([
            'tanggal' => $tanggal,
            'id_pegawai_dispensasi_jenis' => PegawaiDispensasiJenis::FULL,
        ]);

        if ($isPegawaiDispensasi == true) {
            $this->_is_hari_kerja[$tanggal] = false;
            return $this->_is_hari_kerja[$tanggal];
        }

        if ($this->hasCuti(['tanggal' => $tanggal])) {
            $this->_is_hari_kerja[$tanggal] = false;
            return $this->_is_hari_kerja[$tanggal];
        }

        $this->_is_hari_kerja[$tanggal] = true;
        return $this->_is_hari_kerja[$tanggal];
    }

    /**
     * @param array $params
     * @return bool
     */
    public function isHariLibur(array $params=[]): bool
    {
        $query = \app\modules\absensi\models\HariLibur::find();
        $query->andWhere(['tanggal'=> @$params['tanggal']]);

        $model = $query->one();

        if ($model == null) {
            return false;
        }

        return true;
    }

    /**
     * @param array $params
     * @return bool
     */
    public function isPegawaiDispensasi(array $params=[]): bool
    {
        $tanggal = @$params['tanggal'];

        $query = PegawaiDispensasi::find();
        $query->andWhere(['id_pegawai' => $this->id]);
        $query->andFilterWhere(['id_pegawai_dispensasi_jenis' => @$params['id_pegawai_dispensasi_jenis']]);
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_akhir >= :tanggal', [
            ':tanggal' => $tanggal,
        ]);

        $model = $query->one();

        if ($model == null) {
            return false;
        }

        return true;
    }

    /**
     * @param array $params
     * @return bool
     */
    public function hasCuti(array $params=[]): bool
    {
        $query = KetidakhadiranPanjang::find();
        $query->andWhere(['id_pegawai' => $this->id]);
        $query->andWhere(['id_ketidakhadiran_panjang_jenis' => [
            KetidakhadiranPanjangJenis::KETIDAKHADIRAN_CUTI_BESAR,
            KetidakhadiranPanjangJenis::KETIDAKHADIRAN_CUTI_ALASAN_PENTING,
            KetidakhadiranPanjangJenis::KETIDAKHADIRAN_CUTI_TAHUNAN,
            KetidakhadiranPanjangJenis::KETIDAKHADIRAN_CUTI_DILUAR_TANGGUNGAN_NEGARA,
            KetidakhadiranPanjangJenis::KETIDAKHADIRAN_CUTI_MELAHIRKAN,
            KetidakhadiranPanjangJenis::KETIDAKHADIRAN_CUTI_SAKIT,
        ]]);
        $query->andWhere(['id_ketidakhadiran_panjang_status' => KetidakhadiranPanjangStatus::SETUJU]);
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal', [
            ':tanggal' => @$params['tanggal'],
        ]);

        $model = $query->one();

        if ($model == null) {
            return false;
        }

        return true;
    }

    /**
     * $params['tanggal']
     *
     * @param array $params
     * @return bool
     */
    public function hasKetidakhadiranPanjang(array $params): bool
    {
        $ketidakhadiranPanjang = KetidakhadiranPanjang::find()
            ->andWhere(['id_pegawai' => $this->id])
            ->andWhere(['id_ketidakhadiran_panjang_status' => KetidakhadiranPanjangStatus::SETUJU])
            ->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal', [
                ':tanggal' => $params['tanggal'],
            ])
            ->andWhere(['NOT IN', 'id_ketidakhadiran_panjang_jenis', [
                KetidakhadiranPanjangJenis::KETIDAKHADIRAN_TUGAS_KEDINASAN,
                KetidakhadiranPanjangJenis::KETIDAKHADIRAN_ALASAN_TEKNIS,
                KetidakhadiranPanjangJenis::KETIDAKHADIRAN_ALASAN_KHUSUS,
            ]])
            ->one();

        return $ketidakhadiranPanjang !== null;
    }

    public function isTanggalMulaiPengisianCkhp(array $params): bool
    {
        $query = InstansiPegawai::query();
        $query->andWhere(['id_pegawai' => $this->id]);
        $query->andWhere('tanggal_berlaku <= :tanggal AND tanggal_selesai >= :tanggal', [
            ':tanggal' => @$params['tanggal'],
        ]);

        return $query->count() > 0;
    }

    /**
     * @param array $params
     * @return float
     */
    public function getPotonganAtribut(array $params=[]): float
    {
        $bulan = @$params['bulan'];
        $tahun = @$params['tahun'];

        if ($tahun == null) {
            $tahun = Session::getTahun();
        }

        $datetime = \DateTime::createFromFormat('Y-m-d', $tahun . '-' . $bulan . '-01');

        $query = PegawaiAtribut::find();
        $query->andWhere(['id_pegawai' => $this->id]);
        $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir', [
            ':tanggal_awal' => $datetime->format('Y-m-01'),
            ':tanggal_akhir' => $datetime->format('Y-m-t'),
        ]);

        $jumlah = $query->count();

        return $jumlah * 2.5;
    }

    /**
     * @param array $params
     * @return ArrayQuery
     */
    public function getCountAllKegiatanHarian(array $params = [])
    {
        $query = $this->getManyKegiatanHarian();
        $query->andFilterWhere([
            'kegiatan_harian.tanggal' => @$params['tanggal'],
            'kegiatan_harian.id_kegiatan_status' => @$params['id_kegiatan_status'],
            'kegiatan_harian.id_kegiatan_harian_versi' => @$params['id_kegiatan_harian_versi'],
        ]);
        $query->aktif();

        return $query->count();
    }

    /**
     * $params['tanggal']
     *
     * @param array $params
     * @return float|int|null
     */
    public function getStringPotonganCkhp(array $params)
    {
        if ($this->isHariKerja($params) == false) {
            return null;
        }

        if ($this->hasKegiatanHarian($params) == false) {
            $datetime = \DateTime::createFromFormat('Y-m-d', $params['tanggal']);
            return $this->getPersenPotonganCkhp([
                'bulan' => $datetime->format('n'),
                'tahun' => $datetime->format('Y'),
            ]);
        }

        return 0;
    }

    /**
     * $params['bulan']<br>
     * $params['tahun']
     *
     * @param array $params
     * @return int
     */
    public function getJumlahTidakMembuatKegiatanHarian(array $params=[]): int
    {
        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        if ($tahun == null) {
            $tahun = Session::getTahun();
        }

        $datetime = \Datetime::createFromFormat('Y-n-d', $tahun . '-' . $bulan . '-01');

        $jumlah = 0;
        $end = $datetime->format('t');
        for ($i = 1; $i <= $end; $i++) {
            $tanggal = $datetime->format('Y-m-d');

            if ($this->isHariKerja(['tanggal' => $tanggal]) == true
                AND $this->hasKegiatanHarian(['tanggal' => $tanggal]) == false
            ) {
                $jumlah++;
            }

            $datetime->modify('+1 day');
        }

        return $jumlah;
    }

    /**
     * $params['bulan']
     * $params['tahun']
     *
     * @param array $params
     * @return float|int
     */
    public function getTotalPotonganCkhp(array $params)
    {
        $jumlah = $this->getJumlahTidakMembuatKegiatanHarian([
            'bulan' => @$params['bulan'],
            'tahun' => @$params['tahun'],
        ]);

        $potongan = $this->getPersenPotonganCkhp($params);

        return $jumlah * $potongan;
    }

    /**
     * $params['bulan']
     * $params['tahun']
     *
     * @param array $params
     * @return float
     */
    public function getPersenPotonganCkhp(array $params): float
    {
        $pengaturan = Pengaturan::findOne(Pengaturan::PERSEN_POTONGAN_CKHP);

        if ($pengaturan == null) {
            return 0;
        }

        return floatval($pengaturan->getNilaiBerlaku($params));
    }

    public function canAccessKegiatanHarian(array $params): bool
    {
        if (Session::isPegawai() == false) {
            return false;
        }

        $tanggal = @$params['tanggal'];
        $datetime = \DateTime::createFromFormat('Y-m-d', $tanggal);

        $kegiatanHarianDiskresi = KegiatanHarianDiskresi::find()
            ->andWhere(['id_pegawai' => $this->id])
            ->andWhere(['tanggal' => $tanggal])
            ->one();

        if ($kegiatanHarianDiskresi != null) {
            return true;
        }

        if ($tanggal == date('Y-m-d')) {
            return true;
        }

        if ($datetime->format('Y-m') == '2022-04'
            OR $datetime->format('Y-m') == '2022-05'
        ) {
            return true;
        }

        return false;
    }

    /**
     * $params['bulan']
     *
     * @param array $params
     * @return mixed|string|null
     */
    public function getRupiahTppKotorFromRekap(array $params)
    {
        $rekapPegawaiBulan = RekapPegawaiBulan::findOrCreate([
            'bulan' => $params['bulan'],
            'tahun' => Session::getTahun(),
            'id_pegawai' => $this->id,
            'id_rekap_jenis' => RekapJenis::JUMLAH_TPP_KOTOR,
        ]);

        return $rekapPegawaiBulan->nilai;
    }

    /**
     * @return KegiatanRhk[]
     */
    public function findAllKegiatanRhk(array $params  = [])
    {
        $tahun = @$params['tahun'];

        if ($tahun == null) {
            $tahun = date('Y');
        }

        $query = $this->getManyKegiatanRhk();
        $query->andWhere(['kegiatan_rhk.tahun' => $tahun]);

        if (@$params['id_kegiatan_rhk_jenis'] != null) {
            $query->andWhere(['kegiatan_rhk.id_kegiatan_rhk_jenis' => @$params['id_kegiatan_rhk_jenis']]);
        }

        if (@$params['id_kegiatan_rhk_atasan'] != null) {
            $query->andWhere(['kegiatan_rhk.id_kegiatan_rhk_atasan' => @$params['id_kegiatan_rhk_atasan']]);
        }

        if (@$params['id_instansi_pegawai'] != null) {
            $query->andWhere(['kegiatan_rhk.id_instansi_pegawai' => @$params['id_instansi_pegawai']]);
        }

        if (@$params['id_induk_is_null'] != null) {
            $query->andWhere('kegiatan_rhk.id_induk IS NULL');
        }

        return $query->all();
    }

    public function getDataIndeksIpAsnFromSimadig()
    {
        $url = @Yii::$app->params['url_simadig'];
        
        $client = new Client();
        /** @noinspection MissedFieldInspection */
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url . '/api/pegawai/view-ip-asn/' . $this->nip)
            ->setHeaders([
                'Content-Type'=>'application/json',
            ])
            ->send();

        if ($response->isOk !== true) {
            return null;
        }

        $content = json_decode($response->content);
        return $content->data;
    }

    public function updateRekapPegawaiBulanIpAsn(array $params = [])
    {
        $response = $this->getDataIndeksIpAsnFromSimadig();

        RekapPegawaiBulan::updateOrCreate([
            'id_pegawai' => $this->id,
            'id_rekap_jenis' => RekapJenis::INDEKS_IP_ASN,
            'bulan' => $params['bulan'],
            'tahun' => $params['tahun'],
            'nilai' => @$response->total,
        ]);

        return true;
    }

    public static function accessRefreshIpAsn(array $params = [])
    {
        $bulan = @$params['bulan'];

        if (Session::isAdmin()) {
            return true;
        }

        if (Session::isAdminInstansi() OR Session::isInstansi()) {
            
            if ($bulan != null AND $bulan == date('n')) {
                return true;
            }

        }

        return false;
    }

    public function getChecktimeKegiatan(array $params)
    {
        $id_kegiatan = $params['id_kegiatan'];

        $kegiatan = Kegiatan::findOne($id_kegiatan);

        $query = Checkinout::find();
        $query->joinWith('userinfo');
        $query->andWhere('checktime >= :tanggal_awal AND checktime <= :tanggal_akhir', [
            ':tanggal_awal' => $kegiatan->tanggal . ' ' . $kegiatan->jam_mulai_absen,
            ':tanggal_akhir' => $kegiatan->tanggal . ' ' . $kegiatan->jam_selesai_absen,
        ]);

        $string = null;

        foreach ($query->all() as $checkinout) {
            $string .= $checkinout->checktime . ', ';
        }

        $string = substr($string, 0, -2);

        return $string;
    }
}
