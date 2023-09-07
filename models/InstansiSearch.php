<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Instansi;
use app\components\Helper;

/**
 * InstansiSearch represents the model behind the search form of `app\models\Instansi`.
 */
class InstansiSearch extends Instansi
{
    public $mode;
    public $status_tampil;

    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['id','id_induk','id_instansi_jenis','bulan'], 'integer'],
            [['nama', 'alamat', 'telepon'], 'safe'],
            [['status_aktif'], 'integer'],
            [['mode', 'status_tampil'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */

    public function getQuerySearch($params)
    {
        $this->load($params);

        $query = Instansi::find();

        if(User::isVerifikator()) {
            $query->andWhere(['id'=>User::getListIdInstansi()]);
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'id_induk'=>$this->id_induk,
            'id_instansi_jenis'=>$this->id_instansi_jenis,
            'status_aktif' => $this->status_aktif,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'telepon', $this->telepon]);

        return $query;
    }

    public function search($params)
    {
        $query = $this->getQuerySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>['pageSize'=>'10']
        ]);

        return $dataProvider;
    }

    public function getMasa() {

        $masa = '';

        $masa = 'Tahun '.User::getTahun();

        if($this->bulan!=null) {
            $masa = Helper::getBulanLengkap($this->bulan).' '.User::getTahun();
        }

        if($this->tanggal!=null) {
            $masa = Helper::getHariTanggal($this->tanggal);
        }

        return $masa;
    }

    public function getBulanLengkapTahun()
    {
        return Helper::getBulanLengkap($this->bulan).' '.User::getTahun();
    }


}
