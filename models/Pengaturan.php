<?php

namespace app\models;

use app\components\Session;
use Yii;
use app\components\Helper;
use kartik\editable\Editable;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "pengaturan".
 *
 * @property int $id
 * @property string $nama
 * @property string $nilai
 * @property-read mixed $inputTypeEditable
 * @property-read bool $isAktif
 * @property-read bool $isTipeVarchar
 * @see Pengaturan::getDisplayNilai()
 * @property-read string $displayNilai
 * @property-read bool $isTipeTanggal
 * @property-read bool $isTipeBoolean
 * @property-read string $editableNilai
 * @property-read mixed $editableNilaiId5
 * @property-read string $namaNilai
 * @property int $tipe
 */
class Pengaturan extends \yii\db\ActiveRecord
{
    const TIPE_VARCHAR = 1;
    const TIPE_TANGGAL = 2;
    const TIPE_BOOLEAN = 3;
    const ID_METODE_HITUNG_BESAR_TPP_AWAL = 4;
    const ID_TAMPILKAN_NILAI_RUPIAH_TUNJANGAN_PADA_USER_PEGAWAI = 5;

    const BATAS_PENGAJUAN = 1;
    const TANGGAL_BATAS_PENGAJUAN_BERLAKU = 2;
    const JUMLAH_BATAS_PENGAJUAN_HARI_KERJA = 3;
    const METODE_PERHITUNGAN_BESAR_TPP_AWAL = 4;
    const TAMPILKAN_NILAI_RUPIAH_TUNJANGAN_PADA_USER_PEGAWAI = 5;
    const CEK_IMEI = 6;
    const TANGGAL_MULAI_POTONGAN_PRESENSI_MOBILE = 7;
    const PERSEN_POTONGAN_CKHP = 8;
    const PERSEN_DIBAYAR_CUTI_ALASAN_PENTING = 9;
    const PERSEN_DIBAYAR_CUTI_SAKIT = 10;
    const PERSEN_POTONGAN_INDEKS_PROFESIONALITAS = 11;
    const MINIMAL_SKOR_IP_ASN = 12;

