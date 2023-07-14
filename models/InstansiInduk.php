<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "instansi_induk".
 *
 * @property int $id
 * @property int $id_instansi
 * @property int $id_instansi_induk
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 */
class InstansiInduk extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instansi_induk';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_instansi', 'id_instansi_induk', 'tanggal_mulai'], 'required'],
            [['id_instansi', 'id_instansi_induk'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_instansi' => 'Perangkat Daerah',
            'id_instansi_induk' => 'Perangkat Daerah Induk',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
        ];
    }

    public function getInstansiInduk()
    {
        return $this->hasOne(Instansi::class,['id'=>'id_instansi_induk']);
    }

    public function getLinkUpdateIcon()
    {
        return Html::a('<i class="fa fa-pencil"></i>',[
            '/instansi-induk/update',
            'id' => $this->id
        ]);
    }

    public function getLinkDeleteIcon()
    {
        return Html::a('<i class="fa fa-trash"></i>',[
            '/instansi-induk/delete',
            'id' => $this->id
        ],[
            'data-method' => 'post',
            'data-confirm' => 'Yakin akan menghapus data?'
        ]);
    }
}
