<?php

namespace app\modules\tunjangan\models;

use app\components\Session;
use app\models\Instansi;
use app\modules\absensi\models\HukumanDisiplinJenis;
use Yii;
use app\modules\tukin\models\Pegawai;
use yii\helpers\Html;

/**
 * This is the model class for table "tunjangan_potongan_pegawai".
 *
 * @property int $id
 * @property int $id_tunjangan_potongan
 * @property int $id_pegawai
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property int $id_instansi
 * @see TunjanganPotonganPegawai::getInstansi()
 * @property Instansi $instansi
 */
class TunjanganPotonganPegawai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tunjangan_potongan_pegawai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tunjangan_potongan', 'id_pegawai', 'id_instansi'], 'integer'],
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
            'id_tunjangan_potongan' => 'Jenis Potongan',
            'id_pegawai' => 'Pegawai',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
        ];
    }

    public function getTunjanganPotongan()
    {
        return $this->hasOne(TunjanganPotongan::className(), ['id' => 'id_tunjangan_potongan']);
    }

    public function getTunjanganPotonganNilai()
    {
        return $this->hasOne(TunjanganPotonganNilai::className(), ['id_tunjangan_potongan' => 'id_tunjangan_potongan'])
            ->orderBy(['tunjangan_potongan_nilai.tanggal_selesai' => SORT_DESC]);
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::className(), ['id' => 'id_pegawai']);
    }

    public function setTanggalSelesai()
    {
        if($this->tanggal_selesai == null) {
            $this->tanggal_selesai = '9999-12-31';
        }
    }

    public function setIdInstansi()
    {
        if($this->pegawai === null) {
            return false;
        }

        $instansiPegawai = $this->pegawai->getInstansiPegawaiBerlaku($this->tanggal_mulai);

        $this->id_instansi = $instansiPegawai->id_instansi;
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class,['id'=>'id_instansi']);
    }

    public function getAccessUpdate()
    {
        if(Session::isInstansi()) {
            return true;
        }

        if(Session::isAdminInstansi()) {
            return true;
        }

        if(Session::isAdmin()) {
            return true;
        }

        return false;
    }

    public function getAccessDelete()
    {
        return $this->getAccessUpdate();
    }

    public function getLinkViewIcon()
    {
        return Html::a('<i class="fa fa-eye"></i>',[
            '/absensi/hukuman-disiplin/view',
            'id'=>$this->id
        ]);
    }

    public function getLinkUpdateIcon()
    {
        if($this->getAccessUpdate() == false) {
            return '';
        }

        return Html::a('<i class="fa fa-pencil"></i>',[
            '/absensi/hukuman-disiplin/update',
            'id'=>$this->id
        ]);
    }

    public function getLinkDeleteIcon()
    {
        if($this->getAccessDelete() == false) {
            return '';
        }

        return Html::a('<i class="fa fa-trash"></i>',[
            '/absensi/hukuman-disiplin/delete',
            'id'=>$this->id
        ],[
            'data-method'=>'post',
            'data-confirm'=>'Yakin akan menghapus data?'
        ]);
    }
}
