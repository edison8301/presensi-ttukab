<?php

namespace app\models;

use app\components\Session;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "rpjmd_subkegiatan_indikator".
 *
 * @property int $id
 * @property int $id_bidang
 * @property int $id_bidang_urusan
 * @property int $id_rpjmd
 * @property int $id_rpjmd_misi
 * @property int $id_rpjmd_tujuan
 * @property int $id_rpjmd_sasaran
 * @property int $id_rpjmd_program
 * @property int $id_rpjmd_subkegiatan
 * @property int $id_instansi
 * @property string $nama
 * @property string $satuan
 * @property string $kondisi_awal
 * @property string $tahun_awal
 * @property string $target_n0
 * @property string $target_n1
 * @property string $target_n2
 * @property string $target_n3
 * @property string $target_n4
 * @property string $target_n5
 * @property string $target_n6
 * @property string $satuan_target_n1
 * @property string $satuan_target_n2
 * @property string $satuan_target_n3
 * @property string $satuan_target_n4
 * @property string $satuan_target_n5
 * @property string $satuan_target_n6
 * @property string $dana_n0
 * @property string $dana_n1
 * @property string $dana_n2
 * @property string $dana_n3
 * @property string $dana_n4
 * @property string $dana_n5
 * @property string $dana_n6
 * @property string $kondisi_akhir
 * @property string $skpd_penanggung_jawab
 * @property string $capaian_target_n1
 * @property string $capaian_target_n2
 * @property string $capaian_target_n3
 * @property string $capaian_target_n4
 * @property string $capaian_target_n5
 * @property string $capaian_target_n6
 * @property string $capaian_dana_n1
 * @property string $capaian_dana_n2
 * @property string $capaian_dana_n3
 * @property string $capaian_dana_n4
 * @property string $capaian_dana_n5
 * @property string $capaian_dana_n6
 * @property string $tingkat_capaian_target_n1
 * @property string $tingkat_capaian_target_n2
 * @property string $tingkat_capaian_target_n3
 * @property string $tingkat_capaian_target_n4
 * @property string $tingkat_capaian_target_n5
 * @property string $tingkat_capaian_target_n6
 * @property string $tingkat_capaian_dana_n1
 * @property string $tingkat_capaian_dana_n2
 * @property string $tingkat_capaian_dana_n3
 * @property string $tingkat_capaian_dana_n4
 * @property string $tingkat_capaian_dana_n5
 * @property string $tingkat_capaian_dana_n6
 * @property int $status_perubahan
 * @property int $id_model_perubahan
 * @property string $username_pembuat
 * @property string $waktu_dibuat
 * @property string $username_pengubah
 * @property string $waktu_diubah
 */