    public function __toString()
    {
        return $this->nilai;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengaturan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama', 'nilai', 'tipe'], 'required'],
            [['tipe'], 'integer'],
            [['nama'], 'unique'],
            [['nama', 'nilai'], 'string', 'max' => 255],
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
            'nilai' => 'Nilai',
            'tipe' => 'Tipe',
        ];
    }

    public function getIsTipeVarchar()
    {
        return $this->tipe === self::TIPE_VARCHAR;
    }

    public function getIsTipeTanggal()
    {
        return $this->tipe === self::TIPE_TANGGAL;
    }

    public function getIsTipeBoolean()
    {
        return $this->tipe === self::TIPE_BOOLEAN;
    }

    public function getIsAktif()
    {
        if ($this->getIsTipeBoolean()) {
            return (bool) $this->nilai === true;
        }
        return false;
    }

    public function getNama()
    {
        $nama = explode('_', $this->nama);
        foreach ($nama as &$value) {
            $value = ucfirst($value);
        }

        return implode(' ', $nama);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getEditableNilai()
    {
        if($this->id == Pengaturan::ID_METODE_HITUNG_BESAR_TPP_AWAL) {
            return $this->getEditableNilaiId4();
        }

        if($this->id == Pengaturan::ID_TAMPILKAN_NILAI_RUPIAH_TUNJANGAN_PADA_USER_PEGAWAI) {
            return $this->getEditableNilaiId5();
        }

        return Editable::widget([
            'model' => $this,
            'name' => 'nilai',
            'value' => $this->nilai,
            'displayValue' => $this->getDisplayNilai(),
            'beforeInput' => Html::hiddenInput('editableKey', $this->id),
            'asPopover' => true,
            'placement' => 'top',
            'formOptions' => ['action' => ['pengaturan/editable-update']],
            'header' => 'Jumlah',
            'inputType' => $this->getInputTypeEditable(),
            'data' => [1 => 'Aktif', 0 => 'Tidak Aktif'],
            'options'=>[
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]
        ]);
    }

    public function getEditableNilaiId4()
    {
        return Editable::widget([
            'model' => $this,
            'name' => 'nilai',
            'value' => $this->nilai,
            'displayValue' => $this->getNamaNilai(),
            'beforeInput' => Html::hiddenInput('editableKey', $this->id),
            'asPopover' => true,
            'placement' => 'top',
            'formOptions' => ['action' => ['pengaturan/editable-update']],
            'header' => 'Jumlah',
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => Pengaturan::findArrayDropdownNilaiId4(),
            'options'=>[
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]
        ]);
    }

    public function getEditableNilaiId5()
    {
        return Editable::widget([
            'model' => $this,
            'name' => 'nilai',
            'value' => $this->nilai,
            'displayValue' => $this->getNamaNilai(),
            'beforeInput' => Html::hiddenInput('editableKey', $this->id),
            'asPopover' => true,
            'placement' => 'top',
            'formOptions' => ['action' => ['pengaturan/editable-update']],
            'header' => 'Jumlah',
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => Pengaturan::findArrayDropdownNilaiId5(),
            'options'=>[
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]
        ]);
    }

    public function getDisplayNilai()
    {
        if ($this->getIsTipeTanggal()) {
            return Helper::getTanggal($this->nilai);
        }

        if ($this->getIsTipeBoolean()) {
            return (int) $this->nilai === 1 ? 'Aktif' : 'Tidak Aktif';
        }

        return $this->nilai;
    }

    public static function getPengaturanByNama($nama)
    {
        static $pengaturan;
        if ($pengaturan === null) {
            $pengaturan = Pengaturan::find()->indexBy('nama')->all();
        }

        return $pengaturan[$nama];
    }

    public static function nilaiByNama($nama)
    {
        $model = Pengaturan::findOne(['nama'=>$nama]);
        return @$model->nilai;
    }

    public function getInputTypeEditable()
    {
        if ($this->getIsTipeBoolean()) {
            return Editable::INPUT_DROPDOWN_LIST;
        } elseif ($this->getIsTipeTanggal()) {
            return Editable::INPUT_DATE;
        }

        return Editable::INPUT_TEXT;
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

    public static function findArrayDropdown()
    {
        $query = Pengaturan::find();

        return ArrayHelper::map($query->all(),'id','nama');
    }

    public function getNamaNilai()
    {
        if($this->id == Pengaturan::METODE_PERHITUNGAN_BESAR_TPP_AWAL) {
            $array = Pengaturan::findArrayDropdownNilaiId4();
            return @$array[$this->nilai];
        }

        if($this->id == Pengaturan::TAMPILKAN_NILAI_RUPIAH_TUNJANGAN_PADA_USER_PEGAWAI) {
            $array = Pengaturan::findArrayDropdownNilaiId5();
            return @$array[$this->nilai];
        }

        return $this->nilai;

    }

    public static function findNilai($params = [])
    {
        $model = Pengaturan::findOne([
            'id' => @$params['id']
        ]);

        return @$model->nilai;
    }

    public function getLinkPengaturanBerlakuIcon(): string
    {
        return Html::a('<i class="fa fa-calendar"></i>',[
            '/pengaturan-berlaku/index',
            'id_pengaturan' => $this->id
        ]);
    }

    public function getEditable(): string
    {
        return Editable::widget([
            'model' => $this,
            'name' => 'nilai',
            'value' => $this->nilai,
            'beforeInput' => Html::hiddenInput('editableKey', $this->id),
            'asPopover' => true,
            'placement' => 'top',
            'formOptions' => ['action' => ['pengaturan/editable-update']],
            'header' => 'Nilai',
            'inputType' => Editable::INPUT_TEXT,
        ]);
    }

    public function getEditableBatasPengajuan(): string
    {
        return Editable::widget([
            'model' => $this,
            'name' => 'nilai',
            'value' => $this->nilai,
            'displayValue' => $this->nilai == 1 ? 'Aktif' : 'Tidak Aktif',
            'beforeInput' => Html::hiddenInput('editableKey', $this->id),
            'asPopover' => true,
            'placement' => 'top',
            'formOptions' => ['action' => ['pengaturan/editable-update']],
            'header' => 'Nilai',
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => [1 => 'Aktif', 0 => 'Tidak Aktif'],
        ]);
    }

    public function getEditableTanggalBatasPengajuanBerlaku(): string
    {
        return Editable::widget([
            'model' => $this,
            'name' => 'nilai',
            'value' => $this->nilai,
            'displayValue' => Helper::getTanggal($this->nilai),
            'beforeInput' => Html::hiddenInput('editableKey', $this->id),
            'asPopover' => true,
            'placement' => 'top',
            'formOptions' => ['action' => ['pengaturan/editable-update']],
            'header' => 'Nilai',
            'inputType' => Editable::INPUT_DATE,
            'options'=>[
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]
        ]);
    }

    public function getEditableMetodePerhitunganBesaranTppAwal(): string
    {
        return Editable::widget([
            'model' => $this,
            'name' => 'nilai',
            'value' => $this->nilai,
            'displayValue' => $this->getNamaNilai(),
            'beforeInput' => Html::hiddenInput('editableKey', $this->id),
            'asPopover' => true,
            'placement' => 'top',
            'formOptions' => ['action' => ['pengaturan/editable-update']],
            'header' => 'Nilai',
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => Pengaturan::findArrayDropdownNilaiId4(),
        ]);
    }

    public function getEditableTampilkanNilaiRupiahTunjanganPadaUserPegawai(): string
    {
        return Editable::widget([
            'model' => $this,
            'name' => 'nilai',
            'value' => $this->nilai,
            'displayValue' => $this->getNamaNilai(),
            'beforeInput' => Html::hiddenInput('editableKey', $this->id),
            'asPopover' => true,
            'placement' => 'top',
            'formOptions' => ['action' => ['pengaturan/editable-update']],
            'header' => 'Nilai',
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => Pengaturan::findArrayDropdownNilaiId5(),
        ]);
    }

    public function getEditableCekImei(): string
    {
        return Editable::widget([
            'model' => $this,
            'name' => 'nilai',
            'value' => $this->nilai,
            'displayValue' => $this->nilai == 1 ? 'Aktif' : 'Tidak Aktif',
            'beforeInput' => Html::hiddenInput('editableKey', $this->id),
            'asPopover' => true,
            'placement' => 'top',
            'formOptions' => ['action' => ['pengaturan/editable-update']],
            'header' => 'Nilai',
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => [1 => 'Aktif', 0 => 'Tidak Aktif'],
        ]);
    }

    public function getNilaiBerlaku(array $params = [])
    {
        $tahun = @$params['tahun'];
        $bulan = @$params['bulan'];

        if ($tahun == null) {
            $tahun = Session::getTahun();
        }

        if ($bulan == null) {
            $bulan = date('n');
        }

        $datetime = \DateTime::createFromFormat('Y-n', $tahun . '-' . $bulan);

        $query = PengaturanBerlaku::find();
        $query->andWhere(['id_pengaturan' => $this->id]);
        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal', [
            ':tanggal' => $datetime->format('Y-m-15'),
        ]);

        $pengaturanBerlaku = $query->one();

        return @$pengaturanBerlaku->nilai;
    }

}
