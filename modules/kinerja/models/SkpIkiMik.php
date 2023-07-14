<?php

namespace app\modules\kinerja\models;

use app\components\Session;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "skp_iki_mik".
 *
 * @property int $id
 * @property int $id_skp instansi_pegawai_skp
 * @property int $id_skp_iki kegiatan_tahunan
 * @property string $tujuan
 * @property string $definisi
 * @property string $formula
 * @property string $satuan_pengukuran
 * @property string $kualitas_tingkat_kendali
 * @property string $sumber_data
 * @property string $periode_pelaporan
 * @property KegiatanTahunan $skpIki
 */
class SkpIkiMik extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'skp_iki_mik';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_skp', 'id_skp_iki', 'tujuan', 'definisi', 'satuan_pengukuran', 'kualitas_tingkat_kendali', 'sumber_data', 'periode_pelaporan'], 'required'],
            [['id_skp', 'id_skp_iki'], 'integer'],
            [['tujuan', 'definisi', 'formula'], 'string'],
            [['satuan_pengukuran', 'kualitas_tingkat_kendali', 'sumber_data', 'periode_pelaporan'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_skp' => 'Id Skp',
            'id_skp_iki' => 'Id Skp Iki',
            'tujuan' => 'Tujuan',
            'definisi' => 'Definisi',
            'formula' => 'Formula',
            'satuan_pengukuran' => 'Satuan Pengukuran',
            'kualitas_tingkat_kendali' => 'Kualitas dan Tingkat Kendali',
            'sumber_data' => 'Sumber Data',
            'periode_pelaporan' => 'Periode Pelaporan',
        ];
    }

    public function getSkpIki()
    {
        return $this->hasOne(KegiatanTahunan::class, ['id' => 'id_skp_iki']);
    }

    public static function getListKualitasTingkatKendali()
    {
        return [
            'Outcome' => 'Outcome',
            'Outcome Antara' => 'Outcome Antara',
            'Output Kendali Rendah' => 'Output Kendali Rendah',
        ];
    }

    public static function getListPeriodePelaporan()
    {
        return [
            'Bulanan' => 'Bulanan',
            'Triwulan' => 'Triwulan',
            'Semesteran' => 'Semesteran',
            'Tahunan' => 'Tahunan',
        ];
    }

    public static function findOrCreate(array $params = [])
    {
        $id_skp = $params['id_skp'];
        $id_skp_iki = $params['id_skp_iki'];

        $model = SkpIkiMik::findOne([
            'id_skp_iki' => $id_skp_iki,
        ]);

        if ($model == null) {
            $model = new SkpIkiMik([
                'id_skp' => $id_skp,
                'id_skp_iki' => $id_skp_iki,
            ]);
            $model->save(false);
        }

        return $model;
    }

    public function canUpdate()
    {
        if (Session::isAdmin()) {
            return true;
        }

        if (Session::isPegawai() AND @$this->skpIki->id_pegawai == Session::getIdPegawai()) {
            return true;
        }

        return false;
    }

    public function getLinkUpdateButton()
    {
        if ($this->canUpdate() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-pencil"></i> Ubah Manual Indikator Kinerja', [
            '/kinerja/skp-iki-mik/update',
            'id' => $this->id,
        ], ['class' => 'btn btn-success btn-flat']);
    }
}
