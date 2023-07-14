<?php

namespace app\models;

use Yii;
use app\components\Helper;
use app\models\Pegawai;
use yii\helpers\Html;

/**
 * This is the model class for table "pegawai_golongan".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $id_golongan
 * @property string $tanggal_berlaku
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @see PegawaiGolongan::getGolongan()
 * @property Golongan $golongan
 * @see PegawaiGolongan::getPegawai()
 * @property Pegawai $pegawai
 */
class PegawaiGolongan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_golongan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai','id_golongan','tanggal_berlaku','tanggal_mulai'], 'required'],
            [['id_pegawai', 'id_golongan'], 'integer'],
            [['tanggal_berlaku', 'tanggal_mulai', 'tanggal_selesai'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Pegawai',
            'id_golongan' => 'Golongan',
            'tanggal_berlaku' => 'Tanggal TMT',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
        ];
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }

    public function getGolongan()
    {
        return $this->hasOne(Golongan::class, ['id' => 'id_golongan']);
    }

    public function accessUpdate()
    {
        if (User::isAdmin()) {
            return true;
        }
        return false;
    }

    public function accessDelete()
    {
        if (User::isAdmin()) {
            return true;
        }
        return false;
    }

    public function getLinkIconUpdate()
    {
        if($this->accessUpdate()==false) {
            return null;
        }

        return Html::a('<i class="glyphicon glyphicon-pencil"></i>',[
                '/pegawai-golongan/update',
                'id'=>$this->id
            ],[
                'data-toggle'=>'tooltip',
                'title'=>'Ubah'
            ]).' ';
    }

    public function getLinkIconDelete()
    {
        if($this->accessDelete()==false) {
            return null;
        }

        return Html::a('<i class="glyphicon glyphicon-trash"></i>',[
            '/pegawai-golongan/delete',
            'id'=>$this->id
        ],[
            'data-method'=>'post',
            'data-confirm'=>'Yakin akan menghapus data?',
            'data-toggle'=>'tooltip',
            'title'=>'Hapus'
        ]).' ';
    }

    public function getLabelTanggalSelesai()
    {
        if ($this->tanggal_selesai == '9999-12-31') {
            return Html::tag('span','Masih Berlaku',['class' => 'label label-success btn-flat btn-xs']);
        }
        return Helper::getTanggalSingkat($this->tanggal_selesai);
    }

    public function setTanggalMulai($tanggal_mulai=null)
    {
        if($tanggal_mulai!=null) {
            $this->tanggal_mulai = $tanggal_mulai;
        }

        if($this->tanggal_mulai==null) {
            $date = \DateTime::createFromFormat('Y-m-d',$this->tanggal_berlaku);
            if($date->format('j')<10) {
                $this->tanggal_mulai = $date->format('Y-m-01');
            } else {
                $tanggal = $date->format('Y-m-01');
                $date = \DateTime::createFromFormat('Y-m-d',$tanggal);
                $date->modify('+1 month');
                $this->tanggal_mulai = $date->format('Y-m-01');
            }
        }
    }

    public function updateTanggalMulai($tanggal_mulai=null)
    {
        $this->setTanggalMulai($tanggal_mulai);
        $this->updateAttributes([
            'tanggal_mulai'=>$this->tanggal_mulai
        ]);
    }

    public function setTanggalSelesai($tanggal_selesai=null)
    {
        if($this->tanggal_mulai==null) {
            $this->updateTanggalMulai();
        }

        if($tanggal_selesai!=null) {
            $this->tanggal_selesai = $tanggal_selesai;
        }

        if($this->tanggal_selesai==null) {

            // Query untuk cari data instansi_pegawai yang lebih baru
            $query = PegawaiGolongan::find();
            $query->andWhere([
                'id_pegawai'=>$this->id_pegawai,
            ]);
            $query->andWhere('tanggal_mulai > :tanggal_mulai',[
                ':tanggal_mulai' => $this->tanggal_mulai
            ]);
            $query->orderBy(['tanggal_berlaku'=>SORT_ASC]);

            $model = $query->one();

            // Jika ada data instansi_pegawai
            if($model!==null) {
                $tanggal = \DateTime::createFromFormat('Y-m-d',$model->tanggal_mulai);
                $tanggal->modify('-1 day');
                $this->tanggal_selesai = $tanggal->format('Y-m-d');
            }

            // Tidak ada data instansi pegawai yang lebih besar lagi
            if($model===null) {
                $this->tanggal_selesai = '9999-12-31';
            }
        }

    }

    public function updateTanggalSelesai($tanggal_selesai=null)
    {
        $this->setTanggalSelesai($tanggal_selesai);
        $this->updateAttributes([
            'tanggal_selesai'=>$this->tanggal_selesai
        ]);
    }

    public function updateMundurTanggalSelesai()
    {
        $model = $this->findMundur();

        if($model!==null) {
            $tanggal = \DateTime::createFromFormat('Y-m-d',$this->tanggal_mulai);
            $tanggal->modify('-1 day');
            $tanggal_selesai = $tanggal->format('Y-m-d');
            $model->updateTanggalSelesai($tanggal_selesai);
        }

    }

    /**
     * @return PegawaiGolongan
     */
    public function findMundur()
    {
        $query = PegawaiGolongan::find();
        $query->andWhere(['id_pegawai'=>$this->id_pegawai]);
        $query->andWhere('tanggal_berlaku < :tanggal_berlaku',[
            ':tanggal_berlaku' => $this->tanggal_berlaku
        ]);
        $query->orderBy(['tanggal_berlaku'=>SORT_DESC]);

        return $query->one();
    }
}
