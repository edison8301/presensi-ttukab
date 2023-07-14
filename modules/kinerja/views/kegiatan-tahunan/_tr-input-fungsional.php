<?php

use yii\helpers\Html;
?>
<?php if($model->butir_kegiatan_jf != null) { ?>
    <?php for ($i=1; $i<count($model->butir_kegiatan_jf); $i++) { ?>
        <tr class="line">
            <td>
                <?= $form->field($model, 'butir_kegiatan_jf['.$i.']',[
                    //'template' => "{input}",
                    'options' => [
                        'style' => 'margin-bottom:0px'
                    ],
                    'horizontalCssClasses' => [
                        'label' => 'col-md-12',
                        'wrapper' => 'col-md-12',
                        'error' => '',
                        'hint' => '',
                    ],
                ])->textarea(['rows' => 3, 'placeholder' => 'Butir Kegiatan'])->label(false) ?>
            </td>
            <td>
                <?= $form->field($model, 'output_jf['.$i.']',[
                    //'template' => "{input}",
                    'options' => [
                        'style' => 'margin-bottom:0px'
                    ],
                    'horizontalCssClasses' => [
                        'label' => 'col-md-12',
                        'wrapper' => 'col-md-12',
                        'error' => '',
                        'hint' => '',
                    ],
                ])->textInput(['placeholder' => 'Output', 'autocomplete' => 'off'])->label(false) ?>
            </td>
            <td>
                <?= $form->field($model, 'angka_kredit_jf['.$i.']',[
                    //'template' => "{input}",
                    'options' => [
                        'style' => 'margin-bottom:0px',
                    ],
                    'horizontalCssClasses' => [
                        'label' => 'col-md-12',
                        'wrapper' => 'col-md-12',
                        'error' => '',
                        'hint' => '',
                    ],
                ])->textInput([
                    'placeholder' => 'Angka Kredit',
                    'autocomplete' => 'off',
                ])->label(false) ?>
            </td>
            <td class="add" style="text-align: center">
                <?= Html::a('<i class="fa fa-remove"></i>', 'javascript:void(0)', ['class' => 'btn btn-danger btn-flat btn-remove']) ?>
            </td>
        </tr>
    <?php } ?>
<?php } ?>