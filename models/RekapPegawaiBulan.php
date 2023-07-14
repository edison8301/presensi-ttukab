<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "rekap_pegawai_bulan".
 *
 * @property int $id
 * @property int $id_rekap_jenis
 * @property int $id_pegawai
 * @property int $bulan
 * @property string $tahun
 * @property string $nilai
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class RekapPegawaiBulan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rekap_pegawai_bulan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_rekap_jenis', 'id_pegawai', 'bulan', 'tahun'], 'required'],
            [['id_rekap_jenis', 'id_pegawai', 'bulan'], 'integer'],
            [['tahun', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nilai'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_rekap_jenis' => 'Id Rekap Jenis',
            'id_pegawai' => 'Id Pegawai',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'nilai' => 'Nilai',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    public static function findOrCreate(array $params)
    {
        $id_pegawai = $params['id_pegawai'];
        $id_rekap_jenis = $params['id_rekap_jenis'];
        $tahun = $params['tahun'];
        $bulan = $params['bulan'];

        $query = RekapPegawaiBulan::find();
        $query->andWhere([
            'id_pegawai' => $id_pegawai,
            'id_rekap_jenis' => $id_rekap_jenis,
            'tahun' => $tahun,
            'bulan' => $bulan,
        ]);

        $model = $query->one();

        if($model === null) {
            $model = new RekapPegawaiBulan();
            $model->id_pegawai = $id_pegawai;
            $model->id_rekap_jenis = $id_rekap_jenis;
            $model->tahun = $tahun;
            $model->bulan = $bulan;
            if($model->save() == false) {
                print_r($model->getErrors());
                die;
            }
        }

        return $model;
    }

    public static function updateOrCreate(array $params)
    {
        $model = RekapPegawaiBulan::findOrCreate($params);
        $model->updateAttributes([
            'nilai' => @$params['nilai'],
        ]);
    }
}
