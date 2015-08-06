<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\web\session;
use app\models\HtmlHelper;

use app\models\Category;

?>

<!-- Category Lists -->
<nav class="navbar navbar-default">
  	<div class="container-fluid">
  
    	<div class="navbar-header">
      		<a class="navbar-brand" href="#">Categories</a>
    	</div>

    	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      		<ul class="nav navbar-nav">
        		<?php echo $category_menu; ?>
      		</ul>
    	</div><!-- /.navbar-collapse -->
    			
  	</div><!-- /.container-fluid -->
</nav>
<!-- Product layout -->
<div class="panel">
	<div class="panel-body">
	
		<!-- Sub-Category Breadcrumb -->
		<div class="col-md-12">
			<ol class="breadcrumb">
				<?php echo $breadcrumb ?>
			</ol>
		</div>
		
		<!-- Product Lists -->
		<?php foreach($rs as $r) : ?>
			<div class="col-md-2">
				<div class="panel panel-default">
  					<div class="panel-body">
  					<div class="listproduct-product-box">
    					<div class="listproduct-product-image">
							<a href="<?= Url::toRoute(['frontend/detailproduct', 'id' => $r -> id]) ?>">
							<?= isset($r -> img) ? 
								Html::img(Yii::$app->request->baseUrl.'/uploads/products/'.$r -> img) : 
								Html::img(Yii::$app->request->baseUrl.'/uploads/defaults/default.png') ?>
							</a>
						</div>
						<div class="listproduct-product-name">
							<a href="<?= Url::toRoute(['frontend/detailproduct', 'id' => $r -> id]) ?>">
								<?= $r -> name ?>
							</a>
						</div>
						<div class="listproduct-product-price">
							<h5><?= number_format($r -> price) ?> Bath</h5>
						</div>
  					</div>
  					</div>
  					<?php if(Yii::$app -> session -> has('customer_usr')) : ?>
  					<div class="panel-footer">
  						<?= html::beginForm(Url::toRoute('listproduct')) ?>
							<?= html::submitButton($add_cart['label'], ['name' => $add_cart['name'], 'class' => $add_cart['class'], "value" => $r -> id]) ?>
						<?= html::endForm() ?>
					</div>
					<?php endif; ?>
					
				</div>
			</div>
		<?php endforeach; ?>
		
		
		
		
		
		<div>
			<?= $pagination ?>
		</div>
	</div>
</div>