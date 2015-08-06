<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\HtmlHelper;
use app\models\Company;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
	<div class="wrap">
		<div class="header">
			<div class="container">
				<div class="row">
					<h2>Sorry! We are under maintenance.</h2>
				</div>
			</div>
    	</div>

        <div class="container">
        	<div class="row">
            	<div class="content">
            		<?= $content ?>
            	</div>
            </div>
        </div>
	</div>
        
    <footer class="footer">
        <div class="container">
            <div class="pull-left">
            	Email
            </div>
            <div class="pull-right">
            	<i class="glyphicon glyphicon-flash"></i>
            	Powered by 
            	<a href="http://getbootstrap.com/" target="_blank">Bootstrap</a>
            	and
            	<a href="http://www.yiiframework.com/" target="_blank">Yii2 Framework</a>
            </div>
            <div class="clearfix"></div>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
