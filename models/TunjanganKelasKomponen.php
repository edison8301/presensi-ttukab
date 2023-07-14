<?php

namespace app\models;

use kartik\editable\Editable;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tunjangan_kelas_komponen".
 *
 * @property int $id
 * @property int $id_tunjangan_kelas
 * @property int $id_tunjangan_komponen
 * @property string $jumlah_tunjangan
 * @property int $status_aktif
 * @property TunjanganKelas $tunjanganKelas
 */
class TunjanganKelasKomponen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tunjangan_kelas_komponen';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tunjangan_kelas', 'id_tunjangan_komponen'], 'required'],
            [['id_tunjangan_kelas', 'id_tunjangan_komponen', 'status_aktif'], 'integer'],
            [['jumlah_tunjangan'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_tunjangan_kelas' => 'Kelas Jabatan',
            'id_tunjangan_komponen' => 'Id Tunjangan Komponen',
            'jumlah_tunjangan' => 'Jumlah Tunjangan',
            'status_aktif' => 'Status Aktif',
        ];
    }

    /**
     * {@inheritdoc}
     * @return TunjanganKelasKomponenQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TunjanganKelasKomponenQuery(get_called_class());
    }

    public function getTunjanganKelas()
    {
        return $this->hasOne(TunjanganKelas::class,['id'=>'id_tunjangan_kelas']);
    }

    public static function findOrCreate($params)
    {
        $query = TunjanganKelasKomponen::find();
        $query->andFilterWhere([
            'id_tunjangan_kelas'=>@$params['id_tunjangan_kelas'],
            'id_tunjangan_komponen'=>@$params['id_tunjangan_komponen']
        ]);

        $model = $query->one();

        if($model===null) {
            $model = new TunjanganKelasKomponen($params);
            if($model->save()==false) {
                print_r($model->getErrors());
                die();
            }
        }

        return $model;
    }

    public function getNamaStatusAktif()
    {
        if($this->status_aktif==1) {
            return "Ya";
        }

        if($this->status_aktif==0) {
            return "Tidak";
        }

        return "N/A";
    }

    public function getEditableJumlahTunjangan()
    {
        return Editable::widget([
            'model' => $this,
            'name' => 'jumlah_tunjangan',
            'value' => $this->jumlah_tunjangan,
            'beforeInput' => Html::hiddenInput('editableKey', $this->id),
            // 'asPopover' => false,
            'placement' => 'top',
            'valueIfNull' => '....',
            'formOptions' => ['action' => ['/tunjangan-kelas-komponen/update-editable']],
            'inputType' => Editable::INPUT_TEXT,
            'options' => ['class' => 'form-control'],
            'pluginEvents' => [
                'editableSuccess' => 'function(event, val, form, data) { setTimeout(function() { $("#example").DataTable().draw(false); },500) }'
            ]
        ]);
    }

    public function getEditableStatusAktif()
    {
        return Editable::widget([
            'model' => $this,
            'name' => 'status_aktif',
            'value' => $this->getNamaStatusAktif(),
            'beforeInput' => Html::hiddenInput('editableKey', $this->id),
            // 'asPopover' => false,
            'placement' => 'top',
            'valueIfNull' => '....',
            'formOptions' => ['action' => ['/tunjangan-kelas-komponen/update-editable']],
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => ['1'=>'Ya','0'=>'Tidak'],
            'options' => ['class' => 'form-control'],
            'pluginEvents' => [
                'editableSuccess' => 'function(event, val, form, data) { setTimeout(function() { $("#example").DataTable().draw(false); },500) }'
            ]
        ]);
    }
}
