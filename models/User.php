<?php

namespace app\models;

use app\components\Session;
use kartik\password\StrengthValidator;
use Yii;
use yii\helpers\ArrayHelper;
use function array_merge;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property int $id_user_role
 * @property int $id_opd
 * @property int $id_pegawai
 * @property int $id_instansi
 * @property string $username
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 * @property Role $role
 * @property Instansi $instansi
 * @property \yii\db\ActiveQuery $userRole
 * @property mixed $pegawai
 * @property mixed $tunjanganTotal
 * @property array $listIdInstansi
 * @property mixed $tunjanganAbsensi
 * @property mixed $grup
 * @property array $listIdPegawai
 * @property mixed $tunjanganKinerja
 * @property int $pokokTunjangan
 * @property UserInstansi[] $manyUserInstansi
 * @property mixed $listSubordinat
 * @property string $authKey
 * @property mixed $manyGrupPegawai
 * @property Opd $opd
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    public static function getList()
    {
        return [self::ADMIN => 'Admin', self::OPD => 'Opd'];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Mengambil model user dengan username
     * @param $username string
     * @return yii\db\ActiveRecord
     * @return null jika tidak ketemu
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    public static function getIdUser()
    {
        if (Yii::$app->request->isConsoleRequest) {
            return null;
        }

        return @Yii::$app->user->identity->id;
    }

    public static function getIdPegawai()
    {
        if (Yii::$app->request->isConsoleRequest) {
            return null;
        }

        if (User::isPegawai()) {
            return Yii::$app->user->identity->id_pegawai;
        }

        return null;
    }

    public static function isPegawai()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        return Yii::$app->user->identity->id_user_role == UserRole::PEGAWAI;
    }

    public static function getIdInstansi()
    {
        if (User::isInstansi() || User::isOperatorAbsen()) {
            return Yii::$app->user->identity->id_instansi;
        }

        if (User::isAdminInstansi()) {
            return Yii::$app->user->identity->id_instansi;
        }

        return Yii::$app->session->get('id_instansi', null);
    }

    public static function isInstansi()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        return Yii::$app->user->identity->id_user_role == UserRole::INSTANSI;
    }

    public static function isAdminInstansi()
    {
        if (User::getIdUserRole() == UserRole::ADMIN_INSTANSI) {
            return true;
        }

        return null;
    }

    public static function isAdminIki()
    {
        return User::getIdUserRole() === UserRole::ADMIN_IKI;
    }

    public static function isOperatorAbsen()
    {
        return static::getIdUserRole() === UserRole::OPERATOR_ABSEN;
    }

    public static function getIdUserRole()
    {
        if (Yii::$app->request->isConsoleRequest) {
            return null;
        }

        return @Yii::$app->user->identity->id_user_role;
    }

    public static function getIdGrup()
    {
        if (User::isGrup()) {
            return Yii::$app->user->identity->id_grup;
        }

        return Yii::$app->session->get('id_instansi', null);
    }

    public static function isGrup()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        return @Yii::$app->user->identity->id_user_role == UserRole::GRUP;
    }

    public static function isPegawaiEselonII()
    {
        return static::isPegawai() && @Yii::$app->user->identity->pegawai->getIsEselonII();
    }

    public static function isPegawaiEselon()
    {
        return static::isPegawai() && @Yii::$app->user->identity->pegawai->getIsEselon();
    }

    public static function isVerifikator()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        return @Yii::$app->user->identity->id_user_role == UserRole::VERIFIKATOR;
    }

    public static function isMapping()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        return @Yii::$app->user->identity->id_user_role == UserRole::MAPPING;
    }

    public static function getNama()
    {
        if (User::isAdmin()) {
            return ucwords(Yii::$app->user->identity->username);
        }

        if (User::isPegawai()) {
            $model = User::findPegawai();

            if ($model) {
                return $model->nama;
            }

            return '';
        }
    }

    public static function isAdmin()
    {
        if (User::getIdUserRole() == UserRole::ADMIN) {
            $user = Yii::$app->user->identity;
            if ($user->force_logout === 1) {
                $user->force_logout = 0;
                $user->save(false);
                Yii::$app->user->logout();
                return Yii::$app->response->redirect(['site/login']);;
            }
            return true;
        } else {
            return false;
        }

    }

    public static function findPegawai()
    {
        return Pegawai::findOne(Yii::$app->user->identity->id_pegawai);
    }

    public static function getNipPegawai()
    {
        $model = User::findPegawai();
        return @$model->nip;
    }

    public static function getListIdInstansi()
    {
        if (User::isInstansi() || User::isOperatorAbsen()) {
            return [User::getIdInstansi()];
        }
        if (User::isAdminInstansi()) {
            return static::getListIdInstansiUnit();
        }
        $user = User::findBySession();

        $output = [];
        foreach ($user->manyUserInstansi as $userInstansi) {
            $output[] = $userInstansi->id_instansi;
        }

        return $output;
    }

    public static function findBySession()
    {
        $query = User::find();
        $query->andWhere('username = :username', [':username' => Yii::$app->user->identity->username]);
        return $query->one();
    }

    public static function getIsBelumSkp($tanggal = null)
    {
        if (User::isPegawai()) {
            return !@Yii::$app->user->identity->pegawai->getHasSkpDisetujui($tanggal);
        }
        return false;
    }

    /*public static function findPegawai()
    {
    return Pegawai::findOne(User::getIdPegawai());
    }*/

    public static function getUsernameBySession()
    {
        if (Yii::$app->user->isGuest) {
            return null;
        }

        return Yii::$app->user->identity->username;
    }

    public static function getListIdJabatanBawahan()
    {
        if (User::isPegawai() == false) {
            return null;
        }

        $query = Jabatan::find();
        $query->andWhere([
            'id_induk' => User::getIdJabatanBerlaku()
        ]);

        $list = [];
        foreach ($query->all() as $jabatan) {
            $list[] = $jabatan->id;
        }

        return $list;
    }

    public static function getIdJabatanBerlaku($params=[])
    {
        if (Yii::$app->request->isConsoleRequest) {
            return null;
        }

        if (User::isPegawai() == false) {
            return null;
        }

        $bulan = date('n');
        $tahun = date('Y');

        if(@$params['bulan'] != null) {
            $bulan = @$params['bulan'];
        }

        if(@$params['tahun'] != null) {
            $tahun = @$params['tahun'];
        }

        $datetime = \DateTime::createFromFormat('Y-n-d', $tahun.'-'.$bulan.'-01');
        $tanggal = $datetime->format('Y-m-15');

        /*
        if($bulan < 10) {
            $bulan .= '0'.$bulan;
        }

        $tanggal = $tahun.'-'.$bulan.'-15';
        */

        $pegawai = User::getModelPegawai();

        if ($pegawai == null) {
            return null;
        }

        $instansiPegawaiBerlaku = $pegawai->getInstansiPegawaiBerlaku($tanggal);

        $id_jabatan_list = [];

        if($instansiPegawaiBerlaku !== null) {
            $id_jabatan_list[] = $instansiPegawaiBerlaku->id_jabatan;
        }

        $instansiPegawaiBerlakuPlt = $pegawai->getInstansiPegawaiBerlakuPlt($tanggal);

        if($instansiPegawaiBerlakuPlt !== null) {
            $id_jabatan_list[] = $instansiPegawaiBerlakuPlt->id_jabatan;
        }

        return $id_jabatan_list;
    }

    /**
     * @return Pegawai
     */
    public static function getModelPegawai()
    {
        return @Yii::$app->user->identity->pegawai;
    }

    public static function getListIdInstansiUnit()
    {
        $user = static::findBySession();
        if ($user->instansi !== null) {
            return array_merge(
                [$user->instansi->id],
                $user->instansi->getManySub()->select('id')->column()
            );
        }
        return [];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'id_user_role'], 'required'],
            [['username'], 'unique', 'message' => '{attribute} sudah ada yang menggunakan'],
            [['id_user_role'], 'safe'],
            [['id_pegawai', 'id_instansi', 'id_grup', 'force_logout'], 'integer'],
            [['username', 'password', 'auth_key', 'access_token'], 'string', 'max' => 255],
            [['imei'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user_role' => 'Role',
            'id_opd' => 'Opd',
            'id_pegawai' => 'Pegawai',
            'id_instansi' => 'Perangkat Daerah',
            'id_grup' => 'Grup',
            'username' => 'Username',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRole()
    {
        return $this->hasOne(UserRole::className(), ['id' => 'id_user_role']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpd()
    {
        return $this->hasOne(Instansi::className(), ['id' => 'id_opd']);
    }

    public function getGrup()
    {
        return $this->hasOne(Grup::className(), ['id' => 'id_grup']);
    }

    public function getManyGrupPegawai()
    {
        return $this->hasMany(GrupPegawai::className(), ['id_grup' => 'id'])
            ->via('grup');
    }

    public function getManyUserInstansi()
    {
        return $this->hasMany(UserInstansi::className(), ['id_user' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function setPasswordHash()
    {
        $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        return true;
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::className(), ['id' => 'id_pegawai']);
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::className(), ['id' => 'id_instansi']);
    }

    public function findAllKegiatanBulananBreakdown()
    {
        $query = \app\modules\kinerja\models\KegiatanBulananBreakdown::find();
        $query->andWhere(['kegiatanTahunan.pns_id' => $this->id]);

        return $query->all();
    }

    public function findAllKegiatanTahunanDetil()
    {
        $query = \app\modules\kinerja\models\KegiatanTahunanDetil::find();
        $query->joinWith(['kegiatanTahunan']);
        $query->andWhere(['kegiatan_tahunan.pns_id' => $this->id]);
        $query->andWhere(['bulan' => 1]);
        //$query->andWhere(['bulan' => \app\models\User::getBulan()]);

        return $query->all();
    }

    public function findAbsensiUser()
    {
        $model = \app\modules\absensi\models\User::findOne(['nip' => $this->username]);

        if ($model === null) {
            $model = new \app\modules\absensi\models\User;
            $model->username = $this->username;
            $model->password = Yii::$app->getSecurity()->generatePasswordHash($this->username);
            $model->save();
        }

        return $model;

    }

    public function getTunjanganKinerja()
    {
        $tunjangan = $this->findTunjanganKinerja();
        $this->_jumlah_tunjangan_kinerja = $tunjangan->jumlah_tunjangan;

        return $this->_jumlah_tunjangan_kinerja;
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
            'nip' => $this->username,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'jenis' => $jenis,
        ]);

        $model = $query->one();

        if ($model === null) {
            $model = new \app\modules\kinerja\models\UserTunjangan;
            $model->nip = $this->username;
            $model->bulan = $bulan;
            $model->tahun = $tahun;
            $model->jenis = $jenis;

            $model->save();
        }

        return $model;
    }

    public static function getBulan()
    {
        if (is_a(Yii::$app, 'yii\console\Application')) {
            return date('n');
        }
        return Yii::$app->session->get('bulan', date('n'));
    }

    public static function getTahun()
    {
        if (is_a(Yii::$app, 'yii\console\Application')) {
            return date('Y');
        }
        return Yii::$app->session->get('tahun', date('Y'));
    }

    public function getTunjanganAbsensi()
    {
        $tunjangan = $this->findTunjanganAbsensi();
        $this->_jumlah_tunjangan_absensi = $tunjangan->jumlah_tunjangan;

        return $this->_jumlah_tunjangan_absensi;
    }

    public function findTunjanganAbsensi($params = [])
    {
        return $this->findTunjangan('absensi', $params);
    }

    public function getTunjanganTotal()
    {
        return $this->_jumlah_tunjangan_kinerja + $this->_jumlah_tunjangan_absensi;
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
        $query->andWhere(['nip' => $this->username]);

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

    /* Fungsi untuk tunjangan */

    public function getJumlahCuti($params)
    {
        $params['jenis'] = \app\modules\absensi\models\KeteranganJenis::CUTI;

        $query = $this->queryKeterangan($params);

        return $query->count();
    }

    public function getPokokTunjangan()
    {
        /*        $model = \app\modules\kinerja\models\GradeTunjangan::findOne(['id' => $this->grade]);

        if ($model!==null)
        return $model->tunjangan;
        else*/
        return 0;

    }

    public function findAllJamKerja($tanggal)
    {
        $dateTime = date_create($tanggal);

        $query = \app\modules\absensi\models\JamKerja::find();
        $query->andWhere(['hari' => $dateTime->format('N')]);

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

    public function findAbsensiRekap($params = [])
    {
        $bulan = \app\models\User::getBulan();
        $tahun = \app\models\User::getTahun();

        if (!empty($params['bulan'])) {
            $bulan = $params['bulan'];
        }

        if (!empty($params['tahun'])) {
            $bulan = $params['tahun'];
        }

        $query = \app\modules\absensi\models\Rekap::find();

        $query->andWhere([
            'nip' => $this->username,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        $model = $query->one();

        if ($model === null) {
            $model = new \app\modules\absensi\models\Rekap;
            $model->nip = $this->username;
            $model->bulan = $bulan;
            $model->tahun = $tahun;

            $model->save();

        }

        return $model;
    }

    public function getListSubordinat()
    {
        return ArrayHelper::map(
            Pegawai::find()
                ->andWhere(
                    [
                        'kode_pegawai_atasan' => Yii::$app->user->identity->kode_pegawai,
                    ]
                )
                ->all(),
            'id',
            'refPegawai.nama'
        );
    }

    public function visibleIdPegawai()
    {
        if ($this->id_user_role == UserRole::PEGAWAI) {
            return true;
        }

        return false;
    }

    public function visibleIdInstansi()
    {
        if ($this->id_user_role == UserRole::INSTANSI) {
            return true;
        }

        if ($this->id_user_role == UserRole::ADMIN_INSTANSI) {
            return true;
        }

        if ($this->id_user_role == UserRole::OPERATOR_ABSEN) {
            return true;
        }

        return false;
    }

    public function visibleIdGrup()
    {
        if ($this->id_user_role == UserRole::GRUP) {
            return true;
        }

        return false;
    }

    public static function getListIdPegawai()
    {
        $user = User::findBySession();

        $output = [];
        foreach ($user->manyGrupPegawai as $grupPegawai) {
            $output[] = $grupPegawai->id_pegawai;
        }

        return $output;
    }

    public function findOrCreateUserMenu($params = [])
    {
        @$params['id_user'] = $this->id;

        return UserMenu::findOrCreate($params);
    }

    public static function isKepala()
    {
        if(User::isPegawai()) {
            $pegawai = User::findPegawai();

            if($pegawai != null) {
                $instansiPegawai = $pegawai->getInstansiPegawai(date('Y-m-d'))->one();
                $jabatanKepala = @$instansiPegawai->instansi->getManyJabatanKepala()->one();

                if ($jabatanKepala) {
                    /** @var Pegawai $instansiPegawaiKepala */
                    $instansiPegawaiKepala = $jabatanKepala->getManyInstansiPegawai()
                        ->orderBy(['tanggal_mulai'=>SORT_DESC])
                        ->one();

                    if ($instansiPegawaiKepala) {
                        if(@$instansiPegawaiKepala->pegawai->id == $pegawai->id) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    public static function redirectDefaultPassword()
    {
        if(User::isPegawai() == false) {
            return false;
        }

        $password = @Yii::$app->user->identity->pegawai->nip;
        $hash = @Yii::$app->user->identity->password;

        if($password == null) {
            return false;
        }

        if(Yii::$app->getSecurity()->validatePassword($password, $hash) == true) {
            Yii::$app->session->setFlash('danger','Anda masih menggunakan password default/NIP. Silahkan ubah password terlebih dahulu.');
            return Yii::$app->controller->redirect(['/user/change-password','is_password_default'=>1]);
        }

    }

    public function getListImei()
    {
        $imei = explode(',', $this->imei);

        $list = '';
        foreach ($imei as $value) {
            $list .= $value."\n";
        }

        if($list == "\n"){
            $list = null;
        }

        return $list;
    }

    public function updateOrcreateUserMenu()
    {
        foreach ($this->userRole->findAllUserRoleMenu() as $userRoleMenu) {
            $userMenu = $this->findOrCreateUserMenu([
                'id_user_role_menu' => $userRoleMenu->id,
                'path' => $userRoleMenu->path
            ]);
            $userMenu->updateAttributes([
                'path' => $userRoleMenu->path,
            ]);
        }
    }

    public function findAllUserMenu()
    {
        $query = UserMenu::find();
        $query->andWhere(['id_user' => $this->id]);

        return $query->all();
    }

}
