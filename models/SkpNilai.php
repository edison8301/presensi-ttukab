<?php

namespace app\models;

use app\components\Helper;
use app\modules\api2\models\InstansiPegawaiSkp;
use dosamigos\chartjs\ChartJs;
use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "skp_nilai".
 * @property int $bulanSaatIni
 * @property int $id
 * @property int $id_instansi_pegawai_skp
 * @property int $id_skp_periode
 * @property int $periode
 * @property int $nilai_hasil_kerja
 * @property int $nilai_perilaku_kerja
 * @property string $feedback_hasil_kerja
 * @property string $feedback_perilaku_kerja
 * @property string $namaSkpPeriode
 * @property InstansiPegawaiSkp $instansiPegawaiSkp
 */
class SkpNilai extends \yii\db\ActiveRecord
{
    public const PERIODE_TAHUNAN = 1;
    public const PERIODE_TRIWULANAN = 2;
    public const PERIODE_SEMESTERAN = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'skp_nilai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_instansi_pegawai_skp', 'id_skp_periode', 'periode'], 'required'],
            [['id_instansi_pegawai_skp', 'id_skp_periode', 'periode', 'nilai_hasil_kerja', 'nilai_perilaku_kerja'], 'integer'],
            [['feedback_hasil_kerja', 'feedback_perilaku_kerja'],'string'],
  
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_instansi_pegawai_skp' => 'Id Instansi Pegawai Skp',
            'id_skp_periode' => 'Id Skp Periode',
            'periode' => 'Periode',
            'feedback_hasil_kerja' => 'Umpan Balik Hasil Kerja',
            'nilai_hasil_kerja' => 'Nilai Hasil Kerja',
            'feedback_perilaku_kerja' => 'Umpan Balik Perilaku Kerja',
            'nilai_perilaku_kerja' => 'Nilai Perilaku Kerja',
        ];
    }

    public function getInstansiPegawaiSkp()
    {
        return $this->hasOne(InstansiPegawaiSkp::class,['id' => 'id_instansi_pegawai_skp']);
    }
    public function getListKegiatanTriwulan(){
        return $this::find()->andWhere(['id_instansi_pegawai' => $this->id_instansi_pegawai_skp,'id_skp_periode'=> 2]);
    }
    public function getListKegiatanSemester(){
        return $this::find()->andWhere(['id_instansi_pegawai' => $this->id_instansi_pegawai_skp,'id_skp_periode'=> 3]);
    }
    public function getListKegiatanTahunan(){
        $query = $this::find()->andWhere(['id_instansi_pegawai' => $this->id_instansi_pegawai_skp, 'id_skp_periode'=> 1]);
        return $query;
    }
    
    public function getKurvaSkpNilai($nilai_hasil_kerja, $nilai_perilaku_kerja ,$periode = null){
        $labels = [];
        $hasilKerja = [];
        $perilakuKerja = [];
        $nilaiHasilKerja = 0;
        $nilaiPerilakuKerja = 0;
    
        if($periode == "Triwulan"){
            
            for ($i = 1; $i <= 4; $i++) {
                $nilaiHasilKerja = $nilaiHasilKerja + $nilai_hasil_kerja[$i];
                $nilaiPerilakuKerja = $nilaiPerilakuKerja + $nilai_perilaku_kerja[$i];
                $labels[] = "Triwulan " .$i;
                $hasilKerja[] = $nilaiHasilKerja;
                $perilakuKerja[] =  $nilaiPerilakuKerja;
            }
        }

        if($periode == "Semester"){
            for ($i = 1; $i <= 2; $i++) {
                $nilaiHasilKerja = $nilaiHasilKerja + $nilai_hasil_kerja[$i];
                $nilaiPerilakuKerja = $nilaiPerilakuKerja + $nilai_perilaku_kerja[$i];
                $labels[] = "Semester " .$i;
                $hasilKerja[] = $nilaiHasilKerja;
                $perilakuKerja[] = $nilaiPerilakuKerja;
            }
        }

        if($periode == "Tahunan"){
            $nilaiHasilKerja = $nilaiHasilKerja + $nilai_hasil_kerja[0];
            $nilaiPerilakuKerja = $nilaiPerilakuKerja + $nilai_perilaku_kerja[0];
                $labels[] = "Tahunan";
                $hasilKerja[] = $nilaiHasilKerja;
                $perilakuKerja[] = $nilaiPerilakuKerja;
        }
    
        $data = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nilai Hasil Kerja',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.1)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'pointBackgroundColor' => 'rgba(255, 99, 132, 1)',
                    'pointBorderColor' => '#fff',
                    'data' => $hasilKerja
                ],
                [
                    'label' => 'Nilai Perilaku Kerja',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'pointBackgroundColor' => 'rgba(54, 162, 235, 1)',
                    'pointBorderColor' => '#fff',
                    'data' => $perilakuKerja
                ],
            ],
        ];

        echo ChartJs::widget([
            'type' => 'line',
            'options' => [
                'height' => 100,
                'width' => 400,
            ],
            'data' => $data,
        ]);
    
        return $data;
        
    }

    public function getKurvaSum($nilai_hasil_kerja, $nilai_perilaku_kerja, $periode = null)
    {
        $labels = [];
        $datasets = [];
    
        if ($periode == "Triwulan") {
            $hasilKerjaTriwulan = [];
            $perilakuKerjaTriwulan = [];
            $nilaiHasilKerja = 0;
            $nilaiPerilakuKerja = 0;
    
            for ($i = 0; $i <= 3; $i++) {
                $nilaiHasilKerja += $nilai_hasil_kerja[$i];
                $nilaiPerilakuKerja += $nilai_perilaku_kerja[$i];
                $hasilKerjaTriwulan[] = $nilaiHasilKerja;
                $perilakuKerjaTriwulan[] = $nilaiPerilakuKerja;
            }
            $datasets[] = [
                'label' => 'Nilai Hasil Kerja Triwulan',
                'backgroundColor' => 'rgba(144, 238, 144, 0.1)',
                'borderColor' => 'rgba(0, 128, 0, 1)',
                'pointBackgroundColor' => 'rgba(0, 128, 0, 1)',
                'pointBorderColor' => '#fff',
                'data' => $hasilKerjaTriwulan,
            ];

            $datasets[] = [
                'label' => 'Nilai Perilaku Kerja Triwulan',
                'backgroundColor' => 'rgba(0, 100, 0, 0.2)',
                'borderColor' => 'rgba(0, 100, 0, 1)',
                'pointBackgroundColor' => 'rgba(0, 100, 0, 1)',
                'pointBorderColor' => '#fff',
                'data' => $perilakuKerjaTriwulan,
            ];

            
            
        }
    
        if ($periode == "Semester") {
            $hasilKerjaSemester = [];
            $perilakuKerjaSemester = [];
            $nilaiHasilKerja = 0;
            $nilaiPerilakuKerja = 0;
    
            for ($i = 0; $i <= 3; $i++) {
                $nilaiHasilKerja += $nilai_hasil_kerja[$i];
                $nilaiPerilakuKerja += $nilai_perilaku_kerja[$i];
                $hasilKerjaSemester[] = $nilaiHasilKerja;
                $perilakuKerjaSemester[] = $nilaiPerilakuKerja;
            }
            
            $datasets[] = [
                'label' => 'Nilai Hasil Kerja Semester',
                'backgroundColor' => 'rgba(41, 182, 246, 0.2)',
                'borderColor' => 'rgba(41, 182, 246, 1)',
                'pointBackgroundColor' => 'rgba(41, 182, 246, 1)',
                'pointBorderColor' => '#fff',
                'data' => $hasilKerjaSemester,
            ];
            
            $datasets[] = [
                'label' => 'Nilai Perilaku Kerja Semester',
                'backgroundColor' => 'rgba(0, 61, 165, 0.4)',
                'borderColor' => 'rgba(0, 61, 165, 1)',
                'pointBackgroundColor' => 'rgba(0, 61, 165, 1)',
                'pointBorderColor' => '#fff',
                'data' => $perilakuKerjaSemester,
            ];
            
        }
    
        if ($periode == "Tahunan") {
            $hasilKerjaTahunan = [];
            $perilakuKerjaTahunan = [];
            $nilaiHasilKerja = 0;
            $nilaiPerilakuKerja = 0;

            for($i=1; $i<=4;$i++){
                $labels[] = $i;
                $nilaiHasilKerja = $nilai_hasil_kerja;
                $nilaiPerilakuKerja = $nilai_perilaku_kerja;
                $labels;
                $hasilKerjaTahunan[] = $nilaiHasilKerja;
                $perilakuKerjaTahunan[] = $nilaiPerilakuKerja;
            }

            $datasets[] = [
                'label' => 'Nilai Hasil Kerja Tahun',
                'backgroundColor' => 'rgba(255, 0, 0, 0.1)',
                'borderColor' => 'rgba(255, 0, 0, 1)',
                'pointBackgroundColor' => 'rgba(255, 0, 0, 1)',
                'pointBorderColor' => '#fff',
                'data' => $hasilKerjaTahunan,
            ];
            
           $datasets[] = [
                'label' => 'Nilai Perilaku Kerja Tahun',
                'backgroundColor' => 'rgba(255, 0, 0, 0.2)', // Lighter Red
                'borderColor' => 'rgba(128, 0, 0, 1)',
                'pointBackgroundColor' => 'rgba(128, 0, 0, 1)',
                'pointBorderColor' => '#fff',
                'data' => $perilakuKerjaTahunan,
            ];   
            
        }
    
        $data = [
            'labels' => array_values(array_unique($labels)),
            'datasets' => $datasets,
        ];
    
        return $data;
    }
    

    public function getNamaSkpPeriode()
    {
        if($this->id_skp_periode == 1) {
            return "Tahunan";
        }

        if($this->id_skp_periode == 2) {
            return "Triwulan";
        }

        if($this->id_skp_periode == 3) {
            return "Semesteran";
        }
    }

    public function getNamaNilaiHasilKerja()
    {
        if($this->nilai_hasil_kerja == null) {
            return "Belum Dinilai";
        }

        if($this->nilai_hasil_kerja == 1) {
            return "Di Bawah Ekspektasi";
        }

        if($this->nilai_hasil_kerja == 2) {
            return "Sesuai Ekspektasi";
        }

        if($this->nilai_hasil_kerja == 3) {
            return "Di Atas Ekspektasi";
        }

        return "N/A";
    }

    public function getNamaNilaiPerilakuKerja()
    {
        if($this->nilai_perilaku_kerja == null) {
            return "Belum Dinilai";
        }

        if($this->nilai_perilaku_kerja == 1) {
            return "Di Bawah Ekspektasi";
        }

        if($this->nilai_perilaku_kerja == 2) {
            return "Sesuai Ekspektasi";
        }

        if($this->nilai_perilaku_kerja == 3) {
            return "Di Atas Ekspektasi";
        }

        return "N/A";
    }

    public static function getBulanSaatIni()
    {
        $bulanSaatIni = date('n');
        return $bulanSaatIni;
    }

    public function canUpdate()
    {
        $tahun = $this->instansiPegawaiSkp->tahun;

        if($this->id_skp_periode == SkpNilai::PERIODE_TAHUNAN) {

            $datetime = \DateTime::createFromFormat('Y', $tahun);

            if($datetime == false) {
                return false;
            }

            if(date('Y-m') >= $datetime->format('Y-12')) {
                return true;
            }
        }

        if($this->id_skp_periode == SkpNilai::PERIODE_TRIWULANAN) {

            if($this->periode == 1) {

                $bulan = 3;
                $datetime = \DateTime::createFromFormat('Y-n',$tahun.'-'.$bulan);

                if($datetime == false) {
                    return false;
                }

                if(date('Y-m') > $datetime->format('Y-m')) {
                    return true;
                }
            }

            if($this->periode == 2) {

                $bulan = 6;
                $datetime = \DateTime::createFromFormat('Y-n',$tahun.'-'.$bulan);

                if($datetime == false) {
                    return false;
                }

                if(date('Y-m') > $datetime->format('Y-m')) {
                    return true;
                }
            }

            if($this->periode == 3) {

                $bulan = 9;
                $datetime = \DateTime::createFromFormat('Y-n',$tahun.'-'.$bulan);

                if($datetime == false) {
                    return false;
                }

                if(date('Y-m') > $datetime->format('Y-m')) {
                    return true;
                }
            }

            if($this->periode == 4) {

                $bulan = 12;
                $datetime = \DateTime::createFromFormat('Y-n',$tahun.'-'.$bulan);

                if($datetime == false) {
                    return false;
                }

                if(date('Y-m') >= $datetime->format('Y-m')) {
                    return true;
                }

            }
        }

        if($this->id_skp_periode == SkpNilai::PERIODE_SEMESTERAN) {

            if($this->periode == 1) {

                $bulan = 6;
                $datetime = \DateTime::createFromFormat('Y-n',$tahun.'-'.$bulan);

                if($datetime == false) {
                    return false;
                }

                if(date('Y-m') > $datetime->format('Y-m')) {
                    return true;
                }
            }

            if($this->periode == 2) {

                $bulan = 12;
                $datetime = \DateTime::createFromFormat('Y-n',$tahun.'-'.$bulan);

                if($datetime == false) {
                    return false;
                }

                if(date('Y-m') >= $datetime->format('Y-m')) {
                    return true;
                }
            }
        }

        return false;
    }
}
