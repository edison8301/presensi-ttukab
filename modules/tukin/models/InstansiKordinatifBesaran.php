<?php

namespace app\modules\tukin\models;

use app\models\Eselon;
use app\models\Golongan;
use Yii;

/**
 * This is the model class for table "instansi_kordinatif_besaran".
 *
 * @property int $id
 * @property int $id_instansi_kordinatif
 * @property int $status_struktural
 * @property int $id_eselon
 * @property int $id_golongan
 * @property double $besaran
 *
 * @property null|string $eselon
 * @property null|string $golongan
 * @property bool $isStruktural
 * @property Pegawai $pegawai
 * @property InstansiKordinatif $instansiKordinatif
 * @property null|string $kelompok
 */
class InstansiKordinatifBesaran extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'instansi_kordinatif_besaran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_instansi_kordinatif', 'status_struktural'], 'required'],
            [['id_instansi_kordinatif', 'id_eselon', 'id_golongan'], 'integer'],
            [['besaran'], 'number'],
            [['status_struktural'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_instansi_kordinatif' => 'Id Instansi Kordinatif',
            'status_struktural' => 'Status Struktural',
            'id_eselon' => 'Id Eselon',
            'id_golongan' => 'Id Golongan',
            'besaran' => 'Besaran',
        ];
    }

    public function getEselon()
    {
        switch ($this->id_eselon) {
            case 1:
                return 'Eselon 1';
            case 2:
                return 'Eselon 2';
            case 3:
                return 'Eselon 3';
            case 4:
                return 'Eselon 4';
            default:
                return null;
        }
    }

    public function getGolongan()
    {
        switch ($this->id_golongan) {
            case 1:
                return 'Golongan 1';
            case 2:
                return 'Golongan 2';
            case 3:
                return 'Golongan 3';
            case 4:
                return 'Golongan 4';
            default:
                return null;
        }
    }

    public function getIsStruktural()
    {
        return (bool) $this->status_struktural;
    }

    public function getKelompok()
    {
        return $this->getIsStruktural() ? $this->getEselon() : 'JFU & JFT ' . $this->getGolongan();
    }

    public function getInstansiKordinatif()
    {
        return $this->hasOne(InstansiKordinatif::class, ['id' => 'id_instansi_kordinatif']);
    }

    public function getPegawai()
    {
        $relation = $this->hasMany(Pegawai::class, ['id_instansi' => 'id_instansi'])
            ->via('instansiKordinatif');
        if ($this->getIsStruktural()) {
            $relation->andWhere(['in', 'id_eselon', $this->getKelompokEselon()]);
        } else {
            $relation->andWhere(['in', 'id_golongan', $this->getKelompokGolongan()])
                ->andWhere(['id_eselon' => Eselon::NON_ESELON]);
        }
        return $relation;
    }

    public function getKelompokEselon()
    {
        return @Eselon::getKelompok()[$this->id_eselon];
    }

    public function getKelompokGolongan()
    {
        return @Golongan::getKelompok()[$this->id_golongan];
    }

    public function getJumlahPegawai()
    {
        return count($this->pegawai);
    }
}
