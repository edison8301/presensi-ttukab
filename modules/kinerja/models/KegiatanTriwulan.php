<?php

namespace app\modules\kinerja\models;

use app\components\Session;
use app\models\User;
use Yii;
use yii\bootstrap\ButtonDropdown;

/**
 * This is the model class for table "kegiatan_triwulan".
 *
 * @property int $id
 * @property int $id_kegiatan_tahunan
 * @property int $id_kegiatan_bulanan
 * @property string $tahun
 * @property int $bulan
 * @property string $target
 * @property string $realisasi
 * @property string $persen_capaian
 * @property string $deskripsi_capaian
 * @property string $kendala
 * @property string $tindak_lanjut
 */
class KegiatanTriwulan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kegiatan_triwulan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_kegiatan_tahunan', 'id_kegiatan_bulanan', 'periode','id_pegawai'], 'integer'],
            [['tahun', 'periode'], 'required'],
            [['tahun'], 'safe'],
            [['persen_capaian'], 'number'],
            [['target', 'realisasi', 'deskripsi_capaian', 'kendala', 'tindak_lanjut',], 'string', 'max' => 255],
            [['target_kuantitas', 'target_kualitas', 'target_waktu', 'target_biaya'], 'string', 'max' => 100],
            [['realisasi_target_kuantitas', 'realisasi_target_kualitas', 'realisasi_target_waktu', 'realisasi_target_biaya'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_kegiatan_tahunan' => 'Kegiatan Tahunan',
            'id_kegiatan_bulanan' => 'Kegiatan Bulanan',
            'tahun' => 'Tahun',
            'periode' => 'Periode',
            'target' => 'Target',
            'realisasi' => 'Realisasi',
            'persen_capaian' => 'Persen Capaian',
            'deskripsi_capaian' => 'Deskripsi Capaian',
            'kendala' => 'Kendala',
            'tindak_lanjut' => 'Tindak Lanjut',
        ];
    }

    public function getKegiatanTahunan()
    {
        return $this->hasOne(KegiatanTahunan::class,['id' => 'id_kegiatan_tahunan']);
    }

    public function getButtonDropdown()
    {
        return ButtonDropdown::widget([
            'label' => '',
            'options' => ['class' => 'btn btn-xs btn-primary btn-flat'],
            'dropdown' => [
                'encodeLabels' => false,
                'items' => [
                    // ['label' => '<i class="fa fa-plus"></i> Tambah Tahapan', 'url' => ['kegiatan-tahunan/create-v2', 'id_pegawai' => Session::getIdPegawai(), 'id_induk' => $this->id], 'visible' => $this->accessCreateSub()],
                    ['label' => '<i class="fa fa-pencil"></i> Ubah', 'url' => ['kegiatan-triwulan/update', 'id' => $this->id]],
                    ['label' => '<i class="fa fa-trash"></i> Hapus', 'url' => ['kegiatan-triwulan/delete', 'id' => $this->id]],
                ],
            ],
        ]);
    }

    public function accessUpdate()
    {
        if (Session::isAdmin()) {
            return true;
        }

        if (Session::isMappingRpjmd()) {
            return true;
        }

        if (User::isPegawai() AND $this->id_pegawai == User::getIdPegawai()) {

            if ($this->kegiatanTahunan->id_kegiatan_status == KegiatanStatus::KONSEP) {
                return true;
            }

            if ($this->kegiatanTahunan->id_kegiatan_status == KegiatanStatus::TOLAK) {
                return true;
            }
        }

        return true;

    }

    public function accessDelete()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isPegawai() AND $this->id_pegawai == User::getIdPegawai()) {

            if ($this->kegiatanTahunan->id_kegiatan_status == KegiatanStatus::KONSEP) {
                return true;
            }

            if ($this->kegiatanTahunan->id_kegiatan_status == KegiatanStatus::TOLAK) {
                return true;
            }
        }

        return false;

    }

    public function findOrCreateKegiatanTriwulan()
    {
        $allKegiatanTriwulan = KegiatanTriwulan::findAll([
            'id_pegawai' => Session::getIdPegawai()
        ]);

        if($allKegiatanTriwulan == null){
            $model = new KegiatanTriwulan();
            // $model->id_kegiatan_tahunan = 
        }
    }

}
