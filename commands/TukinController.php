<?php


namespace app\commands;


use app\models\Jabatan;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\console\widgets\Table;
use yii\helpers\Console;

class TukinController extends Controller
{
    public function actionImport()
    {
        $fileDir = 'data/tukin-evajab.xlsx';
        if (!file_exists($fileDir)) {
            $this->stdout('File tidak ditemukan : ' . pathinfo($fileDir, PATHINFO_BASENAME), Console::FG_RED, Console::BOLD);
            return 0;
        }

        $reader = IOFactory::load($fileDir);
        $reader->setActiveSheetIndexByName('LAMPIRAN V (2)');
        $sheet = $reader->getActiveSheet();
        $i = 217;
        // $highestRow = $i + 10;
        $highestRow = 2385;
        $updated = $jabatan = $found = 0;
        $missing = [];
        for ($i; $i <= $highestRow; $i++) {
            $b = $sheet->getCell("B$i");
            $str = trim($b->getCalculatedValue());
            if (preg_match('/^\d/', $str) === 1) {
                // Row adalah instansi
                $this->stdout("$str\n", Console::FG_RED);
            } elseif ($b->getStyle()->getFont()->getBold()) {
                // Row adalah kategori
                $this->stdout("$str\n", Console::FG_GREEN);
            } else {
                // Row adalah jabatan
                $jabatan++;
                $str = str_ireplace('jf', '', $str);
                $str2 = str_ireplace('&', 'dan', $str);
                if ($str2 === $str) {
                    $str2 = null;
                }
                $str3 = str_ireplace('  ', ' ', $str);
                if ($str3 === $str) {
                    $str3 = null;
                }

                $result = Jabatan::find()
                    ->andFilterWhere([
                        'or',
                        ['like', 'nama', trim($str)],
                        ['like', 'nama', trim($str2)],
                        ['like', 'nama', trim($str3)],
                    ])
                    ->one();
                $kelasJabatan = $sheet->getCell("C$i")->getCalculatedValue();
                $nilaiJabatan = $sheet->getCell("D$i")->getCalculatedValue();
                if ($result !== null) {
                    $result->updateAttributes([
                        'status_impor' => true,
                        'kelas_jabatan' => $kelasJabatan,
                        'nilai_jabatan' => $nilaiJabatan
                    ]);
                    if ($result->kelas_jabatan !== (int) $kelasJabatan) {
                        $this->stdout("$str\n", Console::FG_YELLOW);
                        $updated++;
                    } else {
                        $this->stdout("$str\n", Console::FG_BLUE);
                    }
                    $found++;
                } else {
                    $missing[] = "$i. $str :$kelasJabatan;$nilaiJabatan";
                    $this->stdout("$str\n");
                }
            }
        }
        $this->stdout("Result : $found/$jabatan in which $updated updated and " . ($jabatan - $found) . ' missing');
        file_put_contents('fungsional.log', implode(PHP_EOL, $missing));
    }

    public function actionImportStruktural()
    {
        $fileDir = 'data/tukin-evajab.xlsx';
        if (!file_exists($fileDir)) {
            $this->stdout('File tidak ditemukan : ' . pathinfo($fileDir, PATHINFO_BASENAME), Console::FG_RED, Console::BOLD);
            return 0;
        }

        $reader = IOFactory::load($fileDir);
        $reader->setActiveSheetIndexByName('LAMPIRAN IV');
        $sheet = $reader->getActiveSheet();
        $i = 11;
        // $highestRow = $i + 10;
        $highestRow = 1040;
        $updated = $jabatan = $found = 0;
        $missing = [];
        for ($i; $i <= $highestRow; $i++) {
            $b = $sheet->getCell("B$i");
            $str = trim($b->getCalculatedValue());
            if (preg_match('/^\d/', $str) === 1) {
                // Row adalah instansi
                $this->stdout("$str\n", Console::FG_RED);
            } elseif ($b->getStyle()->getFont()->getBold()) {
                // Row adalah kategori
                $this->stdout("$str\n", Console::FG_GREEN);
            } else {
                // Row adalah jabatan
                $jabatan++;
                $str2 = str_ireplace('SubBagian', 'Sub Bagian', $str);
                if ($str2 === $str) {
                    $str2 = null;
                }
                $str3 = str_ireplace('  ', ' ', $str);
                if ($str3 === $str) {
                    $str3 = null;
                }
                $str4 = str_ireplace('Sub Bagian', 'SubBagian', $str);
                if ($str4 === $str) {
                    $str4 = null;
                }

                $result = Jabatan::find()
                    ->andFilterWhere([
                        'or',
                        ['like', 'nama', trim($str)],
                        ['like', 'nama', trim($str2)],
                        ['like', 'nama', trim($str3)],
                        ['like', 'nama', trim($str4)],
                    ])
                    ->one();
                $kelasJabatan = $sheet->getCell("C$i")->getCalculatedValue();
                $nilaiJabatan = $sheet->getCell("D$i")->getCalculatedValue();
                if ($result !== null) {
                    $result->updateAttributes([
                        'status_impor' => true,
                        'kelas_jabatan' => $kelasJabatan,
                        'nilai_jabatan' => $nilaiJabatan
                    ]);
                    if ($result->kelas_jabatan !== (int) $kelasJabatan) {
                        $this->stdout("$str\n", Console::FG_YELLOW);
                        $updated++;
                    } else {
                        $this->stdout("$str\n", Console::FG_BLUE);
                    }
                    $found++;
                } else {
                    $missing[] = "$i. $str :$kelasJabatan;$nilaiJabatan";
                    $this->stdout("$str\n");
                }
            }
        }
        $this->stdout("Result : $found/$jabatan in which $updated updated and " . ($jabatan - $found) . ' missing');
        file_put_contents('struktural.log', implode(PHP_EOL, $missing));
    }

