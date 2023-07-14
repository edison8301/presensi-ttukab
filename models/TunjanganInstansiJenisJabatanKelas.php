<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

use kartik\editable\Editable;

/**
 * This is the model class for table "tunjangan_unit_jenis_jabatan_kelas".
 *
 * @property int $id
 * @property int $id_instansi
 * @property int $id_jenis_jabatan
 * @property int $kelas_jabatan
 * @property string $nilai_tpp
 */
class TunjanganInstansiJenisJabatanKelas extends \yii\db\ActiveRecord
{
    const KATEGORI_UMUM = 1;
    const KATEGORI_PENGAWAS_SEKOLAH = 2;
    const KATEGORI_KEPALA_SEKOLAH = 3;
    const KATEGORI_GURU_BERSERTIFIKASI = 4;
    const KATEGORI_GURU_NON_SERTIFIKASI = 5;
    const KATEGORI_CALON_GURU = 6;
    const KATEGORI_PENGAWAS_SEKOLAH_LEPAR_PONGOK_SELAT_NASIK = 7;
    const KATEGORI_KEPALA_SEKOLAH_LEPAR_PONGOK_SELAT_NASIK = 8;
    const KATEGORI_GURU_BERSERTIFIKASI_LEPAR_PONGOK_SELAT_NASIK = 9;
    const KATEGORI_GURU_NON_SERTIFIKASI_LEPAR_PONGOK_SELAT_NASIK = 10;
    const KATEGORI_CALON_GURU_LEPAR_PONGOK_SELAT_NASIK = 11;
    const KATEGORI_PENGAWAS_SEKOLAH_PULAU_LEPAR = 12;
    const KATEGORI_KEPALA_SEKOLAH_PULAU_LEPAR = 13;
    const KATEGORI_GURU_BERSERTIFIKASI_PULAU_LEPAR = 14;
    const KATEGORI_GURU_NON_SERTIFIKASI_PULAU_LEPAR = 15;
    const KATEGORI_CALON_GURU_PULAU_LEPAR = 16;
    const KATEGORI_KEPALA_RSUD_DOKTER_SPESIALIS = 17;
    const KATEGORI_KEPALA_RSUD = 18;
    const KATEGORI_DOKTER_SUBSPESIALIS = 19;
    const KATEGORI_DOKTER_SPESIALIS = 20;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tunjangan_instansi_jenis_jabatan_kelas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_instansi', 'kategori', 'id_jenis_jabatan', 'kelas_jabatan', 'nilai_tpp'], 'required'],
            [['id_instansi', 'id_jenis_jabatan', 'kelas_jabatan', 'beban_kerja_persen', 'prestasi_kerja_persen', 'kondisi_kerja_persen', 'tempat_bertugas_persen', 'kelangkaan_profesi_persen'], 'integer'],
            [['nilai_tpp'], 'number'],
            [['kelas_jabatan'], 'unique',
                'targetAttribute' => ['id_instansi','id_jenis_jabatan','kelas_jabatan', 'kategori'],
                'message' => '{attribute} sudah pernah ditambahkan'
            ],
            [['kategori'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_instansi' => 'Unit',
            'id_jenis_jabatan' => 'Jenis Jabatan',
            'kelas_jabatan' => 'Kelas Jabatan',
            'nilai_tpp' => 'Nilai TPP',
            'beban_kerja_persen' => 'Persen Beban Kerja',
            'prestasi_kerja_persen' => 'Persen Prestasi Kerja',
            'kondisi_kerja_persen' => 'Persen Kondisi Kerja',
            'tempat_bertugas_persen' => 'Persen Tempat Bertugas',
            'kelangkaan_profesi_persen' => 'Persen Kelangkaan Profesi',
        ];
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class,['id'=>'id_instansi']);
    }

    public function getNamaJenisJabatan()
    {
        if($this->id_jenis_jabatan == 1) {
            return "Struktural";
        }

        if($this->id_jenis_jabatan == 2) {
            return "Fungsional";
        }

        if($this->id_jenis_jabatan == 3) {
            return  "Pelaksana";
        }

        return "N/A";
    }

    public function getEditable($atribut)
    {
        return Editable::widget([
            'model' => $this,
            'name' => $atribut,
            'value' => $this->$atribut,
            'asPopover' => true,
            'valueIfNull' => '[...]',
            'formOptions' => ['action' => ['tunjangan-instansi-jenis-jabatan-kelas/editable-update', 'id' => $this->id]],
            'inputType' => Editable::INPUT_TEXT,
            'options' => ['class'=>'form-control'],
            'placement' => 'top',
        ]);
    }

    public function getBesaran($atribut, $nilaiTpp = null)
    {
        if($nilaiTpp == null) {
            $nilaiTpp = 0;
        }

        return $nilaiTpp * ($this->$atribut/100);
    }

    public function getNamaKategori()
    {
        $array = static::findArrayKategori();
        return @$array[$this->kategori];
    }

    public static function findArrayKategori()
    {
        return [
            static::KATEGORI_UMUM => 'UMUM',
            static::KATEGORI_PENGAWAS_SEKOLAH => 'PENGAWAS_SEKOLAH',
            static::KATEGORI_KEPALA_SEKOLAH => 'KEPALA_SEKOLAH',
            static::KATEGORI_GURU_BERSERTIFIKASI => 'GURU_BERSERTIFIKASI',
            static::KATEGORI_GURU_NON_SERTIFIKASI => 'GURU_NON_SERTIFIKASI',
            static::KATEGORI_CALON_GURU => 'CALON_GURU',
            static::KATEGORI_PENGAWAS_SEKOLAH_LEPAR_PONGOK_SELAT_NASIK => 'PENGAWAS_SEKOLAH_LEPAR_PONGOK_SELAT_NASIK',
            static::KATEGORI_KEPALA_SEKOLAH_LEPAR_PONGOK_SELAT_NASIK => 'KEPALA_SEKOLAH_LEPAR_PONGOK_SELAT_NASIK',
            static::KATEGORI_GURU_BERSERTIFIKASI_LEPAR_PONGOK_SELAT_NASIK => 'GURU_BERSERTIFIKASI_LEPAR_PONGOK_SELAT_NASIK',
            static::KATEGORI_GURU_NON_SERTIFIKASI_LEPAR_PONGOK_SELAT_NASIK => 'GURU_NON_SERTIFIKASI_LEPAR_PONGOK_SELAT_NASIK',
            static::KATEGORI_CALON_GURU_LEPAR_PONGOK_SELAT_NASIK => 'CALON_GURU_LEPAR_PONGOK_SELAT_NASIK',
            static::KATEGORI_PENGAWAS_SEKOLAH_PULAU_LEPAR => 'PENGAWAS_SEKOLAH_PULAU_LEPAR',
            static::KATEGORI_KEPALA_SEKOLAH_PULAU_LEPAR => 'KEPALA_SEKOLAH_PULAU_LEPAR',
            static::KATEGORI_GURU_BERSERTIFIKASI_PULAU_LEPAR => 'GURU_BERSERTIFIKASI_PULAU_LEPAR',
            static::KATEGORI_GURU_NON_SERTIFIKASI_PULAU_LEPAR => 'GURU_NON_SERTIFIKASI_PULAU_LEPAR',
            static::KATEGORI_CALON_GURU_PULAU_LEPAR => 'CALON_GURU_PULAU_LEPAR',
            static::KATEGORI_KEPALA_RSUD_DOKTER_SPESIALIS => 'KEPALA_RSUD_DOKTER_SPESIALIS',
            static::KATEGORI_KEPALA_RSUD => 'KEPALA_RSUD',
            static::KATEGORI_DOKTER_SUBSPESIALIS => 'DOKTER_SUBSPESIALIS',
            static::KATEGORI_DOKTER_SPESIALIS => 'DOKTER_SPESIALIS',
        ];
    }

    public static function findKategori($params = [])
    {
        $bulan = @$params['bulan'];
        $tahun = @$params['tahun'];
        $jabatan = @$params['jabatan'];
        $instansi = @$params['instansi'];
        $pegawai = @$params['pegawai'];

        $datetime = \DateTime::createFromFormat('Y-n-d', $tahun.'-'.$bulan.'-01');
        $tanggal = $datetime->format('Y-m-15');

        $nama_jabatan = @$jabatan->nama;
        $id_instansi_lokasi = @$instansi->id_instansi_lokasi;
        $id_instansi = @$instansi->id;
        $id_pegawai = $pegawai->id;

        $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_UMUM;

        if (substr($nama_jabatan, 0, 4) == 'Guru') {

            $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_GURU_NON_SERTIFIKASI;

            if($id_instansi_lokasi == InstansiLokasi::LEPAR_PONGOK_DAN_SELAT_NASIK) {
                $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_GURU_NON_SERTIFIKASI_LEPAR_PONGOK_SELAT_NASIK;
            }

            if($id_instansi_lokasi == InstansiLokasi::PULAU_LEPAR) {
                $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_GURU_NON_SERTIFIKASI_PULAU_LEPAR;
            }

            $querySertifikasi = PegawaiSertifikasi::find();
            $querySertifikasi->andWhere([
                'id_pegawai' => $id_pegawai,
                'id_pegawai_sertifikasi_jenis' => 1
            ]);
            $querySertifikasi->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
                ':tanggal' => $tanggal
            ]);

            $modelSertifikasi = $querySertifikasi->one();

            if($modelSertifikasi !== null) {
                $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_GURU_BERSERTIFIKASI;

                if($id_instansi_lokasi == InstansiLokasi::LEPAR_PONGOK_DAN_SELAT_NASIK) {
                    $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_GURU_BERSERTIFIKASI_LEPAR_PONGOK_SELAT_NASIK;
                }

                if($id_instansi_lokasi == InstansiLokasi::PULAU_LEPAR) {
                    $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_GURU_BERSERTIFIKASI_PULAU_LEPAR;
                }
            }
        }

        if (substr($nama_jabatan, 0, 10) == 'Calon Guru') {

            $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_CALON_GURU;

            if($id_instansi_lokasi == InstansiLokasi::LEPAR_PONGOK_DAN_SELAT_NASIK) {
                $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_CALON_GURU_LEPAR_PONGOK_SELAT_NASIK;
            }

            if($id_instansi_lokasi == InstansiLokasi::PULAU_LEPAR) {
                $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_CALON_GURU_PULAU_LEPAR;
            }
        }

        if (substr($nama_jabatan, 0, 14) == 'Kepala Sekolah') {

            $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_KEPALA_SEKOLAH;

            if($id_instansi_lokasi == InstansiLokasi::LEPAR_PONGOK_DAN_SELAT_NASIK) {
                $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_KEPALA_SEKOLAH_LEPAR_PONGOK_SELAT_NASIK;
            }

            if($id_instansi_lokasi == InstansiLokasi::PULAU_LEPAR) {
                $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_KEPALA_SEKOLAH_PULAU_LEPAR;
            }
        }

        if (substr($nama_jabatan, 0, 16) == 'Pengawas Sekolah') {

            $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_PENGAWAS_SEKOLAH;

            if($id_instansi_lokasi == InstansiLokasi::LEPAR_PONGOK_DAN_SELAT_NASIK) {
                $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_PENGAWAS_SEKOLAH_LEPAR_PONGOK_SELAT_NASIK;
            }

            if($id_instansi_lokasi == InstansiLokasi::PULAU_LEPAR) {
                $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_PENGAWAS_SEKOLAH_PULAU_LEPAR;
            }
        }

        if (
            (substr($nama_jabatan, 0, 14) == 'Direktur Utama' AND $id_instansi == \app\models\Instansi::RSJD)
            OR (substr($nama_jabatan, 0, 8) == 'Direktur' AND $id_instansi == \app\models\Instansi::RSUD)
            OR (substr($nama_jabatan, 0, 8) == 'Direktur' AND $id_instansi == \app\models\Instansi::RSJD)
            //OR (substr($nama_jabatan, 0, 14) == 'Wakil Direktur' AND $id_instansi == Instansi::RSUD_BARU)
            OR (substr($nama_jabatan, 0, 8) == 'Direktur' AND $id_instansi == Instansi::RSUD_BARU)
        ) {
            $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_KEPALA_RSUD;

            $querySertifikasi = PegawaiSertifikasi::find();
            $querySertifikasi->andWhere([
                'id_pegawai' => $id_pegawai,
                'id_pegawai_sertifikasi_jenis' => 2
            ]);
            $querySertifikasi->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
                ':tanggal' => $tanggal
            ]);

            $modelSertifikasi = $querySertifikasi->one();

            if($modelSertifikasi !== null) {
                $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_KEPALA_RSUD_DOKTER_SPESIALIS;
            }

        }

        if (substr($nama_jabatan, 0, 16) == 'Dokter Spesialis') {
            $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_DOKTER_SPESIALIS;
        }

        if (substr($nama_jabatan, 0, 19) == 'Dokter Subspesialis') {
            $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_DOKTER_SUBSPESIALIS;
        }

        return $kategori;
    }

    /**
     * @param array $params
     * @return TunjanganInstansiJenisJabatanKelas|void|null
     */
    public static function findOneByParams($params = [])
    {
        /* @var $instansi \app\models\Instansi */
        $instansi = @$params['instansi'];
        if($instansi === null) {
            return null;
        }

        /* @var $jabatan \app\models\Jabatan */
        $jabatan =  @$params['jabatan'];
        if($jabatan === null) {
            return null;
        }

        $kategori = static::findKategori($params);
        $kelas_jabatan = $jabatan->kelas_jabatan;
        $id_jenis_jabatan = $jabatan->id_jenis_jabatan;
        $id_instansi = $instansi->id;

        if (@$params['kelas_jabatan'] != null) {
            $kelas_jabatan = @$params['kelas_jabatan'];
        }

        if($kategori == null) {
            return null;
        }

        if($kelas_jabatan == null) {
            return null;
        }

        if($id_jenis_jabatan == null) {
            return null;
        }

        if($id_instansi == null) {
            return null;
        }

        if($instansi->id_instansi_jenis != InstansiJenis::UTAMA) {
            $id_instansi = $instansi->id_induk;
        }

        if($instansi->id_instansi_jenis == InstansiJenis::SEKOLAH) {
            $id_instansi = Instansi::DINAS_PENDIDIKAN;
        }

        if($instansi->id == Instansi::RSUD) {
            $id_instansi = Instansi::DINAS_KESEHATAN;
        }

        if($instansi->id == Instansi::RSJD) {
            $id_instansi = Instansi::DINAS_KESEHATAN;
        }

        $query = TunjanganInstansiJenisJabatanKelas::find();

        $query->andWhere([
            'kategori' => $kategori,
            'id_instansi' => $id_instansi,
            'id_jenis_jabatan' => $id_jenis_jabatan,
            'kelas_jabatan' => $kelas_jabatan
        ]);

        return $query->one();
    }
}
