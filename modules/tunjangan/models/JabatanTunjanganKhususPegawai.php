<?php

namespace app\modules\tunjangan\models;

use app\components\Session;
use app\models\Instansi;
use app\models\Pegawai;
use app\modules\absensi\models\HukumanDisiplinJenis;
use Yii;

/**
 * This is the model class for table "jabatan_tunjangan_khusus_pegawai".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $id_instansi
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property string $keterangan
 */
class JabatanTunjanganKhususPegawai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jabatan_tunjangan_khusus_pegawai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_jabatan_tunjangan_khusus_jenis', 'id_pegawai', 'id_instansi', 'tanggal_mulai'], 'required'],
            [['id_jabatan_tunjangan_khusus_jenis', 'id_pegawai', 'id_instansi'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai'], 'safe'],
            [['keterangan'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_jabatan_tunjangan_khusus_jenis' => 'Jenis',
            'id_pegawai' => 'Pegawai',
            'id_instansi' => 'Perangkat Daerah',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'keterangan' => 'Keterangan',
        ];
    }

    /**
     * {@inheritdoc}
     * @return JabatanTunjanganKhususPegawaiQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new JabatanTunjanganKhususPegawaiQuery(get_called_class());
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }

    public function getJabatanTunjanganKhususJenis()
    {
        return $this->hasOne(JabatanTunjanganKhususJenis::class, [
            'id' => 'id_jabatan_tunjangan_khusus_jenis'
        ]);
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class,['id'=>'id_instansi']);
    }

    public function setIdInstansi()
    {
        if($this->pegawai === null) {
            return false;
        }

        $instansiPegawai = $this->pegawai->getInstansiPegawaiBerlaku($this->tanggal_mulai);

        $this->id_instansi = $instansiPegawai->id_instansi;

    }

    public function setTanggalSelesai()
    {
        if($this->tanggal_selesai == null) {
            $this->tanggal_selesai = '9999-12-31';
        }
    }

    public static function query($params=[])
    {
        $id_pegawai = @$params['id_pegawai'];
        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];
        $id_jabatan_tunjangan_khusus_jenis = @$params['id_jabatan_tunjangan_khusus_jenis'];

        if($tahun == null) {
            $tahun = Session::getTahun();
        }

        $query = JabatanTunjanganKhususPegawai::find();
        $query->andWhere([
            'id_pegawai' => $id_pegawai,
            'id_jabatan_tunjangan_khusus_jenis' => $id_jabatan_tunjangan_khusus_jenis
        ]);

        $datetime = \DateTime::createFromFormat('Y-n-d', $tahun.'-'.$bulan.'-01');

        $query->andWhere('tanggal_mulai >= :tanggal_awal AND tanggal_mulai <= :tanggal_akhir
            OR tanggal_selesai >= :tanggal_awal AND tanggal_selesai <= :tanggal_akhir
            OR tanggal_mulai <= :tanggal_awal AND tanggal_selesai >= :tanggal_akhir
        ',[
            ':tanggal_awal' => $datetime->format('Y-m-01'),
            ':tanggal_akhir' => $datetime->format('Y-m-t')
        ]);

        return $query;
    }
}
