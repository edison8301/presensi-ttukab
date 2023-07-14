<?php

namespace app\modules\tunjangan\models;

use Yii;
use app\components\Helper;
use app\models\Golongan;
use app\models\Instansi;
use app\modules\tukin\models\Eselon;
use app\modules\tunjangan\models\JabatanGolongan;
use kartik\editable\Editable;
use yii\helpers\Html;

/**
 * This is the model class for table "jabatan_tunjangan_struktural".
 *
 * @property int $id
 * @property int $id_instansi
 * @property int $id_eselon
 * @property int $id_golongan
 * @property double $besaran_tpp
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 */
class JabatanTunjanganStruktural extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jabatan_tunjangan_struktural';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_eselon', 'besaran_tpp'], 'required'],
            [['id_instansi', 'id_eselon', 'id_jabatan_tunjangan_golongan'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai','id_golongan'], 'safe'],
            [['kelas_jabatan'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_instansi' => 'Instansi',
            'id_eselon' => 'Eselon',
            'besaran_tpp' => 'Besaran TPP',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'id_golongan' => 'Golongan',
            'id_jabatan_tunjangan_golongan' => 'Golongan'
        ];
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::className(), ['id' => 'id_instansi']);
    }

    public function getEselon()
    {
        return $this->hasOne(Eselon::className(), ['id' => 'id_eselon']);
    }

    public function getGolongan()
    {
        return $this->hasOne(JabatanGolongan::className(), ['id' => 'id_golongan']);
    }

    public function getJabatanTunjanganGolongan()
    {
        return $this->hasOne(JabatanTunjanganGolongan::class,['id'=>'id_jabatan_tunjangan_golongan']);
    }

    public function getEditableEselon()
    {
        return Editable::widget([
            'model'=>$this,
            'name'=>'id_eselon',
            'value'=>@$this->eselon->nama,
            'beforeInput' => Html::hiddenInput('editableKey',$this->id),
            'asPopover' => true,
            'placement'=>'bottom',
            'formOptions' => ['action'=>['jabatan-tunjangan-struktural/editable-update','jenis' => 'eselon']],
            'header' => 'Jumlah',
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => Eselon::getList(),
        ]) ;
    }

    public function getEditableGolongan()
    {
        return Editable::widget([
            'model'=>$this,
            'name'=>'id_golongan',
            'value'=>@$this->golongan->nama,
            'beforeInput' => Html::hiddenInput('editableKey',$this->id),
            'asPopover' => true,
            'placement'=>'bottom',
            'formOptions' => ['action'=>['jabatan-tunjangan-struktural/editable-update','jenis' => 'golongan']],
            'header' => 'Jumlah',
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => JabatanGolongan::getList(),
        ]);
    }

    public function getEditableBesaranTPP()
    {
        return Editable::widget([
            'model'=>$this,
            'name'=>'besaran_tpp',
            'value'=>Helper::rp($this->besaran_tpp),
            'beforeInput' => Html::hiddenInput('editableKey',$this->id),
            'asPopover' => true,
            'placement'=>'bottom',
            'formOptions' => ['action'=>['jabatan-tunjangan-struktural/editable-update','jenis' => 'besaran_tpp']],
            'header' => 'Jumlah',
            'inputType' => Editable::INPUT_TEXT,
        ]);
    }

    public function getLabelTanggalSelesai()
    {
        if ($this->tanggal_selesai == '9999-12-31') {
            return Html::tag('span','Masih Berlaku',['class' => 'label label-success btn-flat btn-xs']);
        }
        return Helper::getTanggal($this->tanggal_selesai);
    }

    public function beforeSave($insert)
    {
        if ($this->tanggal_selesai == null) {
            $this->tanggal_selesai = '9999-12-31';
        }
        $this->besaran_tpp = str_replace(',',null, $this->besaran_tpp);
        return parent::beforeSave($insert);
    }

    /**
     * @param array $params
     * @return JabatanTunjanganStruktural[]|\yii\db\ActiveRecord[]
     */
    public static function findAll($params = [])
    {
        $query = JabatanTunjanganStruktural::find();
        return $query->all();
    }

    public static function query($params = [])
    {
        $query = JabatanTunjanganStruktural::find();
        $query->andFilterWhere(['id_eselon' => @$params['id_eselon']]);
        $query->andFilterWhere(['kelas_jabatan' => @$params['kelas_jabatan']]);
        $query->andFilterWhere(['id_instansi' => @$params['id_instansi']]);
        $query->andFilterWhere(['id_jabatan_tunjangan_golongan' => @$params['id_jabatan_tunjangan_golongan']]);

        return $query;
    }
}
