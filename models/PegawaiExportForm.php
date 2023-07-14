<?php


namespace app\models;


use app\components\Helper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\base\Model;

class PegawaiExportForm extends Model
{
    public $id_instansi;
    public $kolom_export;
    public $pilih_semua_kolom;

    CONST NIP = 'NIP';
    CONST JENIS_KELAMIN = 'JENIS_KELAMIN';
    CONST TEMPAT_LAHIR = 'TEMPAT_LAHIR';
    CONST TANGGAL_LAHIR = 'TANGGAL_LAHIR';
    CONST ALAMAT = 'ALAMAT';
    CONST TELEPON = 'TELEPON';
    CONST EMAIL = 'EMAIL';
    CONST INSTANSI = 'INSTANSI';
    CONST JABATAN = 'JABATAN';
    CONST GOLONGAN = 'GOLONGAN';
    CONST JENIS_JABATAN = 'JENIS_JABATAN';
    CONST KELAS_JABATAN = 'KELAS_JABATAN';
    CONST NILAI_JABATAN = 'NILAI_JABATAN';
    CONST TANGGAL_TMT = 'TANGGAL_TMT';
    CONST TANGGAL_MULAI = 'TANGGAL_MULAI';
    CONST SHIFT_KERJA = 'SHIFT_KERJA';
    CONST STATUS_PEGAWAI = 'STATUS_PEGAWAI';
    CONST ESELON_JABATAN = 'ESELON_JABATAN';
    CONST NAMA_ATASAN = 'NAMA_ATASAN';
    CONST JENJANG_FUNGSIONAL = 'JENJANG_FUNGSIONAL';

    public function rules()
    {
        return [
            [['id_instansi', 'kolom_export','pilih_semua_kolom'],'safe']
        ];
    }

    public static function getListKolom()
    {
        return [
            static::NIP => 'NIP',
            static::JENIS_KELAMIN => 'Jenis Kelamin',
            static::TEMPAT_LAHIR => 'Tempat Lahir',
            static::TANGGAL_LAHIR => 'Tanggal Lahir',
            static::ALAMAT => 'Alamat',
            static::TELEPON => 'Telepon',
            static::EMAIL => 'Email',
            static::INSTANSI => 'Unit Kerja',
            static::JABATAN => 'Jabatan',
            static::GOLONGAN => 'Golongan',
            static::JENIS_JABATAN => 'Jenis Jabatan',
            static::KELAS_JABATAN => 'Kelas Jabatan',
            static::NILAI_JABATAN => 'Nilai Jabatan',
            static::TANGGAL_TMT => 'Tanggal TMT',
            static::TANGGAL_MULAI => 'Tanggal Mulai',
            static::SHIFT_KERJA => 'Shift Kerja',
            static::STATUS_PEGAWAI => 'Status Pegawai',
            static::ESELON_JABATAN => 'Eselon Jabatan',
            static::NAMA_ATASAN => 'Nama Atasan',
            static::JENJANG_FUNGSIONAL => 'Jenjang Fungsional',
        ];
    }

