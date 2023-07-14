<?php

namespace app\models;

use Yii;
use yii\widgets\ActiveForm;

/**
 * This is the model class for table "pengaturan_berlaku".
 *
 * @property int $id
 * @property int $id_pengaturan
 * @property string $nilai
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @see PengaturanBerlaku::getPengaturan()
 * @property-read string $namaNilai
 * @property-read mixed $namaPengaturan
 * @property Pengaturan $pengaturan
 */
class PengaturanBerlaku extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengaturan_berlaku';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pengaturan', 'nilai', 'tanggal_mulai', 'tanggal_selesai'], 'required'],
            [['id_pengaturan'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai'], 'safe'],
            [['nilai'], 'string', 'max' => 255],
            [['tanggal_mulai'], 'unique',
                'targetAttribute' => ['id_pengaturan','tanggal_mulai'],
                'message' => 'Sudah ada data pada {attribute} tersebut. Silahkan ganti {attribute}'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pengaturan' => 'Id Pengaturan',
            'nilai' => 'Nilai',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
        ];
    }

    public function getPengaturan()
    {
        return $this->hasOne(Pengaturan::class,['id'=>'id_pengaturan']);
    }

    public function getNamaPengaturan()
    {
        return @$this->pengaturan->nama;
    }

    public static function findArrayDropdownNilaiId4()
    {
        return [
            '1' => 'Berdasarkan Jenis Jabatan dan Pertimbangan Lain',
            '2' => 'Berdasarkan Kelas Jabatan'
        ];
    }

    public static function findArrayDropdownNilaiId5()
    {
        return [
            '1' => 'Tampil',
            '2' => 'Sembunyikan'
        ];
    }

    public function getFormFieldNilai(ActiveForm $form, $params = [])
    {
        if($this->id_pengaturan == Pengaturan::ID_METODE_HITUNG_BESAR_TPP_AWAL) {
            return $form->field($this,'nilai')->dropDownList(PengaturanBerlaku::findArrayDropdownNilaiId4());
        }

        if($this->id_pengaturan == Pengaturan::ID_TAMPILKAN_NILAI_RUPIAH_TUNJANGAN_PADA_USER_PEGAWAI) {
            return $form->field($this,'nilai')->dropDownList(PengaturanBerlaku::findArrayDropdownNilaiId5());
        }

        return $form->field($this,'nilai')->textInput();
    }

    public function setTanggalSelesai()
    {
        if($this->tanggal_selesai == null) {
            $this->tanggal_selesai = '9999-12-31';
        }

        return $this->tanggal_selesai;
    }

    public function getNamaNilai()
    {
        if($this->id_pengaturan == Pengaturan::ID_METODE_HITUNG_BESAR_TPP_AWAL) {
            $array = PengaturanBerlaku::findArrayDropdownNilaiId4();
            return @$array[$this->nilai];
        }

        return $this->nilai;

    }

    public function adjustTanggalSelesai()
    {
        $queryPrev = PengaturanBerlaku::find();
        $queryPrev->andWhere(['id_pengaturan' => $this->id_pengaturan]);
        $queryPrev->andWhere('tanggal_mulai < :tanggal_mulai',[
            ':tanggal_mulai' => $this->tanggal_mulai
        ]);
        $queryPrev->orderBy(['tanggal_mulai'=>SORT_DESC]);
        $modelPrev = $queryPrev->one();

        if($modelPrev !== null) {
            $datetime = \DateTime::createFromFormat('Y-m-d', $this->tanggal_mulai);
            $datetime->modify('-1 day');

            $modelPrev->updateAttributes([
                'tanggal_selesai' => $datetime->format('Y-m-d')
            ]);
        }

        $queryNext = PengaturanBerlaku::find();
        $queryNext->andWhere(['id_pengaturan' => $this->id_pengaturan]);
        $queryNext->andWhere('tanggal_mulai > :tanggal_mulai',[
            ':tanggal_mulai' => $this->tanggal_mulai
        ]);
        $queryNext->orderBy(['tanggal_mulai' => SORT_ASC]);
        $modelNext = $queryNext->one();

        if($modelNext !== null) {
            $datetime = \DateTime::createFromFormat('Y-m-d', $modelNext->tanggal_mulai);
            $datetime->modify('-1 day');
            $this->updateAttributes([
                'tanggal_selesai' => $datetime->format('Y-m-d')
            ]);
        }
    }
}
