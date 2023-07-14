<?php

namespace app\models;

use DateTime;
use Yii;
use app\components\Helper;
use app\components\Session;
use app\modules\kinerja\models\KegiatanTahunan;
use app\modules\tukin\models\InstansiKordinatif;
use yii2mod\query\ArrayQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "instansi".
 *
 * @property integer $id
 * @property string $nama
 * @property string $singkatan
 * @property string $alamat
 * @property string $telepon
 * @property string $email
 *
 * @property mixed $username
 * @property Pegawai $manyPegawai
 * @property mixed $allUserinfo
 * @property null $checktimeTerakhir
 * @property null $lastActivityTerakhir
 * @property mixed $manyIclock
 * @property mixed $manyCheckinout
 * @property mixed $checkinoutTerakhir
 * @property mixed $manyMesinAbsensi
 * @property bool $isUptdHasInduk
 * @property InstansiKordinatif[] $manyInstansiKordinatif
 * @property mixed $idUser
 * @property mixed $countManyPegawai
 * @property mixed $induk
 * @property mixed $manyUserinfo
 * @property mixed $allCheckinout
 * @property mixed $linkNama
 * @property mixed $listInstansiInduk
 * @property void $arrayMesinAbsensi
 * @property string $textLastActivityTerakhir
 * @property string $textChecktimeTerakhir
 * @property int $id_induk [int(11)]
 * @property int $id_instansi_jenis [int(11)]
 * @property bool $status_kordinator [tinyint(1)]
 *
 * @property null $linkIconUserSetPassword
 * @property null|string $linkIconUpdate
 * @property Jabatan[] $manyJabatanKepala
 * @property mixed $manyInstansiPegawai
 * @property mixed $manyJabatan
 * @property null|string $linkIconDelete
 * @property null|string $linkIconView
 * @property null|string $linkIconViewJabatan
 * @property null $linkButtonUpdate
 * @property mixed $manyJabatanBelumDipetakan
 * @property Instansi[] $manySub
 * @property int $id_instansi_lokasi
 * @see Instansi::getInstansiLokasi()
 * @property InstansiLokasi $instansiLokasi
 * @property int $status_aktif
 */
class Instansi extends \yii\db\ActiveRecord
{
    const DINAS_KESEHATAN = 25;
    const RSUD = 43;
    const RSJD = 42;
    const RSUD_BARU = 213;
    const DINAS_PENDIDIKAN = 33;
    const SMKN_1_SELAT_NASIK = 121;
    const SMAN_1_LEPAR_PONGOK = 150;

    /**
     * @inheritdoc
     */

    public $bulan = 1;
    public $tahun;
    public $tanggal;

