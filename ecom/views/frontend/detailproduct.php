<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\web\session;
use app\models\HtmlHelper;

?>

<div class="panel">
	<div class="panel-body">
		
			<div class="detailproduct-product-box">
			
				<div class="detailproduct-image col-md-4">
					<?= isset($r -> img) ? 
						Html::img(Yii::$app->request->baseUrl.'/uploads/products/'.$r -> img, ['width' => '350px', 'height' => '130px', 'class'=>'img-thumbnail']) : 
						Html::img(Yii::$app->request->baseUrl.'/uploads/defaults/default.png', ['width' => '200px', 'height' => '130px', 'class'=>'img-thumbnail']) ?>
				</div>
				<div class="detailproduct-text col-md-8">
					<div class="panel panel-default">
  						<div class="panel-heading"><h4><?= $r -> name ?></h4></div>
  						<div class="panel-body">
  							<p><strong>Categories : </strong><?php echo $categories; ?></p>
    						<p><strong>Detail : </strong><br/><?= isset($r -> detail) ? $r -> detail:"-"; ?></p><br/>
    						<p><strong>Remark * : </strong><?= isset($r -> remark) ? $r -> remark:"-"; ?></p><br/>
    						<p><strong>Quantity : </strong><?= isset($r -> qty) ? $r -> qty:"-"; ?></p><br/>
							<h4><?= number_format($r -> price) ?> Bath</h4>
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
				
			</div>
		
		<div class="product-detail-box">
		<?php foreach($rs2 as $r2) :?>
			<div class="col-md-2">
				<?= isset($r2 -> url) ? 
					Html::img(Yii::$app->request->baseUrl.'/uploads/products/'.$r2 -> url, ['width' => '200px', 'height' => '130px', 'class'=>'img-thumbnail img-responsive']) : 
					Html::img(Yii::$app->request->baseUrl.'/uploads/defaults/default.png', ['width' => '200px', 'height' => '130px', 'class'=>'img-thumbnail img-responsive']) ?>
			</div>
		<?php endforeach; ?>
		</div>
	</div>
</div>
		