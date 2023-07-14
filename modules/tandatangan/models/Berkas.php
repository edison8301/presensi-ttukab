<?php

namespace app\modules\tandatangan\models;

use app\components\Session;
use app\models\Instansi;
use app\models\Pegawai;
use app\models\User as BaseUser;
use app\modules\tandatangan\models\User;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "berkas".
 *
 * @property int $id
 * @property string $nama
 * @property string $uraian
 * @property string $berkas_mentah
 * @property string $berkas_tandatangan
 * @property int $id_berkas_status
 * @property string $nip_tandatangan
 * @property string $waktu_tandatangan
 * @property int $id_aplikasi
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Berkas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'berkas';
    }

    public static function getDb()
    {
        return Yii::$app->get('db_tandatangan');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uraian'], 'string'],
            [['berkas_mentah'], 'required'],
            [['id_berkas_status', 'id_aplikasi', 'id_berkas_jenis', 'id_instansi', 'bulan'], 'integer'],
            [['waktu_tandatangan', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nama', 'berkas_mentah', 'berkas_mentah_tandatangan', 'berkas_tandatangan'], 'string', 'max' => 255],
            [['nip_tandatangan'], 'string', 'max' => 20],
            [['tahun'], 'safe'],
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
            'uraian' => 'Uraian',
            'berkas_mentah' => 'Berkas Mentah',
            'berkas_tandatangan' => 'Berkas Tandatangan',
            'id_berkas_status' => 'Berkas Status',
            'nip_tandatangan' => 'Nip Tandatangan',
            'waktu_tandatangan' => 'Waktu Tandatangan',
            'id_aplikasi' => 'Aplikasi',
            'id_instansi' => 'Unit Kerja',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    public function getBerkasStatus()
    {
        return $this->hasOne(BerkasStatus::class, ['id' => 'id_berkas_status']);
    }

    public function getManyVerifikasi()
    {
        return $this->hasMany(Verifikasi::class, ['id_berkas' => 'id']);
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi']);
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['nip' => 'nip_tandatangan']);
    }

    public function getLabelBerkasStatus()
    {
        if($this->id_berkas_status === BerkasStatus::SELESAI) {
            return Html::tag('span', @$this->berkasStatus->nama, ['class' => 'label label-success']);
        }

        if($this->id_berkas_status === BerkasStatus::VERIFIKASI) {
            return Html::tag('span', @$this->berkasStatus->nama, ['class' => 'label label-default']);
        }

        if($this->id_berkas_status === BerkasStatus::TANDATANGAN) {
            return Html::tag('span', @$this->berkasStatus->nama, ['class' => 'label label-info']);
        }

        if($this->id_berkas_status === BerkasStatus::TOLAK) {
            return Html::tag('span', @$this->berkasStatus->nama, ['class' => 'label label-danger']);
        }

        return 'N/A';
    }

    public function getLinkIconTandatangan()
    {
        $instansi = @$this->instansi;
        $nik = @$instansi->pegawaiKepala->nik;

        if($this->id_berkas_status == BerkasStatus::TANDATANGAN AND $this->accessTandatangan()) {
            return Html::a('<i class="fa fa-edit"></i>', 'javascript:void(0)', [
                'onClick' => "showModalTandatangan(event, $this->id, '$nik')",
                'data-toggle' =>'tooltip',
                'title' => 'Tandatangan'
            ]);
        }

        return null;
    }

    public function getLinkIconRiwayat()
    {
        return Html::a('<i class="fa fa-history"></i>', 'javascript:void(0)', [
            'onClick' => "showModalRiwayat(event, $this->id)",
            'data-toggle' =>'tooltip',
            'title' => 'Riwayat'
        ]);
    }

    public function getLinkIconLog()
    {
        return Html::a('<i class="fa fa-history"></i>', 'javascript:void(0)', [
            'onClick' => "showModalLog(event, $this->id)",
            'data-toggle' =>'tooltip',
            'title' => 'Log'
        ]);
    }

    public function getLinkIconView()
    {
        return Html::a('<i class="fa fa-eye"></i>', 'javascript:void(0)', [
            'onClick' => "showModalViewBerkas(event, $this->id)",
            'data-toggle' =>'tooltip',
            'title' => 'Rincian'
        ]);
    }

    public function getLinkIconDelete() 
    {
        if(!$this->accessDelete()) {
            return null;
        }

        return Html::a('<i class="fa fa-trash"></i>', [
            '/tandatangan/berkas/delete',
            'id' => $this->id
        ], [
            'data-toggle'=>'tooltip',
            'title' => 'Hapus Berkas',
            'data-method' => 'POST',
            'data-confirm' => 'Yakin akan menghapus data?',
        ]);
    }

    public function accessTandatangan()
    {
        if(BaseUser::isAdmin()) {
            return true;
        }

        if(BaseUser::isPegawai()) {
            return true;
        }

        return false;
    }

    public function accessDelete() {

        if($this->id_berkas_status == BerkasStatus::SELESAI) {

            if(BaseUser::isAdmin()) {
                return true;
            }

            return false;

        }

        return true;
    }

    public static function findFileBerkas($searchModel, $id_berkas_jenis)
    {
        $url = @Yii::$app->params['url_tandatangan'];

        $instansi = Instansi::findOne($searchModel->id_instansi);
        $nip_tandatangan = @$instansi->pegawaiKepala->nip;
        
        $query = Berkas::find();
        $query->andWhere(['tahun' => Session::getTahun()]);
        $query->andWhere(['bulan' => $searchModel->bulan]);
        $query->andWhere(['id_berkas_jenis' => $id_berkas_jenis]);
        $query->andWhere(['id_instansi' => $searchModel->id_instansi]);
        $query->andFilterWhere(['nip_tandatangan' => $nip_tandatangan]);

        $model = $query->one();

        if($model === null) {
            return null;
        }

        $berkas_mentah = $url.'/berkas-mentah/'.$model->berkas_mentah;
        $berkas_tandatangan = $url.'/berkas-tandatangan/'.$model->berkas_tandatangan;

        if(@fopen($berkas_tandatangan, 'r') != false) {
            return $berkas_tandatangan;
        }

        if(@fopen($berkas_mentah, 'r') != false) {
            return $berkas_mentah;
        }

        return null;
    }

    public function generateRiwayat($id_riwayat_jenis, $id_user=null)
    {
        $id_user = $this->getIdUserTandatangan();

        $model = new Riwayat();
        $model->id_berkas = $this->id;
        $model->id_user = $id_user;
        $model->id_riwayat_jenis = $id_riwayat_jenis;
        $model->created_at = date('Y-m-d H:i:s');
        $model->updated_at = date('Y-m-d H:i:s');
        $model->save();
    }

    protected function getIdUserTandatangan()
    {
        $username = Session::getUsername();
        if($username == null) {
            print_r('username tidak ditemukan');die;
        }

        $user = User::findOne(['username' => $username]);

        if($user == null) {
            $user = new User();
            $user->username = $username;
            $user->created_at = date('Y-m-d H:i:s');
            $user->updated_at = date('Y-m-d H:i:s');
            if(!$user->save()) {
                print_r($user->getErrors());die;
            }
        }

        return $user->id;
    }
}
