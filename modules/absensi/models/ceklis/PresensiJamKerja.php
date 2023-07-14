<?php


namespace app\modules\absensi\models\ceklis;


use app\models\Pegawai;
use app\modules\absensi\models\JamKerja;
use app\modules\absensi\models\KetidakhadiranCeklis;
use app\modules\absensi\models\KetidakhadiranJamKerja;
use app\modules\absensi\models\KetidakhadiranPanjang;
use app\modules\absensi\models\PresensiCeklis;
use app\modules\absensi\models\ShiftKerja;
use DateTime;
use yii\base\Component;
use yii\helpers\Html;
use function implode;

/**
 *
 * @property string $nama
 * @property string $range
 * @property void $potongan
 *
 * @property KetidakhadiranCeklis $ketidakhadiranCeklis
 * @property Pegawai $pegawai
 * @property string $tanggal
 * @property ShiftKerja $shiftKerja
 * @property string $stringCheckinout
 * @property PresensiCeklis $presensiCeklis
 * @property string $button
 * @property DateTime $date
 */
class PresensiJamKerja extends Component
{
    /**
     * @var PresensiHari
     */
    public $presensiHari;
    /**
     * @var JamKerja
     */
    public $jamKerja;
    protected $_modelKetidakhadiranCeklis = false;

    public function getNama()
    {
        return $this->jamKerja->jamKerjaJenis->nama;
    }

    public function getStringCheckinout()
    {
        if (($ketidakhadiran = $this->getKetidakhadiranPanjang(true))) {
            return $ketidakhadiran->ketidakhadiranPanjangJenis->nama;
        }
        if (($ketidakhadiran = $this->getKetidakhadiranJamKerja(true))) {
            return $ketidakhadiran->ketidakhadiranJamKerjaJenis->nama;
        }

        if ($this->presensiHari->isHariLibur()) {
            return 'Hari Libur';
        }

        return $this->getPotongan() === 0 ? 'Hadir' : 'Tidak Hadir';
    }

    /**
     * @param bool $setuju
     * @return KetidakhadiranJamKerja|false
     */
    public function getKetidakhadiranJamKerja($setuju = true)
    {
        $result = $this->presensiCeklis
            ->hasKetidakhadiranJamKerja($this->tanggal, $this->jamKerja->id);
        if ($result) {
            if (!$setuju || ($setuju && $result->isDisetujui)) {
                return $result;
            }
        }
        return false;
    }

    public function getPotongan()
    {
        if ($this->getKetidakhadiranPanjang(true)) {
            return 0;
        }
        if ($this->getKetidakhadiranJamKerja(true)) {
            return 0;
        }
        return $this->getKetidakhadiranCeklis() !== null ? 4 : 0;
    }

    public function getKetidakhadiranCeklis()
    {
        if ($this->_modelKetidakhadiranCeklis === false) {
            $this->_modelKetidakhadiranCeklis = KetidakhadiranCeklis::findOne([
                'id_pegawai' => $this->pegawai->id,
                'tanggal' => $this->tanggal,
                'id_jam_kerja' => $this->jamKerja->id,
            ]);
        }
        return $this->_modelKetidakhadiranCeklis;
    }

    public function getPresensiCeklis()
    {
        return $this->presensiHari->presensiCeklis;
    }

    public function getRange()
    {
        return $this->jamKerja->getRangeAbsensi();
    }

    public function getShiftKerja()
    {
        return $this->presensiHari->shiftKerja;
    }

    public function getPegawai()
    {
        return $this->presensiHari->pegawai;
    }

    public function getDate()
    {
        return $this->presensiHari->date;
    }

    public function getTanggal()
    {
        return $this->presensiHari->tanggal;
    }

    public function getButton()
    {
        $ketidakhadiran = $this->getKetidakhadiranJamKerja(false);
        if (!$ketidakhadiran) {
            if (KetidakhadiranJamKerja::accessCreate()) {
                return Html::a('<i class="fa fa-plus"></i>', [
                    '/absensi/ketidakhadiran-jam-kerja/create',
                    'id_pegawai' => $this->pegawai->id,
                    'tanggal' => $this->tanggal,
                    'id_jam_kerja' => $this->jamKerja->id,
                ], ['data-toggle' => 'tooltip', 'title' => 'Ajukan Keterangan']);
            }
            return null;
        }

        $buttons = [
            $ketidakhadiran->getLabelIdKetidakhadiranJamKerjaJenis(),
            $ketidakhadiran->getLabelIdKetidakhadiranJamKerjaStatus()
        ];
        $buttons[] = Html::a('<i class="fa fa-eye"></i>', [
            '/absensi/ketidakhadiran-jam-kerja/view',
            'id' => $ketidakhadiran->id,
        ], ['data-toggle' => 'tooltip', 'title' => 'Lihat Keterangan']);
        if ($ketidakhadiran->accessUpdate()) {
            $buttons[] = Html::a('<i class="fa fa-pencil"></i>', [
                '/absensi/ketidakhadiran-jam-kerja/update',
                'id' => $ketidakhadiran->id,
            ], ['data-toggle' => 'tooltip', 'title' => 'Ubah Keterangan']);
        }
        if ($ketidakhadiran->accessSetSetuju()) {
            $buttons[] = Html::a('<i class="fa fa-check"></i>', [
                '/absensi/ketidakhadiran-jam-kerja/set-setuju',
                'id' => $ketidakhadiran->id,
            ], ['data-toggle' => 'tooltip', 'title' => 'Setujui Pengajuan', 'onclick' => 'return confirm("Yakin akan menyetujui pengajuan?");']);
        }
        if ($ketidakhadiran->accessSetTolak()) {
            $buttons[] = Html::a('<i class="fa fa-remove"></i>', [
                '/absensi/ketidakhadiran-jam-kerja/set-tolak',
                'id' => $ketidakhadiran->id,
            ], ['data-toggle' => 'tooltip', 'title' => 'Tolak Pengajuan', 'onclick' => 'return confirm("Yakin akan menolak pengajuan?");']);
        }
        if ($ketidakhadiran->accessDelete()) {
            $buttons[] = Html::a('<i class="fa fa-trash"></i>', [
                '/absensi/ketidakhadiran-jam-kerja/delete',
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
            );
        }
        return implode(' ', $buttons);
    }

    /**
     * @param bool $setuju
     * @return KetidakhadiranPanjang|false
     */
    public function getKetidakhadiranPanjang($setuju = true)
    {
        $result = $this->presensiCeklis
            ->hasKetidakhadiranPanjang($this->tanggal);
        if ($result) {
            if (!$setuju || ($setuju && $result->isDisetujui)) {
                return $result;
            }
        }
        return false;
    }
}
