<?php

namespace app\modules\tukin\models;

use app\components\Config;
use app\components\kinerja\KinerjaBulan;
use app\components\Session;
use app\models\InstansiLokasi;
use app\models\PegawaiCutiIbadah;
use app\models\PegawaiJenis;
use app\models\PegawaiPenghargaan;
use app\models\PegawaiPenghargaanStatus;
use app\models\PegawaiPenghargaanTingkat;
use app\models\PegawaiRb;
use app\models\PegawaiRbJenis;
use app\models\PegawaiSertifikasi;
use app\models\PegawaiTugasBelajar;
use app\models\PegawaiTundaBayar;
use app\models\PengaturanBerlaku;
use app\models\TunjanganInstansiJenisJabatanKelas;
use app\modules\absensi\models\KetidakhadiranPanjang;
use app\modules\kinerja\models\KegiatanBulanan;
use app\modules\kinerja\models\KegiatanHarianSearch;
use app\modules\kinerja\models\KegiatanStatus;
use app\modules\kinerja\models\KegiatanTahunan;
use app\modules\tunjangan\models\JabatanGolongan;
use app\modules\tunjangan\models\JabatanTunjanganGolongan;
use app\modules\tunjangan\models\JabatanTunjanganKhusus;
use app\modules\tunjangan\models\JabatanTunjanganKhususJenis;
use app\modules\tunjangan\models\JabatanTunjanganKhususPegawai;
use Yii;
use app\models\Eselon;
use app\models\Golongan;
use app\models\PegawaiSertifikasiJenis;
use app\models\User;
use app\modules\tunjangan\models\JabatanTunjanganFungsional;
use app\modules\tunjangan\models\JabatanTunjanganPelaksana;
use app\modules\tunjangan\models\JabatanTunjanganStruktural;
use app\modules\tunjangan\models\TunjanganPotongan;
use app\modules\tunjangan\models\TunjanganPotonganPegawai;
use yii2mod\query\ArrayQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "pegawai".
 *
 * @property int $id
 * @property string $nama
 * @property string $nip
 * @property int $id_instansi
 * @property int $id_jabatan
 * @property int $id_atasan
 * @property int $id_golongan
 * @property int $id_instansi_pegawai_bak
 * @property string $nama_jabatan
 * @property int $status_batas_pengajuan
 * @property string $gender
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string $alamat
 * @property string $telepon
 * @property string $email
 * @property string $foto
 * @property string $grade
 * @property string $gelar_depan
 * @property string $gelar_belakang
 * @property int $id_eselon
 * @property string $eselon_bak
 * @property int $id_pegawai_status
 * @property string $status_hapus
 * @property int $jumlah_userinfo
 * @property int $jumlah_checkinout
 *
 * @property Instansi $instansi
 * @property Eselon $eselon
 * @property Pegawai $atasan
 * @property KelasJabatan $kelasJabatan
 * @property PegawaiRekapTunjangan[] $pegawaiRekapTunjangan
 * @property InstansiPegawai[] $allInstansiPegawai
 * @property float $rupiahAbsensi
 * @property mixed $hukumanDisiplin
 * @property PegawaiVariabelObjektif[] $manyPegawaiVariabelObjektif
 * @property float $rupiahTukin
 * @property string $namaNip
 * @property float $rupiahKinerja
 * @property Jabatan $jabatan
 * @property InstansiKordinatifBesaran $instansiKordinatifBesaran
 */
class Pegawai extends \app\models\Pegawai
{
    /**
     * @var mixed|null
     *
     */
    private $_tpp_awal_v3;

    private $_keterangan_singkat_tpp_awal_v3;

    private $_keterangan_lengkap_tpp_awal_v3;

    /**
     * @var mixed|null
     */
    private $_tpp_awal_plt_v3;

    /**
     * @var float|int
     */
    private $_rupiah_tpp_tambahan_penghargaan;

    /**
     * @var float|int
     */
    private $_rupiah_tpp_tambahan_pengisian_renbangkom;

    /**
     * @var mixed|null
     */
    private $_rupiah_tpp_tambahan_pemutakhiran_simadig;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDb()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Yii::$app->get('db');
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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama', 'nip', 'id_instansi', 'gender'], 'required'],
            [['id_instansi', 'id_jabatan', 'id_atasan', 'id_golongan', 'id_instansi_pegawai_bak', 'status_batas_pengajuan', 'id_eselon', 'id_pegawai_status', 'jumlah_userinfo', 'jumlah_checkinout'], 'integer'],
            [['tanggal_lahir', 'status_hapus'], 'safe'],
            [['alamat'], 'string'],
            [['nama', 'gender', 'tempat_lahir', 'telepon', 'email'], 'string', 'max' => 100],
            [['nip'], 'string', 'max' => 50],
            [['nama_jabatan', 'gelar_depan', 'gelar_belakang', 'eselon_bak'], 'string', 'max' => 255],
            [['foto'], 'string', 'max' => 200],
            [['grade'], 'string', 'max' => 10],
            [['nip'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'nip' => 'Nip',
            'id_instansi' => 'Instansi',
            'id_jabatan' => 'Jabatan',
            'id_atasan' => 'Atasan',
            'id_golongan' => 'Golongan',
            'nama_jabatan' => 'Nama Jabatan',
            'status_batas_pengajuan' => 'Status Batas Pengajuan',
            'gender' => 'Gender',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'alamat' => 'Alamat',
            'telepon' => 'Telepon',
            'email' => 'Email',
            'foto' => 'Foto',
            'grade' => 'Grade',
            'gelar_depan' => 'Gelar Depan',
            'gelar_belakang' => 'Gelar Belakang',
            'id_eselon' => 'Eselon',
            'eselon_bak' => 'Eselon Bak',
            'id_pegawai_status' => 'Pegawai Status',
            'status_hapus' => 'Status Hapus',
            'jumlah_userinfo' => 'Jumlah Userinfo',
            'jumlah_checkinout' => 'Jumlah Checkinout',
        ];
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi']);
    }

    public function getJabatan()
    {
        return $this->hasOne(Jabatan::class, ['id' => 'id_jabatan']);
    }

    public function getEselon()
    {
        return $this->hasOne(Eselon::class, ['id' => 'id_eselon']);
    }

    public function getAtasan()
    {
        return $this->hasOne(self::class, ['id' => 'id_atasan']);
    }

    public function getTunjanganPotonganPegawai()
    {
        return $this->hasOne(TunjanganPotonganPegawai::class, ['id_pegawai' => 'id']);
    }

    public function getPegawaiRekapTunjangan()
    {
        return $this->hasMany(PegawaiRekapTunjangan::class, ['id_pegawai' => 'id', 'id_instansi' => 'id_instansi'])
            ->andWhere(['tahun' => User::getTahun()]);
    }

    public function getManyPegawaiSertifikasi()
    {
        return $this->hasMany(PegawaiSertifikasi::class, ['id_pegawai' => 'id']);
    }

    protected $_pegawaiRekapTunjangan = false;
    /**
     * @param $bulan
     * @return PegawaiRekapTunjangan
     */
    public function findOrCreatePegawaiRekapTunjangan($bulan)
    {
        if ($this->_pegawaiRekapTunjangan === false) {
            $this->_pegawaiRekapTunjangan = $this->pegawaiRekapTunjangan;
        }
        $query = new ArrayQuery(['from' => $this->_pegawaiRekapTunjangan]);
        $query->andWhere(['bulan' => $bulan]);
        if (!$query->exists()) {
            $new = new PegawaiRekapTunjangan([
                'id_pegawai' => $this->id,
                'id_instansi' => $this->id_instansi,
                'bulan' => $bulan,
                'tahun' => User::getTahun(),
            ]);
            $new->save(false);
            $this->_pegawaiRekapTunjangan[] = $new;
            return $new;
        }
        return $query->one();
    }

    public function getAllInstansiPegawai()
    {
        return $this->hasMany(InstansiPegawai::class, ['id_pegawai' => 'id'])
            ->aktif();
    }

    public function getRupiah()
    {
        if ($this->jabatan == null) {
            return 0;
        }
        if (@$this->jabatan->getIsJumlahTetap()) {
            return $this->jabatan->jumlah_tetap;
        }

        if ($this->kelasJabatan !== null) {
            return $this->kelasJabatan->getNilaiTengah()
                * $this->jabatan->penyeimbang
                * Yii::$app->params['idrp'];
        }

    }

    /**
     * @return float
     */
    public function getRupiahKinerja()
    {
        return $this->getRupiah()
            * 0.8;
    }

    /**
     * @param PegawaiRekapKinerja|int $data
     * @return float|int
     */
    public function getRupiahKinerjaPersen($data)
    {
        if (!$data instanceof PegawaiRekapKinerja && is_int($data)) {
            $data = $this->findOrCreatePegawaiRekapTunjangan($data);
        }
        return $this->getRupiahKinerja()
            * ($data->getPersenKinerja() / 100);
    }

    /**
     * @return float
     */
    public function getRupiahAbsensi()
    {
        return $this->getRupiah()
            * 0.1;
    }

    /**
     * @param PegawaiRekapAbsensi|int $data
     * @return float|int
     */
    public function getRupiahAbsensiPersen($data)
    {
        if (!$data instanceof PegawaiRekapAbsensi && is_int($data)) {
            $data = $this->findOrCreatePegawaiRekapTunjangan($data);
        }
        return $this->getRupiahAbsensi()
            * ($data->getPersenAbsensi() / 100);
    }

    public function getRupiahSerapanAnggaran()
    {
        return $this->getRupiah()
            * 0.1;
    }

    /**
     * @param PegawaiRekapAbsensi|int $data
     * @return float|int
     */
    public function getRupiahSerapanAnggaranPersen($data)
    {
        if (!$data instanceof PegawaiRekapAbsensi && is_int($data)) {
            $data = $this->findOrCreatePegawaiRekapTunjangan($data);
        }
        return $this->getRupiahSerapanAnggaran()
            * ($data->getPersenSerapanAnggaran() / 100);
    }

    /**
     * @param int $bulan
     * @return float|int
     */
    public function getRupiahTukinPersen($bulan)
    {
        $bulan = (int) $bulan;
        return $this->getRupiahKinerjaPersen($bulan)
            + $this->getRupiahAbsensiPersen($bulan)
            + $this->getRupiahSerapanAnggaranPersen($bulan)
            + $this->getTarifVariabelObjektifBulan($bulan)
            + $this->getRupiahInstansiKordinatif($bulan);
    }

    /**
     * @param int $bulan
     * @return float|int
     */
    public function getRupiahAkhirPersen($bulan)
    {
        $bulan = (int) $bulan;
        $rupiah = $this->getRupiahTukinPersen($bulan);
        return  $rupiah -  ($rupiah * $this->getPotonganHukumanDisiplin($bulan) / 100);
    }

    /**
     * @return float
     */
    public function getRupiahTukin()
    {
        return $this->getRupiahKinerja()
            + $this->getRupiahAbsensi()
            + $this->getRupiahSerapanAnggaran();
    }

    public function getHukumanDisiplin()
    {
        return $this->hasMany(HukumanDisiplin::class, ['id_pegawai' => 'id'])
            ->aktif();
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

    public function getCountHukumanDisiplin($bulan = null)
    {
        if ($bulan === null) {
            return count($this->hukumanDisiplin);
        }
        return count($this->getManyHukumanDisiplin($bulan));
    }

    /**
     * @param $bulan
     * @param $id_hukuman_disiplin_jenis
     * @param null $tahun
     * @return float|int
     */
    public function getPersenPotonganHukumanDisiplinJenis($bulan, $id_hukuman_disiplin_jenis, $tahun=null)
    {
        if($tahun == null) {
            $tahun = Session::getTahun();
        }

        $datetime = \DateTime::createFromFormat('Y-n-d', $tahun.'-'.$bulan.'-01');

        $query = HukumanDisiplin::find();
        $query->with(['hukumanDisiplinJenis']);

        $query->andWhere([
            'id_pegawai' => $this->id,
            'id_hukuman_disiplin_jenis' => $id_hukuman_disiplin_jenis
        ]);

        $query->andWhere('tanggal_mulai >= :tanggal_awal AND tanggal_mulai <= :tanggal_akhir
            OR tanggal_selesai >= :tanggal_awal AND tanggal_selesai <= :tanggal_akhir
            OR tanggal_mulai <= :tanggal_awal AND tanggal_selesai >= :tanggal_akhir
        ',[
            ':tanggal_awal' => $datetime->format('Y-m-01'),
            ':tanggal_akhir' => $datetime->format('Y-m-t')
        ]);



        $persenPotongan = 0;
        foreach($query->all() as $data) {
            $persenPotongan += @$data->hukumanDisiplinJenis->potongan;
        }

        return $persenPotongan;
    }

    public $_potongan_hukuman_disiplin_ringan;

    public function getPersenPotonganHukumanDisiplinRingan($bulan)
    {
        if ($this->_potongan_hukuman_disiplin_ringan != null) {
            return $this->_potongan_hukuman_disiplin_ringan;
        }

        $potongan = $this->getPersenPotonganHukumanDisiplinJenis($bulan, HukumanDisiplinJenis::RINGAN);

        $this->_potongan_hukuman_disiplin_ringan = $potongan;

        return $this->_potongan_hukuman_disiplin_ringan;

    }