    public function actionCek()
    {
        $fileDir = 'data/tukin-evajab.xlsx';
        if (!file_exists($fileDir)) {
            $this->stdout('File tidak ditemukan : ' . pathinfo($fileDir, PATHINFO_BASENAME), Console::FG_RED, Console::BOLD);
            return 0;
        }

        $reader = IOFactory::load($fileDir);
        $reader->setActiveSheetIndexByName('LAMPIRAN V (2)');
        $sheet = $reader->getActiveSheet();
        $jabatan = [];
        $jumlah = 0;
        for ($i = 10; $i <= 2385; $i++) {
            $b = $sheet->getCell("B$i");
            $str = $b->getValue();
            if ($b->getStyle()->getFont()->getBold()) {
                continue;
            }
            $jumlah++;
            $jabatan[$str][] = $i;
        }
        // Filter hanya jabatan muncul lebih dari 1x
        $filter = array_filter(
            $jabatan,
            function ($value, $key) {
                return count($value) > 1;
            },
            ARRAY_FILTER_USE_BOTH
        );
        echo count($filter) . "/$jumlah\n";

        foreach ($filter as $key => $value) {
            $kelas = [];
            foreach ($value as $row) {
                $float = (float) $sheet->getCell("D$row")->getCalculatedValue();
                $kelas[] = $float;
                if ((int) $float === 0) {
                    echo "row : $row - value " . $sheet->getCell("D$row")->getCalculatedValue() . "\n";
                }
            }
            if (count(array_unique($kelas)) === 1) {
                // $this->stdout($key . "\n", Console::FG_GREEN);
            } else {
                $this->stdout($key . '(' . implode(', ', array_unique($kelas)) . ')'. "\n");
            }
        }
    }

    public function actionCekStruktural()
    {
        $fileDir = 'data/tukin-evajab.xlsx';
        if (!file_exists($fileDir)) {
            $this->stdout('File tidak ditemukan : ' . pathinfo($fileDir, PATHINFO_BASENAME), Console::FG_RED, Console::BOLD);
            return 0;
        }

        $reader = IOFactory::load($fileDir);
        $reader->setActiveSheetIndexByName('LAMPIRAN IV');
        $sheet = $reader->getActiveSheet();
        $jabatan = [];
        $jumlah = 0;
        for ($i = 11; $i <= 1040; $i++) {
            $b = $sheet->getCell("B$i");
            $str = $b->getCalculatedValue();
            $str = str_ireplace('SubBagian', 'Sub Bagian', $str);
            $str = str_ireplace('  ', ' ', $str);
            if ($b->getStyle()->getFont()->getBold()) {
                continue;
            }
            $jumlah++;
            $jabatan[$str][] = $i;
        }
        // Filter hanya jabatan muncul lebih dari 1x
        $filter = array_filter(
            $jabatan,
            function ($value, $key) {
                return count($value) > 1;
            },
            ARRAY_FILTER_USE_BOTH
        );
        echo count($filter) . "/$jumlah\n";

        foreach ($filter as $key => $value) {
            $kelas = [];
            foreach ($value as $row) {
                $float = (float) $sheet->getCell("D$row")->getCalculatedValue();
                $kelas[] = $float;
                if ((int) $float === 0) {
                    echo "row : $row - value " . $sheet->getCell("D$row")->getCalculatedValue() . "\n";
                }
            }
            if (count(array_unique($kelas)) === 1) {
                // $this->stdout($key . "\n", Console::FG_GREEN);
            } else {
                $this->stdout($key . '(' . implode(', ', array_unique($kelas)) . ')'. "\n");
            }
        }

    }

    public function actionFix($nama, $kelas, $nilai)
    {
        $jabatan = Jabatan::find()->andWhere(['like', 'nama', $nama])->andWhere(['status_impor' => false])->all();
        if ($jabatan !== []) {
            $result = $jabatan[0];
            $list = array_map(
                function (Jabatan $jabatan) {
                    return $jabatan->nama;
                },
                $jabatan
            );
            $list = array_unique($list);
            $confirm = Console::confirm('Jabatan yang ditemukan :' . implode('; ', $list) . '( ' . count($jabatan) . ')');
            if ($confirm) {
                $table = new Table([
                    'headers' => ['', 'Prev', 'Fix']
                ]);
                $table->setRows([
                    ['id', $result->id, '-'],
                    ['nama', $result->nama, '-'],
                    ['jumlah', count($jabatan), '-'],
                    ['kelas', $result->kelas_jabatan, $kelas],
                    ['nilai', $result->nilai_jabatan, $nilai],
                ]);
                echo $table->run();
                $confirm = Console::confirm('Update jabatan?');
                if ($confirm) {
                    foreach ($jabatan as $item) {
                        $item->updateAttributes([
                            'kelas_jabatan' => $kelas,
                            'nilai_jabatan' => $nilai,
                            'status_impor' => true,
                        ]);
                    }
                    return ExitCode::OK;
                }
            }
            $this->stdout('Cancelled');
            return ExitCode::OK;
        }
        $this->stdout('Not found');
        return ExitCode::OK;
    }
}