class RpjmdSubkegiatanIndikator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rpjmd_subkegiatan_indikator';
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
            [['id_bidang', 'id_bidang_urusan', 'id_rpjmd', 'id_rpjmd_misi', 'id_rpjmd_tujuan', 'id_rpjmd_sasaran', 'id_rpjmd_program', 'id_rpjmd_subkegiatan', 'id_instansi', 'status_perubahan', 'id_model_perubahan'], 'integer'],
            [['tahun_awal', 'waktu_dibuat', 'waktu_diubah'], 'safe'],
            [['target_n1', 'target_n2', 'target_n3', 'target_n4', 'target_n5', 'target_n6', 'satuan_target_n1', 'satuan_target_n2', 'satuan_target_n3', 'satuan_target_n4', 'satuan_target_n5', 'satuan_target_n6', 'capaian_target_n1', 'capaian_target_n2', 'capaian_target_n3', 'capaian_target_n4', 'capaian_target_n5', 'capaian_target_n6', 'tingkat_capaian_target_n1', 'tingkat_capaian_target_n2', 'tingkat_capaian_target_n3', 'tingkat_capaian_target_n4', 'tingkat_capaian_target_n5', 'tingkat_capaian_target_n6'], 'string'],
            [['dana_n0'], 'number'],
            [['nama', 'satuan', 'kondisi_awal', 'target_n0', 'kondisi_akhir', 'skpd_penanggung_jawab', 'tingkat_capaian_dana_n1', 'tingkat_capaian_dana_n2', 'tingkat_capaian_dana_n3', 'tingkat_capaian_dana_n4', 'tingkat_capaian_dana_n5', 'tingkat_capaian_dana_n6', 'username_pembuat', 'username_pengubah'], 'string', 'max' => 255],
            [['dana_n1', 'dana_n2', 'dana_n3', 'dana_n4', 'dana_n5', 'dana_n6', 'capaian_dana_n1', 'capaian_dana_n2', 'capaian_dana_n3', 'capaian_dana_n4', 'capaian_dana_n5', 'capaian_dana_n6'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_bidang' => 'Id Bidang',
            'id_bidang_urusan' => 'Id Bidang Urusan',
            'id_rpjmd' => 'Id Rpjmd',
            'id_rpjmd_misi' => 'Id Rpjmd Misi',
            'id_rpjmd_tujuan' => 'Id Rpjmd Tujuan',
            'id_rpjmd_sasaran' => 'Id Rpjmd Sasaran',
            'id_rpjmd_program' => 'Id Rpjmd Program',
            'id_rpjmd_subkegiatan' => 'Id Rpjmd Subkegiatan',
            'id_instansi' => 'Id Instansi',
            'nama' => 'Nama',
            'satuan' => 'Satuan',
            'kondisi_awal' => 'Kondisi Awal',
            'tahun_awal' => 'Tahun Awal',
            'target_n0' => 'Target N0',
            'target_n1' => 'Target N1',
            'target_n2' => 'Target N2',
            'target_n3' => 'Target N3',
            'target_n4' => 'Target N4',
            'target_n5' => 'Target N5',
            'target_n6' => 'Target N6',
            'satuan_target_n1' => 'Satuan Target N1',
            'satuan_target_n2' => 'Satuan Target N2',
            'satuan_target_n3' => 'Satuan Target N3',
            'satuan_target_n4' => 'Satuan Target N4',
            'satuan_target_n5' => 'Satuan Target N5',
            'satuan_target_n6' => 'Satuan Target N6',
            'dana_n0' => 'Dana N0',
            'dana_n1' => 'Dana N1',
            'dana_n2' => 'Dana N2',
            'dana_n3' => 'Dana N3',
            'dana_n4' => 'Dana N4',
            'dana_n5' => 'Dana N5',
            'dana_n6' => 'Dana N6',
            'kondisi_akhir' => 'Kondisi Akhir',
            'skpd_penanggung_jawab' => 'Skpd Penanggung Jawab',
            'capaian_target_n1' => 'Capaian Target N1',
            'capaian_target_n2' => 'Capaian Target N2',
            'capaian_target_n3' => 'Capaian Target N3',
            'capaian_target_n4' => 'Capaian Target N4',
            'capaian_target_n5' => 'Capaian Target N5',
            'capaian_target_n6' => 'Capaian Target N6',
            'capaian_dana_n1' => 'Capaian Dana N1',
            'capaian_dana_n2' => 'Capaian Dana N2',
            'capaian_dana_n3' => 'Capaian Dana N3',
            'capaian_dana_n4' => 'Capaian Dana N4',
            'capaian_dana_n5' => 'Capaian Dana N5',
            'capaian_dana_n6' => 'Capaian Dana N6',
            'tingkat_capaian_target_n1' => 'Tingkat Capaian Target N1',
            'tingkat_capaian_target_n2' => 'Tingkat Capaian Target N2',
            'tingkat_capaian_target_n3' => 'Tingkat Capaian Target N3',
            'tingkat_capaian_target_n4' => 'Tingkat Capaian Target N4',
            'tingkat_capaian_target_n5' => 'Tingkat Capaian Target N5',
            'tingkat_capaian_target_n6' => 'Tingkat Capaian Target N6',
            'tingkat_capaian_dana_n1' => 'Tingkat Capaian Dana N1',
            'tingkat_capaian_dana_n2' => 'Tingkat Capaian Dana N2',
            'tingkat_capaian_dana_n3' => 'Tingkat Capaian Dana N3',
            'tingkat_capaian_dana_n4' => 'Tingkat Capaian Dana N4',
            'tingkat_capaian_dana_n5' => 'Tingkat Capaian Dana N5',
            'tingkat_capaian_dana_n6' => 'Tingkat Capaian Dana N6',
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
