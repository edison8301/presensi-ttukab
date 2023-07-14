<?php

namespace app\models;

use Yii;

/**
 * FilterForm is the model behind the filter form.
 */
class FilterForm extends \yii\db\ActiveRecord
{
    /**
     * @var int
     */
    public $bulan;
    /**
     * @var int
     */
    public $tahun;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tahun'], 'required'],
            [['tahun'], 'number', 'min' => 2018, 'max' => date('Y')],
            [['bulan'], 'number', 'min' => 1, 'max' => 12],
        ];
    }

    public function init()
    {
        $this->tahun = User::getTahun();
        $this->bulan = date('m');
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tahun' => 'Tahun',
            'bulan' => 'Bulan'
        ];
    }

    /**
     * Set session berdasarkan tahun terisi pada model.
     * @return void
     */
    public function setSession()
    {
        if ($this->validate() && !empty($this->tahun)) {
            Yii::$app->session->set('tahun', $this->tahun);
        }
    }
}
