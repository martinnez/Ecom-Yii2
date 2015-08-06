<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

use yii\helpers\Url;

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
    <link href="<?= Yii::getAlias('@web') ?>/css/backend.css" rel="stylesheet">
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrapper">
        <?php
            NavBar::begin([
                'brandLabel' => 'E-Commerce	',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                	['label' => Yii::t('app/backend', 'home'), 'url' => ['/backend/home']],
                	[
                		'label' => Yii::t('app/backend', 'cargo'),
                		'items' => [
                				'<li class="dropdown-header"><strong>'.Yii::t('app/backend', 'add_data').'</strong></li>',
                				['label' => Yii::t('app/backend', 'brand'), 'url' => ['/brand/list']],
                				['label' => Yii::t('app/backend', 'category'), 'url' => ['/category/list']],
                				['label' => Yii::t('app/backend', 'product'), 'url' => ['/product/list']],
                				'<li class="divider"></li>',
                				'<li class="dropdown-header"><strong>'.Yii::t('app/backend', 'management').'</strong></li>',
                				['label' => Yii::t('app/backend', 'brand_category'), 'url' => ['/brandcategory/list']],
                				['label' => Yii::t('app/backend', 'category_product'), 'url' => ['/categoryproduct/list']],
                		],
                	],
                	[
                		'label' => Yii::t('app/backend', 'transaction'),
                		'items' => [
                				'<li class="dropdown-header"><strong>'.Yii::t('app/backend', 'add_data').'</strong></li>',
                				['label' => Yii::t('app/backend', 'order'), 'url' => ['/order/list']],
                				'<li class="divider"></li>',
                				'<li class="dropdown-header"><strong>'.Yii::t('app/backend', 'management').'</strong></li>',
                				['label' => Yii::t('app/backend', 'transaction'), 'url' => ['/transaction/listorder']],
                		],
                	],
//                 	[
//                 		'label' => Yii::t('app/backend', 'report'),
//                 		'items' => [
//                 				'<li class="dropdown-header"><strong>'.Yii::t('app/backend', 'financial').'</strong></li>',
//                 				['label' => Yii::t('app/backend', 'list_income'), 'url' => ['/report/listincome']],
//                 		],
//                 	],
                	[
                		'label' => Yii::t('app/backend', 'user'),
                		'items' => [
                				'<li class="dropdown-header"><strong>'.Yii::t('app/backend', 'add_data').'</strong></li>',
                				['label' => Yii::t('app/backend', 'customer'), 'url' => ['/customer/list']],
                				['label' => Yii::t('app/backend', 'staff'), 'url' => ['/staff/list'], 'visible' => Yii::$app->session->has('staff_role') && Yii::$app->session->get('staff_role') === "master" ? true:false],
                				'<li class="divider"></li>',
                				'<li class="dropdown-header"><strong>'.Yii::t('app/backend', 'management').'</strong></li>',
                				['label' => Yii::t('app/backend', 'staff_permission'), 'url' => ['/staffpermission/list']],
                				['label' => Yii::t('app/backend', 'staff_rule'), 'url' => ['/staffrule/list']],
                		],
                	],
                	[
                		'label' => Yii::t('app/backend', 'setting'),
                		'items' => [
                				'<li class="dropdown-header"><strong>'.Yii::t('app/backend', 'frontend_information').'</strong></li>',
                				['label' => Yii::t('app/backend', 'company'), 'url' => ['/company/detail']],
                				'<li class="divider"></li>',
                				'<li class="dropdown-header"><strong>'.Yii::t('app/backend', 'backend_information').'</strong></li>',
                				['label' => Yii::t('app/backend', 'anouncement'), 'url' => ['/anouncement/list']],
                		],
                	],
                ],
            ]);
            NavBar::end();
        ?>

        <div class="container">
        	<div class="row row-bar">
        		<div class="pull-left">
        			<?= Yii::$app->session->has('staff_usr') ? "<h4>".Yii::t('app/backend', 'welcome')." ".Yii::$app->session->get('staff_usr')."</h4>":'' ?>
        		</div>
        		<div class="pull-right">
        		<?php if (Yii::$app -> session -> has('staff_id')) :?>
        			<?= html::a("<i class=\"glyphicon glyphicon-cog\"></i> ".Yii::t('app/user', 'edit'), Url::toRoute(['backend/changeprofile', 'id' => Yii::$app -> session -> get('staff_id')]), ['class' => 'btn btn-info']) ?>
        			<?= html::a("<i class=\"glyphicon glyphicon-log-out\"></i> ".Yii::t('app/user', 'logout'), Url::toRoute(['backend/logout']), ['class' => 'btn btn-danger', 'onclick' => 'return confirm("'.Yii::t('app/msg', 'confirm').'")']) ?>
        		<?php else :?>
        			<?= html::beginForm(Url::toRoute('backend/login')) ?>
        			<div class="form-inline">
        				<div class="input-group">
  							<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-user"></i></span>
  							<?= html::textInput("staff[usr]", null, ['class' => 'form-control', 'placeholder'=>Yii::t('app/user', 'usr'), 'aria-describedby'=>"basic-addon1", "size" => "10"] ) ?>
						</div>
						<div class="input-group">
  							<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-lock"></i></span>
  							<?= html::passwordInput("staff[pwd]", null, ['class' => 'form-control', 'placeholder'=>Yii::t('app/user', 'pwd'), 'aria-describedby'=>"basic-addon1", "size" => "10"] ) ?>
						</div>
        				<?= html::submitButton("<i class=\"glyphicon glyphicon-log-in\"></i> ".Yii::t('app/user', 'login'), ['name' => 'delete', 'class' => 'btn btn-success']);?>
        			</div>
        		<?php endif; ?>
        		</div>
        		<div class="clearfix"></div>
        	</div>
        	<div class="row row-content">
            	<div class="content">
            		<?= $content ?>
            	</div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left"> E-Commerce </p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
