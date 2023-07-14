<?php

namespace app\modules\absensi\models;

use Yii;

/**
 * This is the model class for table "jam_kerja".
 *
 * @property integer $id
 * @property integer $id_shift_kerja
 * @property integer $hari
 * @property integer $jenis
 * @property string $nama
 * @property string $jam_mulai_pindai
 * @property string $jam_selesai_pindai
 * @property string $jam_mulai_normal
 * @property integer $jam_selesai_normal
 * @property integer $status_wajib
 * @property string jam_mulai_hitung
 * @property string jam_selesai_hitung
 * @property string jam_minimal_absensi
 * @property string jam_maksimal_absensi
 *
 * @property JamKerjaJenis $jamKerjaJenis
 */
class JamKerja extends \yii\db\ActiveRecord
{
    const MASUK = 1;
    const KELUAR = 2;

    const HARI_LIBUR = 8;

    public $debug = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jam_kerja';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_shift_kerja', 'id_jam_kerja_jenis'], 'required'],
            [['id_shift_kerja', 'hari', 'id_jam_kerja_jenis'], 'integer'],
            [['nama'], 'string', 'max' => 255],
            [['jam_mulai_hitung', 'jam_selesai_hitung', 'jam_minimal_absensi', 'jam_maksimal_absensi'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_shift_kerja' => 'Id Shift Kerja',
            'hari' => 'Hari',
            'id_jam_kerja_jenis' => 'Jenis',
            'nama' => 'Nama',
            'jam_mulai_pindai' => 'Jam Mulai Pindai',
            'jam_selesai_pindai' => 'Jam Selesai Pindai',
            'jam_mulai_normal' => 'Jam Mulai Normal',
            'jam_selesai_normal' => 'Jam Selesai Normal',
        ];
    }

    public function getLabelJenis()
    {
        if ($this->jenis == 1) {
            return "Masuk";
        }

        if ($this->jenis == 2) {
            return "Keluar";
        }

    }

    public function getJamKerjaJenis()
    {
        return $this->hasOne(JamKerjaJenis::className(), ['id' => 'id_jam_kerja_jenis']);
    }

    public function getRangeAbsensi()
    {
        return $this->getJamMinimalAbsensiKeterangan() . ' - ' . $this->getJamMaksimalAbsensiKeterangan();
    }

    public function getJamMinimalAbsensiKeterangan()
    {
        if ($this->jam_minimal_absensi > '24:00:00') {
            $jam = explode(':', $this->jam_minimal_absensi);
            $jamLebih = $jam[0] - 24;
            $jam[0] = (int) $jam[0] - 24;
            return implode(':', $jam) . " (Hari Berikutnya)";
        }
        return $this->jam_minimal_absensi;
    }

    public function getJamMaksimalAbsensiKeterangan()
    {
        if ($this->jam_maksimal_absensi > '24:00:00') {
            $jam = explode(':', $this->jam_maksimal_absensi);
            $jamLebih = $jam[0] - 24;
            $jam[0] = (int) $jam[0] - 24;
            return implode(':', $jam) . " (Hari Berikutnya)";
        }
        return $this->jam_maksimal_absensi;
    }

    public function getJamMulaiHitung(\DateTime $date)
    {
        return $date->format('Y-m-d') . ' ' . $this->jam_mulai_hitung;
    }

    public function getJamSelesaiHitung(\DateTime $date)
    {
        return $date->format('Y-m-d') . ' ' . $this->jam_selesai_hitung;
    }

    public function getJamMinimalAbsensi(\DateTime $date)
    {
        return $date->format('Y-m-d') . ' ' . $this->jam_minimal_absensi;
    }

    public function getJamMaksimalAbsensi(\DateTime $date)
    {
        return $date->format('Y-m-d') . ' ' . $this->jam_maksimal_absensi;
    }

    public function getDateMulaiAbsensi($date)
    {
        if ($this->jam_minimal_absensi > '24:00:00') {
            $jam = explode(':', $this->jam_minimal_absensi);
            $jamLebih = $jam[0] - 24;
            $date->modify('+1 day');
            $jam[0] = (int) $jam[0] - 24;
            $tgl = $date->format('Y-m-d') . ' ' . implode(':', $jam);
            $date->modify('-1 day');
            return date_create($tgl);
        }
        return date_create($date->format('Y-m-d') . ' ' . $this->jam_minimal_absensi);
    }

    public function getDateSelesaiAbsensi($date)
    {
        if ($this->jam_maksimal_absensi > '24:00:00') {
            $jam = explode(':', $this->jam_maksimal_absensi);
            $date->modify('+1 day');
            $jam[0] = (int) $jam[0] - 24;
            $tgl = $date->format('Y-m-d') . ' ' . implode(':', $jam);
            $date->modify('-1 day');
            return date_create($tgl);
        }
        return date_create($date->format('Y-m-d') . ' ' . $this->jam_maksimal_absensi);
    }

    public function getDateMulaiHitung($date)
    {
        if ($this->jam_mulai_hitung > '24:00:00') {
            $jam = explode(':', $this->jam_mulai_hitung);
            $date->modify('+1 day');
            $jam[0] = (int) $jam[0] - 24;
            $tgl = $date->format('Y-m-d') . ' ' . implode(':', $jam);
            $date->modify('-1 day');
            return date_create($tgl);
        }
        return date_create($date->format('Y-m-d') . ' ' . $this->jam_mulai_hitung);
    }

    public function getDateSelesaiHitung($date)
    {
        if ($this->jam_selesai_hitung > '24:00:00') {
            $jam = explode(':', $this->jam_selesai_hitung);
            $date->modify('+1 day');
            $jam[0] = (int) $jam[0] - 24;
            $tgl = $date->format('Y-m-d') . ' ' . implode(':', $jam);
            $date->modify('-1 day');
            return date_create($tgl);
        }
        return date_create($date->format('Y-m-d') . ' ' . $this->jam_selesai_hitung);
    }

    public function getNama()
    {
        return $this->nama;
    }
}
