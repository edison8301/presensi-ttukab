<?php

namespace app\models;

use app\components\Session;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "pegawai_rb".
 *
 * @property int $id
 * @property string $tahun
 * @property string $tanggal
 * @property int $id_pegawai
 * @property int $id_pegawai_rb_jenis
 * @property int $status_realisasi
 * @property Pegawai $pegawai
 * @property PegawaiRbJenis $pegawaiRbJenis
 */
class PegawaiRb extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_rb';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tahun', 'id_pegawai', 'id_pegawai_rb_jenis'], 'required'],
            [['tahun', 'tanggal'], 'safe'],
            [['id_pegawai', 'id_pegawai_rb_jenis', 'status_realisasi'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tahun' => 'Tahun',
            'tanggal' => 'Tanggal',
            'id_pegawai' => 'Pegawai',
            'id_pegawai_rb_jenis' => 'Pegawai Rb Jenis',
            'status_realisasi' => 'Status Realisasi',
        ];
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }

    public function getPegawaiRbJenis()
    {
        return $this->hasOne(PegawaiRbJenis::class, ['id' => 'id_pegawai_rb_jenis']);
    }

    public function getManyInstansiPegawai()
    {
        return $this->hasMany(InstansiPegawai::class, ['id_pegawai' => 'id_pegawai']);
    }

    public static function findOrCreate($params=[])
    {
        $tahun = @$params['tahun'];
        if ($tahun == null) {
            $tahun = Session::getTahun();
        }

        $query = PegawaiRb::find();
        $query->andWhere(['tahun' => $tahun]);
        $query->andWhere(['id_pegawai' => @$params['id_pegawai']]);
        $query->andWhere(['id_pegawai_rb_jenis' => @$params['id_pegawai_rb_jenis']]);

        $model = $query->one();

        if ($model == null) {
            $model = new PegawaiRb();
            $model->tahun = $tahun;
            $model->id_pegawai = @$params['id_pegawai'];
            $model->id_pegawai_rb_jenis = @$params['id_pegawai_rb_jenis'];
            $model->tanggal = @$params['tanggal'];
            $model->status_realisasi = 0;
            $model->save();
        }

        return $model;
    }

    public function getLabelStatusRealisasi()
    {
        if ($this->status_realisasi == 1) {
            return Html::tag('span', 'Sudah', ['class' => 'label label-success']);
        }

        return Html::tag('span', 'Belum', ['class' => 'label label-danger']);
    }
}
