<?php


namespace app\components;


use app\models\Instansi;
use app\models\InstansiJenis;
use app\models\UserRole;
use app\modules\absensi\models\MesinAbsensi;
use Yii;

class Session
{
    public static function getIdUserRole()
    {
        if (Yii::$app->request->isConsoleRequest) {
            return null;
        }

        return @Yii::$app->user->identity->id_user_role;
    }

    public static function getIdUser()
    {
        return @Yii::$app->user->identity->id;
    }

    public static function isAdmin()
    {
        return @Yii::$app->user->identity->id_user_role==UserRole::ADMIN;
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
        if (Session::getIdUserRole() == UserRole::ADMIN_INSTANSI) {
            return true;
        }

        return null;
    }

    public static function isOperatorAbsen()
    {
        return Session::getIdUserRole() === UserRole::OPERATOR_ABSEN;
    }

    public static function isOperatorStruktur()
    {
        return Session::getIdUserRole() === UserRole::OPERATOR_STRUKTUR;
    }

    public static function isPegawai()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        return Yii::$app->user->identity->id_user_role == UserRole::PEGAWAI;
    }

    public static function getListSN()
    {
        if(Session::isInstansi() OR Session::isAdminInstansi() OR Session::isOperatorAbsen()) {
            $query = MesinAbsensi::find();
            $query->andWhere([
                'id_instansi'=>Session::getIdInstansi()
            ]);

            $query->select('serialnumber');
            return $query->column();
        }

        return [0];
    }

    public static function getIdInstansi()
    {
        if (Session::isInstansi() || Session::isOperatorAbsen()) {
            return Yii::$app->user->identity->id_instansi;
        }

        if (Session::isAdminInstansi()) {
            return Yii::$app->user->identity->id_instansi;
        }

        return Yii::$app->session->get('id_instansi', null);
    }

    public static function getTahun()
    {
        if (is_a(Yii::$app, 'yii\console\Application')) {
            return date('Y');
        }

        return Yii::$app->session->get('tahun', date('Y'));
    }

    public static function isPemeriksaAbsensi()
    {
        return Session::getIdUserRole() === UserRole::PEMERIKSA_ABSENSI;
    }

    public static function isPemeriksaKinerja()
    {
        return Session::getIdUserRole() === UserRole::PEMERIKSA_KINERJA;
    }

    public static function isPemeriksaIki()
    {
        return Session::getIdUserRole() === UserRole::PEMERIKSA_IKI;
    }

    public static function isMappingRpjmd()
    {
        return Session::getIdUserRole() === UserRole::MAPPING_RPJMD;
    }

    public static function getUsername()
    {
        return @Yii::$app->user->identity->username;
    }

    public static function getIdPegawai()
    {
        return @Yii::$app->user->identity->id_pegawai;
    }

    public static function isVisibleShiftKerja()
    {
        $id_instansi = Session::getIdInstansi();

        if (in_array($id_instansi, [42, 43, 44, 1, 213, 61, 25])) {
            return true;
        }

        $instansi = Instansi::findOne($id_instansi);

        if (Session::isAdminInstansi()) {
            return true;
        }

        if ($instansi->id_instansi_jenis == InstansiJenis::SEKOLAH) {
            return true;
        }

        if (substr($instansi->nama, 0, strlen('UPTD SAMSAT')) == 'UPTD SAMSAT') {
            return true;
        }

        if (substr(self::getUsername(), 0, strlen('OPT-CABDIN PDWIL')) == 'OPT-CABDIN PDWIL') {
            return true;
        }

        return false;
    }

    public static function isKinerjaPP30Aktif()
    {
        if (Session::isAdmin()) {
            return true;
        }

        if (date('Y-m') <= '2023-01') {
            return true;
        }

        return false;
    }
}
