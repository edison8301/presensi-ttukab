<?php

use yii\helpers\Html;

use app\assets\AppAsset;

/**
 * @var \yii\web\View $this
 * @var string $content
 */


$hide = "$('.hide-alert').animate({opacity: 1.0}, 3000).fadeOut('slow')";
$this->registerJs($hide, $this::POS_END, '');

?>

<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>E-Absensi</title>

<?php $baseUrl = Yii::$app->request->baseUrl;  ?>
  

<link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
<link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>

<link rel="stylesheet" href="<?= $baseUrl; ?>/css/login-frontend.css">
<?php $this->registerCssFile(Yii::$app->request->baseUrl.'/css/login-frontend.css'); ?>

  
</head>

<body>
  

<div class="pen-title">
  <h1>E - Absensi</h1>
  <span>KABUPATEN TIMOR TENGAH UTARA</span>
</div>
	<?= $content; ?>

</body>


</html>
