<?php

use app\components\Helper;
use app\models\Pegawai;
use app\modules\kinerja\models\KegiatanStatus;
use app\models\User;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanBulananSearch */
/* @var $form kartik\form\ActiveForm */
/* @var $title string|null */

if (!isset($bawahan)) {
    $bawahan = false;
}

if (@$title == null) {
    $title = 'Filter Kegiatan Harian';
}

?>

<?php $form = ActiveForm::begin([
    'action' => ['/' . Yii::$app->controller->route],
    'type'=>'inline',
    'method' => 'get',
]); ?>
<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title"><?= @$title ?></h3>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if(User::isPegawai() AND $searchModel->scenario=='pegawai') { ?>

                    <?= $form->field($searchModel, 'id_pegawai',[
                        'addon' => ['prepend' => ['content'=>'Pegawai']]
                    ])->dropDownList(Pegawai::getList(),['onchange'=>'this.form.submit()']); ?>

                <?php } elseif(User::isPegawai() AND $searchModel->scenario=='atasan') { ?>

                    <?= $form->field($searchModel, 'id_pegawai',[
                        'addon' => ['prepend' => ['content'=>'Pegawai']]
                    ])->dropDownList(Pegawai::getListBawahan([
                        'bulan' => $searchModel->bulan,
                        'tahun' => $searchModel->tahun
                    ]),[
                        'prompt'=>'Semua Pegawai Bawahan',
                        'onchange'=>'this.form.submit()'
                    ]); ?>

                <?php } else { ?>

                    <?php echo $form->field($searchModel, 'id_pegawai')->widget(Select2::class, [
                        // 'data' => Pegawai::getList(),
                        'initValueText' => @$searchModel->pegawai->nama !== null ? @$searchModel->pegawai->nama.' - '.@$searchModel->pegawai->nip : null,
                        'language' => 'id',
                        'options' => [
                            'placeholder' => '- Pilih Pegawai -',
                            'id' => 'id-pegawai',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => Yii::$app->params['select2.minimumInputLength'],
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['/pegawai/get-list-ajax', 'bawahan' => (int) $bawahan]),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) {
                                    return {q:params.term};
                                }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) {
                                return markup;
                            }'),
                                'templateResult' => new JsExpression('function(pegawai) {
                                return pegawai.text;
                            }'),
                                'templateSelection' => new JsExpression('function (pegawai) {
                                return pegawai.text;
                            }'),
                            'width' => '400px',
                        ]
                    ])->label(false);
                } ?>


            <?= $form->field($searchModel, 'bulan',[
                    'addon' => ['prepend' => ['content'=>'Bulan']]
            ])->dropDownList(Helper::getListBulan(), [
                    'prompt' => 'Semua',
                    'onchange'=>'this.form.submit()'
            ]); ?>


            <?= $form->field($searchModel, 'tanggal')->widget(DatePicker::class, [
                'options' => [
                     'placeholder' => 'Tanggal',
                     'onchange'=>'this.form.submit()'
                ],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label(false) ?>

            <?= $form->field($searchModel, 'id_kegiatan_status',[
                'addon' => ['prepend' => ['content'=>'Status']]
            ])->dropDownList(KegiatanStatus::getList(),[
                    'prompt'=>'Semua',
                    'onchange'=>'this.form.submit()'
            ])->label(false); ?>

            <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>

            </div>
        </div><!-- .row -->
    </div><!-- .box-body -->
</div>
<?php ActiveForm::end(); ?>
