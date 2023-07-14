<?php

namespace app\models;

use app\components\Session;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "pegawai_penghargaan".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $nama
 * @property string $tanggal
 * @property int $id_pegawai_penghargaan_tingkat
 * @property int $id_pegawai_penghargaan_status
 * @property Pegawai $pegawai
 * @property PegawaiPenghargaanStatus $pegawaiPenghargaanStatus
 * @property PegawaiPenghargaanTingkat $pegawaiPenghargaanTingkat
 */
class PegawaiPenghargaan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_penghargaan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'nama', 'tanggal', 'id_pegawai_penghargaan_tingkat'], 'required'],
            [['id_pegawai', 'id_pegawai_penghargaan_status', 'id_pegawai_penghargaan_tingkat'], 'integer'],
            [['tanggal'], 'safe'],
            [['nama'], 'string', 'max' => 255],
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
            'nama' => 'Nama',
            'tanggal' => 'Tanggal',
            'id_pegawai_penghargaan_tingkat' => 'Tingkat Penghargaan',
            'id_pegawai_penghargaan_status' => 'Pegawai Penghargaan Status',
        ];
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }

    public function getPegawaiPenghargaanTingkat()
    {
        return $this->hasOne(PegawaiPenghargaanTingkat::class, ['id' => 'id_pegawai_penghargaan_tingkat']);
    }

    public function getPegawaiPenghargaanStatus()
    {
        return $this->hasOne(PegawaiPenghargaanStatus::class, ['id' => 'id_pegawai_penghargaan_status']);
    }

    public function getLabelPegawaiPenghargaanStatus()
    {
        if ($this->id_pegawai_penghargaan_status == PegawaiPenghargaanStatus::SETUJU) {
            return Html::tag('span', $this->pegawaiPenghargaanStatus->nama, ['class' => 'label label-success']);
        }

        if ($this->id_pegawai_penghargaan_status == PegawaiPenghargaanStatus::PROSES) {
            return Html::tag('span', $this->pegawaiPenghargaanStatus->nama, ['class' => 'label label-warning']);
        }

        if ($this->id_pegawai_penghargaan_status == PegawaiPenghargaanStatus::TOLAK) {
            return Html::tag('span', $this->pegawaiPenghargaanStatus->nama, ['class' => 'label label-danger']);
        }

        return 'N/A';
    }

    public function getLinkViewIcon()
    {
        return Html::a('<i class="fa fa-eye"></i>', [
            '/pegawai-penghargaan/view',
            'id' => $this->id,
        ], [
            'data-toggle' => 'tooltip',
            'title' => 'Lihat',
        ]);
    }

    public function getLinkUpdateIcon()
    {
        return Html::a('<i class="fa fa-pencil"></i>', [
            '/pegawai-penghargaan/update',
            'id' => $this->id,
        ], [
            'data-toggle' => 'tooltip',
            'title' => 'Ubah',
        ]);
    }

    public function getLinkDeleteIcon()
    {
        return Html::a('<i class="fa fa-trash"></i>', [
            '/pegawai-penghargaan/delete',
            'id' => $this->id,
        ], [
            'data-toggle' => 'tooltip',
            'title' => 'Hapus',
            'data-method' => 'post',
            'data-confirm' => 'Yakin ingin menghapus data ini?',
        ]);
    }

    public function getLinkSetSetujuIcon()
    {
        if ($this->canSetuju() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-check"></i>', [
            '/pegawai-penghargaan/set-setuju',
            'id' => $this->id,
        ], [
            'data-toggle' => 'tooltip',
            'title' => 'Setuju',
            'data-confirm' => 'Yakin ingin setujui data ini?',
        ]);
    }

    public function getLinkSetTolakIcon()
    {
        if ($this->canTolak() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-times"></i>', [
            '/pegawai-penghargaan/set-tolak',
            'id' => $this->id,
        ], [
            'data-toggle' => 'tooltip',
            'title' => 'Tolak',
            'data-confirm' => 'Yakin ingin tolak data ini?',
        ]);
    }

    public function canSetuju(): bool
    {
        if (Session::isAdmin()) {
            return true;
        }

        return false;
    }

    public function canTolak(): bool
    {
        if (Session::isAdmin()) {
            return true;
        }

        return false;
    }
}
