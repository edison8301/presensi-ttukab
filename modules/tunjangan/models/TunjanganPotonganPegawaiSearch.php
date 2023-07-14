<?php

namespace app\modules\tunjangan\models;

use app\components\Session;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tunjangan\models\TunjanganPotonganPegawai;

/**
 * TunjanganPotonganPegawaiSearch represents the model behind the search form of `app\modules\tunjangan\models\TunjanganPotonganPegawai`.
 */
class TunjanganPotonganPegawaiSearch extends TunjanganPotonganPegawai
{
    public $nama_pegawai;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_tunjangan_potongan', 'id_pegawai','id_instansi'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai'], 'safe'],
            [['nama_pegawai'], 'safe'],
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
     * @return \yii\db\ActiveQuery
     */
    public function getQuerySearch($params)
    {
        $this->load($params);

        $query = TunjanganPotonganPegawai::find();

        if(Session::isInstansi()) {
            $this->id_instansi = Session::getIdInstansi();
        }

        if(Session::isAdminInstansi()) {
            $this->id_instansi = Session::getIdInstansi();
        }

        if($this->nama_pegawai !== null) {
            $query->joinWith(['pegawai']);
            $query->andWhere(['like','pegawai.nama', $this->nama_pegawai]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_tunjangan_potongan' => $this->id_tunjangan_potongan,
            'id_pegawai' => $this->id_pegawai,
            'id_instansi' => $this->id_instansi,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
        ]);

        $query->orderBy(['id' => SORT_DESC]);

        return $query;
    }

    public function search($params)
    {
        $query = $this->getQuerySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }


}
