<?php 
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use app\models\Realisasi;
use app\models\Rencana;
use app\models\Pagu;
use app\models\User;
use yii\helpers\Url;
use app\components\Helper;

/* @var $this yii\web\View */
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            Rekapitulasi Pegawai
        </h3>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'layout'=>'inline',
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'label' => 'col-lg-1',
                    'wrapper' => 'col-lg-2',
                    'error' => '',
                    'hint' => '',
                ],
            ]
        ]); ?>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-lg-4">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>
                            20
                        </h3>
                        <p>Jumlah Realisasi Sampai Bulan Ini</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <a href="<?= Url::to(['opd/index']); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>
                            20
                        </h3>
                        <p>Jumlah Total Pagu</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money"></i>
                    </div>
                    <a href="<?= Url::to(['opd/index']); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>
                            40
                                
                        </h3>
                        <p>Persentasi Realisasi Terhadap Pagu</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-book"></i>
                    </div>
                    <a href="<?= Url::to(['opd/index']); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php /*
<?= $this->render('_absensi',['model' => $model]); ?>
*/ ?>