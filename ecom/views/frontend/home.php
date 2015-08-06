<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\web\session;
use app\models\HtmlHelper;

use app\models\Category;

?>

<div class="panel">
	<div class="panel-body">
	
	<!-- Carousel ================================================== -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
    
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>
      
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img class="first-slide" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="First slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Lorem Ipsum.</h1>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div>
          </div>
        </div>
        <div class="item">
          <img class="second-slide" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Second slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Lorem Ipsum.</h1>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div>
          </div>
        </div>
        <div class="item">
          <img class="third-slide" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Third slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Lorem Ipsum.</h1>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div>
          </div>
        </div>
      </div>
      
      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div><!-- /.carousel -->
    
    <!-- New Arrival -->
    <div>
    	<h4><?= $new_arrivals['header'] ?></h4>
    	<?php foreach($new_arrivals['new_arrival_arr'] as $product) :?>
    	<div class="col-md-2">
    		<div class="panel panel-default">
  				<div class="panel-body">
  					<div class="frontend-new-arrival-product-box">
    				<div class="frontend-new-arrival-product-image">
						<a href="<?= Url::toRoute(['frontend/detailproduct', 'id' => $product -> id]) ?>">
							<?= isset($product -> img) ? 
								Html::img(Yii::$app->request->baseUrl.'/uploads/products/'.$product -> img) : 
								Html::img(Yii::$app->request->baseUrl.'/uploads/defaults/default.png') ?>
						</a>
					</div>
					<div class="frontend-new-arrival-product-name">
						<a href="<?= Url::toRoute(['frontend/detailproduct', 'id' => $product -> id]) ?>">
							<?= $product -> name ?>
						</a>
					</div>
					<div class="frontend-new-arrival-product-price">
						<h5><?= number_format($product -> price) ?> Bath</h5>
					</div>
					</div>
  				</div>
  					
  				<?php if(Yii::$app -> session -> has('customer_usr')) : ?>
  				<div class="panel-footer">
  					<?= html::beginForm(Url::toRoute('listproduct')) ?>
						<?= html::submitButton($add_cart['label'], ['name' => $add_cart['name'], 'class' => $add_cart['class'], "value" => $product -> id]) ?>
					<?= html::endForm() ?>
				</div>
				<?php endif; ?>
					
			</div>
		</div>
    	<?php endforeach; ?>
    </div>
		
	</div>
</div>

<!-- Products by Brands -->
<div class="panel">
	<div class="panel-body">
		<h4><?= $brands['header'] ?></h4>
		<?php foreach($brands['brand_arr'] as $brand) :?>
				<div class="brand-image">
					<a href="<?= Url::toRoute(['frontend/listproduct', 'search'=>null, 'category_id' => null, 'brand_id' => $brand -> id]) ?>">
						<?= isset($brand -> img) ? 
							Html::img(Yii::$app->request->baseUrl.'/uploads/brands/'.$brand-> img) : 
							Html::img(Yii::$app->request->baseUrl.'/uploads/defaults/default.png') ?>
					</a>
				</div>
		<?php endforeach; ?>
	</div>
</div>

<!-- Best Seller 
<div class="panel">
	<div class="panel-body">
		<h4>Best Seller!</h4>
		<div class="well">Best Seller Lists</div>
	</div>
</div>
-->

<!-- Part 3 -->
<div class="panel">
	<div class="panel-body">
		<h4><?= $categories['header'] ?></h4>
		<div class="col-md-3">
			<div class="panel panel-default">
  				<div class="panel-heading"><?= $category_menu['header'] ?></div>
  				<div class="panel-body">
  					<?php 
						echo $category_menu['category_menu'];
  					?>
  				</div>
			</div>
		</div>
		<div class="col-md-9">
			
			<!-- Product in category -->
			<?php foreach($categories['product_arr'] as $category => $products) :?>
			<div class="panel panel-default">
  				<div class="panel-heading">
    				<h3 class="panel-title"><?= $category ?></h3>
  				</div>
  				<div class="panel-body">
					<?php foreach($products as $product) :?>
					<div class="col-md-3">
						<div class="panel panel-default">
  							<div class="panel-body frontend-category-product-box">
    							<div class="frontend-category-product-image">
									<a href="<?= Url::toRoute(['frontend/detailproduct', 'id' => $product -> id]) ?>">
										<?= isset($product -> img) ? 
											Html::img(Yii::$app->request->baseUrl.'/uploads/products/'.$product -> img) : 
											Html::img(Yii::$app->request->baseUrl.'/uploads/defaults/default.png') ?>
									</a>
								</div>
								<div class="frontend-category-product-name">
									<a href="<?= Url::toRoute(['frontend/detailproduct', 'id' => $product -> id]) ?>">
										<?= $product -> name ?>
									</a>
								</div>
								<div class="frontend-category-product-price">
									<h5><?= number_format($product -> price) ?> Bath</h5>
								</div>
  							</div>
  					
  							<?php if(Yii::$app -> session -> has('customer_usr')) : ?>
  							<div class="panel-footer">
  								<?= html::beginForm(Url::toRoute('listproduct')) ?>
									<?= html::submitButton($add_cart['label'], ['name' => $add_cart['name'], 'class' => $add_cart['class'], "value" => $product -> id]) ?>
								<?= html::endForm() ?>
							</div>
							<?php endif; ?>
					
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>