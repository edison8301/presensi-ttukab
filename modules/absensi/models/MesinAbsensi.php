<?php

namespace app\modules\absensi\models;

use app\components\Session;
use app\models\User;
use Yii;
use app\components\Helper;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "mesin_absensi".
 *
 * @property integer $id
 * @property integer $id_instansi
 * @property string $serialnumber
 */
class MesinAbsensi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mesin_absensi';
    }

    public static function getList()
    {
        $query = static::find();

        if (User::isAdminInstansi() or User::isInstansi() OR Session::isOperatorAbsen()) {
            $query->andWhere([
                'id_instansi' => User::getIdInstansi()
            ]);
        }


        return ArrayHelper::map($query->all(), 'serialnumber', 'snInstansi');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serialnumber'],'unique'],
            [['id_instansi', 'serialnumber'], 'required'],
            [['id_instansi'], 'integer'],
            [['serialnumber'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_instansi' => 'SKPD',
            'serialnumber' => 'Serialnumber',
        ];
    }

    public function getIclock()
    {
        return $this->hasOne(\app\modules\iclock\models\Iclock::className(), ['SN' => 'serialnumber']);
    }

    public function getInstansi()
    {
        return $this->hasOne(\app\models\Instansi::className(), ['id' => 'id_instansi']);
    }

    public function getManyCheckinout()
    {
        return $this->hasMany(\app\modules\iclock\models\Checkinout::className(), ['SN' => 'SN'])
            ->via('iclock');
    }

    public function getManyTemplate()
    {
        return $this->hasMany(\app\modules\iclock\models\Template::className(), ['SN' => 'SN'])
            ->via('iclock');
    }

    public function getCheckinoutTerakhir()
    {
        return $this->getManyCheckinout()
            ->orderBy(['checktime'=>SORT_DESC])
            ->one();
    }

    public function getChecktimeTerakhir()
    {
        if($this->getCheckinoutTerakhir()!==null)
            return $this->getCheckinoutTerakhir()->checktime;
        else
            return null;
    }

    public function getTextChecktimeTerakhir()
    {
        $checktimeTerakhir = $this->getChecktimeTerakhir();

        if($checktimeTerakhir != null)
            return  Helper::getSelisihWaktu($checktimeTerakhir)." Lalu<br>".$checktimeTerakhir;
        else
            return null;
    }

    public function getTextLastActivity()
    {
        if($this->iclock)
            return Helper::getSelisihWaktu($this->iclock->LastActivity)." Lalu<br>".$this->iclock->LastActivity;
        else
            return "";
    }

    public function getSnInstansi()
    {
        return $this->serialnumber . ($this->instansi !== null ? ' - ' . $this->instansi->nama : null);
    }

    public static function getListSn($params=[])
    {
        $query = MesinAbsensi::find();
        $query->andFilterWhere([
            'id_instansi'=>@$params['id_instansi']
        ]);

        $list = [];

        foreach($query->all() as $data) {
            $list[] = $data->serialnumber;
        }

        return $list;
    }
}