    public $_potongan_hukuman_disiplin_sedang;

    public function getPersenPotonganHukumanDisiplinSedang($bulan)
    {
        if ($this->_potongan_hukuman_disiplin_sedang == null) {
            $this->_potongan_hukuman_disiplin_sedang = $this->getPersenPotonganHukumanDisiplinJenis($bulan, HukumanDisiplinJenis::SEDANG);
        }

        return $this->_potongan_hukuman_disiplin_sedang;
    }

    public $_potongan_hukuman_disiplin_berat;

    public function getPersenPotonganHukumanDisiplinBerat($bulan)
    {
        if ($this->_potongan_hukuman_disiplin_berat == null) {
            $this->_potongan_hukuman_disiplin_berat = $this->getPersenPotonganHukumanDisiplinJenis($bulan, HukumanDisiplinJenis::BERAT);
        }

        return $this->_potongan_hukuman_disiplin_berat;
    }

    /**
     * @var float|int
     */
    protected $_potongan_disiplin;

    public function getPotonganHukumanDisiplin($bulan)
    {
        $bulan = (int) $bulan;
        if (!isset($this->_potongan_disiplin[$bulan])) {
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

    public function getNamaNip()
    {
        return "$this->nama - $this->nip";
    }

    public function getManyPegawaiVariabelObjektif()
    {
        return $this->hasMany(PegawaiVariabelObjektif::class, ['id_pegawai' => 'id'])
            ->andWhere(['tahun' => User::getTahun()])
            ->inverseOf('pegawai');
    }

    /**
     * @param $bulan
     * @return PegawaiVariabelObjektif[]
     */
    public function getManyVariabelObjektifBulan($bulan)
    {
        return (new ArrayQuery(['from' => $this->manyPegawaiVariabelObjektif]))
            ->andWhere(['<=', 'bulan_mulai', $bulan])
            ->andWhere(['>=', 'bulan_selesai', $bulan])
            ->all();
    }

    public function getCountManyVariableObjektifBulan($bulan)
    {
        return count($this->getManyVariabelObjektifBulan($bulan));
    }

    public function getTarifVariabelObjektifBulan($bulan)
    {
        return array_sum(
            array_map(
                function ($row) {
                    return $row->tarif;
                },
                $this->getManyVariabelObjektifBulan($bulan)
            )
        );
    }

    public function getIsInstansiKordinatif($bulan = null)
    {
        return $this->instansi->getHasKordinatifAktif($bulan);
    }

    public function getIsStruktural()
    {
        return $this->id_eselon !== null && $this->id_eselon !== Eselon::NON_ESELON;
    }

    /**
     * @param null $bulan
     * @return InstansiKordinatifBesaran|null
     */
    public function getInstansiKordinatifBesaran($bulan = null)
    {
        if ($this->getIsInstansiKordinatif($bulan)) {
            $instansiKordinatif = $this->instansi->getInstansiKordinasiAktif($bulan);
            $query = new ArrayQuery(['from' => $instansiKordinatif->manyBesaran]);
            if ($this->jabatan->getIsStruktural()) {
                foreach (Eselon::getKelompok() as $kelompok => $eselon) {
                    if (in_array($this->id_eselon, $eselon)) {
                        return $query->andWhere(['id_eselon' => $kelompok])->one();
                    }
                }
            } else {
                foreach (Golongan::getKelompok() as $kelompok => $golongan) {
                    if (in_array($this->id_golongan, $golongan)) {
                        return $query->andWhere(['id_golongan' => $kelompok])->one();
                    }
                }
            }
            return $query->one();
        }
        return null;
    }

    public function getRupiahInstansiKordinatif($bulan)
    {
        if ($this->getIsInstansiKordinatif($bulan)) {
            return $this->getInstansiKordinatifBesaran($bulan)->besaran;
        }
        return 0;
    }

    public function getBesaranStruktural($bulan, $params=[])
    {
        $tahun = @$params['tahun'];

        if($tahun==null) {
            $tahun = User::getTahun();
        }

        $tanggal = $tahun.'-'.$bulan.'-15';
        $instansiPegawai = @$params['instansiPegawai'];

        if($instansiPegawai === null) {
            $instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);
        }

        if(substr(@$instansiPegawai->nama_jabatan,0,9) == 'Staf Ahli') {
            $query = JabatanTunjanganKhusus::find()->andWhere([
                'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::STAF_AHLI_GUBERNUR,
            ]);
            $query->berlaku($tanggal);

            $model = $query->one();
            return @$model->besaran_tpp;
        }

        if(substr(@$instansiPegawai->nama_jabatan,0,20) == 'Asisten Pemerintahan') {
            $query = JabatanTunjanganKhusus::find()->andWhere([
                'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::ASISTEN_SEKRETARIS_DAERAH,
            ]);
            $query->berlaku($tanggal);

            $model = $query->one();
            return @$model->besaran_tpp;
        }

        if(substr(@$instansiPegawai->nama_jabatan,0,20) == 'Asisten Perekonomian') {
            $query = JabatanTunjanganKhusus::find()->andWhere([
                'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::ASISTEN_SEKRETARIS_DAERAH,
            ]);
            $query->berlaku($tanggal);

            $model = $query->one();
            return @$model->besaran_tpp;
        }

        if(substr(@$instansiPegawai->nama_jabatan,0,20) == 'Asisten Administrasi') {
            $query = JabatanTunjanganKhusus::find()->andWhere([
                'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::ASISTEN_SEKRETARIS_DAERAH,
            ]);
            $query->berlaku($tanggal);

            $model = $query->one();
            return @$model->besaran_tpp;
        }

        $id_instansi = $instansiPegawai->jabatan->id_instansi;

        if($instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::UPTD) {
            $id_instansi = $instansiPegawai->instansi->id_induk;
        }

        if($instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::CABANG_DINAS) {
            $id_instansi = $instansiPegawai->instansi->id_induk;
        }

        if($instansiPegawai->instansi->id_instansi_jenis == InstansiJenis::SEKOLAH) {
            $id_instansi = 33;
        }

        if($instansiPegawai->id_instansi == 42 //RSJD
            OR $instansiPegawai->id_instansi == 43 //RSUD
            OR $instansiPegawai->id_instansi == 213 //RSUD Ir. SOEKARNO
        ) {
            $id_instansi = 25;
        }

        $query = JabatanTunjanganStruktural::find();

        $id_eselon = @$instansiPegawai->jabatan->id_eselon;

        if($id_eselon == null) {
            $id_eselon = 0;
        }

        $query->andWhere(['id_eselon' => $id_eselon]);

        if($instansiPegawai->jabatan->id_eselon == Eselon::ESELON_IA) {
            $query->andWhere(['id_instansi' => $id_instansi]);
        }

        if($instansiPegawai->jabatan->id_eselon == Eselon::ESELON_IB) {
            $query->andWhere(['id_instansi' => $id_instansi]);
        }

        if($instansiPegawai->jabatan->id_eselon == Eselon::ESELON_IIA) {
            $query->andWhere(['id_instansi' => $id_instansi]);
        }

        if($instansiPegawai->jabatan->id_eselon == Eselon::ESELON_IIB) {
            $query->andWhere(['id_instansi' => $id_instansi]);
        }

        if($instansiPegawai->jabatan->id_eselon == Eselon::ESELON_IIIA) {
            $query->andWhere(['id_instansi' => $id_instansi]);
            $query->andWhere(['id_jabatan_tunjangan_golongan' => $this->getJabatanTunjanganGolongan($bulan, $tahun)]);
        }

        if($instansiPegawai->jabatan->id_eselon == Eselon::ESELON_IIIB) {
            //ID ISNTANSI 3 = BAKEUDA
            if($id_instansi == 3) {
                $query->andWhere(['id_instansi' => $id_instansi]);
                $query->andWhere(['id_jabatan_tunjangan_golongan' => $this->getJabatanTunjanganGolongan($bulan, $tahun)]);
            } else {
                $query->andWhere('id_instansi IS NULL');
            }
        }

        if($instansiPegawai->jabatan->id_eselon == Eselon::ESELON_IVA) {
            $query->andWhere(['id_instansi' => $id_instansi]);
            $query->andWhere(['id_jabatan_tunjangan_golongan' => $this->getJabatanTunjanganGolongan($bulan, $tahun)]);
        }

        if($instansiPegawai->jabatan->id_eselon == Eselon::ESELON_IVB) {
            $query->andWhere(['id_instansi' => $id_instansi]);
            $query->andWhere(['id_jabatan_tunjangan_golongan' => $this->getJabatanTunjanganGolongan($bulan, $tahun)]);
        }

        $model = $query->one();

        if ($model !== null) {
            return $model->besaran_tpp;
        } else {
            return 0;
        }
    }

    public $_is_guru;
    public $_is_kepala_sekolah;
    public $_is_dokter_spesialis;
    public $_is_dokter_subspesialis;
    public $_is_direktur_utama;

    public function getBesaranFungsional($bulan, $tahun=null)
    {
        if($tahun==null) {
            $tahun = User::getTahun();
        }

        $datetime = \DateTime::createFromFormat('Y-n-d',$tahun.'-'.$bulan.'-01');
        $tanggal = $datetime->format('Y-m-15');

        $pegawaiTukin = $this->getPegawaiTukin([
            'tahun' => $tahun,
            'bulan' => $bulan,
        ]);

        //$instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);
        $instansiPegawai = $pegawaiTukin->getInstansiPegawai();

        if(substr(@$instansiPegawai->nama_jabatan,0,6) == 'Dokter') {
            if(strpos(@$instansiPegawai->nama_jabatan,'Spesialis') !== false
                AND strpos(@$instansiPegawai->nama_jabatan,'Subspesialis') === false
            ) {
                $this->_is_dokter_spesialis = true;
                $query = JabatanTunjanganKhusus::find()->andWhere([
                    'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::DOKTER_SPESIALIS,
                ]);
                $query->berlaku($tanggal);

                $model = $query->one();
                return @$model->besaran_tpp;
            }

            if(strpos(@$instansiPegawai->nama_jabatan,'Subspesialis') !== false) {

                $this->_is_dokter_subspesialis = true;

                $query = JabatanTunjanganKhusus::find()->andWhere([
                    'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::DOKTER_SUBSPESIALIS,
                ]);
                $query->berlaku($tanggal);

                $model = $query->one();
                return @$model->besaran_tpp;
            }
        }

        //Guru
        if(substr(@$instansiPegawai->nama_jabatan,0,4) == 'Guru'
            OR substr(@$instansiPegawai->nama_jabatan,0,10) == 'Calon Guru'
            OR substr(@$instansiPegawai->nama_jabatan,0,15) == 'Tenaga Pendidik'
        ) {

            $this->_is_guru = true;

            $id_jabatan_tunjangan_golongan = $this->getJabatanTunjanganGolongan($bulan, $tahun);

            if($id_jabatan_tunjangan_golongan == null) {
                $id_jabatan_tunjangan_golongan = 0;
            }

            $query = JabatanTunjanganKhusus::find()->andWhere([
               'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::GURU,
               'id_jabatan_tunjangan_golongan' => $id_jabatan_tunjangan_golongan,
            ]);
            $query->berlaku($tanggal);

            $model = $query->one();
            return @$model->besaran_tpp;

        }

        //Kepala Sekolah
        if(substr(@$instansiPegawai->nama_jabatan,0,14) == 'Kepala Sekolah') {

            $this->_is_kepala_sekolah = true;

            $id_jabatan_tunjangan_golongan = $this->getJabatanTunjanganGolongan($bulan, $tahun);

            if($id_jabatan_tunjangan_golongan == null) {
                $id_jabatan_tunjangan_golongan = 0;
            }

            $query = JabatanTunjanganKhusus::find()->andWhere([
                'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::KEPALA_SEKOLAH,
                'id_jabatan_tunjangan_golongan' => $id_jabatan_tunjangan_golongan,
            ]);
            $query->berlaku($tanggal);

            $model = $query->one();
            return @$model->besaran_tpp;

        }

        //Pengawas Sekolah
        if(substr(@$instansiPegawai->nama_jabatan,0,16) == 'Pengawas Sekolah') {
            $id_jabatan_tunjangan_golongan = $this->getJabatanTunjanganGolongan($bulan, $tahun);

            if($id_jabatan_tunjangan_golongan == null) {
                $id_jabatan_tunjangan_golongan = 0;
            }

            $query = JabatanTunjanganKhusus::find()->andWhere([
                'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::PENGAWAS_SEKOLAH,
                'id_jabatan_tunjangan_golongan' => $id_jabatan_tunjangan_golongan,
            ]);
            $query->berlaku($tanggal);

            $model = $query->one();
            return @$model->besaran_tpp;

        }

        //Diretkru RSU/RSJ
        if(substr(@$instansiPegawai->nama_jabatan,0,8) == 'Direktur') {

            $this->_is_direktur_utama = true;

            $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::DIREKTUR_RSP_IR_SOEKARNO_NON_SPESIALIS;

            $queryJenis = JabatanTunjanganKhususPegawai::query([
                'id_pegawai' => $this->id,
                'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::DIREKTUR_RSP_IR_SOEKARNO_SPESIALIS,
                'bulan' => $bulan,
                'tahun' => $tahun
            ]);

            $modelJenis = $queryJenis->one();

            if($modelJenis !== null) {
                $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::DIREKTUR_RSP_IR_SOEKARNO_SPESIALIS;
            }

            $query = JabatanTunjanganKhusus::find()->andWhere([
                'id_jabatan_tunjangan_khusus_jenis' => $id_jabatan_tunjangan_khusus_jenis,
            ]);
            $query->berlaku($tanggal);

            $model = $query->one();
            return @$model->besaran_tpp;

        }

        $id_instansi = $instansiPegawai->id_instansi;
        if($instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::SEKOLAH) {
            $id_instansi = 33;
        }

        if($instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::UPTD) {
            $id_instansi = $instansiPegawai->instansi->id_induk;
        }

        if($instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::CABANG_DINAS) {
            $id_instansi = $instansiPegawai->instansi->id_induk;
        }

        if($instansiPegawai->id_instansi == 42 //RSJD
            OR $instansiPegawai->id_instansi == 43 //RSUD
            OR $instansiPegawai->id_instansi == 213 //RSUD Ir. SOEKARNO
        ) {
            $id_instansi = 25;
        }

        $query = JabatanTunjanganFungsional::find();
        $query->andWhere(['id_instansi' => $id_instansi]);
        $query->andWhere(['id_tingkatan_fungsional' => @$instansiPegawai->jabatan->id_tingkatan_fungsional]);

        $model = $query->one();

        if ($model !== null) {
            return $model->besaran_tpp;
        } else {
            return 0;
        }
    }

    public $_is_pengemudi;
    public $_is_bendahara;
    public function getBesaranPelaksana($bulan, $tahun=null)
    {
        if($tahun==null) {
            $tahun = User::getTahun();
        }

        $datetime = \DateTime::createFromFormat('Y-n-d', $tahun.'-'.$bulan.'-01');
        $tanggal = $datetime->format('Y-m-15');

        $pegawaiTukin = $this->getPegawaiTukin([
            'tahun' => $tahun,
            'bulan' => $bulan,
        ]);

        //$instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);
        $instansiPegawai = $pegawaiTukin->getInstansiPegawai();

        //Calon Guru
        if(substr(@$instansiPegawai->nama_jabatan,0,10) == 'Calon Guru') {
            return $this->getBesaranFungsional($bulan);
        }

        //Tenaga Pendidik
        if(substr(@$instansiPegawai->nama_jabatan,0,15) == 'Tenaga Pendidik') {
            return $this->getBesaranFungsional($bulan);
        }

        //Pengemudi
        if(substr(@$instansiPegawai->nama_jabatan,0,9) == 'Pengemudi' AND $this->_status_tubel != true) {

            $this->_is_pengemudi = true;

            $query = JabatanTunjanganKhusus::find()->andWhere([
                'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::SUPIR,
            ]);
            $query->berlaku($tanggal);

            $model = $query->one();
            return @$model->besaran_tpp;
        }

        //Bendahara
        if(substr(@$instansiPegawai->nama_jabatan,0,9) == 'Bendahara' AND $this->_status_tubel != true) {

            $this->_is_bendahara = true;

            $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::BENDAHARA_SAMPAI_20M;

            $queryJenis = JabatanTunjanganKhususPegawai::query([
                'id_pegawai' => $this->id,
                'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::BENDAHARA_LEBIH_20M,
                'bulan' => $bulan,
                'tahun' => $tahun
            ]);

            $modelJenis = $queryJenis->one();

            if($modelJenis !== null) {
                $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::BENDAHARA_LEBIH_20M;
            }

            $query = JabatanTunjanganKhusus::find()->andWhere([
                'id_jabatan_tunjangan_khusus_jenis' => $id_jabatan_tunjangan_khusus_jenis,
            ]);
            $query->berlaku($tanggal);

            $model = $query->one();
            return @$model->besaran_tpp;

        }

        $id_instansi = $instansiPegawai->id_instansi;
        if($instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::SEKOLAH) {
            $id_instansi = 33;
        }

        if($instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::CABANG_DINAS) {
            $id_instansi = $instansiPegawai->instansi->id_induk;
        }

        if($instansiPegawai->id_instansi == 42 //RSJD
            OR $instansiPegawai->id_instansi == 43 //RSUD
            OR $instansiPegawai->id_instansi == 213 //RSUD Ir. SOEKARNO
        ) {
            $id_instansi = 25;
        }

        if($instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::UPTD) {
            $id_instansi = $instansiPegawai->instansi->id_induk;
        }

        $id_jabatan_tunjangan_golongan = $this->getJabatanTunjanganGolongan($bulan, $tahun);

        $query = JabatanTunjanganPelaksana::find();

        $query->andWhere(['id_jabatan_tunjangan_golongan' => $id_jabatan_tunjangan_golongan]);

        if($id_jabatan_tunjangan_golongan == JabatanTunjanganGolongan::IV) {
            $query->andWhere(['id_instansi' => $id_instansi]);
        }

        if($id_jabatan_tunjangan_golongan == JabatanTunjanganGolongan::III) {
            $query->andWhere(['id_instansi' => $id_instansi]);
        }

        if($id_jabatan_tunjangan_golongan == JabatanTunjanganGolongan::II) {
            $query->andWhere(['id_instansi' => $id_instansi]);
        }

        if($id_jabatan_tunjangan_golongan == JabatanTunjanganGolongan::I) {
            $query->andWhere(['id_instansi' => $id_instansi]);
        }

        $model = $query->one();

        if ($model !== null) {
            return $model->besaran_tpp;
        } else {
            return 0;
        }
    }

    public function getBesaranJabatanTunjanganKhusus($params=[])
    {
        $tanggal = @$params['tanggal'];
        if ($tanggal == null) {
            $tanggal = date('Y-m-d');
        }

        $query = JabatanTunjanganKhusus::find();
        $query->andWhere(['kelas_jabatan' => @$params['kelas_jabatan']]);
        $query->andWhere(['id_jabatan_tunjangan_khusus_jenis' => @$params['id_jabatan_tunjangan_khusus_jenis']]);
        $query->andFilterWhere(['id_jabatan_tunjangan_golongan' => @$params['id_jabatan_tunjangan_golongan']]);

        if (@$params['keterangan_tidak_sama_dengan'] == true) {
            $query->andFilterWhere(['!=', 'keterangan', @$params['keterangan']]);
        } else {
            $query->andFilterWhere(['keterangan' => @$params['keterangan']]);
        }

        if ($tanggal <= '2022-12-31') {
            $query->andWhere(['status_p3k' => 0]);
        }

        if ($tanggal >= '2023-01-01') {
            $query->andWhere(['status_p3k' => $this->id_pegawai_jenis == PegawaiJenis::P3K]);
        }

        $query->berlaku($tanggal);

        $model = $query->one();
        if ($model == null) {
            return 0;
        }

        return $model->besaran_tpp;
    }

    public function isGuruBersertifikasi($params=[])
    {
        $tanggal = @$params['tanggal'];
        if ($tanggal == null) {
            $tanggal = date('Y-m-d');
        }

        $query = $this->getManyPegawaiSertifikasi();
        $query->andWhere(['id_pegawai_sertifikasi_jenis' => PegawaiSertifikasiJenis::GURU_SERTIFIKASI]);
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal', [
            ':tanggal' => $tanggal
        ]);

