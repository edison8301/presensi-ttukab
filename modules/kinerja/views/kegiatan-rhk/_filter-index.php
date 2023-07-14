<?php

use app\components\Session;
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
    <div class="box-header with-border">
        <h3 class="box-title">Filter RHK</h3>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if (Session::isAdmin() OR Session::isPemeriksaKinerja()) { ?>
                    <?= $form->field($searchModel, 'id_pegawai')->widget(Select2::className(), [
                        'data' => Pegawai::getList(),
                        'options' => [
                            'placeholder' => '- Pilih Pegawai -',
                            'id' => 'id-pegawai',
                            'onchange'=>'this.form.submit()'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'width' => '400px'
                        ]
                    ])->label(false); ?>
                <?php } ?>

                <?php if (Session::isPegawai()) { ?>
                    <?= $form->field($searchModel, 'id_pegawai', [
                        'addon' => ['prepend' => ['content'=>'Pegawai']]
                    ])->dropDownList(Pegawai::getList()) ?>
                <?php } ?>

                <?= $form->field($searchModel, 'nomor_skp',[
                    'addon' => ['prepend' => ['content'=>'No SKP']],
                    'horizontalCssClasses' => [
                        'label' => 'col-sm-2',
                        'wrapper' => 'col-sm-4',
                        'error' => '',
                        'hint' => '',
                    ]
                ])->dropDownList(InstansiPegawaiSkp::getList(['id_pegawai'=>$searchModel->id_pegawai]),[
                    'prompt' => '- Pilih SKP -',
                    'onchange' => 'this.form.submit()'
                ]) ?>

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
