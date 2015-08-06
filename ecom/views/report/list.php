<?php
	use yii\helpers\Html;
	use yii\grid\GridView;
	use yii\grid\DataColumn;
	use yii\bootstrap\ActiveForm;
	use yii\web\session;
	use app\models\HtmlHelper;
	
	use app\controllers\ActiveDataProvider;
	
	use app\models\Product;
	use app\models\OrderDetail;
?>

<div class="panel well">
	<div class="panel-body">
		<h4><i class="<?= $icon ?>"></i> <?= $header ?></h4>
		<hr/>

		<?= $msg ?>
		
		<?php $f = ActiveForm::begin(); ?>
		
		<div class="pull-left">
			<?= Html::submitButton($by_time['label'], ['name' => $by_time['name'], 'class' => $by_time['class']]) ?>
			<?= Html::submitButton($by_product['label'], ['name' => $by_product['name'], 'class' => $by_product['class']]) ?>
			<?= Html::submitButton($by_customer['label'], ['name' => $by_customer['name'], 'class' => $by_customer['class']]) ?>
			<?= Html::submitButton($by_brand['label'], ['name' => $by_brand['name'], 'class' => $by_brand['class']]) ?>
			<?= Html::submitButton($by_category['label'], ['name' => $by_category['name'], 'class' => $by_category['class']]) ?>
		</div>
		<div class="clearfix"></div>
		
<?= 
	GridView::widget([
        'dataProvider' => $data,
        'layout' => "<div class='pull-left'>".$header2."</div><div class='pull-right'>{summary}</div>\n<div class='clearfix'>{items}</div>\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'tel',
            [
            	'header' => 'Product Name',
            	'format' => 'raw',
            	'value' =>function($data){
            		$html = "";
            		$rs = OrderDetail::findAll(['order_id'=> $data -> id]);
            		for($i=0;$i<count($rs);$i++){
            			$html .= Product::findOne(['id'=>$rs[$i] -> product_id]) -> name;
            			if($i < count($rs)-1){
            				$html .= ", ";
            			}
            		}
            		return $html;
            	},
            	'headerOptions'=>['style'=>'text-align:center'],
            	'contentOptions'=>['style'=>'text-align:center'],
            ],
            [
            	'header' => 'Total Price',
            	'format' => 'raw',
            	'value' =>function($data){
            		$total = 0;
            		$rs = OrderDetail::findAll(['order_id'=> $data -> id]);
            		for($i=0;$i<count($rs);$i++){
            			$price = (int)(Product::findOne(['id'=>$rs[$i] -> product_id]) -> price);
            			if(!isset($price) && !is_numeric((int)($price))){
            				$total = "Error!";
            				break;
            			}
            			$total += Product::findOne(['id'=>$rs[$i] -> product_id]) -> price;
            		}
            		return $total;
            	},
            	'headerOptions'=>['style'=>'text-align:center'],
            	'contentOptions'=>['style'=>'text-align:center'],
            ],
        ],
    ]); 
?>

		<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>