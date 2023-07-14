<?php

namespace app\modules\kinerja\models;

use app\components\Session;
use Yii;
use yii\helpers\Html;
use app\models\User;

/**
 * This is the model class for table "skp_perilaku".
 *
 * @property int $id
 * @property int $id_skp
 * @property int $id_skp_perilaku_jenis
 * @property string $ekspektasi
 * @see SkpPerilaku::getSkp()
 * @property InstansiPegawaiSkp $skp
 */
class SkpPerilaku extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'skp_perilaku';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_skp', 'id_skp_perilaku_jenis'], 'required'],
            [['id_skp', 'id_skp_perilaku_jenis'], 'integer'],
            [['ekspektasi'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_skp' => 'Id Skp',
            'id_skp_perilaku_jenis' => 'Id Skp Perilaku Jenis',
            'ekspektasi' => 'Ekspektasi',
        ];
    }

    public function getSkp()
    {
        return $this->hasOne(InstansiPegawaiSkp::class, ['id' => 'id_skp']);
    }

    public static function findOrCreate(array $params = [])
    {
        $query = SkpPerilaku::find();
        $query->andWhere([
            'id_skp' => @$params['id_skp'],
            'id_skp_perilaku_jenis' => @$params['id_skp_perilaku_jenis'],
        ]);

        $model = $query->one();

        if ($model == null) {
            $model = new SkpPerilaku([
                'id_skp' => @$params['id_skp'],
                'id_skp_perilaku_jenis' => @$params['id_skp_perilaku_jenis'],
            ]);
            $model->save();
        }

        return $model;
    }

    public function canUpdate()
    {
        $instansiPegawai = @$this->skp->instansiPegawai;

        if (Session::isAdmin()) {
            return true;
        }

        if (Session::isPegawai()
            AND in_array(@$instansiPegawai->jabatan->id_induk, User::getIdJabatanBerlaku())
        ) {
            return true;
        }

        return false;
    }

    public function getLinkUpdateIcon()
    {
        if ($this->canUpdate() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-pencil-square-o"></i>', [
            '/kinerja/skp-perilaku/update',
            'id' => $this->id,
        ]);
    }
}