    public function exportDatPegawai($params, $filename)
    {
        ini_set('max_execution_time', '300');
        ini_set('memory_limit', '1024M');

        $chrDefault = 67;
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->garbageCollect();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(35);
        $sheet->getColumnDimension('E')->setWidth(35);
        $sheet->getColumnDimension('F')->setWidth(35);
        $sheet->getColumnDimension('G')->setWidth(35);
        $sheet->getColumnDimension('H')->setWidth(35);
        $sheet->getColumnDimension('I')->setWidth(35);
        $sheet->getColumnDimension('J')->setWidth(35);
        $sheet->getColumnDimension('K')->setWidth(35);
        $sheet->getColumnDimension('L')->setWidth(35);
        $sheet->getColumnDimension('M')->setWidth(35);
        $sheet->getColumnDimension('N')->setWidth(35);
        $sheet->getColumnDimension('O')->setWidth(35);
        $sheet->getColumnDimension('P')->setWidth(35);
        $sheet->getColumnDimension('Q')->setWidth(35);
        $sheet->getColumnDimension('R')->setWidth(35);
        $sheet->getColumnDimension('S')->setWidth(35);

        $instansiPegawaiSearch = new InstansiPegawaiSearch();
        $instansiPegawaiSearch->tahun = date('Y');
        $instansiPegawaiSearch->bulan = date('m');

        $querySearch = $instansiPegawaiSearch->getQuerySearch();
        $querySearch->with(['pegawai.pegawaiGolongan', 'instansi', 'jabatan']);
        if (!empty($this->id_instansi)) {
            $querySearch->andWhere(['pegawai.id_instansi' => $this->id_instansi]);
        }

        $sheet->setCellValue("A1", "Daftar Pegawai Bulan " . Helper::getBulanLengkap($instansiPegawaiSearch->bulan) . " Tahun " . $instansiPegawaiSearch->tahun);
        $sheet->getStyle("A1")->getFont()->setBold(true);

        $sheet->mergeCells('A1:E1');

        $headerAwal = $header = 3;
        $sheet
            ->setCellValue("A$header", 'NO')
            ->setCellValue("B$header", "NAMA PEGAWAI");

        if ($this->pilih_semua_kolom) {
            $sheet->setCellValue("C$header", "NIP");
            $sheet->setCellValue("D$header", "JENIS KELAMIN");
            $sheet->setCellValue("E$header", "TEMPAT LAHIR");
            $sheet->setCellValue("F$header", "TANGGAL LAHIR");
            $sheet->setCellValue("G$header", "ALAMAT");
            $sheet->setCellValue("H$header", "TELEPON");
            $sheet->setCellValue("I$header", "EMAIL");
            $sheet->setCellValue("J$header", "UNIT KERJA");
            $sheet->setCellValue("K$header", "JABATAN");
            $sheet->setCellValue("L$header", "GOLONGAN");
            $sheet->setCellValue("M$header", "JENIS JABATAN");
            $sheet->setCellValue("N$header", "KELAS JABATAN");
            $sheet->setCellValue("O$header", "NILAI JABATAN");
            $sheet->setCellValue("P$header", "TANGGAL TMT JABATAN");
            $sheet->setCellValue("Q$header", "TANGGAL MULAI EFEKTIF");
            $sheet->setCellValue("R$header", "SHIFT KERJA");
            $sheet->setCellValue("S$header", "STATUS PEGAWAI");

            $chrDefault = 83;
        } else {
            if (!empty($this->kolom_export)) {
                foreach ($this->kolom_export as $key => $value) {
                    $sheet->setCellValue(Helper::chr($chrDefault).$header, $value);

                    $chrDefault++;
                }
            }
        }

        $sheet->getStyle("A$headerAwal:".Helper::chr($chrDefault)."$header")
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ],
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

        $row = $header;
        $i = 1;

        $queryPegawai = $querySearch->all();

