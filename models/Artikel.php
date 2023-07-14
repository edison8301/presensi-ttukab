<?php

namespace app\models;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;


use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "artikel".
 *
 * @property int $id
 * @property string $judul
 * @property string $slug
 * @property string $konten
 * @property int $id_user_buat
 * @property int $id_user_ubah
 * @property int $id_artikel_kategori
 * @property int $waktu_buat
 * @property int $waktu_ubah
 * @property string $waktu_terbit
 * @property string $thumbnail
 */
class Artikel extends \yii\db\ActiveRecord
{
    public $thumbnailInstance;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'artikel';
    }

    /**
     * {@inheritdoc}
     */

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'judul',
                'ensureUnique' => true,
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'waktu_buat',
                'updatedAtAttribute' => null,
                'value' => time(),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['judul', 'konten',], 'required'],
            [['konten'], 'string'],
            [['id_user_buat', 'id_user_ubah', 'id_artikel_kategori', 'waktu_buat', 'waktu_ubah'], 'integer'],
            [['waktu_terbit' , 'id_user_buat', 'thumbnail'], 'safe'],
            [['judul', 'slug', ], 'string', 'max' => 255],

            ['thumbnailInstance','image']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'judul' => 'Judul',
            'slug' => 'Slug',
            'konten' => 'Konten',
            'id_user_buat' => 'Id User Buat',
            'id_user_ubah' => 'Id User Ubah',
            'id_artikel_kategori' => 'Id Artikel Kategori',
            'waktu_buat' => 'Waktu Buat',
            'waktu_ubah' => 'Waktu Ubah',
            'waktu_terbit' => 'Waktu Terbit',
            'thumbnail' => 'Thumbnail',
        ];
    }

public function getJudulArtikelUcWords()
    {
        return ucwords(strtolower($this->judul));
    }

    public function getJudulArtikelStrtoUpper()
    {
        return strtoupper($this->judul);
    }

    public function isThumbnailIsExist()
    {
        $path = \Yii::getAlias('@app').'/web/uploads/artikel/';

        if ($this->thumbnail !== null AND $this->thumbnail !== '' AND is_file($path.$this->thumbnail)) {
            return true;
        }

        return false;
    }

    public function getThumbnail($htmlOptions=[])
    {
        if ($this->isThumbnailIsExist()) {
            return Html::img('@web'.'/uploads/artikel/'.$this->thumbnail, $htmlOptions);
        }

        return Html::img('@web'.'/uploads/image-not-found.png',$htmlOptions);
    }

    public function isArtikelHasEdit()
    {
        if ($this->waktu_ubah > $this->waktu_buat) {
            return true;
        }

        return false;
    }

}
