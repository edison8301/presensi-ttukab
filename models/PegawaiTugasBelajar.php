<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pegawai_tugas_belajar".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $semester
 * @property string $indeks_prestasi
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Pegawai $pegawai
 */
class PegawaiTugasBelajar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_tugas_belajar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'semester', 'indeks_prestasi', 'tanggal_mulai', 'tanggal_selesai'], 'required'],
            [['id_pegawai', 'semester'], 'integer'],
            [['indeks_prestasi'], 'number'],
            [['tanggal_mulai', 'tanggal_selesai', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Pegawai',
            'semester' => 'Semester',
            'indeks_prestasi' => 'Indeks Prestasi',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }

    /**
     * @return string[]
     */
    public static function getListSemester(): array
    {
        return [
            0 => 'Baru Masuk',
            1 => 'Semester 1',
            2 => 'Semester 2',
            3 => 'Semester 3',
            4 => 'Semester 4',
            5 => 'Semester 5',
            6 => 'Semester 6',
            7 => 'Semester 7',
            8 => 'Semester 8',
        ];
    }

    /**
     * @return string
     */
    public function getLabelSemester(): string
    {
        return @$this->getListSemester()[$this->semester];
    }

    /**
     * @return int
     */
    public function getPersen(): int
    {
        if ($this->semester == 0) {
            return 50;
        }

        if ($this->indeks_prestasi >= 2.76 AND $this->indeks_prestasi <= 3.00) {
            return 30;
        }

        if ($this->indeks_prestasi >= 3.01 AND $this->indeks_prestasi <= 3.50) {
            return 50;
        }

        if ($this->indeks_prestasi >= 3.51 AND $this->indeks_prestasi <= 4.00) {
            return 70;
        }

        return 0;
    }
}
