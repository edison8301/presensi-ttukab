<?php


namespace app\modules\absensi\models\ceklis;


use app\models\Pegawai;
use app\modules\absensi\models\KetidakhadiranPanjang;
use app\modules\absensi\models\PresensiCeklis;
use app\modules\absensi\models\ShiftKerja;
use DateTime;
use yii\base\Component;
use yii\helpers\Html;
use function array_map;
use function implode;

/**
 *
 * @property string $tanggal
 * @property PresensiJamKerja[] $presensiJamKerja
 * @property ShiftKerja $shiftKerja
 * @property int $hari
 * @property mixed $potongan
 * @property string $stringCheckinout
 * @property Pegawai $pegawai
 */
class PresensiHari extends Component
{
    /**
     * @var DateTime
     */
    public $date;
    /**
     * @var PresensiCeklis
     */
    public $presensiCeklis;
    /**
     * @var PresensiJamKerja[]
     */
    protected $_presensiJamKerja = false;

    public function init()
    {
        if ($this->_presensiJamKerja === false) {
            $this->_presensiJamKerja = [];
            $shiftKerja = $this->getShiftKerja();
            foreach ($shiftKerja->findAllJamKerja($this->hari) as $jamKerja) {
                $this->_presensiJamKerja[] = new PresensiJamKerja([
                    'jamKerja' => $jamKerja,
                    'presensiHari' => $this
                ]);
            }
        }
    }

    public function getShiftKerja()
    {
        return $this->pegawai->getShiftKerjaAktif($this->tanggal);
    }

    public function getStringCheckinout()
    {
        if (($ketidakhadiran = $this->getKetidakhadiranPanjang(true))) {
            return $ketidakhadiran->ketidakhadiranPanjangJenis->nama;
        }

        if ($this->isHariLibur()) {
            return 'Hari Libur';
        }

        if (empty($this->presensiJamKerja)) {
            return 'Tidak ada Jam Kerja';
        }

        if ($this->getPotongan() === 0) {
            return 'Hadir';
        }
        return null;
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

    public function getPotongan()
    {
        return array_sum(
            array_map(
                function (PresensiJamKerja $presensiJamKerja) {
                    return $presensiJamKerja->getPotongan();
                },
                $this->presensiJamKerja
            )
        );
    }

    public function getHari()
    {
        return $this->date->format('N');
    }

    public function getPresensiJamKerja()
    {
        return $this->_presensiJamKerja;
    }

    public function getPegawai()
    {
        return $this->presensiCeklis->pegawai;
    }

    public function getTanggal()
    {
        return $this->date->format('Y-m-d');
    }

    public function getButtons()
    {
        $ketidakhadiran = $this->getKetidakhadiranPanjang(false);
        if (!$ketidakhadiran) {
            return null;
        }
        $buttons = [
            $ketidakhadiran->ketidakhadiranPanjangJenis->getLabelNama(),
            $ketidakhadiran->ketidakhadiranPanjangStatus->getLabelNama()
        ];

        $buttons[] = Html::a('<i class="fa fa-eye"></i>', [
                '/absensi/ketidakhadiran/view',
                'id' => $ketidakhadiran->id,
            ], ['data-toggle' => 'tooltip', 'title' => 'Lihat Keterangan']) . ' ';
        if ($ketidakhadiran->accessUpdate()) {
            $buttons[] = Html::a('<i class="fa fa-pencil"></i>', [
                    '/absensi/ketidakhadiran-panjang/update',
                    'id' => $ketidakhadiran->id,
                ], ['data-toggle' => 'tooltip', 'title' => 'Ubah Keterangan']) . ' ';
        }
        if ($ketidakhadiran->accessSetSetuju()) {
            $buttons[] = Html::a('<i class="fa fa-check"></i>', [
                    '/absensi/ketidakhadiran-panjang/set-setuju',
                    'id' => $ketidakhadiran->id,
                ], ['data-toggle' => 'tooltip', 'title' => 'Setujui Pengajuan', 'onclick' => 'return confirm("Yakin akan menyetujui pengajuan?");']) . ' ';
        }
        if ($ketidakhadiran->accessSetTolak()) {
            $buttons[] = Html::a('<i class="fa fa-remove"></i>', [
                    '/absensi/ketidakhadiran-panjang/set-tolak',
                    'id' => $ketidakhadiran->id,
                ], ['data-toggle' => 'tooltip', 'title' => 'Tolak Pengajuan', 'onclick' => 'return confirm("Yakin akan menolak pengajuan?");']) . ' ';
        }
        if ($ketidakhadiran->accessDelete()) {
            $buttons[] = Html::a('<i class="fa fa-trash"></i>', [
                    '/absensi/ketidakhadiran-panjang/delete',
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

        return implode(' ', $buttons);
    }

    public function isHariLibur()
    {
        $isHariLibur = $this->pegawai->isHariLibur([
            'tanggal' => $this->tanggal,
        ]);

        if ($isHariLibur && $this->shiftKerja && $this->shiftKerja->getIsLiburNasional()) {
            return true;
        }

        return false;
    }
}
