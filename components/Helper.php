<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class Helper extends Component
{
    public static function rp($jumlah, $null = null,$decimal=0,$satuan=null)
    {

        if ($jumlah == null) {
            return $null;
        }

        if (is_numeric($jumlah)) {

            if($satuan=='ribu') {
                $jumlah = $jumlah / 1000;
            }

            if($satuan=='juta') {
                $jumlah = $jumlah / 1000000;
            }

            if($satuan=='miliar') {
                $jumlah = $jumlah / 1000000000;
            }

            return number_format($jumlah, $decimal, ',', '.');
        }

        return "N/A";
    }

    public static function persen(?float $atas, ?float $bawah, $decimals=1)
    {
        if($bawah==0) {
            return 0;
        }

        return round($atas/$bawah*100, $decimals);

    }

	public static function getHariByInteger($int)
	{
		if($int==1) return "Senin";
		if($int==2) return "Selasa";
		if($int==3) return "Rabu";
		if($int==4) return "Kamis";
		if($int==5) return "Jumat";
		if($int==6) return "Sabtu";
		if($int==7) return "Minggu";
		if($int==8) return "Hari Libur";
	}

	public static function getJamMenit($waktu)
	{
		$date = date_create($waktu);
		return $date->format('H:i');
	}

	public static function getJamMenitDetik($waktu)
	{
		$date = date_create($waktu);
		return $date->format('H:i:s');
	}

	public static function getSelisihWaktu($time1=null,$time2=null)
	{
		if($time1==null) {
			return null;
		}

		if($time2==null) {
			date_default_timezone_set('Asia/Jakarta');
			$time2 = date('Y-m-d H:i:s');
		}

		$time1 = date_create($time1);
        $time2 = date_create($time2);
        $dateDiff = date_diff($time1,$time2);

        $bulan = $dateDiff->m;
        $hari = $dateDiff->d;
        $jam = $dateDiff->h;
        $menit = $dateDiff->i;
        $detik = $dateDiff->s;

        if($bulan!=0) {
        	return $bulan.' Bulan';
        }

        if($hari!=0) {
        	return $hari.' Hari';
        }

        if($jam!=0) {
        	return $jam.' Jam';
        }

        if($menit!=0) {
        	return $menit.' Menit';
        }

        if($detik!=0) {
        	return $detik.' Detik';
        }

        return null;
	}

	public static function getTanggalSingkat($tanggal)
	{
	    if($tanggal=='9999-12-31') {
	        return '';
        }

		if($tanggal==null)
			return null;

		if($tanggal=='0000-00-00')
			return null;

		$time = strtotime($tanggal);

		return date('j',$time).' '.Helper::getBulanSingkat(date('m',$time)).' '.date('Y',$time);
	}

	public static function getIconRemove($status=null,$tooltip=null)
	{
		if($status==1)
		{
			return "<i class='fa fa-remove' data-toggle='tooltip' title='".$tooltip."'></i>";
		}

	}

	public static function getIconExcel($status=null,$tooltip=null)
	{
		if($status==1)
		{
			return "x";
		}

	}

	public static function getTanggal($tanggal)
	{
		if($tanggal==null)
			return null;

		if($tanggal=='0000-00-00')
			return null;

		$time = strtotime($tanggal);

		return date('j',$time).' '.Helper::getBulanLengkap(date('m',$time)).' '.date('Y',$time);
	}

	public static function getHari($tanggal)
	{
		$time = strtotime($tanggal);

		$h = date('N',$time);

		if($h == '1') $hari = 'Senin';
		if($h == '2') $hari = 'Selasa';
		if($h == '3') $hari = 'Rabu';
		if($h == '4') $hari = 'Kamis';
		if($h == '5') $hari = 'Jumat';
		if($h == '6') $hari = 'Sabtu';
		if($h == '7') $hari = 'Minggu';

		return $hari;
	}


	public static function getHariTanggal($tanggal)
	{
		if($tanggal==null)
			return null;

		if($tanggal=='0000-00-00')
			return null;

		$time = strtotime($tanggal);

		$h = date('N',$time);

		if($h == '1') $hari = 'Senin';
		if($h == '2') $hari = 'Selasa';
		if($h == '3') $hari = 'Rabu';
		if($h == '4') $hari = 'Kamis';
		if($h == '5') $hari = 'Jumat';
		if($h == '6') $hari = 'Sabtu';
		if($h == '7') $hari = 'Minggu';

		return $hari.', '.date('j',$time).' '.Helper::getBulanLengkap(date('m',$time)).' '.date('Y',$time);
	}

	public static function getBulanSingkat($i)
	{
		$bulan = '';

		if(strlen($i)==1) $i = '0'.$i;

		if($i=='01') $bulan = 'Jan';
		if($i=='02') $bulan = 'Feb';
		if($i=='03') $bulan = 'Mar';
		if($i=='04') $bulan = 'Apr';
		if($i=='05') $bulan = 'Mei';
		if($i=='06') $bulan = 'Jun';
		if($i=='07') $bulan = 'Jul';
		if($i=='08') $bulan = 'Agt';
		if($i=='09') $bulan = 'Sep';
		if($i=='10') $bulan = 'Okt';
		if($i=='11') $bulan = 'Nov';
		if($i=='12') $bulan = 'Des';

		return $bulan;

	}

	public static function getBulanLengkap($i)
	{
		$bulan = '';

		if(strlen($i)==1) $i = '0'.$i;

		if($i=='01') $bulan = 'Januari';
		if($i=='02') $bulan = 'Februari';
		if($i=='03') $bulan = 'Maret';
		if($i=='04') $bulan = 'April';
		if($i=='05') $bulan = 'Mei';
		if($i=='06') $bulan = 'Juni';
		if($i=='07') $bulan = 'Juli';
		if($i=='08') $bulan = 'Agustus';
		if($i=='09') $bulan = 'September';
		if($i=='10') $bulan = 'Oktober';
		if($i=='11') $bulan = 'November';
		if($i=='12') $bulan = 'Desember';

		return $bulan;

	}

	public static function getWaktuWIB($waktu)
	{
		if($waktu == '')
			return null;
		else {
		$time = strtotime($waktu);

		$h = date('N',$time);

		if($h == '1') $hari = 'Senin';
		if($h == '2') $hari = 'Selasa';
		if($h == '3') $hari = 'Rabu';
		if($h == '4') $hari = 'Kamis';
		if($h == '5') $hari = 'Jumat';
		if($h == '6') $hari = 'Sabtu';
		if($h == '7') $hari = 'Minggu';


		$tgl = date('j',$time);

		$h = date('n',$time);

		if($h == '1') $bulan = 'Januari';
		if($h == '2') $bulan = 'Februari';
		if($h == '3') $bulan = 'Maret';
		if($h == '4') $bulan = 'April';
		if($h == '5') $bulan = 'Mei';
		if($h == '6') $bulan = 'Juni';
		if($h == '7') $bulan = 'Juli';
		if($h == '8') $bulan = 'Agustus';
		if($h == '9') $bulan = 'September';
		if($h == '10') $bulan = 'Oktober';
		if($h == '11') $bulan = 'November';
		if($h == '12') $bulan = 'Desember';

		$tahun  = date('Y',$time);

		$pukul = date('H:i:s',$time);

		$output = $hari.', '.$tgl.' '.$bulan.' '.$tahun.' | '.$pukul.' WIB';

		return $output;
		}

	}

	public static function getWaktu($waktu,$wib=false)
	{
		$time = strtotime($waktu);

		$pukul = date('H:i',$time);

		if ($wib)
			$pukul .= " WIB";

		return $pukul;
	}

	public static function getListBulan($lengkap = true)
	{
		$bulan = [];

		for($i=1;$i<=12;$i++) {
			$bulan[$i] = Helper::getBulanLengkap($i);
		}

		return $bulan;
	}

	public static function getListBulanSingkat($lengkap = true)
	{
		$bulan = [];

		for($i=1;$i<=12;$i++) {
			$bulan[$i] = Helper::getBulanSingkat($i);
		}

		return $bulan;
	}

    public static function getBulanList($index = true)
    {
        $bulan = [];
        $i = 1;

        if ($index) {
            while ($i <= 12) {
                if (strlen($i) == 1) $i = '0'.$i;

                $bulan[$i] = self::getBulanLengkap($i);
                $i++;
            }
        } else {
            while ($i <= 12) {
                $bulan[] = self::getBulanLengkap($i);
                $i++;
            }
        }
        return $bulan;
    }

	public function getBulanListFilter()
	{
		$bulan = [];
		$i = 1;
		while ($i <= 12) {
			$i = sprintf('%02d', $i);
			$bulan[$i] = self::getBulanLengkap($i);
			$i++;
		}

		return $bulan;
	}

	public function getTahun($tanggal)
	{
		$data = new \DateTime($tanggal);

		return $data->format('Y');
	}

	public static function getFormatRupiahExcel()
	{
		return '[$Rp-421]#,##0.00';
	}

	public static function getFormatRupiahExcelTanpaRp()
	{
		return '#,##0.00';
	}

	public static function getTriwulan($i)
	{
		if (in_array($i, [1,2,3,4,'1','2','3','4'])) {
			if ($i == 1) {
				return self::getBulanLengkap(1) . ' - ' . self::getBulanLengkap(3);
			} elseif ($i == 2) {
				return self::getBulanLengkap(4) . ' - ' . self::getBulanLengkap(6);
			} elseif ($i == 3) {
				return self::getBulanLengkap(7) . ' - ' . self::getBulanLengkap(9);
			} elseif ($i == 4) {
				return self::getBulanLengkap(10) . ' - ' . self::getBulanLengkap(12);
			}
		} else {
			return null;
		}

	}

    public static function getTerbilang($rp,$tri=0)
    {
        $ones = array(
            "",
            " satu",
            " dua",
            " tiga",
            " empat",
            " lima",
            " enam",
            " tujuh",
            " delapan",
            " sembilan",
            " sepuluh",
            " sebelas",
            " dua belas",
            " tiga belas",
            " empat belas",
            " lima belas",
            " enam belas",
            " tujuh belas",
            " delapan belas",
            " sembilan belas"
        );

        $tens = array(
            "",
            "",
            " dua puluh",
            " tiga puluh",
            " empat puluh",
            " lima puluh",
            " enam puluh",
            " tujuh puluh",
            " delapan puluh",
            " sembilan puluh"
        );

        $triplets = array(
            "",
            " ribu",
            " juta",
            " miliar",
            " triliun",
        );

        // chunk the number, ...rxyy
        $r = (int) ($rp / 1000);
        $x = ($rp / 100) % 10;
        $y = $rp % 100;

        // init the output string
        $str = "";

        // do hundreds
        if ($x > 0)
        {
            if($x==1)
                $str =  " seratus";
            else
                $str = $ones[$x] . " ratus";
        }

        // do ones and tens
        if ($y < 20)
            $str .= $ones[$y];
        else
            $str .= $tens[(int) ($y / 10)] . $ones[$y % 10];

        // add triplet modifier only if there
        // is some output to be modified...
        if ($str != "")
            $str .= $triplets[$tri];

        // continue recursing?
        if ($r > 0)
            return Helper::getTerbilang($r, $tri+1).$str;
        else
            return $str;
    }

    public static function chr($char,$append = null)
    {
        if ($char > 90) {
            if ($append == null) {
                $append = 64;
            }

            return self::chr(($char - 26), ++$append);
        } else {
            if ($append !== null) {
                $append = chr($append);
            }

            return $append . chr($char);
        }
    }

    public static function chrKecil($char, $append = null)
    {
        if ($char > 122) {
            if ($append == null) {
                $append = 97;
            }

            return self::chrKecil(($char - 26), ++$append);
        } else {
            if ($append !== null) {
                $append = chr($append);
            }

            return $append . chr($char);
        }
    }

    public static function getTanggalDanJam($tanggal)
    {
        if ($tanggal !== '' AND $tanggal !== null) {
            return static::getTanggal($tanggal).', '.static::getJamPadaYmdHis($tanggal);
        }

        return '-';
    }

    private static function getJamPadaYmdHis($tanggal)
    {
         $dateTime = new \DateTime($tanggal);

        if ($dateTime->format('H:i:s') !== null) {
            return $dateTime->format('H:i');
        }

        return '';
	}

    public static function getTanggalBulan($tanggal)
    {
        if($tanggal==null)
            return null;

        if($tanggal=='0000-00-00')
            return null;

        $time = strtotime($tanggal);

        return date('j',$time).' '.Helper::getBulanLengkap(date('m',$time));
    }

}