    public static function tableName()
    {
        return 'instansi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['id_instansi_jenis', 'id_induk'], 'integer'],
            [['nama', 'singkatan', 'alamat', 'telepon', 'email'], 'string', 'max' => 255],
            [['bulan', 'tahun', 'tanggal'], 'safe'],
            [['id_instansi_lokasi', 'status_aktif'], 'integer'],
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
            'singkatan' => 'Singkatan',
            'alamat' => 'Alamat',
            'telepon' => 'Telepon',
            'email' => 'Email',
            'id_induk' => 'Instansi Induk',
            'id_instansi_jenis' => 'Jenis',
            'id_instansi_lokasi' => 'Lokasi',
        ];
    }

    public static function find()
    {
        $find = parent::find();
        /*
        if (PHP_SAPI !== 'cli' && User::isInstansi()) {
            $find->andWhere(['id' => User::getIdInstansi()]);
        }
        */
        return $find;
    }

    public function getInstansiJenis()
    {
        return $this->hasOne(InstansiJenis::class,['id'=>'id_instansi_jenis']);
    }

    public function getManyPegawai()
    {
        return $this->hasMany(Pegawai::className(), ['id_instansi' => 'id']);
    }

    public function getManyJabatan()
    {
        return $this->hasMany(Jabatan::class,['id_instansi'=>'id']);
    }

    public function getManyJabatanKepala()
    {
        return $this->getManyJabatan()->andWhere(['status_kepala' => 1]);
    }

    public function getManyJabatanBelumDipetakan()
    {
        return $this->getManyJabatan()
            ->andWhere(['status_kepala' => false, 'id_induk' => null]);
    }

    public function getManyMesinAbsensi()
    {
        return $this->hasMany(\app\modules\absensi\models\MesinAbsensi::className(), ['id_instansi' => 'id']);
    }

    public function getManyUserinfo()
    {
        return $this->hasMany(\app\modules\iclock\models\Userinfo::className(), ['badgenumber' => 'nip'])
            ->via('manyPegawai');
    }

    public function getManyIclock()
    {
        return $this->hasMany(\app\modules\iclock\models\Iclock::className(), ['SN' => 'serialnumber'])
            ->via('manyMesinAbsensi');
    }

    public function getManyCheckinout()
    {
        $query = $this->hasMany(\app\modules\iclock\models\Checkinout::className(), ['SN' => 'SN']);
        $query->via('manyIclock');

        return $query;
    }

    public function getCountManyPegawai()
    {
        return count($this->manyPegawai);
    }

    public function getInstansiLokasi()
    {
        return $this->hasOne(InstansiLokasi::class,['id'=>'id_instansi_lokasi']);
    }

    public function queryPegawai()
    {
        $query = Pegawai::find();
        $query->andWhere([
            'tahun' => $this->tahun,
            'kode_instansi' => $this->kode_instansi,
        ]);

        return $query;
    }

    public function AllPegawai()
    {
        $query = $this->queryPegawai();

        return $query->all();
    }

    public function countPegawai()
    {
        $query = $this->getManyPegawai();

        return $query->count();
    }

    public function countMesinAbsensi()
    {
        return $this->getManyMesinAbsensi()->count();
    }

    public function countMesinAbsensiAktif()
    {
        return $this->getManyMesinAbsensi()->count();
    }

    public function getRelationField($relation, $field)
    {
        if (!empty($this->$relation->$field)) {
            return $this->$relation->$field;
        } else {
            return null;
        }

    }

    public static function getList()
    {
        $query = Instansi::find();

        if (User::isInstansi() or User::isAdminInstansi() or User::isOperatorAbsen()) {
            $query->andWhere(['id' => User::getListIdInstansi()]);
        }

        if (User::isVerifikator()) {
            $query->andWhere(['id' => User::getListIdInstansi()]);
        }

        if (User::isPegawai()) {
            $query->andWhere(['in', 'id', Yii::$app->user->identity->pegawai->getAllIdInstansiPegawai()]);
        }

        if (User::isMapping()) {
            $query->andWhere(['in', 'id', User::getListIdInstansi()]);
        }

        return ArrayHelper::map($query->all(), 'id', 'nama');
    }

    public static function getListInduk()
    {
        return ArrayHelper::map(self::find()->andWhere(['!=', 'id_instansi_jenis', 3])->all(), 'id', 'nama');
    }

    public function getAllUserinfo()
    {
        return $this
            ->hasMany(\app\modules\iclock\models\Userinfo::className(), ['badgenumber' => 'nip'])
            ->via('manyPegawai');
    }

    public function getAllCheckinout()
    {
        return $this
            ->hasMany(\app\modules\iclock\models\Checkinout::className(), ['userid' => 'userid'])
            ->via('allUserinfo');
    }

    public function getLinkNama()
    {
        return Html::a($this->nama);
    }

    public function loadParams($params)
    {

        $this->tahun = User::getTahun();

        if (isset($params['bulan'])) {
            $this->bulan = $params['bulan'];
        }

        if (isset($params['tanggal'])) {
            $this->tanggal = $params['tanggal'];
        }
    }

    public function queryCheckinout($params = [])
    {

        $query = $this->getManyCheckinout();

        $this->loadParams($params);

        $date = date_create($this->tahun);
        $checktime_awal = $date->format('Y-01-01 00:00:00');
        $checktime_akhir = $date->format('Y-12-31 23:59:59');

        if ($this->bulan != null) {
            $date = date_create($this->tahun . '-' . $this->bulan);
            $checktime_awal = $date->format('Y-m-01 00:00:00');
            $checktime_akhir = $date->format('Y-m-t 23:59:59');
        }

        if ($this->tanggal != null) {
            $date = date_create($this->tanggal);
            $checktime_awal = $date->format('Y-m-d 00:00:00');
            $checktime_akhir = $date->format('Y-m-d 23:59:59');
        }

        $query->andWhere('checktime >= :checktime_awal AND checktime <= :checktime_akhir', [
            ':checktime_awal' => $checktime_awal,
            ':checktime_akhir' => $checktime_akhir,
        ]);

        return $query;
    }

    public function countCheckinout($params = [])
    {
        $query = $this->queryCheckinout($params);
        return $query->count();
    }

    public function getCheckinoutTerakhir()
    {
        $query = $this->getManyCheckinout();
        $query->limit(1);
        $query->orderBy(['checktime' => SORT_DESC]);
        return $query->one();
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

    public function getTextChecktimeTerakhir()
    {
        $checktimeTerakhir = $this->getChecktimeTerakhir();

        if ($checktimeTerakhir != null) {
            return Helper::getSelisihWaktu($checktimeTerakhir) . " Lalu<br>" . $checktimeTerakhir;
        } else {
            return "";
        }

    }

    public function getLastActivityTerakhir()
    {
        $query = $this->getManyIclock();
        $query->orderBy(['LastActivity' => SORT_DESC]);

        $model = $query->one();

        if ($model !== null) {
            return $model->LastActivity;
        } else {
            return null;
        }

    }

    public function getTextLastActivityTerakhir()
    {
        $lastActivityTerakhir = $this->getLastActivityTerakhir();

        if ($lastActivityTerakhir != null) {
            return Helper::getSelisihWaktu($lastActivityTerakhir) . " Lalu<br>" . $lastActivityTerakhir;
        } else {
            return "";
        }

    }

    public function findUser()
    {
        $model = User::findOne([
            'id_instansi' => $this->id,
            'id_user_role' => UserRole::INSTANSI,
        ]);

        if ($model === null) {
            $username = $this->nama;

            if ($this->singkatan != null) {
                $username = $this->singkatan;
            }

            $model = new User;
            $model->username = $username;
            $model->id_instansi = $this->id;
            $model->id_user_role = UserRole::INSTANSI;
            $model->password = Yii::$app->getSecurity()->generatePasswordHash($username);

            if (!$model->save()) {
                print 'ID Instansi : '.$this->id.'<br/>';
                print 'Nama Instansi : '.$this->nama.'<br/>';
                print_r($model->getErrors());
                die();
            }
        }

        return $model;
    }

    public function getIdUser()
    {
        $model = $this->findUser();

        return $model->id;
    }

    public function getUsername()
    {
        $model = $this->findUser();

        return $model->username;
    }

    public function getArrayMesinAbsensi()
    {

    }

    public function getListInstansiInduk()
    {
        if ($this->getIsNewRecord()) {
            return ArrayHelper::map(static::find()->all(), 'id', 'nama');
        }
        return ArrayHelper::map(static::find()->andWhere(['!=', 'id', $this->id])->all(), 'id', 'nama');
    }

    public function getInduk()
    {
        return $this->hasOne(static::class, ['id' => 'id_induk']);
    }

    public function getManySub()
    {
        return $this->hasMany(static::class, ['id_induk' => 'id']);
    }

    public function getIsUptdHasInduk()
    {
        return $this->induk !== null;
    }

    public function getSingkatan()
    {
        return $this->singkatan !== null ? $this->singkatan : substr($this->nama, 0, 25) . "...";
    }

    public function accessViewJabatan()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isMapping()) {
            return true;
        }

        if(Session::isOperatorStruktur()) {
            return true;
        }

        return false;

    }

    public function getLinkIconViewJabatan()
    {
        if($this->accessViewJabatan()==false) {
            return null;
        }

        return Html::a('<i class="fa fa-sitemap"></i>',[
                '/instansi/view-jabatan',
                'id'=>$this->id
            ],[
                'data-toggle'=>'tooltip',
                'title'=>'Lihat Struktur Jabatan'
            ]).' ';

    }

    public function getLinkButtonUpdate()
    {
        if($this->accessUpdate()==false) {
            return null;
        }

        return Html::a('<i class="fa fa-pencil"></i> Sunting Instansi', [
            'update', 'id' => $this->id
        ], ['class' => 'btn btn-success btn-flat']);
    }

    public function getLinkIconView()
    {
        if($this->accessView()==false) {
            return null;
        }

        return Html::a('<i class="fa fa-eye"></i>',[
                '/instansi/view',
                'id'=>$this->id
            ],[
                'data-toggle'=>'tooltip',
                'title'=>'View'
            ]).' ';
    }

    public function getLinkIconUpdate()
    {
        if($this->accessUpdate()==false) {
            return null;
        }

        return Html::a('<i class="fa fa-pencil"></i>',[
                '/instansi/update',
                'id'=>$this->id
            ],[
                'data-toggle'=>'tooltip',
                'title'=>'Ubah'
            ]).' ';
    }

    public static function accessIndex()
    {
        if(User::isAdmin()) {
            return true;
        }

        return false;
    }

    public function accessView()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator()) {
            return true;
        }

        if(User::isMapping()) {
            return true;
        }

        return false;

    }

    public static function accessCreate()
    {
        if(User::isAdmin()) {
            return true;
        }

        return false;
    }

    public function accessUpdate()
    {
        if(User::isAdmin()) {
            return true;
        }

        return false;
    }


    public function accessDelete()
    {
        if(User::isAdmin()) {
            return true;
        }

        return false;
    }

    public function accessUserSetPassword()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator()) {
            return true;
        }

        return false;
    }


    public function getLinkIconDelete()
    {
        if($this->accessDelete()==false) {
            return null;
        }

        return Html::a('<i class="fa fa-trash"></i>',[
                '/instansi/delete',
                'id'=>$this->id
            ],[
                'data-method'=>'post',
                'data-confirm'=>'Yakin akan menghapus data?',
                'data-toggle'=>'tooltip',
                'title'=>'Hapus'
            ]).' ';
    }

    public function getLinkIconUserSetPassword()
    {
        if($this->accessUserSetPassword()==false) {
            return null;
        }

        return Html::a('<i class="fa fa-lock"></i>',[
            '/user/set-password',
            'id'=>$this->getIdUser()
        ],['data-toggle'=>'tooltip','title'=>'Set Password']);
    }

    public static function getLinkButtonCreate()
    {
        if(Instansi::accessCreate()==false) {
            return null;
        }

        return Html::a('<i class="fa fa-plus"></i> Tambah Unit Kerja', [
            '/instansi/create'
        ], ['class' => 'btn btn-success btn-flat']);
    }

    /**
     * @param array $params
     * @return Jabatan[]
     */
    public function findAllJabatan($params=[])
    {
        $query = $this->getManyJabatan();

        if(@$params['arrayJabatan']!=null) {
            $query = new ArrayQuery();
            $query->from($params['arrayJabatan']);
        }

        $query->andFilterWhere([
            'id_induk' => @$params['id_induk'],
            'status_kepala' => @$params['status_kepala'],
            'status_tampil' => @$params['status_tampil'],
        ]);

        if(@$params['id_induk']=='null') {
            $query->andWhere('id_induk IS NULL');
        }

        return $query->all();
    }

    /**
     * @param array $params
     * @return Jabatan[]
     */
    public function findAllJabatanKepala($params=[])
    {
        @$params['status_kepala'] = 1;
        return $this->findAllJabatan($params);
    }

    public function countInstansiPegawaiByBulanTahun($bulan=null,$tahun=null)
    {
        if($bulan==null) {
            $bulan = date('n');
        }

        if($tahun==null) {
            $tahun = Session::getTahun();
        }

        $query = $this->getManyInstansiPegawai();
        $query->filterByBulanTahun($bulan,$tahun);
        return $query->count();
    }

    public function getManyInstansiPegawai()
    {
        return $this->hasMany(InstansiPegawai::class, ['id_instansi' => 'id']);
    }

    public function countJabatan($params=[])
    {
        $query = $this->getManyJabatan();

        $query->andFilterWhere([
            'id_jenis_jabatan'=>@$params['id_jenis_jabatan'],
            'id_jabatan_eselon'=>@$params['id_jabatan_eselon']
        ]);

        return $query->count();
    }

    public function countJabatanSudahDipetakan()
    {
        $query = $this->getManyJabatan();
        $query->andWhere('status_kepala = 1 OR id_induk IS NOT NULL');
        return $query->count();
    }

    public function countJabatanBelumDipetakan()
    {
        $query = $this->getManyJabatan();
        $query->andWhere('status_kepala != 1 AND id_induk IS NULL');
        return $query->count();
    }

    public static function accessExportJabatan()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isMapping()) {
            return true;
        }

        if (Session::isOperatorStruktur()) {
            return true;
        }
        return false;
    }

    public function findAllInstansiInduk()
    {
        $query = InstansiInduk::find();
        $query->andWhere([
            'id_instansi' => $this->id
        ]);

        return $query->all();
    }

    public function getPegawaiKepala()
    {
        if (!empty($this->manyJabatanKepala)) {
            $kepala = $this->manyJabatanKepala[0];
            if (!empty($kepala->manyInstansiPegawai)) {
                return @$kepala->getManyInstansiPegawaiByBulanTahun()->one()->pegawai;
            }
        }
        return null;
    }

    public function getInstansiIndukBerlaku($params = [])
    {
        $tanggal = @$params['tanggal'];
        if($tanggal == null) {
            $tanggal = date('Y-m-d');
        }

        $query = InstansiInduk::find();
        $query->andWhere([
            'id_instansi' => $this->id
        ]);
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
            ':tanggal' => $tanggal
        ]);

        $query->orderBy(['tanggal_mulai'=>SORT_DESC]);

        return $query->one();
    }

    public function getLinkJumlahIkiKepala($params=[])
    {
        $id_kegiatan_tahunan_versi = @$params['id_kegiatan_tahunan_versi'];
        if ($id_kegiatan_tahunan_versi == null) $id_kegiatan_tahunan_versi = 2; // default

        $jabatan = $this->getManyJabatanKepala()->one();

        if ($jabatan == null) {
            return 0;
        }

        $instansiPegawai = $jabatan->getManyInstansiPegawaiByBulanTahun()->one();
        $jumlah = 0;

        if ($instansiPegawai != null) {
            $jumlah = KegiatanTahunan::find()
                ->andWhere(['tahun' => Session::getTahun()])
                ->andWhere(['id_instansi_pegawai' => $instansiPegawai->id])
                ->andWhere(['id_kegiatan_tahunan_versi' => $id_kegiatan_tahunan_versi])
                ->count();
        }

        return Html::a($jumlah, [
            '/kinerja/kegiatan-tahunan/index-v2',
            'KegiatanTahunanSearch[id_pegawai]' => @$instansiPegawai->id_pegawai,
        ]);
    }

    public function findAllSub($params = [])
    {
        $query = Instansi::find();
        $query->andWhere(['id_induk' => $this->id]);

        if (@$params['status_aktif'] !== null) {
            $query->andWhere(['status_aktif' => @$params['status_aktif']]);
        }

        return $query->all();
    }

    public function getTotalPegawaiPotonganCkhp($bulan, $params=[])
    {
        $tahun = @$params['tahun'];
        $listKegiatanHarian = @$params['listKegiatanHarian'];

        if ($tahun == null) {
            $tahun = Session::getTahun();
        }

        $query = $this->getManyInstansiPegawai();
        $query->filterByBulanTahun($bulan,$tahun);

        $datetime = \DateTime::createFromFormat('Y-n-d', $tahun . '-' . $bulan . '-01');
        $akhir = $datetime->format('Y-m-t');

        $allInstansiPegawai = $query->all();
        $total = 0;

        /* @var $instansiPegawai InstansiPegawai */
        foreach ($allInstansiPegawai as $instansiPegawai) {
            if ($instansiPegawai->pegawai == null) {
                continue;
            }

            $continue = false;

            for ($i=1; $i<31; $i++) {
                if ($continue == true) {
                    continue;
                }

                $date = \DateTime::createFromFormat('Y-n-d', $tahun . '-' . $bulan . '-' . $i);
                $tanggal = $date->format('Y-m-d');

                $isHariKerja = $instansiPegawai->pegawai->isHariKerja([
                    'tanggal' => $tanggal,
                ]);

                $hasCkhp = @$listKegiatanHarian[$instansiPegawai->id_pegawai][$tanggal];

                if ($isHariKerja == true AND $hasCkhp == null) {
                    $total++;
                    $continue = true;
                }
            }

        }

        return $total;
    }

    /**
     * @param array $params
     * @return InstansiPegawai[]
     */
    public function getListInstansiPegawaiBerlaku($params = [])
    {
        $query = InstansiPegawai::find();
        $query->andWhere([
            'id_instansi' => $this->id
        ]);
        $query->berlaku(@$params['tanggal']);

        return $query->all();
    }

    /**
     * @param array $params
     * @return RekapInstansiBulan[]
     */
    public function findAllRekapInstansiBulan(array $params = [])
    {
        $query = RekapInstansiBulan::find();
        $query->andWhere([
            'id_instansi' => $this->id,
        ]);

        if (@$params['bulan'] != null) {
            $query->andWhere(['bulan' => @$params['bulan']]);
        }

        return $query->all();
    }

    /**
     * $params['bulan']<br>
     * $params['id_rekap_jenis']
     *
     * @param array $params
     * @return RekapInstansiBulan
     */
    public function getRekapInstansiBulanByJenis(array $params)
    {
        return RekapInstansiBulan::findOrCreate([
            'id_instansi' => $this->id,
            'tahun' => Session::getTahun(),
            'bulan' => $params['bulan'],
            'id_rekap_jenis' => $params['id_rekap_jenis'],
        ]);
    }

    /**
     * @param $bulan
     * @return string
     */
    public function getJumlahPegawaiPotonganCkhp($bulan)
    {
        $rekapInstansiBulan = $this->getRekapInstansiBulanByJenis([
            'bulan' => $bulan,
            'id_rekap_jenis' => RekapJenis::JUMLAH_PEGAWAI_POTONGAN_CKHP,
        ]);

        return $rekapInstansiBulan->nilai;
    }

    /**
     * @param $bulan
     * @return float|int
     */
    public function getPersenPegawaiPotonganCkhp($bulan)
    {
        $jumlahPegawai = $this->countInstansiPegawaiByBulanTahun($bulan);
        $jumlahPegawaiPotongan = $this->getJumlahPegawaiPotonganCkhp($bulan);

        if ($jumlahPegawaiPotongan == 0) {
            return 0;
        }

        return Helper::rp(($jumlahPegawaiPotongan / $jumlahPegawai) * 100, 0, 0);
    }

    /**
     * @param $bulan
     * @return string
     */
    public function getJumlahPegawaiPotonganCkhpIki($bulan)
    {
        $rekapInstansiBulan = $this->getRekapInstansiBulanByJenis([
            'bulan' => $bulan,
            'id_rekap_jenis' => RekapJenis::JUMLAH_PEGAWAI_POTONGAN_IKI,
        ]);

        return $rekapInstansiBulan->nilai;
    }

    /**
     * @param $bulan
     * @return float|int
     */
    public function getPersenPegawaiPotonganIki($bulan)
    {
        $jumlahPegawai = $this->countInstansiPegawaiByBulanTahun($bulan);
        $jumlahPegawaiPotongan = $this->getJumlahPegawaiPotonganCkhpIki($bulan);

        if ($jumlahPegawaiPotongan == 0) {
            return 0;
        }

        return Helper::rp(($jumlahPegawaiPotongan / $jumlahPegawai) * 100, 0, 0);
    }

    public function getLabelStatusAktif()
    {
        if ($this->status_aktif == 1) {
            return Html::tag('span', 'Aktif', ['class' => 'label label-success']);
        }

        if ($this->status_aktif == 0) {
            return Html::tag('span', 'Tidak Aktif', ['class' => 'label label-danger']);
        }

        return null;
    }
}
