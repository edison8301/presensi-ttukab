<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "rekap_instansi_bulan".
 *
 * @property int $id
 * @property int $id_rekap_jenis
 * @property int $id_instansi
 * @property int $bulan
 * @property string $tahun
 * @property string $nilai
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property RekapJenis $rekapJenis
 */
class RekapInstansiBulan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rekap_instansi_bulan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_rekap_jenis', 'id_instansi', 'bulan', 'tahun'], 'required'],
            [['id_rekap_jenis', 'id_instansi', 'bulan'], 'integer'],
            [['tahun', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nilai'], 'number'],
            [['id_rekap_jenis', 'id_instansi', 'bulan', 'tahun'], 'unique', 'targetAttribute' => ['id_rekap_jenis', 'id_instansi', 'bulan', 'tahun']],
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
            'id_instansi' => 'Id Instansi',
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

    public static function find()
    {
        $query = parent::find();
        $query->andWhere('deleted_at is null');

        return $query;
    }

    public function getRekapJenis()
    {
        return $this->hasOne(RekapJenis::class, ['id' => 'id_rekap_jenis']);
    }

    public static function findOrCreate(array $params)
    {
        $id_instansi = $params['id_instansi'];
        $id_rekap_jenis = $params['id_rekap_jenis'];
        $tahun = $params['tahun'];
        $bulan = $params['bulan'];

        $query = RekapInstansiBulan::find();
        $query->andWhere([
            'id_instansi' => $id_instansi,
            'id_rekap_jenis' => $id_rekap_jenis,
            'tahun' => $tahun,
            'bulan' => $bulan,
        ]);

        $model = $query->one();

        if($model === null) {
            $model = new RekapInstansiBulan();
            $model->id_instansi = $id_instansi;
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
}
