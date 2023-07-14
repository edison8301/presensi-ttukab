<?php
namespace app\modules\kinerja\models;

use Yii;
use DateTime;
use app\models\Pegawai;
use app\models\InstansiPegawai;
use yii\base\Model;
use app\models\User;

/**
 * Login form
 */
class RekapBulananForm extends Model
{
    protected $_instansi_pegawai;
    protected $_date;
    protected $_pegawai;

    public $id_instansi_pegawai;
    public $bulan;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['id'], 'required'],
            // rememberMe must be a boolean value
            [['id_instansi_pegawai', 'id_instansi', 'bulan'], 'integer'],
        ];
    }

    public function loadDefaultAttributes()
    {
        if (empty($this->bulan)) {
            $this->bulan = date('m');
        }
        if ($this->pegawai !== null && empty($this->id_instansi_pegawai)) {
            $this->id_instansi_pegawai = $this->pegawai->getInstansiPegawaiBerlaku()->id;
        }
    }

    public function getPegawai()
    {
        if ($this->_pegawai === null) {
            $this->_pegawai = Pegawai::find()
                ->andWhere(['id' => User::getIdPegawai()])
                ->with([
                    'manyKegiatanHarian' => function (\yii\db\ActiveQuery $query){
                        $query->andWhere(['between', 'tanggal', $this->date->format('Y-m-01'), $this->date->format('Y-m-t')])
                            ->andWhere(['id_instansi_pegawai' => $this->id_instansi_pegawai])
                            ->aktif();
                    }
                ])
                ->one();
        }
        return $this->_pegawai;
    }

    public function getDate($refresh = false)
    {
        if ($this->_date === null OR $refresh) {
            $this->_date = new DateTime(date('Y-' . $this->bulan . '-01'));
        }
        return $this->_date;
    }

    public function getInstansiPegawai()
    {
        if ($this->_instansi_pegawai === null) {
            $this->_instansi_pegawai = InstansiPegawai::findOne($this->id_instansi_pegawai);
        }
        return $this->_instansi_pegawai;
    }
}
