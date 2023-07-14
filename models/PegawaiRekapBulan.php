<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pegawai_rekap_bulan".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $bulan
 * @property string $tahun
 * @property int $id_pegawai_rekap_jenis
 * @property string $nilai
 * @property int $status_kunci
 * @property string $waktu_kunci
 * @property string $waktu_buat
 */
class PegawaiRekapBulan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_rekap_bulan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'bulan', 'tahun', 'id_pegawai_rekap_jenis'], 'required'],
            [['id_pegawai', 'bulan', 'id_pegawai_rekap_jenis', 'status_kunci'], 'integer'],
            [['tahun', 'waktu_kunci', 'waktu_buat'], 'safe'],
            [['nilai','keterangan'], 'safe'],
            [['id_pegawai', 'bulan', 'tahun', 'id_pegawai_rekap_jenis'], 'unique', 'targetAttribute' => ['id_pegawai', 'bulan', 'tahun', 'id_pegawai_rekap_jenis']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Id Pegawai',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'id_pegawai_rekap_jenis' => 'Id Pegawai Rekap Jenis',
            'nilai' => 'Nilai',
            'status_kunci' => 'Status Kunci',
            'waktu_kunci' => 'Waktu Kunci',
            'waktu_buat' => 'Waktu Buat',
        ];
    }

    /**
     * {@inheritdoc}
     * @return PegawaiRekapBulanQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PegawaiRekapBulanQuery(get_called_class());
    }

    public static function createOrUpdate(array $params)
    {
        $query = PegawaiRekapBulan::find();

        $query->andWhere([
            'id_pegawai' => @$params['id_pegawai'],
            'bulan' => @$params['bulan'],
            'tahun' => @$params['tahun'],
            'id_pegawai_rekap_jenis' => @$params['id_pegawai_rekap_jenis']
        ]);

        $model = $query->one();

        if($model===null) {
            $model = new PegawaiRekapBulan([
                'id_pegawai' => @$params['id_pegawai'],
                'bulan' => @$params['bulan'],
                'tahun' => @$params['tahun'],
                'id_pegawai_rekap_jenis' => @$params['id_pegawai_rekap_jenis']
            ]);
        }

        $model->nilai = @$params['nilai'];
        $model->keterangan = @$params['keterangan'];

        if($model->save()!=true) {
            print_r($model->getErrors());
            die();
        }

        return true;
    }

    public static function query($params=[])
    {
        $query = PegawaiRekapBulan::find();
        $query->andFilterWhere([
            'id_pegawai' => @$params['id_pegawai'],
            'bulan' => @$params['bulan'],
            'tahun' => @$params['tahun'],
            'id_pegawai_rekap_jenis' => @$params['id_pegawai_rekap_jenis'],
            'nilai' => @$params['nilai'],
            'keterangan' => @$params['keterangan']
        ]);

        return $query;
    }

    public static function count(array $params=[])
    {
        $query = PegawaiRekapBulan::query($params);
        return $query->count();
    }

    public function getPegawai()
    {
        return $this->hasOne(\app\models\Pegawai::class,['id'=>'id_pegawai']);
    }

    public function getPegawaiRekapJenis()
    {
        return $this->hasOne(\app\models\PegawaiRekapJenis::class,['id'=>'id_pegawai_rekap_jenis']);
    }

}
