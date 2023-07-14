<?php

namespace app\models;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImportPegawaiGolonganForm extends Model
{
    public $kolom_nip = 'C';
    public $kolom_golongan = 'D';
    public $kolom_tmt = 'E';
    public $baris_awal;
    public $baris_akhir;
    public $berkas;

    /**
     * @var UploadedFile
     */
    public $berkasUploaded;

    public function rules()
    {
        return [
            [['kolom_nip', 'kolom_golongan', 'kolom_tmt', 'baris_awal', 'baris_akhir', 'berkas'], 'required'],
            [
                'baris_akhir',
                'compare',
                'compareAttribute' => 'baris_awal',
                'operator' => '>=',
                'enableClientValidation' => false,
                'message' => '{attribute} harus lebih dari atau sama dengan "{compareValueOrAttribute}".'
            ],
        ];
    }

    public function upload()
    {
        if ($this->berkasUploaded === null) {
            return true;
        }

        $this->setFileName();
        if ($this->berkasUploaded instanceof UploadedFile && $this->validate()) {
            $path = Yii::getAlias('@app/files');

            $this->berkasUploaded->saveAs("$path/{$this->berkas}");
            return true;
        }
        return false;
    }

    protected function setFileName()
    {
        $time = time();
        $baseName = str_replace(' ', '_', $this->berkasUploaded->baseName);
        $extension = $this->berkasUploaded->extension;
        $this->berkas =  "{$baseName}_{$time}.{$extension}";
    }

    public function execute()
    {
        $fileDir = "../files/$this->berkas";
        if (!file_exists($fileDir)) {
            echo 'File tidak ditemukan : ' . pathinfo($fileDir, PATHINFO_BASENAME);
            die;
        }
        $reader = IOFactory::load($fileDir);
        $reader->setActiveSheetIndex(0);
        $sheet = $reader->getActiveSheet();

        for ($i = $this->baris_awal; $i <= $this->baris_akhir; $i++) {
            $nip = trim($sheet->getCell("$this->kolom_nip$i")->getValue());
            $gol = trim($sheet->getCell("$this->kolom_golongan$i")->getValue());
            $tmt = trim($sheet->getCell("$this->kolom_tmt$i")->getValue());

            $pegawai = Pegawai::findOne(['nip' => $nip]);
            $golongan = Golongan::findOne(['golongan' => $gol]);

            $datetime = \DateTime::createFromFormat('d-m-Y', $tmt);

            if ($pegawai == null OR $golongan == null OR $datetime == false) {
                continue;
            }

            $tanggal = $datetime->format('Y-m-d');
            $pegawaiGolongan = $pegawai->getManyPegawaiGolongan()
                ->andWhere(['id_golongan' => $golongan->id])
                ->andWhere(['tanggal_berlaku' => $tanggal])
                ->andWhere(['tanggal_mulai' => $tanggal])
                ->one();

            if ($pegawaiGolongan != null) {
                continue;
            }

            $model = new PegawaiGolongan();
            $model->id_pegawai = $pegawai->id;
            $model->id_golongan = $golongan->id;
            $model->tanggal_berlaku = $tanggal;
            $model->tanggal_mulai = $tanggal;

            $model->setTanggalMulai();
            $model->setTanggalSelesai();

            if ($model->save()) {
                $model->updateMundurTanggalSelesai();
            }
        }

        return true;
    }
}