        $model = $query->one();
        if ($model == null) {
            return false;
        }

        return true;
    }

    public function isDokterSpesialisasi($params=[])
    {
        $id_jabatan_tunjangan_khusus_jenis = @$params['id_jabatan_tunjangan_khusus_jenis'];
        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        $query = JabatanTunjanganKhususPegawai::query([
            'id_pegawai' => $this->id,
            'id_jabatan_tunjangan_khusus_jenis' => $id_jabatan_tunjangan_khusus_jenis,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);

        $model = $query->one();
        if($model == null) {
            return false;
        }

        return true;
    }

    public function getBesaranStruktural2022($params=[])
    {
        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];
        $instansiPegawai = @$params['instansiPegawai'];

        if ($tahun==null) {
            $tahun = User::getTahun();
        }

        if ($bulan == null) {
            $bulan = date('m');
        }

        $datetime = \DateTime::createFromFormat('Y-n-d',$tahun.'-'.$bulan.'-01');

        $tanggal = $datetime->format('Y-m-15');

        if($instansiPegawai === null) {
            $instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);
        }

        $id_instansi = @$instansiPegawai->jabatan->id_instansi;
        $kelas_jabatan = @$instansiPegawai->jabatan->kelas_jabatan;
        $id_eselon = @$instansiPegawai->jabatan->id_eselon;

        if($id_eselon == null) {
            $id_eselon = 0;
        }

        //Direktur RSU/RSJ
        if(substr(@$instansiPegawai->nama_jabatan,0,8) == 'Direktur') {
            return $this->getBesaranFungsional2022($params);
        }

        if(substr(@$instansiPegawai->nama_jabatan,0,9) == 'Staf Ahli') {
            $besaran = $this->getBesaranJabatanTunjanganKhusus([
                'tanggal' => $tanggal,
                'kelas_jabatan' => $kelas_jabatan,
                'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::STAF_AHLI_GUBERNUR,
            ]);
            return $besaran;
        }

        if(substr(@$instansiPegawai->nama_jabatan,0,20) == 'Asisten Pemerintahan'
            OR substr(@$instansiPegawai->nama_jabatan,0,20) == 'Asisten Perekonomian'
            OR substr(@$instansiPegawai->nama_jabatan,0,20) == 'Asisten Administrasi'
        ) {
            $besaran = $this->getBesaranJabatanTunjanganKhusus([
                'tanggal' => $tanggal,
                'kelas_jabatan' => $kelas_jabatan,
                'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::ASISTEN_SEKRETARIS_DAERAH,
            ]);
            return $besaran;
        }

        if($instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::UPTD) {
            $id_instansi = $instansiPegawai->instansi->id_induk;
        }

        if($instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::CABANG_DINAS) {
            $id_instansi = $instansiPegawai->instansi->id_induk;
        }

        if($instansiPegawai->instansi->id_instansi_jenis == InstansiJenis::SEKOLAH) {
            $id_instansi = 33;
        }

        if($instansiPegawai->id_instansi == 42 //RSJD
            OR $instansiPegawai->id_instansi == 43 //RSUD
            OR $instansiPegawai->id_instansi == 213 //RSUD Ir. SOEKARNO
        ) {
            $id_instansi = 25;
        }

        $query = JabatanTunjanganStruktural::find();
        $query->orderBy(['tanggal_mulai' => SORT_DESC]);
        $query->andWhere(['id_eselon' => $id_eselon]);
        $query->andWhere(['kelas_jabatan' => $kelas_jabatan]);
        $query->andWhere(['id_instansi' => $id_instansi]);
        $query->andWhere(['id_jabatan_tunjangan_golongan' => $this->getJabatanTunjanganGolongan($bulan, $tahun)]);
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
            ':tanggal' => $tanggal,
        ]);

        $model = $query->one();

        if ($model !== null) {
            return $model->besaran_tpp;
        } else {
            return 0;
        }
    }

    public function getBesaranFungsional2022($params=[])
    {
        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];
        $instansiPegawai = @$params['instansiPegawai'];

        if ($tahun==null) {
            $tahun = User::getTahun();
        }

        if ($bulan == null) {
            $bulan = date('m');
        }

        $datetime = \DateTime::createFromFormat('Y-n-d',$tahun.'-'.$bulan.'-01');

        $tanggal = $datetime->format('Y-m-15');

        if($instansiPegawai === null) {
            $instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);
        }

        $kelas_jabatan = @$instansiPegawai->jabatan->kelas_jabatan;

        // PEGAWAI KANWIL DEPAG
        if (strpos(@$instansiPegawai->nama_jabatan, 'Pegawai Kanwil DEPAG') !== false
            AND substr(@$instansiPegawai->nama_jabatan,0,4) == 'Guru'
        ) {
            $this->_is_guru = true;

            $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::GURU;
            $id_jabatan_tunjangan_golongan = $this->getJabatanTunjanganGolongan($bulan, $tahun);
            $keterangan = 'Pegawai Kanwil DEPAG';

            $isGuruBersertifikasi = $this->isGuruBersertifikasi(['tanggal' => $tanggal]);
            if ($isGuruBersertifikasi == true) {
                $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::GURU_BERSERTIFIKASI;
            }

            if($id_jabatan_tunjangan_golongan == null) {
                $id_jabatan_tunjangan_golongan = 0;
            }

            $besaran = $this->getBesaranJabatanTunjanganKhusus([
                'tanggal' => $tanggal,
                'kelas_jabatan' => $kelas_jabatan,
                'id_jabatan_tunjangan_khusus_jenis' => $id_jabatan_tunjangan_khusus_jenis,
                'id_jabatan_tunjangan_golongan' => $id_jabatan_tunjangan_golongan,
                'keterangan' => $keterangan,
            ]);
            return $besaran;
        }

        if(substr(@$instansiPegawai->nama_jabatan,0,6) == 'Dokter') {
            if(strpos(@$instansiPegawai->nama_jabatan,'Spesialis') !== false
                AND strpos(@$instansiPegawai->nama_jabatan,'Subspesialis') === false
            ) {
                $this->_is_dokter_spesialis = true;
                $besaran = $this->getBesaranJabatanTunjanganKhusus([
                    'tanggal' => $tanggal,
                    'kelas_jabatan' => $kelas_jabatan,
                    'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::DOKTER_SPESIALIS,
                ]);
                return $besaran;
            }

            if(strpos(@$instansiPegawai->nama_jabatan,'Subspesialis') !== false) {
                $this->_is_dokter_subspesialis = true;
                $besaran = $this->getBesaranJabatanTunjanganKhusus([
                    'tanggal' => $tanggal,
                    'kelas_jabatan' => $kelas_jabatan,
                    'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::DOKTER_SUBSPESIALIS,
                ]);
                return $besaran;
            }
        }

        //Guru
        if(substr(@$instansiPegawai->nama_jabatan,0,4) == 'Guru'
            OR substr(@$instansiPegawai->nama_jabatan,0,10) == 'Calon Guru'
            OR substr(@$instansiPegawai->nama_jabatan,0,15) == 'Tenaga Pendidik'
        ) {
            $this->_is_guru = true;

            $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::GURU;
            $id_jabatan_tunjangan_golongan = $this->getJabatanTunjanganGolongan($bulan, $tahun);

            // AWAL KETERANGAN TIDAK SAMA DENGAN `Pegawai Kanwil DEPAG`
            $keterangan_tidak_sama_dengan = true;
            $keterangan = 'Pegawai Kanwil DEPAG';

            if($id_jabatan_tunjangan_golongan == null) {
                $id_jabatan_tunjangan_golongan = 0;
            }

            $isGuruBersertifikasi = $this->isGuruBersertifikasi(['tanggal' => $tanggal]);
            if ($isGuruBersertifikasi == true) {
                $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::GURU_BERSERTIFIKASI;
            }

            if ($instansiPegawai->instansi->id_instansi_lokasi == InstansiLokasi::LEPAR_PONGOK_DAN_SELAT_NASIK) {
                $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::TEMPAT_BERTUGAS_LEPAR_PONGOK;

                if (strpos(@$instansiPegawai->instansi->nama, 'SELAT NASIK') !== false) {
                    $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::TEMPAT_BERTUGAS_SELAT_NASIK;
                }
            }

            if ($instansiPegawai->instansi->id_instansi_lokasi == InstansiLokasi::PULAU_LEPAR) {
                $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::TEMPAT_BERTUGAS_KEPULAUAN_PONGOK;
            }

            // JENIS LEPAR PONGOK, KEPULAUAN PONGOK, SELAT NASIK
            if ($instansiPegawai->instansi->id_instansi_lokasi != InstansiLokasi::UMUM) {
                if (substr(@$instansiPegawai->nama_jabatan,0,4) == 'Guru') {
                    $keterangan_tidak_sama_dengan = false;
                    $keterangan = 'GURU NON SERTIFIKASI';
                    if ($isGuruBersertifikasi == true) {
                        $keterangan = 'GURU BERSERTIFIKASI';
                    }
                }

                if (substr(@$instansiPegawai->nama_jabatan,0,10) == 'Calon Guru') {
                    $keterangan_tidak_sama_dengan = false;
                    $keterangan = 'CALON GURU';
                }

                if (substr(@$instansiPegawai->nama_jabatan,0,15) == 'Tenaga Pendidik') {
                    $keterangan_tidak_sama_dengan = false;
                    $keterangan = 'TENAGA PENDIDIK';
                }
            } else {
                // DIBUAT UNTUK JENIS CALON GURU / TENAGA PENDIDIK JENISNYA MENJADI GURU
                if (substr(@$instansiPegawai->nama_jabatan,0,10) == 'Calon Guru') {
                    $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::GURU;
                    $keterangan_tidak_sama_dengan = false;
                    $keterangan = 'CALON GURU';
                }

                if (substr(@$instansiPegawai->nama_jabatan,0,15) == 'Tenaga Pendidik') {
                    $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::GURU;
                    $keterangan_tidak_sama_dengan = false;
                    $keterangan = 'TENAGA PENDIDIK';
                }
            }

            $besaran = $this->getBesaranJabatanTunjanganKhusus([
                'tanggal' => $tanggal,
                'kelas_jabatan' => $kelas_jabatan,
                'id_jabatan_tunjangan_khusus_jenis' => $id_jabatan_tunjangan_khusus_jenis,
                'id_jabatan_tunjangan_golongan' => $id_jabatan_tunjangan_golongan,
                'keterangan' => $keterangan,
                'keterangan_tidak_sama_dengan' => $keterangan_tidak_sama_dengan,
            ]);
            return $besaran;
        }

        //Kepala Sekolah
        if(substr(@$instansiPegawai->nama_jabatan,0,14) == 'Kepala Sekolah') {
            $this->_is_kepala_sekolah = true;

            $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::KEPALA_SEKOLAH;
            $id_jabatan_tunjangan_golongan = $this->getJabatanTunjanganGolongan($bulan, $tahun);
            $keterangan = null;

            if($id_jabatan_tunjangan_golongan == null) {
                $id_jabatan_tunjangan_golongan = 0;
            }

            if ($instansiPegawai->instansi->id_instansi_lokasi == InstansiLokasi::LEPAR_PONGOK_DAN_SELAT_NASIK) {
                $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::TEMPAT_BERTUGAS_LEPAR_PONGOK;

                if (strpos($instansiPegawai->instansi->nama, 'SELAT NASIK') !== false) {
                    $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::TEMPAT_BERTUGAS_SELAT_NASIK;
                }

                $keterangan = 'KEPALA SEKOLAH';
            }

            if ($instansiPegawai->instansi->id_instansi_lokasi == InstansiLokasi::PULAU_LEPAR) {
                $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::TEMPAT_BERTUGAS_KEPULAUAN_PONGOK;
                $keterangan = 'KEPALA SEKOLAH';
            }

            $besaran = $this->getBesaranJabatanTunjanganKhusus([
                'tanggal' => $tanggal,
                'kelas_jabatan' => $kelas_jabatan,
                'id_jabatan_tunjangan_khusus_jenis' => $id_jabatan_tunjangan_khusus_jenis,
                'id_jabatan_tunjangan_golongan' => $id_jabatan_tunjangan_golongan,
                'keterangan' => $keterangan,
            ]);
            return $besaran;

        }

        //Pengawas Sekolah
        if(substr(@$instansiPegawai->nama_jabatan,0,16) == 'Pengawas Sekolah') {
            $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::PENGAWAS_SEKOLAH;
            $id_jabatan_tunjangan_golongan = $this->getJabatanTunjanganGolongan($bulan, $tahun);
            $keterangan = null;

            if($id_jabatan_tunjangan_golongan == null) {
                $id_jabatan_tunjangan_golongan = 0;
            }

            if ($instansiPegawai->instansi->id_instansi_lokasi == InstansiLokasi::LEPAR_PONGOK_DAN_SELAT_NASIK) {
                $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::TEMPAT_BERTUGAS_LEPAR_PONGOK;

                if (strpos($instansiPegawai->instansi->nama, 'SELAT NASIK') !== false) {
                    $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::TEMPAT_BERTUGAS_SELAT_NASIK;
                }

                $keterangan = 'PENGAWAS SEKOLAH';
            }

            if ($instansiPegawai->instansi->id_instansi_lokasi == InstansiLokasi::PULAU_LEPAR) {
                $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::TEMPAT_BERTUGAS_KEPULAUAN_PONGOK;
                $keterangan = 'PENGAWAS SEKOLAH';
            }

            $besaran = $this->getBesaranJabatanTunjanganKhusus([
                'tanggal' => $tanggal,
                'kelas_jabatan' => $kelas_jabatan,
                'id_jabatan_tunjangan_khusus_jenis' => $id_jabatan_tunjangan_khusus_jenis,
                'id_jabatan_tunjangan_golongan' => $id_jabatan_tunjangan_golongan,
                'keterangan' => $keterangan,
            ]);
            return $besaran;
        }

        //Direktur RSU/RSJ
        if(substr(@$instansiPegawai->nama_jabatan,0,8) == 'Direktur') {

            $this->_is_direktur_utama = true;

            $id_jabatan_tunjangan_khusus_jenis = null;

            if ($instansiPegawai->id_instansi == 42) { // RUMAH SAKIT JIWA
                $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::DIREKTUR_RSJ_NON_SPESIALIS;

                $isDokterSpesialisasi = $this->isDokterSpesialisasi([
                    'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::DIREKTUR_RSJ_SPESIALIS,
                    'bulan' => $bulan,
                    'tahun' => $tahun
                ]);

                if ($isDokterSpesialisasi == true) {
                    $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::DIREKTUR_RSJ_SPESIALIS;
                }
            }

            if ($instansiPegawai->id_instansi == 213) { // RUMAH SAKIT UMUM Ir. SOEKARNO
                $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::DIREKTUR_RSP_IR_SOEKARNO_NON_SPESIALIS;

                $isDokterSpesialisasi = $this->isDokterSpesialisasi([
                    'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::DIREKTUR_RSP_IR_SOEKARNO_SPESIALIS,
                    'bulan' => $bulan,
                    'tahun' => $tahun
                ]);

                if ($isDokterSpesialisasi == true) {
                    $id_jabatan_tunjangan_khusus_jenis = JabatanTunjanganKhususJenis::DIREKTUR_RSP_IR_SOEKARNO_SPESIALIS;
                }
            }

            $besaran = $this->getBesaranJabatanTunjanganKhusus([
                'tanggal' => $tanggal,
                'kelas_jabatan' => $kelas_jabatan,
                'id_jabatan_tunjangan_khusus_jenis' => $id_jabatan_tunjangan_khusus_jenis,
            ]);
            return $besaran;
        }

        $id_instansi = $instansiPegawai->id_instansi;
        if($instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::SEKOLAH) {
            $id_instansi = 33;
        }

        if($instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::UPTD) {
            $id_instansi = $instansiPegawai->instansi->id_induk;
        }

        if($instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::CABANG_DINAS) {
            $id_instansi = $instansiPegawai->instansi->id_induk;
        }

        if($instansiPegawai->id_instansi == 42 //RSJD
            OR $instansiPegawai->id_instansi == 43 //RSUD
            OR $instansiPegawai->id_instansi == 213 //RSUD Ir. SOEKARNO
        ) {
            $id_instansi = 25;
        }

        $query = JabatanTunjanganFungsional::find();
        $query->orderBy(['tanggal_mulai' => SORT_DESC]);
        $query->andWhere(['id_instansi' => $id_instansi]);
        $query->andWhere(['kelas_jabatan' => $kelas_jabatan]);
        $query->andWhere(['id_tingkatan_fungsional' => @$instansiPegawai->jabatan->id_tingkatan_fungsional]);
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
            ':tanggal' => $tanggal,
        ]);

        if ($tanggal <= '2022-12-31') {
            $query->andWhere(['status_p3k' => 0]);
        }

        if ($tanggal >= '2023-01-01') {
            $query->andWhere(['status_p3k' => $this->id_pegawai_jenis == PegawaiJenis::P3K]);
        }

        $model = $query->one();

        if ($model !== null) {
            return $model->besaran_tpp;
        } else {
            return 0;
        }
    }

    public function getBesaranPelaksana2022($params=[])
    {
        $instansiPegawai = @$params['instansiPegawai'];
        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        if ($tahun==null) {
            $tahun = User::getTahun();
        }

        if ($bulan == null) {
            $bulan = date('m');
        }

        $datetime = \DateTime::createFromFormat('Y-n-d',$tahun.'-'.$bulan.'-01');
        $tanggal = $datetime->format('Y-m-15');

        if ($instansiPegawai == null) {
            $instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);
        }

        $id_instansi = @$instansiPegawai->id_instansi;
        $kelas_jabatan = @$instansiPegawai->jabatan->kelas_jabatan;

        if (@$params['kelas_jabatan'] !== null) {
            $kelas_jabatan = @$params['kelas_jabatan'];
        }

        //Calon Guru
        if(substr(@$instansiPegawai->nama_jabatan,0,10) == 'Calon Guru') {
            return $this->getBesaranFungsional2022($params);
        }

        //Tenaga Pendidik
        if(substr(@$instansiPegawai->nama_jabatan,0,15) == 'Tenaga Pendidik') {
            return $this->getBesaranFungsional2022($params);
        }

        //Pengemudi
        if(substr(@$instansiPegawai->nama_jabatan,0,9) == 'Pengemudi' AND $this->_status_tubel != true) {
            $this->_is_pengemudi = true;
            $besaran = $this->getBesaranJabatanTunjanganKhusus([
                'tanggal' => $tanggal,
                'kelas_jabatan' => $kelas_jabatan,
                'id_jabatan_tunjangan_khusus_jenis' => JabatanTunjanganKhususJenis::SUPIR,
            ]);
            return $besaran;
        }

        if($instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::SEKOLAH) {
            $id_instansi = 33;
        }

        if($instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::CABANG_DINAS) {
            $id_instansi = $instansiPegawai->instansi->id_induk;
        }

        if($instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::UPTD) {
            $id_instansi = $instansiPegawai->instansi->id_induk;
        }

        if($instansiPegawai->id_instansi == 42 //RSJD
            OR $instansiPegawai->id_instansi == 43 //RSUD
            OR $instansiPegawai->id_instansi == 213 //RSUD Ir. SOEKARNO
        ) {
            $id_instansi = 25;
        }

        $query = JabatanTunjanganPelaksana::find();
        $query->orderBy(['tanggal_mulai' => SORT_DESC]);
        $query->andWhere(['kelas_jabatan' => $kelas_jabatan]);
        $query->andWhere(['id_instansi' => $id_instansi]);
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
            ':tanggal' => $tanggal,
        ]);

        $model = $query->one();

        if ($model !== null) {
            return $model->besaran_tpp;
        } else {
            return 0;
        }
    }

    public function getNilaiBesaranTpp($bulan, $params=[])
    {
        $tahun = @$params['tahun'];
        @$params['bulan'] = $bulan;

        if($tahun == null) {
            $tahun = Session::getTahun();
            @$params['tahun'] = $tahun;
        }

        $datetime = \DateTime::createFromFormat('Y-n-d', $tahun.'-'.$bulan.'-01');
        $tanggal = $datetime->format('Y-m-15');

        if($datetime->format('Y-m') >= '2021-01') {
            $tanggal = $datetime->format('Y-m-02');
        }

        $instansiPegawai = @$params['instansiPegawai'];

        if ($instansiPegawai === null) {
            $instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);
            @$params['instansiPegawai'] = $instansiPegawai;
        }

        if($tanggal >= '2022-01-01') {
            if (@$instansiPegawai->jabatan->id_jenis_jabatan == Jabatan::STRUKTURAL) {
                return $this->getBesaranStruktural2022($params);
            }

            if (@$instansiPegawai->jabatan->id_jenis_jabatan == Jabatan::FUNGSIONAL) {
                return $this->getBesaranFungsional2022($params);
            }

            if (@$instansiPegawai->jabatan->id_jenis_jabatan == Jabatan::PELAKSANA) {
                return $this->getBesaranPelaksana2022($params);
            }
        }

        if($tanggal >= '2021-01-01') {
            return $this->getBesaranPergub2021($params);
        }

        if($this->_status_tubel == true) {
            return $this->getBesaranPelaksana($bulan);
        }

        if (@$instansiPegawai->jabatan->id_jenis_jabatan == Jabatan::STRUKTURAL) {
            return $this->getBesaranStruktural($bulan,$params);
        }

        if (@$instansiPegawai->jabatan->id_jenis_jabatan == Jabatan::FUNGSIONAL) {
            return $this->getBesaranFungsional($bulan);
        }

        if (@$instansiPegawai->jabatan->id_jenis_jabatan == Jabatan::PELAKSANA) {
            return $this->getBesaranPelaksana($bulan);
        }

        return 0;
    }

    public $_tpp_awal;
    public $_has_jabatan_plt;
    public $_status_tubel;

    /**
     * @param $bulan
     * @param array $params
     * @return float|int
     */
    public function getTppAwal($bulan, $params=[])
    {
        if ($this->_tpp_awal !== null) {
            return $this->_tpp_awal;
        }

        if(Config::PERHITUNGAN_PERGUB_2022 OR @$params['v3'] !== null) {
            @$params['bulan'] = $bulan;
            $this->_tpp_awal = $this->getTppAwalV3($params);
            $this->_tpp_awal_plt = $this->_tpp_awal_plt_v3;
            return $this->_tpp_awal;
        }

        $faktor_kali = 1;

        $tahun = @$params['tahun'];

        if($tahun == null) {
            $tahun = Session::getTahun();
        }

        $datetime = \DateTime::createFromFormat('Y-n-d', $tahun.'-'.$bulan.'-01');
        $tanggal = $datetime->format('Y-m-15');

        if($datetime->format('Y-m') >= '2021-01') {
            $tanggal = $datetime->format('Y-m-02');
        }

        $queryTubel = KetidakhadiranPanjang::find();
        $queryTubel->andWhere(['id_pegawai' => $this->id]);
        $queryTubel->andWhere([
            'id_ketidakhadiran_panjang_jenis' => 5
        ]);
        $queryTubel->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
            ':tanggal' => $tanggal
        ]);
        $tubel = $queryTubel->one();

        if($tubel !== null AND $datetime->format('Y-m') <= '2021-12') {
            $this->_status_tubel = true;
            $faktor_kali = 0.7;
        }

        $queryCpns = TunjanganPotonganPegawai::find();
        $queryCpns->andWhere(['id_pegawai' => $this->id]);
        $queryCpns->andWhere([
            'id_tunjangan_potongan' => TunjanganPotongan::CPNS
        ]);
        $queryCpns->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
            ':tanggal' => $tanggal
        ]);
        $cpns = $queryCpns->one();

        if($cpns !== null) {
            $faktor_kali = (1 - @$cpns->getTunjanganPotonganNilai()->one()->nilai / 100);
        }

        $instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);
        $instansiPegawaiPlt = $this->getInstansiPegawaiBerlakuPlt($tanggal);

        $tpp_awal = $this->getNilaiBesaranTpp($bulan, ['instansiPegawai' => $instansiPegawai]);

        if($instansiPegawaiPlt === null) {
            $this->_tpp_awal = $tpp_awal * $faktor_kali;
            return $this->_tpp_awal;
        }

        $tpp_awal_plt = $this->getNilaiBesaranTpp($bulan, ['instansiPegawai' => $instansiPegawaiPlt]);

        if($datetime->format('Y-m') >= '2022-01') {
            if($instansiPegawai->jabatan->kelas_jabatan < $instansiPegawaiPlt->jabatan->kelas_jabatan) {
                $this->_tpp_awal = $tpp_awal_plt * $faktor_kali;;
                $this->_tpp_awal_plt = 0;
                return $this->_tpp_awal;
            }

            $this->_tpp_awal = $tpp_awal * $faktor_kali;
            $this->_tpp_awal_plt = $tpp_awal_plt;

            if($tpp_awal_plt > $tpp_awal) {
                $this->_tpp_awal = $tpp_awal_plt * $faktor_kali;
                $this->_tpp_awal_plt = 0;
            }

            return $this->_tpp_awal;
        }

        if($instansiPegawai->jabatan->id_jenis_jabatan == \app\models\Jabatan::FUNGSIONAL) {
            if($tpp_awal_plt > $tpp_awal) {
                $tpp_awal = $tpp_awal_plt;
            }
            $this->_tpp_awal = $tpp_awal * $faktor_kali;
            $this->_tpp_awal_plt = 0;
            return $this->_tpp_awal;
        }

        if(Session::getTahun() <= 2020) {
            if($instansiPegawaiPlt->jabatan->id_eselon == $instansiPegawai->jabatan->id_eselon) {
                $this->_tpp_awal = $tpp_awal * $faktor_kali;
                $this->_tpp_awal_plt = $tpp_awal_plt;

                return $this->_tpp_awal;
            }

            if($instansiPegawaiPlt->jabatan->id_eselon < $instansiPegawai->jabatan->id_eselon) {
                $this->_tpp_awal = $tpp_awal_plt * $faktor_kali;;
                return $this->_tpp_awal;
            }

            if($instansiPegawaiPlt->jabatan->id_eselon > $instansiPegawai->jabatan->id_eselon) {
                $this->_tpp_awal_plt = $tpp_awal_plt;
                $this->_tpp_awal = $tpp_awal * $faktor_kali;;
                return $this->_tpp_awal;
            }
        }

        if($instansiPegawaiPlt->jabatan->id_eselon < $instansiPegawai->jabatan->id_eselon) {
            $this->_tpp_awal = $tpp_awal_plt * $faktor_kali;
            $this->_tpp_awal_plt = 0;
            return $this->_tpp_awal;
        }

        $this->_tpp_awal = $tpp_awal * $faktor_kali;
        $this->_tpp_awal_plt = $tpp_awal_plt;

        if($tpp_awal_plt > $tpp_awal) {
            $this->_tpp_awal = $tpp_awal_plt * $faktor_kali;
            $this->_tpp_awal_plt = $tpp_awal;
        }

        return $this->_tpp_awal;

    }

    public function getTppAwalV3(array $params)
    {
        if($this->_tpp_awal_v3 !== null) {
            return $this->_tpp_awal_v3;
        }

        $tpp_awal = 0;
        $tpp_awal += $this->getTppAwalDasar($params);
        $tpp_awal += $this->getRupiahTppTambahanPenghargaan($params);
        $tpp_awal += $this->getRupiahTppTambahanPemutakhiranSimadig($params);
        $tpp_awal += $this->getRupiahTppTambahanPengisianRenbangkom($params);
        $tpp_awal += $this->getRupiahTppTambahanPemenuhanBangkom20Jp();

        if ($this->hasPenundaanTpp($params) AND @$params['penundaan'] !== false) {
            $tpp_awal = 0;
        }

        if ($this->_status_tubel === true AND $this->getFaktorKaliTubel($params) == 0) {
            $tpp_awal = 0;
        }

        $this->_tpp_awal_v3 = $tpp_awal;
        $this->_tpp_awal_plt_v3 = $this->_tpp_awal_dasar_plt;

        return $this->_tpp_awal_v3;
    }

    public $_tpp_awal_dasar;
    public $_tpp_awal_dasar_plt;
    public function getTppAwalDasar(array $params)
    {
        if ($this->_tpp_awal_dasar !== null) {
            return $this->_tpp_awal_dasar;
        }

        $faktor_kali = 1;

        $params = $this->updateParams($params);
        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        $datetime = \DateTime::createFromFormat('Y-n-d', $tahun.'-'.$bulan.'-01');
        $tanggal = $datetime->format('Y-m-15');

        $pegawaiTukin = $this->getPegawaiTukin($params);

        /* Pegawai Tubel */
        $pegawaiTugasBelajar = $this->getPegawaiTugasBelajar([
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        if($pegawaiTugasBelajar !== null AND @$params['nonTpp'] !== true) {
            $this->_status_tubel = true;
            $faktor_kali = $this->getFaktorKaliTubel([
                'bulan' => $bulan,
                'tahun' => $tahun,
            ]);

            $this->_keterangan_lengkap_tpp_awal_v3 = "Tugas Belajar {$pegawaiTugasBelajar->getLabelSemester()}";
            $this->_keterangan_singkat_tpp_awal_v3 = "Tubel";
        }

        /* Penundaan Cuti Ibadah */
        $queryCuti = PegawaiCutiIbadah::find();
        $queryCuti->andWhere(['id_pegawai' => $this->id]);
        $queryCuti->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal', [
            ':tanggal' => $datetime->format('Y-m-15'),
        ]);

        $pegawaiCutiIbadah = $queryCuti->one();

        if ($pegawaiCutiIbadah !== null AND @$params['nonTpp'] !== true) {
            $faktor_kali = 0.8;
            $this->_keterangan_lengkap_tpp_awal_v3 = 'Cuti Ibadah';
            $this->_keterangan_singkat_tpp_awal_v3 = 'Cuti';
        }

        /* Penundaan TPP */
        $queryTunda = PegawaiTundaBayar::find();
        $queryTunda->andWhere(['id_pegawai' => $this->id]);
        $queryTunda->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal', [
            ':tanggal' => $datetime->format('Y-m-15'),
        ]);

        /* @var $pegawaiTundaBayar PegawaiTundaBayar */
        $pegawaiTundaBayar = $queryTunda->one();

        if ($pegawaiTundaBayar !== null AND @$params['penundaan'] !== false) {
            $faktor_kali = 0;
            $this->_keterangan_lengkap_tpp_awal_v3 = @$pegawaiTundaBayar->pegawaiTundaBayarJenis->nama;
            $this->_keterangan_singkat_tpp_awal_v3 = 'Penundaan';
        }

        $queryCpns = TunjanganPotonganPegawai::find();
        $queryCpns->andWhere(['id_pegawai' => $this->id]);
        $queryCpns->andWhere([
            'id_tunjangan_potongan' => [
                TunjanganPotongan::CPNS,
                TunjanganPotongan::CUTI_BESAR_ALASAN_KEAGAMAAN,
            ]
        ]);
        $queryCpns->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
            ':tanggal' => $tanggal
        ]);
        $cpns = $queryCpns->one();

        if ($cpns !== null) {
            $faktor_kali = (1 - @$cpns->getTunjanganPotonganNilai()->one()->nilai / 100);
        }

        if ($pegawaiTukin->isPegawaiCutiBesarNonTpp()) {
            $faktor_kali = 0;

            $this->_keterangan_lengkap_tpp_awal_v3 = "Cuti Besar Non TPP";
            $this->_keterangan_singkat_tpp_awal_v3 = "Cuti Besar Non TPP";
        }

        if ($pegawaiTukin->isPegawaiTugasBelajarNonTpp()) {
            $faktor_kali = 0;

            $this->_keterangan_lengkap_tpp_awal_v3 = "Tugas Belajar Non TPP";
            $this->_keterangan_singkat_tpp_awal_v3 = "Tugas Belajar Non TPP";
        }

        /*
        $instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);
        $instansiPegawaiPlt = $this->getInstansiPegawaiBerlakuPlt($tanggal);
        */
        
        $instansiPegawai = $pegawaiTukin->getInstansiPegawai();
        $instansiPegawaiPlt = $pegawaiTukin->getInstansiPegawaiPlt();

        if (@$params['nonTpp'] === true
            AND $instansiPegawai !== null 
            AND strtoupper($instansiPegawai->getNamaJabatan()) == 'CUTI BESAR (NON TPP)'
        ) {
            $instansiPegawaiPrev = $this->getInstansiPegawaiPrev($instansiPegawai);

            if ($instansiPegawaiPrev != null) {
                $instansiPegawai = $instansiPegawaiPrev;
            }
        }

        $tpp_awal_dasar = $this->getNilaiBesaranTpp($bulan, ['instansiPegawai' => $instansiPegawai]);

        if ($instansiPegawaiPlt === null) {
            $tpp_awal_dasar = $tpp_awal_dasar * $faktor_kali;
            $this->_tpp_awal_dasar = $tpp_awal_dasar;
            return $this->_tpp_awal_dasar;
        }

        $tpp_awal_dasar_plt = $this->getNilaiBesaranTpp($bulan, ['instansiPegawai' => $instansiPegawaiPlt]);

        // setingan khusus
        if ($this->nip == '196312231986031006' AND $instansiPegawaiPlt->id == 17427
            OR $this->nip == '197211052002121003' AND $instansiPegawaiPlt->id == 17614
            OR $this->nip == '196705051992031009' AND $instansiPegawaiPlt->id == 18744
        ) {
            $this->_tpp_awal_dasar = $tpp_awal_dasar;
            $this->_tpp_awal_dasar_plt = $tpp_awal_dasar_plt;
            return $this->_tpp_awal_dasar;
        }

        if($instansiPegawai->jabatan->kelas_jabatan < $instansiPegawaiPlt->jabatan->kelas_jabatan) {
            $this->_tpp_awal_dasar = $tpp_awal_dasar_plt * $faktor_kali;
            $this->_tpp_awal_dasar_plt = 0;
            return $this->_tpp_awal_dasar;
        }

        $this->_tpp_awal_dasar = $tpp_awal_dasar * $faktor_kali;
        $this->_tpp_awal_dasar_plt = $tpp_awal_dasar_plt;

        if($tpp_awal_dasar_plt > $tpp_awal_dasar) {
            $this->_tpp_awal_dasar = $tpp_awal_dasar_plt * $faktor_kali;
            $this->_tpp_awal_dasar_plt = 0;
        }

        return $this->_tpp_awal_dasar;
    }

    public $_tpp_awal_plt;
    public function getTppAwalPlt($bulan)
    {
        if ($this->_tpp_awal_plt != null) {
            return $this->_tpp_awal_plt;
        }

        $this->getTppAwal($bulan);

        return $this->_tpp_awal_plt;
    }

    public $_tpp_70;
    public function getTpp70($bulan)
    {
        if ($this->_tpp_70 == null) {
            $this->_tpp_70 = $this->getTppAwal($bulan) * 0.7;
        }
        return $this->_tpp_70;
    }

    public $_tpp_30;
    public function getTpp30($bulan)
    {
        if ($this->_tpp_30 == null) {
            $this->_tpp_30 = $this->getTppAwal($bulan) * 0.3;
        }
        return $this->_tpp_30;
    }

    public function getQueryKegiatanBulanan($bulan, $tahun=null)
    {
        if($tahun==null) {
            $tahun = User::getTahun();
        }

        $query = KegiatanBulanan::query();
        $query->joinWith(['kegiatanTahunan', 'instansiPegawai', 'kegiatanTahunan.instansiPegawaiSkp']);
        $query->andWhere([
            'kegiatan_tahunan.id_pegawai' => $this->id,
            'kegiatan_tahunan.id_kegiatan_status' => 1,
            'kegiatan_tahunan.tahun' => $tahun,
            'kegiatan_tahunan.status_hapus' => 0,
            'bulan' => $bulan,
        ]);

        $datetimeSession = \DateTime::createFromFormat('Y-n-d',Session::getTahun().'-'.$bulan.'-01');

        $tahunBulan = $datetimeSession->format('Y-m');

        if ($tahunBulan <= '2021-06') {
            $query->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_versi' => 1]);
        }

        if ($tahunBulan >= '2021-07' AND $tahunBulan <= '2022-12') {
            $query->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_versi' => 2]);
        }

        if ($tahunBulan == '2023-01') {
            $query->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_versi' => [2,3]]);
        }

        if ($tahunBulan >= '2023-02') {
            $query->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_versi' => 3]);
        }

        $query->andWhere('kegiatan_bulanan.target IS NOT NULL');

        return $query;
    }

    public $_persen_potongan_skp_bulanan;
    public function getPersenPotonganSkpBulanan($bulan, $tahun=null, $params=[])
    {
        if($tahun==null) {
            $tahun = Session::getTahun();
        }

        if ($this->_persen_potongan_skp_bulanan != null) {
            return $this->_persen_potongan_skp_bulanan;
        }

        if($this->getStatusTubel($tahun, $bulan) == true) {
            $this->_persen_potongan_skp_bulanan = 0;
            return $this->_persen_potongan_skp_bulanan;
        }

        if($this->getIsGuru($tahun, $bulan) == true) {
            $this->_persen_potongan_skp_bulanan = 0;
            return $this->_persen_potongan_skp_bulanan;
        }

        if($this->_is_direktur_utama == true) {
            $this->_persen_potongan_skp_bulanan = 0;
            return $this->_persen_potongan_skp_bulanan;
        }

        if($this->_is_kepala_sekolah == true) {
            $this->_persen_potongan_skp_bulanan = 0;
            return $this->_persen_potongan_skp_bulanan;
        }

        if($this->getIsDokterSpesialis(['bulan'=>$bulan,'tahun'=>$tahun]) == true) {
            $this->_persen_potongan_skp_bulanan = 0;
            return $this->_persen_potongan_skp_bulanan;
        }

        if($this->getIsDokterSubspesialis(['bulan'=>$bulan,'tahun'=>$tahun]) == true) {
            $this->_persen_potongan_skp_bulanan = 0;
            return $this->_persen_potongan_skp_bulanan;
        }

        $bulanOk = $bulan;
        if($bulanOk<10 AND strlen(strval($bulanOk))==1) {
            $bulanOk = '0'.$bulan;
        }

        if($this->getIsPegawaiDispensasiCkhp($tahun.'-'.$bulanOk.'-15')==true) {
            $this->_persen_potongan_skp_bulanan = 0;
            return $this->_persen_potongan_skp_bulanan;
        }

        $query = $this->getQueryKegiatanBulanan($bulan, $tahun);
        $query->andWhere(['kegiatan_tahunan.status_plt'=>'0']);

        $realisasi = $query->average('persen_realisasi');

        $potongan = 100 - $realisasi;

        if (@$params['potonganCkhp'] === null) {
            @$params['potonganCkhp'] = true;
        }

        if (Config::PERHITUNGAN_PERGUB_2022 AND @$params['potonganCkhp'] === true
            OR @$params['v3'] === true AND @$params['potonganCkhp'] === true
        ) {
            $kinerjaBulan = new KinerjaBulan([
                'tahun' => $tahun,
                'bulan' => $bulan,
                'pegawai' => $this
            ]);
            $kinerjaBulan->execute();

            $potongan += $kinerjaBulan->getTotalPersenPotongan();
            /*$potongan += $this->getPotonganKegiatanHarian([
                'tahun' => $tahun,
                'bulan' => $bulan,
            ]);*/
        }

        if ($potongan > 100) {
            $potongan = 100;
        }

        $this->_persen_potongan_skp_bulanan = $potongan;

        return $this->_persen_potongan_skp_bulanan;
    }

    /**
     * $params['bulan']
     * $params['tahun']
     *
     * @param array $params
     * @return float|int
     */
    public function getPotonganKegiatanHarian(array $params=[])
    {
        return $this->getTotalPotonganCkhp([
            'bulan' => $params['bulan'],
            'tahun' => $params['tahun'],
        ]);
    }

    public function getPersenRealisasiKegiatanBulanan($bulan, $tahun=null)
    {
        if($tahun==null) {
            $tahun = Session::getTahun();
        }

        if($this->_status_tubel == true) {
            return 100;
        }

        if($this->getIsGuru($tahun, $bulan) == true) {
            return 100;
        }

        if($this->_is_kepala_sekolah == true) {
            return 100;
        }

        if($this->_is_direktur_utama == true) {
            return 100;
        }

        if($this->getIsDokterSpesialis(['bulan'=>$bulan,'tahun'=>$tahun]) == true) {
            return 100;
        }

        if($this->getIsDokterSubspesialis(['bulan'=>$bulan,'tahun'=>$tahun]) == true) {
            return 100;
        }

        $bulanOk = $bulan;
        if($bulanOk<10 AND strlen(strval($bulanOk))==1) {
            $bulanOk = '0'.$bulan;
        }

        if($this->getIsPegawaiDispensasiCkhp($tahun.'-'.$bulanOk.'-15')==true) {
            return 100;
        }

        $query = $this->getQueryKegiatanBulanan($bulan, $tahun);

        $realisasi = $query->average('persen_realisasi');

        return $realisasi;
    }

    public $_rupiah_potongan_produktivitas;

    public function getRupiahPotonganProduktivitas($bulan, $params=[])
    {
        if ($this->_rupiah_potongan_produktivitas == null) {
            $this->_rupiah_potongan_produktivitas = $this->getTpp70($bulan) * ($this->getPersenPotonganSkpBulanan($bulan)/100);
        }

        return $this->_rupiah_potongan_produktivitas;
    }

    public $_persen_potongan_presensi_tunjangan;

    public function getPersenPotonganPresensiTunjangan($bulan,$params=[])
    {
        if ($this->_persen_potongan_presensi_tunjangan != null) {
            return $this->_persen_potongan_presensi_tunjangan;
        }

        $this->_persen_potongan_presensi_tunjangan = $this->getPersenPotonganPresensi($bulan, $params);

        return $this->_persen_potongan_presensi_tunjangan;
    }

    public $_potongan_disiplin_tukin;

    public function getRupiahPotonganDisiplinKerja($bulan,$params=[])
    {
        if ($this->_potongan_disiplin_tukin != null) {
            return $this->_potongan_disiplin_tukin;
        }

        $this->_potongan_disiplin_tukin = $this->getTpp30($bulan) * ($this->getPersenPotonganPresensiTunjangan($bulan,$params)/100);

        return $this->_potongan_disiplin_tukin;
    }

    public $_potongan_faktor_kinerja;
    public function getRupiahPotonganFaktorKinerja($bulan)
    {
        if ($this->_potongan_faktor_kinerja == null) {
            $this->_potongan_faktor_kinerja = 0;
            $this->_potongan_faktor_kinerja += $this->getRupiahPotonganProduktivitas($bulan);
            $this->_potongan_faktor_kinerja += $this->getRupiahPotonganDisiplinKerja($bulan);
        }

        return $this->_potongan_faktor_kinerja;
    }

    public $_rupiah_potongan_disiplin;
    public function getRupiahPotonganHukumanDisiplinTotal($bulan, $params=[])
    {
        if ($this->_rupiah_potongan_disiplin == null) {
            $this->_rupiah_potongan_disiplin = $this->getTppAwal($bulan) * ($this->getPersenPotonganHukumanDisiplinTotal($bulan)/100);
        }

        return $this->_rupiah_potongan_disiplin;
    }

    public $_total_potongan_disiplin;
    public function getPersenPotonganHukumanDisiplinTotal($bulan)
    {
        if ($this->_total_potongan_disiplin == null) {
            $this->_total_potongan_disiplin =
                $this->getPersenPotonganHukumanDisiplinRingan($bulan) +
                $this->getPersenPotonganHukumanDisiplinSedang($bulan) +
                $this->getPersenPotonganHukumanDisiplinBerat($bulan) +
                $this->getHukumanLHKPN($bulan) +
                $this->getHukumanTPTGR($bulan);
        }

        return $this->_total_potongan_disiplin;
    }

    public $_hukuman_lhkpn;
    public function getHukumanLHKPN($bulan)
    {
        if ($this->_hukuman_lhkpn != null) {
            return $this->_hukuman_lhkpn;
        }

        $this->_hukuman_lhkpn = $this->getPersenTunjanganPotongan(TunjanganPotongan::LHKPN,$bulan);

        return $this->_hukuman_lhkpn;
    }

    public function getModelPersenPotonganTunjangan($id_tunjangan_potongan, $bulan)
    {
        $tahun = User::getTahun();
        $tanggal_mulai = $tahun.'-'.$bulan.'-'.date('d');
        $tanggal_selesai = $tahun.'-'.$bulan.'-31';


        return $this->getTunjanganPotonganPegawai()
            ->joinWith('tunjanganPotonganNilai')
            ->andWhere(['tunjangan_potongan_pegawai.id_tunjangan_potongan' => $id_tunjangan_potongan])
            ->andWHere('tunjangan_potongan_nilai.tanggal_mulai <= :mulai AND tunjangan_potongan_nilai.tanggal_selesai >= :selesai',[
                ':mulai' => $tanggal_mulai,
                ':selesai' => $tanggal_selesai,
            ])
            ->one();
    }

    public function getPersenTunjanganPotongan($id_tunjangan_potongan, $bulan, $tahun=null)
    {
        if($tahun == null) {
            $tahun = Session::getTahun();
        }

        $datetime = \DateTime::createFromFormat('Y-n-d', $tahun.'-'.$bulan.'-01');

        $query = TunjanganPotonganPegawai::find();
        $query->with(['tunjanganPotongan']);

        $query->andWhere([
            'id_pegawai' => $this->id,
            'id_tunjangan_potongan' => $id_tunjangan_potongan
        ]);

        $query->andWhere('tanggal_mulai >= :tanggal_awal AND tanggal_mulai <= :tanggal_akhir
            OR tanggal_selesai >= :tanggal_awal AND tanggal_selesai <= :tanggal_akhir
            OR tanggal_mulai <= :tanggal_awal AND tanggal_selesai >= :tanggal_akhir
        ',[
            ':tanggal_awal' => $datetime->format('Y-m-01'),
            ':tanggal_akhir' => $datetime->format('Y-m-t')
        ]);

        $persenPotongan = 0;

        foreach($query->all() as $data) {
            $persenPotongan += @$data->tunjanganPotongan->potongan;
        }

        return $persenPotongan;

    }

    public $_hukuman_tptgr;
    public function getHukumanTPTGR($bulan)
    {
        if ($this->_hukuman_tptgr == null) {
            $this->_hukuman_tptgr = $this->getPersenTunjanganPotongan(TunjanganPotongan::LHKPN,$bulan);
        }
        return $this->_hukuman_tptgr;
    }


    public $_persen_potongan_dupak;
    public function getPersenPotonganDupak($bulan)
    {
        if ($this->_persen_potongan_dupak == null) {
            $this->_persen_potongan_dupak = $this->getPersenTunjanganPotongan(TunjanganPotongan::TIDAK_ADA_DUPAK,$bulan);
        }
        return $this->_persen_potongan_dupak;
    }

    public $_persen_jf_belum_diangkat;
    public function getPersenPotonganJfBelumDiangkat($bulan)
    {
        if ($this->_persen_jf_belum_diangkat == null) {
            $this->_persen_jf_belum_diangkat = $this->getPersenTunjanganPotongan(TunjanganPotongan::BELUM_DIANGKAT_JF_SELAMA_7_TAHUN,$bulan);
        }
        return $this->_persen_jf_belum_diangkat;
    }

    public $_total_potongan_ketentuan_jf;
    public function getPersenPotonganPelanggaranKetentuanJf($bulan)
    {
        if ($this->_total_potongan_ketentuan_jf == null) {
            $potongan = 0;
            $potongan += $this->getPersenPotonganDupak($bulan);
            $potongan += $this->getPersenPotonganJfBelumDiangkat($bulan);
            $this->_total_potongan_ketentuan_jf = $potongan;
        }

        return $this->_total_potongan_ketentuan_jf;

    }

    public $_rupiah_potongan_ketentuan_jf;
    public function getRupiahPotonganPelanggaranKetentuanJf($bulan, $params=[])
    {
        if ($this->_rupiah_potongan_ketentuan_jf == null) {
            $this->_rupiah_potongan_ketentuan_jf = $this->getTppAwal($bulan) * ($this->getPersenPotonganPelanggaranKetentuanJf($bulan)/100);
        }
        return $this->_rupiah_potongan_ketentuan_jf;
    }

    public $_total_potongan_keseluruhan;

    public function getRupiahPotonganKeseluruhan($bulan, $params=[])
    {
        if($this->_total_potongan_keseluruhan != null) {
            return $this->_total_potongan_keseluruhan;
        }

        /* if($this->id == 545) {
            $this->_total_potongan_keseluruhan = 10788283;
            return $this->_total_potongan_keseluruhan;
        } */

        $this->_total_potongan_keseluruhan = 0;

        //Potongan SKP
        $this->_total_potongan_keseluruhan += $this->getRupiahPotonganProduktivitas($bulan, $params);

        //Potongan Presensi
        $this->_total_potongan_keseluruhan += $this->getRupiahPotonganDisiplinKerja($bulan, $params);

        //Potongan Hukuman Disiplin
        $this->_total_potongan_keseluruhan += $this->getRupiahPotonganHukumanDisiplinTotal($bulan, $params);

        //Potongan Faktor JF
        $this->_total_potongan_keseluruhan += $this->getRupiahPotonganPelanggaranKetentuanJf($bulan, $params);

        //Potongan Faktor IP ASN
        $this->_total_potongan_keseluruhan += $this->getRupiahPotonganIndeksProfAsn($bulan, $params);

        return $this->_total_potongan_keseluruhan;
    }

    public $_total_tpp_sebelum_pajak;
    public function getRupiahTPPSebelumPajak($bulan, $params=[])
    {
        if ($this->_total_tpp_sebelum_pajak != null) {
            return $this->_total_tpp_sebelum_pajak;
        }

        $this->_total_tpp_sebelum_pajak = 0;
        $this->_total_tpp_sebelum_pajak += $this->getTppAwal($bulan, $params);
        $this->_total_tpp_sebelum_pajak -= $this->getRupiahPotonganKeseluruhan($bulan, $params);

        if($this->_total_tpp_sebelum_pajak < 0) {
            $this->_total_tpp_sebelum_pajak = 0;
        }

        return $this->_total_tpp_sebelum_pajak;
    }

    /* --- PEMBAYARAN TPP --- */

    public function getVolumePerBulan()
    {
        return 1;
    }

    public function getJumlahKotorTPP($bulan)
    {
        $total = $this->getRupiahTPPSebelumPajak($bulan) + $this->getRupiahTunjanganPlt($bulan);
        $total = $total * $this->getVolumePerBulan();
        return $total;
    }

    public function getPotonganPajakByGolonganIX($params = [])
    {
        $pegawaiGolongan = $this->getPegawaiGolonganBerlaku($params);

        if ($pegawaiGolongan !== null AND $pegawaiGolongan->id_golongan == 19) {
            return 5;
        }

        return 0;
    }

    public function getPotonganPajakByGolonganIV($params = [])
    {
        $pegawaiGolongan = $this->getPegawaiGolonganBerlaku($params);

        $arrayGolonganIV = [
            13, 14, 15, 16, 17
        ];

        if ($pegawaiGolongan !== null) {
            if (in_array($pegawaiGolongan->id_golongan, $arrayGolonganIV)) {
                return 15;
            }
        }

        return 0;
    }

    public function getPotonganPajakByGolonganIII($params = [])
    {
        $pegawaiGolongan = $this->getPegawaiGolonganBerlaku($params);

        $arrayGolonganIII = [
            9, 10, 11, 12
        ];

        if ($pegawaiGolongan !== null) {
            if (in_array($pegawaiGolongan->id_golongan, $arrayGolonganIII)) {
                return 5;
            }
        }

        return 0;
    }

    public function getPotonganPajakByGolonganX($params = [])
    {
        $pegawaiGolongan = $this->getPegawaiGolonganBerlaku($params);

        if ($pegawaiGolongan !== null AND $pegawaiGolongan->id_golongan == Golongan::X) {
            return 5;
        }

        return 0;
    }

    public function getPersenPotonganPajak($params = [])
    {
        $potongan = 0;
        $potongan += $this->getPotonganPajakByGolonganIX($params);
        $potongan += $this->getPotonganPajakByGolonganIV($params);
        $potongan += $this->getPotonganPajakByGolonganIII($params);
        $potongan += $this->getPotonganPajakByGolonganX($params);

        return $potongan;
    }

    public function getRupiahPotonganPajak($bulan)
    {
        return $this->getJumlahKotorTPP($bulan) * ($this->getPersenPotonganPajak(['bulan'=>$bulan])/100);
    }

    public function getRupiahTunjanganPlt($bulan)
    {
        $tpp_awal_plt = $this->getTppAwalPlt($bulan);

        return $tpp_awal_plt * 0.2;
    }

    public function getRupiahTPPBersih($bulan, $params=[])
    {
        $tpp_awal = ($this->getJumlahKotorTPP($bulan)-$this->getRupiahPotonganPajak($bulan));

        $tahun = @$params['tahun'];

        if($tahun == null) {
            $tahun = Session::getTahun();
        }

        $datetime = \DateTime::createFromFormat('Y-n-d', $tahun.'-'.$bulan.'-01');
        $tanggal = $datetime->format('Y-m-15');

        $pegawaiTukin = $this->getPegawaiTukin([
            'tahun' => $tahun,
            'bulan' => $bulan,
        ]);

        //$instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);
        $instansiPegawai = $pegawaiTukin->getInstansiPegawai();

        if ($datetime->format('Y-m') >= '2022-01' AND $datetime->format('Y-m') < '2022-07') {
            if(substr(@$instansiPegawai->nama_jabatan,0,13) == 'Tugas Belajar') {
                $tpp_awal = $tpp_awal * 0.7;
            }
        }

        $params['tahun'] = $tahun;
        $params['bulan'] = $bulan;

        $tpp_awal = $tpp_awal * $this->getPersenDibayarCutiAlasanPenting($params);
        $tpp_awal = $tpp_awal * $this->getPersenDibayarCutiSakit($params);

        return $tpp_awal;
    }

    public function getButtonTunjanganPotonganPegawai($id_tunjangan_potongan)
    {
        if ($this->accessCreateTunjanganPotonganPegawai()) {
            return Html::a("<i class='fa fa-pencil'></i>",['tunjangan-potongan-pegawai/index','TunjanganPotonganPegawaiSearch[id_pegawai]' => $this->id,'TunjanganPotonganPegawaiSearch[id_tunjangan_potongan]' => $id_tunjangan_potongan]);
        }
    }

    public function accessCreateTunjanganPotonganPegawai()
    {
        if (User::isAdmin()) {
            return true;
        }
        return false;
    }

    public function getBesaranPergub2021(array $params = [])
    {
        $bulan = @$params['bulan'];
        $tahun = @$params['tahun'];

        if($bulan == null) {
            $bulan = date('n');
        }

        if($tahun == null) {
            $tahun = Session::getTahun();
        }

        /* @var $instansiPegawai \app\models\InstansiPegawai */
        $instansiPegawai = @$params['instansiPegawai'];

        if($instansiPegawai === null) {
            return 0;
        }

        $model = TunjanganInstansiJenisJabatanKelas::findOneByParams([
            'bulan' => $bulan,
            'tahun' => $tahun,
            'instansi' => $instansiPegawai->instansi,
            'jabatan' => $instansiPegawai->jabatan,
            'pegawai' => $this
        ]);

        if($model === null) {
            return 0;
        }

        return $model->nilai_tpp;
    }

    /**
     * @param array $params
     * @return \DateTime|false
     */
    public function getDatetime(array $params)
    {
        $bulan = @$params['bulan'];
        $tahun = @$params['tahun'];

        if ($bulan == null) {
            $bulan = date('m');
        }

        if ($tahun == null) {
            $tahun = Session::getTahun();
        }

        $datetime = \DateTime::createFromFormat('Y-m-d', $tahun . '-' . $bulan . '-01');
        return $datetime;
    }

    /**
     * @param array $params
     * @return float
     */
    public function getFaktorKaliTubel(array $params)
    {
        $datetime = $this->getDatetime($params);

        $query = PegawaiTugasBelajar::find();
        $query->andWhere(['id_pegawai' => $this->id]);
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal', [
            ':tanggal' => $datetime->format('Y-m-15'),
        ]);

        $persen = 0;
        /* @var $pegawaiTugasBelajar PegawaiTugasBelajar */
        foreach ($query->all() as $pegawaiTugasBelajar) {
            $persen += $pegawaiTugasBelajar->getPersen();
        }

        return $persen / 100;
    }

    public function getKeteranganLengkapTpp()
    {
        if ($this->_keterangan_lengkap_tpp_awal_v3 !== null) {
            return $this->_keterangan_lengkap_tpp_awal_v3;
        }

        $this->_keterangan_lengkap_tpp_awal_v3 = '-';

        return $this->_keterangan_lengkap_tpp_awal_v3;
    }

    public function getBesaranTppPelaksanaByKelasJabatan(array $params)
    {
        /* @var $instansiPegawai InstansiPegawai */
        $instansiPegawai = @$params['instansiPegawai'];

        $datetime = $this->getDatetime($params);
        $tanggal = $datetime->format('Y-m-15');

        if ($instansiPegawai == null) {
            $instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);
        }

        $id_instansi = $instansiPegawai->id_instansi;
        if(@$instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::SEKOLAH) {
            $id_instansi = 33;
        }

        if(@$instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::UPTD) {
            $id_instansi = $instansiPegawai->instansi->id_induk;
        }

        if(@$instansiPegawai->instansi->id_instansi_jenis == \app\models\InstansiJenis::CABANG_DINAS) {
            $id_instansi = $instansiPegawai->instansi->id_induk;
        }

        if($instansiPegawai->id_instansi == 42 //RSJD
            OR $instansiPegawai->id_instansi == 43 //RSUD
            OR $instansiPegawai->id_instansi == 213 //RSUD Ir. SOEKARNO
        ) {
            $id_instansi = 25;
        }

        $query = JabatanTunjanganPelaksana::find();
        $query->andWhere(['id_instansi' => $id_instansi]);
        $query->andWhere(['kelas_jabatan' => @$params['kelas_jabatan']]);
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
            ':tanggal' => $tanggal,
        ]);
        $query->orderBy(['besaran_tpp' => SORT_DESC]);

        /* @var $jabatanTunjanganFungsional JabatanTunjanganFungsional */
        $jabatanTunjanganFungsional = $query->one();

        if ($jabatanTunjanganFungsional == null) {
            return 0;
        }

        return $jabatanTunjanganFungsional->besaran_tpp;
    }

    public function getBesaranTppPelaksanaMaxKelas7(array $params)
    {
        $params = $this->updateParams($params);
        $pegawaiTukin = $this->getPegawaiTukin($params);

        $instansiPegawai = $pegawaiTukin->getInstansiPegawai();
        $kelas_jabatan = @$instansiPegawai->jabatan->kelas_jabatan;

        if ($kelas_jabatan >= 7) {
            $kelas_jabatan = 7;
        }

        return $this->getBesaranTppPelaksanaByKelasJabatan([
            'instansiPegawai' => $instansiPegawai,
            'kelas_jabatan' => $kelas_jabatan,
            'bulan' => @$params['bulan'],
            'tahun' => @$params['tahun'],
        ]);
    }

    public function getPersenTppTambahanPenghargaan(array $params)
    {
        $datetime = $this->getDatetime($params);

        $query = PegawaiPenghargaan::find();
        $query->andWhere(['id_pegawai' => $this->id]);
        $query->andWhere(['id_pegawai_penghargaan_status' => PegawaiPenghargaanStatus::SETUJU]);
        $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir', [
            ':tanggal_awal' => $datetime->format('Y-m-01'),
            ':tanggal_akhir' => $datetime->format('Y-m-t'),
        ]);

        /* @var $pegawaiPenghargaan PegawaiPenghargaan */
        $pegawaiPenghargaan = $query->one();

        if ($pegawaiPenghargaan == null) {
            return 0;
        }

        $persen = 0;

        if ($pegawaiPenghargaan->id_pegawai_penghargaan_tingkat === PegawaiPenghargaanTingkat::PROVINSI) {
            $persen = 0.3;
        }

        if ($pegawaiPenghargaan->id_pegawai_penghargaan_tingkat === PegawaiPenghargaanTingkat::NASIONAL) {
            $persen = 0.4;
        }

        if ($pegawaiPenghargaan->id_pegawai_penghargaan_tingkat === PegawaiPenghargaanTingkat::INTERNASIONAL) {
            $persen = 0.5;
        }

        return $persen;
    }

    public function getRupiahTppTambahanPenghargaan(array $params)
    {
        if ($this->_rupiah_tpp_tambahan_penghargaan !== null) {
            return $this->_rupiah_tpp_tambahan_penghargaan;
        }

        $rupiah = $this->getBesaranTppPelaksanaMaxKelas7($params);
        $persen = $this->getPersenTppTambahanPenghargaan($params);

        $this->_rupiah_tpp_tambahan_penghargaan = $rupiah * $persen;

        return $this->_rupiah_tpp_tambahan_penghargaan;
    }

    public function getRupiahTppTambahanPemutakhiranSimadig(array $params)
    {
        if ($this->_rupiah_tpp_tambahan_pemutakhiran_simadig !== null) {
            return $this->_rupiah_tpp_tambahan_pemutakhiran_simadig;
        }

        $datetime = $this->getDatetime($params);

        $query = PegawaiRb::find();
        $query->andWhere(['id_pegawai_rb_jenis' => PegawaiRbJenis::PEMUTAKHIRAN_SIMADIG]);
        $query->andWhere(['id_pegawai' => $this->id]);
        $query->andWhere(['status_realisasi' => 1]);
        $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir', [
            ':tanggal_awal' => $datetime->format('Y-m-01'),
            ':tanggal_akhir' => $datetime->format('Y-m-t'),
        ]);

        $model = $query->one();

        if ($model == null) {
            $this->_rupiah_tpp_tambahan_pemutakhiran_simadig = 0;
            return $this->_rupiah_tpp_tambahan_pemutakhiran_simadig;
        }

        $rupiah = $this->getBesaranTppPelaksanaMaxKelas7($params);

        $this->_rupiah_tpp_tambahan_pemutakhiran_simadig = $rupiah * 0.01;

        return $this->_rupiah_tpp_tambahan_pemutakhiran_simadig;
    }

    /**
     * @param array $params
     * @return float|int
     */
    public function getRupiahTppTambahanPengisianRenbangkom(array $params)
    {
        if ($this->_rupiah_tpp_tambahan_pengisian_renbangkom !== null) {
            return $this->_rupiah_tpp_tambahan_pengisian_renbangkom;
        }

        $datetime = $this->getDatetime($params);
        $datetime->modify('-1 month');

        $query = PegawaiRb::find();
        $query->andWhere(['id_pegawai_rb_jenis' => PegawaiRbJenis::PERENCANAAN_BANGKOM]);
        $query->andWhere(['id_pegawai' => $this->id]);
        $query->andWhere(['status_realisasi' => 1]);
        $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir', [
           ':tanggal_awal' => $datetime->format('Y-m-01'),
           ':tanggal_akhir' => $datetime->format('Y-m-t'),
        ]);

        $model = $query->one();

        if ($model == null) {
            $this->_rupiah_tpp_tambahan_pengisian_renbangkom = 0;
            return $this->_rupiah_tpp_tambahan_pengisian_renbangkom;
        }

        $rupiah = $this->getBesaranTppPelaksanaMaxKelas7($params);

        $this->_rupiah_tpp_tambahan_pengisian_renbangkom = $rupiah * 0.01;

        return $this->_rupiah_tpp_tambahan_pengisian_renbangkom;
    }

    public function getRupiahTppTambahanPemenuhanBangkom20Jp()
    {
        return 0;
    }

    /**
     * @param array $params
     * @return bool
     */
    public function hasPenundaanTpp(array $params)
    {
        $datetime = $this->getDatetime($params);

        $query = PegawaiTundaBayar::find();
        $query->andWhere(['id_pegawai' => $this->id]);
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal', [
            ':tanggal' => $datetime->format('Y-m-15'),
        ]);

        /* @var $pegawaiTundaBayar PegawaiTundaBayar */
        $pegawaiTundaBayar = $query->one();

        if ($pegawaiTundaBayar === null) {
            return false;
        }

        return true;
    }

    public $_total_tpp_sebelum_pajak_14;
    public function getRupiahTPPSebelumPajak14($bulan, $params=[])
    {
        if ($this->_total_tpp_sebelum_pajak_14 != null) {
            return $this->_total_tpp_sebelum_pajak_14;
        }

        $params = $this->updateParams($params);
        $params['nonTpp'] = true;

        $tahun = @$params['tahun'];
        if ($tahun == null) {
            $tahun = Session::getTahun();
        }

        $totalTppSebelumPajak14 = 0;
        $totalTppSebelumPajak14 += $this->getTppAwal($bulan, $params);

        if($totalTppSebelumPajak14 < 0) {
            $totalTppSebelumPajak14 = 0;
        }
        

        $datetime = $this->getDatetime(['bulan' => $bulan, 'tahun' => $tahun]);
        $tanggal = $datetime->format('Y-m-15');
        //$instansiPegawai = $this->getInstansiPegawaiBerlaku($tanggal);

        $pegawaiTukin = $this->getPegawaiTukin($params);
        $instansiPegawai = $pegawaiTukin->getInstansiPegawai();

        $tahunBulan = $datetime->format('Y-m');
        if ($tahunBulan >= '2022-01' AND $tahunBulan <= '2022-12') {
            if(substr(@$instansiPegawai->nama_jabatan, 0, 13) == 'Tugas Belajar') {
                $totalTppSebelumPajak14 = $totalTppSebelumPajak14 * 0.7;
            }
        }

        if(substr(@$instansiPegawai->nama_jabatan, 0, 13) == 'Tugas Belajar' 
            AND @$instansiPegawai->jabatan->kelas_jabatan == null
            AND $instansiPegawai->jabatan->id_jenis_jabatan == JenisJabatan::PELAKSANA
        ) {
            $totalTppSebelumPajak14 = $this->getBesaranPelaksana2022([
                'tahun' => $tahun,
                'bulan' => $bulan,
                'kelas_jabatan' => 7,
            ]);
        }


        $totalTppSebelumPajak14 = $totalTppSebelumPajak14 * 0.5;

        $this->_total_tpp_sebelum_pajak_14 = $totalTppSebelumPajak14;

        return $this->_total_tpp_sebelum_pajak_14;
    }

    public function getRupiahTunjanganPlt14($bulan, $params = [])
    {
        $rupiah = $this->getRupiahTunjanganPlt($bulan);
        
        return $rupiah * 0.5;
    }

    public function getJumlahKotorTPP14($bulan)
    {
        $total = $this->getRupiahTPPSebelumPajak14($bulan);
        $total += $this->getRupiahTunjanganPlt14($bulan);
        $total = $total * $this->getVolumePerBulan();
        return $total;
    }

    public $_rupiah_tpp_bersih_14;
    public function getRupiahTPPBersih14($bulan)
    {
        if ($this->_rupiah_tpp_bersih_14 !== null) {
            return $this->_rupiah_tpp_bersih_14;
        }

        $tppSebelumPajak = $this->getJumlahKotorTPP14($bulan);
        $persenPotonganPajak = ($this->getPersenPotonganPajak(['bulan' => $bulan]) / 100);

        $rupiahPotonganPajak = $tppSebelumPajak * $persenPotonganPajak;
        $rupiahTppBersih14 = $tppSebelumPajak - $rupiahPotonganPajak;

        if ($rupiahTppBersih14 < 0) {
            $rupiahTppBersih14 = 0;
        }

        $this->_rupiah_tpp_bersih_14 = $rupiahTppBersih14;

        return $this->_rupiah_tpp_bersih_14;
    }

    public $_pegawai_tugas_belajar;
    /**
     * $params['bulan']<br>
     * $params['tahun']
     *
     * @param array $params
     * @return PegawaiTugasBelajar|array|\yii\db\ActiveRecord
     */
    public function getPegawaiTugasBelajar(array $params)
    {
        $datetime = $this->getDatetime(['bulan' => @$params['bulan'], 'tahun' => @$params['tahun']]);
        $bulan = $datetime->format('n');

        if (@$this->_pegawai_tugas_belajar[$bulan] !== null) {
            return $this->_pegawai_tugas_belajar[$bulan];
        }

        $query = PegawaiTugasBelajar::find();
        $query->andWhere(['id_pegawai' => $this->id]);
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal', [
            ':tanggal' => $datetime->format('Y-m-15'),
        ]);

        $pegawaiTugasBelajar = $query->one();

        $this->_pegawai_tugas_belajar[$bulan] = $pegawaiTugasBelajar;
        return $this->_pegawai_tugas_belajar[$bulan];
    }

    public function getInstansiPegawaiPrev($instansiPegawai)
    {
        $datetime = \DateTime::createFromFormat('Y-m-d', $instansiPegawai->tanggal_mulai);
        $datetime->modify('-2 month');

        $tanggal = $datetime->format('Y-m-15');

        return $this->getInstansiPegawaiBerlaku($tanggal);
    }

    public function getPersenDibayarCutiAlasanPenting($params)
    {
        $pegawaiTukin = $this->getPegawaiTukin($params);

        return $pegawaiTukin->getPersenDibayarCutiAlasanPenting();
    }

    public function getPersenDibayarCutiSakit($params)
    {
        $pegawaiTukin = $this->getPegawaiTukin($params);

        return $pegawaiTukin->getPersenDibayarCutiSakit();
    }

    public function getPersenPotonganIndexProfAsn($bulan, array $params = [])
    {
        $params['bulan'] = $bulan;
        $params = $this->updateParams($params);

        $pegawaiTukin = $this->getPegawaiTukin($params);

        return $pegawaiTukin->getPersenPotonganIndexProfAsn();
    }

    public function getRupiahPotonganIndeksProfAsn($bulan, array $params = [])
    {
        $params['bulan'] = $bulan;
        $params = $this->updateParams($params);

        $pegawaiTukin = $this->getPegawaiTukin($params);
        $pegawaiTukin->besaran_tpp = $this->getTppAwal($bulan);

        return $pegawaiTukin->getRupiahPotonganIndeksProfAsn();
    }

    public function getSkorIndeksIpAsn($bulan, array $params = [])
    {
        $params['bulan'] = $bulan;
        $params = $this->updateParams($params);

        $pegawaiTukin = $this->getPegawaiTukin($params);

        return $pegawaiTukin->getSkorIndeksIpAsn();
    }

    private $_pegawaiTukin;
    /**
     * @return PegawaiTukin
     */
    public function getPegawaiTukin($params)
    {
        if ($this->_pegawaiTukin !== null) {
            return $this->_pegawaiTukin;
        }

        $pegawaiTukin = new PegawaiTukin([
            'id_pegawai' => $this->id,
            'tahun' => $params['tahun'],
            'bulan' => $params['bulan'],
        ]);

        $this->_pegawaiTukin = $pegawaiTukin;
        return $this->_pegawaiTukin;
    }

    public function updateParams($params)
    {
        if (@$params['tahun'] == null) {
            @$params['tahun'] = Session::getTahun();
        }

        return $params;
    }
}
