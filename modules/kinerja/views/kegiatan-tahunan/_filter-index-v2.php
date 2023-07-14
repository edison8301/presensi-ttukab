<?php

use app\models\Pegawai;
use app\models\User;
use app\modules\kinerja\models\InstansiPegawaiSkp;
use app\modules\kinerja\models\KegiatanStatus;
use kartik\select2\Select2;
use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form kartik\form\ActiveForm */
/* @var $searchModel app\modules\kinerja\models\KegiatanTahunanSearch */
?>

<?php $form = ActiveForm::begin([
    'type' => 'inline',
    'action' => ['/' . Yii::$app->controller->route],
    'method' => 'get',
]); ?>
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Filter Kinerja Tahunan</h3>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if(User::isPegawai() AND $searchModel->isScenarioPegawai()) { ?>

                    <?= $form->field($searchModel, 'id_pegawai',[
                        'addon' => ['prepend' => ['content'=>'Pegawai']]
                    ])->dropDownList(
                         Pegawai::getList()
                    ); ?>

                <?php } elseif(User::isPegawai() AND $searchModel->isScenarioAtasan()) { ?>

                    <?= $form->field($searchModel, 'id_pegawai',[
                        'addon' => ['prepend' => ['content'=>'Pegawai']]
                    ])->dropDownList(
                        Pegawai::getListBawahan(),
                        [
                            'prompt'=>'Semua Pegawai Bawahan',
                            'onchange'=>'this.form.submit()'
                        ]
                    ); ?>

                <?php } else { ?>

                    <?= $form->field($searchModel, 'id_pegawai')->widget(Select2::className(), [
                        'data' => $searchModel->getListPegawai(),
                        'options' => [
                            'placeholder' => '- Pilih Pegawai -',
                            'id' => 'id-pegawai',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ])->label(false); ?>
                <?php } ?>

                <?php if($searchModel->id_pegawai!=null) { ?>

                    <?= $form->field($searchModel, 'nomor_skp',[
                        'addon' => ['prepend' => ['content'=>'No SKP']],
                        'horizontalCssClasses' => [
                            'label' => 'col-sm-2',
                            'wrapper' => 'col-sm-4',
                            'error' => '',
                            'hint' => '',
                        ]
                    ])->dropDownList(InstansiPegawaiSkp::getList(['id_pegawai'=>$searchModel->id_pegawai]),[
                        'prompt'=>'Semua',
                        'onchange'=>'this.form.submit()'
                    ]) ?>

                <?php } else { ?>

                    <?= $form->field($searchModel, 'nomor_skp',[
                        'addon' => ['prepend' => ['content'=>'No SKP']]
                    ])->textInput()->label(false); ?>

                <?php } ?>

                <?php /* $form->field($searchModel, 'id_instansi_pegawai')->widget(DepDrop::className(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'data' => InstansiPegawai::getListInstansi($searchModel->id_pegawai, true),
                    'pluginOptions' => [
                        'depends' => ['id-pegawai'],
                        'placeholder' => '- Pilih Jabatan -',
                        'url' => Url::to(['/instansi-pegawai/get-list']),
                    ],
                    'select2Options' => [
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ]
                ])->label(false); */ ?>

                <?= $form->field($searchModel, 'id_kegiatan_status',[
                    'addon' => ['prepend' => ['content'=>'Status']]
                ])->dropDownList(
                    KegiatanStatus::getList(),
                    [
                        'prompt'=>'Semua',
                        'onchange'=>'this.form.submit()'
                    ]
                )->label(false); ?>

                <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>
            </div>
        </div>

    </div>

</div>
<?php ActiveForm::end(); ?>
