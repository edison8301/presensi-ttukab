<?php

namespace app\models;

use app\components\Session;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "peta".
 *
 * @property int $id
 * @property string $nama
 * @property int $id_induk
 * @property string $keterangan
 */
class Peta extends \yii\db\ActiveRecord
{
    const BUKA = 0;
    const KUNCI = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'peta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['id_induk','jarak', 'id_pegawai'], 'integer'],
            [['keterangan'], 'string'],
            [['nama'], 'string', 'max' => 255],
            [['latlong','latitude','longitude','id_peta_jenis','id_instansi','jarak'], 'safe'],
            [['status_kunci'], 'integer'],
            [['status_rumah'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'id_induk' => 'Id Induk',
            'id_pegawai' => 'Pegawai',
            'keterangan' => 'Keterangan',
            'jarak' => 'Jarak',
        ];
    }

    public function getPetaJenis()
    {
        return $this->hasOne(PetaJenis::class, ['id' => 'id_peta_jenis']);
    }

    public function getManyPetaPoint()
    {
        return $this->hasMany(PetaPoint::class, ['id_peta' => 'id']);
    }    

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi']);
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }

    public function findAllPetaPoint()
    {
        return $this->getManyPetaPoint()
            ->orderBy(['urutan' => SORT_ASC])
            ->all();
    }

    public function getArrayJsPetaPoint()
    {
        $list = "[";
        foreach ($this->manyPetaPoint as $point) {
            $list .= "['Urutan ke ".$point->urutan." |".$point->latitude." ".$point->longitude."', ".$point->latitude.", ".$point->longitude."],";
        }
        $list .= "];";
        return $list;        
    }

    public function getJsonPetaPoint()
    {
        $list = null;
        $no=0;
        $count = count($this->manyPetaPoint);
        foreach ($this->manyPetaPoint as $point) {
            $no++;
            if ($count == $no) {
                $list .= "{lat: ".$point->latitude.", lng: ".$point->longitude."}";
            } else {
                $list .= "{lat: ".$point->latitude.", lng: ".$point->longitude."}, ";
            }
        }
        return $list;        
    }    

    public function setKoordinat($koordinat)
    {
        $model = PetaPoint::deleteAll(['id_peta' => $this->id]);

        $explode = explode("),", $koordinat);

        /*return $koordinat;*/

        $no=0;
        foreach ($explode as $key => $value) {
            $model = new PetaPoint();
            $model->id_peta = $this->id;
            $model->urutan = $no++;

            $koordinat = $value;
            $koordinat = str_replace(",(", null, $koordinat);
            $koordinat = str_replace("(", null, $koordinat);
            $koordinat = str_replace('"', null, $koordinat);
            $koordinat = str_replace(')', null, $koordinat);
            
            $latlong = explode(",", $koordinat);

            echo $koordinat."\n";

            $model->latitude = @$latlong[0];
            $model->longitude = @$latlong[1];

            $model->save();
        }
    }        

    public function getLinkIconShow($mode = null)
    {
        return Html::a('<i class="fa fa-eye"></i>', [
            '/peta/view',
            'id' => $this->id,
            'mode' => $mode,
        ],[
            'data-toggle' => 'tooltip',
            'title' => 'Lihat',
        ]);
    }

    public function getLinkIconEdit($mode)
    {
        return Html::a('<i class="fa fa-pencil"></i>', ['update','id' => $this->id, 'mode' => $mode],[
            'data-toggle'=>'tooltip',
            'title' => 'Ubah',
        ]);
    }

    public function getLinkIconDelete()
    {
        if (!Session::isAdmin()) {
            return null;
        }

        return Html::a('<i class="fa fa-trash"></i>', ['delete','id' => $this->id],
            [
                'data-toggle' => 'tooltip',
                'title' => 'Hapus',
                'data-method'=>'post',
                'data-confirm' => 'Apakah Anda yakin ingin menghapus data ini?'
            ]);
    }

    public function getSetStatusKunci()
    {
        if ($this->status_kunci == Peta::BUKA) {
            return Html::a('Dibuka', [
                '/peta/set-kunci',
                'id' => $this->id
            ], [
                'class' => 'label label-success',
                'data-toggle' => 'tooltip',
                'title' => 'Data Terbuka, Klik Untuk Mengunci',
                'data-confirm' => 'Yakin akan mengunci data?',
            ]);
        }

        if ($this->status_kunci == Peta::KUNCI) {
            return Html::a('Dikunci', [
                '/peta/set-buka',
                'id' => $this->id
            ], [
                'class' => 'label label-danger',
                'data-toggle' => 'tooltip',
                'title' => 'Data Terkunci, Klik Untuk Membuka',
                'data-confirm' => 'Yakin akan membuka data?',
            ]);
        }
    }

    public static function accessCreate()
    {
        if (Session::isAdmin()) {
            return true;
        }

        return false;
    }

    public static function getList(array $params = [])
    {
        $query = Peta::find();

        if (@$params['mode'] == 'khusus') {
            $query->andWhere('id_instansi is null AND id_pegawai is null');
        }

        return ArrayHelper::map($query->all(), 'id', 'nama');
    }
}