        foreach ($queryPegawai as $instansiPegawai) {
            $row++;
            $sheet->setCellValue("A$row", $i);
            $sheet->setCellValue("B$row", @$instansiPegawai->pegawai->nama);
            if ($this->pilih_semua_kolom) {
                $sheet->setCellValue("C$row", @$instansiPegawai->pegawai->nipFormat);
                $sheet->setCellValue("D$row", @$instansiPegawai->pegawai->gender);
                $sheet->setCellValue("E$row", @$instansiPegawai->pegawai->tempat_lahir);
                $sheet->setCellValue("F$row", Helper::getTanggal(@$instansiPegawai->pegawai->tanggal_lahir));
                $sheet->setCellValue("G$row", @$instansiPegawai->pegawai->alamat);
                $sheet->setCellValue("H$row", @$instansiPegawai->pegawai->telepon);
                $sheet->setCellValue("I$row", " ".@$instansiPegawai->pegawai->email);
                $sheet->setCellValue("J$row", @$instansiPegawai->instansi->nama);
                $sheet->setCellValue("K$row", @$instansiPegawai->getNamaJabatan(false));
                $sheet->setCellValue("L$row", @$instansiPegawai->pegawai->pegawaiGolongan->golongan->golongan);
                $sheet->setCellValue("M$row", @$instansiPegawai->jabatan->jenisJabatan);
                $sheet->setCellValue("N$row", @$instansiPegawai->jabatan->kelas_jabatan);
                $sheet->setCellValue("O$row", @$instansiPegawai->jabatan->nilai_jabatan);
                $sheet->setCellValue("P$row", Helper::getTanggal(@$instansiPegawai->tanggal_berlaku));
                $sheet->setCellValue("Q$row", Helper::getTanggal(@$instansiPegawai->tanggal_mulai));
                $sheet->setCellValue("R$row", @$instansiPegawai->pegawai->getNamaShiftKerja());
                $sheet->setCellValue("S$row", @$instansiPegawai->getTextStatusPegawai());
            } else {
                $chrValue = 67;
                if (!empty($this->kolom_export)) {
                    foreach ($this->kolom_export as $key => $value) {
                        $valueKolom = '';
                        switch ($value) {
                            case static::NIP:
                                $valueKolom .= @$instansiPegawai->pegawai->nipFormat;
                                break;
                            case static::JENIS_KELAMIN:
                                $valueKolom .= @$instansiPegawai->pegawai->gender;
                                break;
                            case static::TEMPAT_LAHIR:
                                $valueKolom .= @$instansiPegawai->pegawai->tempat_lahir;
                            case static::TANGGAL_LAHIR:
                                $valueKolom .= Helper::getTanggal(@$instansiPegawai->pegawai->tanggal_lahir);
                                break;
                            case static::ALAMAT:
                                $valueKolom .= @$instansiPegawai->pegawai->alamat;
                                break;
                            case static::TELEPON:
                                $valueKolom .= @$instansiPegawai->pegawai->telepon;
                                break;
                            case static::EMAIL:
                                $valueKolom .= @$instansiPegawai->pegawai->email;
                                break;
                            case static::INSTANSI:
                                $valueKolom .= @$instansiPegawai->instansi->nama;
                                break;
                            case static::JABATAN:
                                $valueKolom .= @$instansiPegawai->getNamaJabatan(false);
                                break;
                            case static::GOLONGAN:
                                $valueKolom .= @$instansiPegawai->pegawai->pegawaiGolongan->golongan->golongan;
                                break;
                            case static::JENIS_JABATAN:
                                $valueKolom .= @$instansiPegawai->jabatan->jenisJabatan;
                                break;
                            case static::KELAS_JABATAN:
                                $valueKolom .= @$instansiPegawai->jabatan->kelas_jabatan;
                                break;
                            case static::NILAI_JABATAN:
                                $valueKolom .= @$instansiPegawai->jabatan->nilai_jabatan;
                                break;
                            case static::TANGGAL_TMT:
                                $valueKolom .= Helper::getTanggal(@$instansiPegawai->tanggal_berlaku);
                                break;
                            case static::TANGGAL_MULAI:
                                $valueKolom .= Helper::getTanggal(@$instansiPegawai->tanggal_mulai);
                                break;
                            case static::SHIFT_KERJA:
                                $valueKolom .= @$instansiPegawai->pegawai->getNamaShiftKerja();
                                break;
                            case static::STATUS_PEGAWAI:
                                $valueKolom .= @$instansiPegawai->getTextStatusPegawai();
                                break;
                            case static::ESELON_JABATAN:
                                $valueKolom .= @$instansiPegawai->jabatan->eselon->nama;
                                break;
                            case static::NAMA_ATASAN:
                                $valueKolom .= @$instansiPegawai->getNamaAtasan();
                                break;
                            case static::JENJANG_FUNGSIONAL:
                                $valueKolom .= @$instansiPegawai->jabatan->tingkatanFungsional->nama;
                                break;
                        }
                        $sheet->setCellValue(Helper::chr($chrValue) . $row, $valueKolom);

                        $chrValue++;
                    }
                }
            }

            $i++;
        }


        $sheet->getStyle("A".$header.":A".$row)
            ->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
        $sheet->getStyle("C".$header.":C".$row)
            ->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
        $sheet->getStyle("F".$header.":S".$row)
            ->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
        $sheet->getStyle("A".$header.":".Helper::chr($chrDefault).$row)
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ],
            ]);

        //$sheet->getStyle("A1:S$row")->getAlignment()->setWrapText(true);


        $path = 'exports/'.$filename;
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);
    }
}
