<?php

use yii\helpers\Html;

$this->title = 'Kirim Dokumen';

?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">
            <?= $this->title ?>
        </h3>
    </div>

    <div class="box-body">
        <label for="">Berkas</label>

        <?= Html::beginForm(false, 'post', [
            'enctype' => 'multipart/form-data',
            'id' => 'myForm'
        ]) ?>

        <?= Html::fileInput('berkas_mentah') ?>

        <?= Html::endForm() ?>
    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-send"></i> Kirim', false, [
            'class' => 'btn btn-success btn-flat kirim',
        ]) ?>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    console.log('Hello');

    let url = 'http://localhost:8074/tandatangan/public/api/berkas/save';

    $('.kirim').on('click', function() {
        var fd = new FormData();
        var files = $('input[name="berkas_mentah"]')[0].files[0];

        if(files != null ) {
            fd.append('berkas_mentah', files);

            $.ajax({
                url: url,
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response){
                    console.log(response);
                },
            });
        } else {
            alert('Berkas tidak boleh kosong');
        }
    });
});
</script>