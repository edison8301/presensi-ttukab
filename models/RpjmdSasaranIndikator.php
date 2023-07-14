<?php

namespace app\models;

use app\components\Session;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "rpjmd_sasaran_indikator".
 *
 * @property int $id
 * @property int $id_rpjmd
 * @property int $id_rpjmd_misi
 * @property int $id_rpjmd_tujuan
 * @property string $id_rpjmd_sasaran
 * @property int $id_instansi
 * @property string $nama
 * @property int $jenis
 * @property string $satuan
 * @property string $kondisi_awal
 * @property string $tahun_awal
 * @property string $target_n1
 * @property string $target_n2
 * @property string $target_n3
 * @property string $target_n4
 * @property string $target_n5
 * @property string $target_n6
 * @property string $realisasi_n1
 * @property string $realisasi_n2
 * @property string $realisasi_n3
 * @property string $realisasi_n4
 * @property string $realisasi_n5
 * @property string $realisasi_n6
 * @property string $tingkat_realisasi_n1
 * @property string $tingkat_realisasi_n2
 * @property string $tingkat_realisasi_n3
 * @property string $tingkat_realisasi_n4
 * @property string $tingkat_realisasi_n5
 * @property string $tingkat_realisasi_n6
 * @property int $realisasi_status_n1
 * @property int $realisasi_status_n2
 * @property int $realisasi_status_n3
 * @property int $realisasi_status_n4
 * @property int $realisasi_status_n5
 * @property int $realisasi_status_n6
 * @property string $kondisi_akhir
 * @property string $faktor_penghambat
 * @property string $faktor_pendorong
 * @property string $usulan_tindak_lanjut
 * @property int $status_perubahan
 * @property int $id_model_perubahan
 * @property string $username_pembuat
 * @property string $waktu_dibuat
 * @property string $username_pengubah
 * @property string $waktu_diubah
 */
class RpjmdSasaranIndikator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rpjmd_sasaran_indikator';
    }

    public static function getDb()
    {
        return Yii::$app->get('db_sakip');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_rpjmd', 'id_rpjmd_misi', 'id_rpjmd_tujuan', 'id_instansi', 'jenis', 'realisasi_status_n1', 'realisasi_status_n2', 'realisasi_status_n3', 'realisasi_status_n4', 'realisasi_status_n5', 'realisasi_status_n6', 'status_perubahan', 'id_model_perubahan'], 'integer'],
            [['faktor_penghambat', 'faktor_pendorong', 'usulan_tindak_lanjut'], 'string'],
            [['waktu_dibuat', 'waktu_diubah'], 'safe'],
            [['id_rpjmd_sasaran', 'nama', 'satuan', 'kondisi_awal', 'tahun_awal', 'target_n1', 'target_n2', 'target_n3', 'target_n4', 'target_n5', 'target_n6', 'realisasi_n1', 'realisasi_n2', 'realisasi_n3', 'realisasi_n4', 'realisasi_n5', 'realisasi_n6', 'kondisi_akhir', 'username_pembuat', 'username_pengubah'], 'string', 'max' => 255],
            [['tingkat_realisasi_n1', 'tingkat_realisasi_n2', 'tingkat_realisasi_n3', 'tingkat_realisasi_n4', 'tingkat_realisasi_n5', 'tingkat_realisasi_n6'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_rpjmd' => 'Id Rpjmd',
            'id_rpjmd_misi' => 'Id Rpjmd Misi',
            'id_rpjmd_tujuan' => 'Id Rpjmd Tujuan',
            'id_rpjmd_sasaran' => 'Id Rpjmd Sasaran',
            'id_instansi' => 'Id Instansi',
            'nama' => 'Nama',
            'jenis' => 'Jenis',
            'satuan' => 'Satuan',
            'kondisi_awal' => 'Kondisi Awal',
            'tahun_awal' => 'Tahun Awal',
            'target_n1' => 'Target N1',
            'target_n2' => 'Target N2',
            'target_n3' => 'Target N3',
            'target_n4' => 'Target N4',
            'target_n5' => 'Target N5',
            'target_n6' => 'Target N6',
            'realisasi_n1' => 'Realisasi N1',
            'realisasi_n2' => 'Realisasi N2',
            'realisasi_n3' => 'Realisasi N3',
            'realisasi_n4' => 'Realisasi N4',
            'realisasi_n5' => 'Realisasi N5',
            'realisasi_n6' => 'Realisasi N6',
            'tingkat_realisasi_n1' => 'Tingkat Realisasi N1',
            'tingkat_realisasi_n2' => 'Tingkat Realisasi N2',
            'tingkat_realisasi_n3' => 'Tingkat Realisasi N3',
            'tingkat_realisasi_n4' => 'Tingkat Realisasi N4',
            'tingkat_realisasi_n5' => 'Tingkat Realisasi N5',
            'tingkat_realisasi_n6' => 'Tingkat Realisasi N6',
            'realisasi_status_n1' => 'Realisasi Status N1',
            'realisasi_status_n2' => 'Realisasi Status N2',
            'realisasi_status_n3' => 'Realisasi Status N3',
            'realisasi_status_n4' => 'Realisasi Status N4',
            'realisasi_status_n5' => 'Realisasi Status N5',
            'realisasi_status_n6' => 'Realisasi Status N6',
            'kondisi_akhir' => 'Kondisi Akhir',
            'faktor_penghambat' => 'Faktor Penghambat',
            'faktor_pendorong' => 'Faktor Pendorong',
            'usulan_tindak_lanjut' => 'Usulan Tindak Lanjut',
            'status_perubahan' => 'Status Perubahan',
            'id_model_perubahan' => 'Id Model Perubahan',
            'username_pembuat' => 'Username Pembuat',
            'waktu_dibuat' => 'Waktu Dibuat',
            'username_pengubah' => 'Username Pengubah',
            'waktu_diubah' => 'Waktu Diubah',
        ];
    }

    public function getRpjmd()
    {
        return $this->hasOne(Rpjmd::class, ['id' => 'id_rpjmd']);
    }

    public function getNamaLengkap()
    {
        if (@$this->rpjmd !== null) {
            return $this->nama . ' ('.@$this->rpjmd->tahun_awal.'-'.$this->rpjmd->tahun_akhir.')';
        }

        return $this->nama;
    }

    public static function getList($params=[])
    {
        $tahun = $params['tahun'];
        if ($tahun == null) {
            $tahun = Session::getTahun();
        }

        $query = static::find();
        $query->joinWith(['rpjmd']);
        $query->andFilterWhere(['id_instansi' => @$params['id_instansi']]);
        $query->andWhere('rpjmd.tahun_awal <= :tahun AND rpjmd.tahun_akhir >= :tahun', [
            ':tahun' => $tahun,
        ]);

        return ArrayHelper::map($query->all(), 'id', 'namaLengkap');
    }
}
