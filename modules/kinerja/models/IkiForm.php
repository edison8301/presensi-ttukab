<?php

namespace app\modules\kinerja\models;

use Yii;
use app\models\InstansiPegawai;
use app\models\Pegawai;
use app\models\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Login form
 *
 * @property Pegawai[] $listPegawai
 * @property Pegawai $pegawai
 * @property KegiatanTahunan[] $manyKegiatanTahunan
 * @property InstansiPegawai $instansiPegawai
 */
class IkiForm extends Model
{
    /**
     * @var InstansiPegawai
     */
    protected $_instansi_pegawai;

    /**
     * @var int
     */
    public $id_instansi_pegawai;
    /**
     * @var int
     */
    public $id_instansi;
    /**
     * @var int
     */
    public $id_pegawai;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['id', 'id_pegawai'], 'required'],
            // rememberMe must be a boolean value
            [['id_instansi_pegawai', 'id_instansi', 'id_pegawai'], 'integer'],
        ];
    }

    /**
     * @return Pegawai
     */
    public function getPegawai()
    {
        return Pegawai::findOne($this->id_pegawai);
    }

    public function getManyKegiatanTahunan()
    {
        return $this->instansiPegawai !== null
            ? $this->instansiPegawai
                ->getManyKegiatanTahunanIndukSetuju()
                ->andWhere(['tahun' => User::getTahun()])
            : [];
    }

    /**
     * @return InstansiPegawai
     */
    public function getInstansiPegawai()
    {
        if ($this->_instansi_pegawai === null) {
            $this->_instansi_pegawai = InstansiPegawai::findOne($this->id_instansi_pegawai);
        }
        return $this->_instansi_pegawai;
    }

    /**
     * @return Pegawai[]
     */
    public function getListPegawai()
    {
        if (User::isPegawai()) {
            $data = ArrayHelper::merge([Yii::$app->user->identity->pegawai], Pegawai::find()
                    ->joinWith('allInstansiPegawai')
                    ->andWhere(['{{%instansi_pegawai}}.id_atasan' => User::getIdPegawai()])
                    ->all());
            return ArrayHelper::map($data, 'id', 'namaNip');
        }

        return Pegawai::getList();
    }

    public function isFiltered()
    {
        return $this->instansiPegawai !== null;
    }
}
