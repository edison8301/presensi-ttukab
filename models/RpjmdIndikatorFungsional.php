<?php

namespace app\models;

use app\components\Session;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "rpjmd_indikator_fungsional".
 *
 * @property int $id
 * @property int $id_rpjmd
 * @property int $id_instansi
 * @property int $eselon
 * @property string $nama
 */
class RpjmdIndikatorFungsional extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rpjmd_indikator_fungsional';
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
            [['id_rpjmd', 'id_instansi', 'eselon', 'nama'], 'required'],
            [['id_rpjmd', 'id_instansi', 'eselon'], 'integer'],
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
            'id_rpjmd' => 'Id Rpjmd',
            'id_instansi' => 'Id Instansi',
            'eselon' => 'Eselon',
            'nama' => 'Nama',
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
