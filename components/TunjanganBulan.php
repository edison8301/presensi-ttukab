<?php

namespace app\components;

use app\models\InstansiJenis;
use app\models\InstansiLokasi;
use app\models\InstansiPegawai;
use app\models\PegawaiSertifikasi;
use app\models\TunjanganInstansiJenisJabatanKelas;
use app\modules\tukin\models\Instansi;
use yii\base\Model;

/**
 * @property InstansiPegawai $instansiPegawai
 */
class TunjanganBulan extends Model
{
	public $id_pegawai;
	public $bulan;
	public $tahun;
	public $datetime;
	public $pegawai;
	public $_array_checkinout;
	public $nama_jabatan;
	public $instansi;
	public $instansiPegawai;
	public $_kategoriTunjanganInstansiJenisJabatanKelas;
    /**
     * @var mixed
     */
    private $_tunjanganInstansiJenisJabatanKelas;

    public function hitung()
	{
		\DateTime::createFromFormat('Y-n',$this->tahun.'-'.$this->bulan);
		$this->setArrayCheckinout();
	}

	public function setArrayCheckinout()
	{

	}

	public function getKategoriTunjanganInstansiJenisJabatanKelas()
    {
        if($this->_kategoriTunjanganInstansiJenisJabatanKelas !== null) {
            return $this->_kategoriTunjanganInstansiJenisJabatanKelas;
        }

        $bulan = $this->bulan;
        $tahun = $this->tahun;

        if($bulan === null) {
            $bulan = date('n');
        }

        if($tahun === null) {
            $tahun = date('Y');
        }

        $datetime = \DateTime::createFromFormat('Y-n', $tahun.'-'.$bulan);
        $tanggal = $datetime->format('Y-m-15');

        $nama_jabatan = @$this->instansiPegawai->nama_jabatan;
        $id_instansi_lokasi = @$this->instansiPegawai->instansi->id_instansi_lokasi;
        $id_instansi = @$this->instansiPegawai->id_instansi;
        $id_pegawai = $this->instansiPegawai->id_pegawai;

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
                    $kategori = TunjanganInstansiJenisJabatanKelas::KATEGORI_GURU_BERSERTIFIKASI_LEPAR_PONGOK_SELAT_NASIK;
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

        $this->_kategoriTunjanganInstansiJenisJabatanKelas = $kategori;
        return $this->_kategoriTunjanganInstansiJenisJabatanKelas;

    }

    public function getTunjanganInstansiJenisJabatanKelas()
    {
        if($this->_tunjanganInstansiJenisJabatanKelas !== null) {
            return $this->_tunjanganInstansiJenisJabatanKelas;
        }

        $kategori = $this->getKategoriTunjanganInstansiJenisJabatanKelas();
        $id_instansi = @$this->instansiPegawai->id_instansi;
        $id_jenis_jabatan = @$this->instansiPegawai->jabatan->id_jenis_jabatan;
        $kelas_jabatan = @$this->instansiPegawai->jabatan->kelas_jabatan;

        if($this->instansiPegawai->instansi->id_instansi_jenis != InstansiJenis::UTAMA) {
            $id_instansi = $this->instansiPegawai->instansi->id_induk;
        }

        if($kategori === null) {
            return null;
        }

        if($id_instansi == null) {
            return null;
        }

        if($id_jenis_jabatan == null) {
            return null;
        }

        if($kelas_jabatan == null) {
            return null;
        }

        $query = TunjanganInstansiJenisJabatanKelas::find();

        $query->andWhere([
            'kategori' => $kategori,
            'id_instansi' => $id_instansi,
            'id_jenis_jabatan' => $id_jenis_jabatan,
            'kelas_jabatan' => $kelas_jabatan
        ]);

        $this->_tunjanganInstansiJenisJabatanKelas = $query->one();
        return $this->_tunjanganInstansiJenisJabatanKelas;
    }


}
