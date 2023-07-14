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
class SkpForm extends Model
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

    public function getManyKegiatanTahunan($params=[])
    {
        return $this->instansiPegawai !== null
            ? $this->instansiPegawai
                ->getManyKegiatanTahunanIndukSetuju()
                ->andWhere(['tahun' => User::getTahun()])
                ->andFilterWhere(['id_kegiatan_tahunan_versi' => @$params['id_kegiatan_tahunan_versi']])
            : [];
    }

    public function findAllKegiatanTahunan($params=[])
    {
        $query = $this->instansiPegawai->getManyKegiatanTahunan();
        $query->andWhere(['id_kegiatan_tahunan_versi' => @$params['id_kegiatan_tahunan_versi']]);
        $query->andWhere(['id_kegiatan_tahunan_jenis' => @$params['id_kegiatan_tahunan_jenis']]);
        return $query->all();
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
}
