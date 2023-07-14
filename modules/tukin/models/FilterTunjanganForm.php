<?php
/**
 * Created by PhpStorm.
 * User: iqbal
 * Date: 10/16/2018
 * Time: 2:24 PM
 */

namespace app\modules\tukin\models;


use yii\base\Model;

class FilterTunjanganForm extends Model
{
    /**
     * @var Pegawai $pegawai
     */
    public $pegawai;

    public $bulan;

    public $tahun;

    public function rules()
    {
        return [
            ['bulan', 'integer', 'min' => 1, 'max' => 12],
            [['tahun'], 'integer'],
        ];
    }

    public function init()
    {
        if (empty($this->bulan)) {
            $this->bulan = date('m');
        }
    }

    /**
     * FilterTunjanganForm constructor.
     * @param $pegawai
     * @param array $config
     */
    public function __construct(Pegawai $pegawai, $config = [])
    {
        $this->pegawai = &$pegawai;
        parent::__construct($config);
    }
}
