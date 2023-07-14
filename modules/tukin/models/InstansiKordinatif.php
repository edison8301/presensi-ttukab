<?php

namespace app\modules\tukin\models;

use Yii;

/**
 * This is the model class for table "instansi_kordinatif".
 *
 * @property int $id
 * @property int $id_instansi
 * @property string $tanggal_berlaku_mulai
 * @property string $tanggal_berlaku_selesai
 *
 * @property InstansiKordinatifBesaran[] $manyBesaran
 * @property Instansi $instansi
 */
class InstansiKordinatif extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'instansi_kordinatif';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_instansi', 'tanggal_berlaku_mulai', 'tanggal_berlaku_selesai'], 'required'],
            [['id_instansi'], 'integer'],
            [['tanggal_berlaku_mulai', 'tanggal_berlaku_selesai'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_instansi' => 'Instansi',
            'tanggal_berlaku_mulai' => 'Tanggal Berlaku Mulai',
            'tanggal_berlaku_selesai' => 'Tanggal Berlaku Selesai',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi']);
    }

    public function getManyBesaran()
    {
        return $this->hasMany(InstansiKordinatifBesaran::class, ['id_instansi_kordinatif' => 'id']);
    }

    /**
     * @return InstansiKordinatifBesaran[]
     */
    public function findOrCreateBesaran()
    {
        if ($this->manyBesaran === []) {
            $return = [];
            foreach (range(1, 4) as $eselon) {
                $new = new InstansiKordinatifBesaran([
                    'id_instansi_kordinatif' => $this->id,
                    'id_eselon' => $eselon,
                    'status_struktural' => true,
                ]);
                $new->save();
                $return[] = $new;
            }
            foreach (range(1, 4) as $golongan) {
                $new = new InstansiKordinatifBesaran([
                    'id_instansi_kordinatif' => $this->id,
                    'id_golongan' => $golongan,
                    'status_struktural' => false,
                ]);
                $new->save();
                $return[] = $new;
            }
            return $return;
        }
        return $this->manyBesaran;
    }
}
