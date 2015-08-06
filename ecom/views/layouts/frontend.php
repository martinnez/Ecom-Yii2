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

$mdl = Company::find() -> one();
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
    <!-- CSS file for frontend layout -->
    <link href="<?= Yii::getAlias('@web') ?>/css/frontend.css" rel="stylesheet">
    <!-- CSS file for Carousel slide -->
    <link href="<?= Yii::getAlias('@web') ?>/css/carousel.css" rel="stylesheet">
</head>
<body>

<?php $this->beginBody() ?>
	<div class="wrapper">
		<div class="header">
			<div class="container">
				<div class="row">
					<div class="pull-left">
						<?php 
							if(isset($mdl)){
								if(!empty($mdl -> logo)){
									echo Html::img(Yii::getAlias('@web')."/uploads/logos/".$mdl -> logo, ['width'=>'40px']);
								}
								echo " ".$mdl -> title;
							}
							else{
								echo "Your company logo and name.";
							}
						?>
					</div>
					<div class="pull-right">
						<ul class="nav nav-pills">
  							<li role="presentation"><?= html::a("<i class=\"glyphicon glyphicon-phone-alt\"></i> ".Yii::t('app/frontend', 'contact'), Url::to(['frontend/contact'])) ?></li>
  							<li role="presentation"><?= html::a("<i class=\"glyphicon glyphicon-question-sign\"></i> ".Yii::t('app/frontend', 'help'), Url::to(['frontend/help'])) ?></li>
						</ul>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
    	</div>

        <?php
            NavBar::begin([
                'options' => [
                    'class' => 'navbar-inverse',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-left'],
                'items' => [
                    ['label' => Yii::t('app/frontend', 'home'), 'url' => ['/frontend/home']],
                	['label' => Yii::t('app/frontend', 'product'), 'url' => ['/frontend/listproduct']],
                	['label' => Yii::t('app/frontend', 'about'), 'url' => ['/frontend/about']],
                	['label' => Yii::t('app/frontend', 'payment'), 'url' => ['/frontend/payment']],
                ],
            ]);
        ?>
        <div class="navbar-form navbar-right">
        	<div class="form-inline">
        		<div class="input-group">
        			<?= html::beginForm(Url::toRoute('frontend/search')) ?>
  						<?= html::textInput("search", null, ['name' => 'search', 'class' => 'form-control', 'placeholder'=>Yii::t('app/frontend', 'search'), 'aria-describedby'=>"basic-addon1", "size"=>"15"] ) ?>
  						<?= html::submitButton("<i class=\"glyphicon glyphicon-search\"></i>", ['name' => 'btn_search', 'class' => 'btn btn-primary']);?>
  					<?= html::endForm() ?>
				</div>

			<?php if (Yii::$app -> session -> has('staff_id')) :?>
			
        		<div class="btn-group">
  					<button type="button" class="btn btn-primary"><?= "<i class=\"glyphicon glyphicon-user\"></i> ".Yii::$app -> session -> get('staff_usr') ?></button>
  					<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    					<span class="caret"></span>
    					<span class="sr-only">Toggle Dropdown</span>
  					</button>
  					<ul class="dropdown-menu" role="menu">
    					<li><?= html::a("<i class=\"glyphicon glyphicon-cog\"></i> ".Yii::t('app/user', 'edit'), Url::toRoute(['backend/changeprofile', 'id' => Yii::$app -> session -> get('staff_id')])) ?></li>
  					</ul>
				</div>
				<?= html::a("<i class=\"glyphicon glyphicon-log-out\"></i> ".Yii::t('app/user', 'logout'), Url::toRoute(['backend/logout']), ['class' => 'btn btn-danger', 'onclick' => 'return confirm("'.Yii::t('app/msg', 'confirm').'")']) ?>
				
        	<?php elseif (Yii::$app -> session -> has('customer_id')) :?>
        	
				<div class="input-group">
					<?= html::beginForm(Url::toRoute('cart/list')) ?>
        				<?php $total = Yii::$app -> session -> has('cart') ? count(Yii::$app -> session -> get('cart')):'0' ?>
  						<?= html::submitButton("<i class=\"glyphicon glyphicon-shopping-cart\"> <span class=\"badge\">{$total}</span></i>", ['name' => 'cart', 'class' => 'btn btn-primary']);?>
  					<?= html::endForm() ?>
				</div>
				<div class="btn-group">
  					<button type="button" class="btn btn-primary"><?= "<i class=\"glyphicon glyphicon-user\"></i><strong> ".Yii::t('app/user', 'account')."</strong>" ?></button>
  					<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    					<span class="caret"></span>
    					<span class="sr-only">Toggle Dropdown</span>
  					</button>
  					<ul class="dropdown-menu" role="menu">
        				<li><?= html::a("<i class=\"glyphicon glyphicon-list-alt\"></i> ".Yii::t('app/frontend', 'change_profile'), Url::toRoute(['frontend/changeprofile', 'id' => Yii::$app -> session -> get('customer_id')])) ?></li>
        				<li><?= html::a("<i class=\"glyphicon glyphicon-list-alt\"></i> ".Yii::t('app/frontend', 'change_password'), Url::toRoute(['frontend/changepassword', 'id' => Yii::$app -> session -> get('customer_id')])) ?></li>
        				<li class="divider"></li>
    					<li><?= html::a("<i class=\"glyphicon glyphicon-list-alt\"></i> ".Yii::t('app/frontend', 'history'), Url::toRoute(['history/listorder'])) ?></li>
  					</ul>
				</div>
        		<?= html::a("<i class=\"glyphicon glyphicon-log-out\"></i> ".Yii::t('app/user', 'logout'), Url::toRoute(['frontend/logout']), ['class' => 'btn btn-danger', 'onclick' => 'return confirm("'.Yii::t('app/msg', 'confirm').'")']) ?>
        		
        	<?php else :?>
        	
        		<div class="input-group">
        			<?= html::beginForm(Url::toRoute('/frontend/login')) ?>
        			<div class="input-group">
  						<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-user"></i></span>
  						<?= html::textInput("customer[usr]", null, ['class' => 'form-control', 'placeholder'=>Yii::t('app/user', 'usr'), 'aria-describedby'=>"basic-addon1", "size"=>"10"] ) ?>
					</div>
					<div class="input-group">
  						<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-lock"></i></span>
  						<?= html::passwordInput("customer[pwd]", null, ['class' => 'form-control', 'placeholder'=>Yii::t('app/user', 'pwd'), 'aria-describedby'=>"basic-addon1", "size"=>"10"] ) ?>
					</div>
        			<?= html::submitButton("<i class=\"glyphicon glyphicon-log-in\"></i> ".Yii::t('app/user', 'login'), ['name' => 'login', 'class' => 'btn btn-success']);?>
        			<?= html::a("<i class=\"glyphicon glyphicon-user\"></i> ".Yii::t('app/user', 'register'), Url::toRoute(['frontend/register', 'id' => Yii::$app -> session -> get('customer_id')]), ['class' => 'btn btn-success']) ?>
        			<?= html::endForm() ?>
        		</div>
        	</div>
        	
        	<?php endif; ?>
		</div>      

        <?php 
            NavBar::end();
        ?>

        <div class="container">
        	<!--
        	<div class="row">
        		<!-- Advertising 
				<div class="image_container">
					<div class="image">
						<a href="#"><p>advestising</p></a>
					</div>
				</div>
			</div>
			-->
        	<div class="row">
            	<div class="content">
            		<?= $content ?>
            	</div>
            </div>
        </div>
	</div>
	
	<br/>
	<br/>
        
    <footer class="footer">
        <div class="container">
            <div class="pull-left">
            	<?php if(isset($mdl)) :?>
            		<div>&copy; <?= $mdl -> name." ".date('Y') ?></div>
            	<?php else : ?>
            		<div>&copy; Your company name.<?= date('Y') ?></div>
            	<?php endif; ?>
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
