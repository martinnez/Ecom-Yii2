<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\DataColumn;
use yii\bootstrap\ActiveForm;
	
use app\controllers\ActiveDataProvider;
?>

<div class="panel well">
	<div class="panel-body">
		<h4><i class="<?= $icon ?>"></i> <?= $header ?></h4>
		<hr/>

		<?= $msg ?>

		<?php $f = ActiveForm::begin(); ?>
		
<?= 
	GridView::widget([
        'dataProvider' => $data,
        'layout' => "<div class='pull-left'>".$header2."</div><div class='pull-right'>{summary}</div>\n<div class='clearfix'>{items}</div>\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['attribute' => 'brand_id', 'value' => 'brand.name'],
            'code',
            'name',
            'price',
            [
            	'header' => $qty['label'],
            	'format' => 'raw',
        		'value' =>function($data) use ($cart){
        				if(isset($cart)){
        					foreach ($cart as $product){
        						if(Html::encode($data -> id) == $product['id']){
        							return $product['qty'];
        						}
        					}
        				}
				},
				'headerOptions'=>['style'=>'text-align:center'],
				'contentOptions'=>['style'=>'text-align:center'],
            ],
            [
            	'header' => $remove['label'],
            	'format' => 'raw',
            	'value' =>function($data) use ($remove){
            			return Html::submitButton('<span class="glyphicon glyphicon-remove" aria-hidden="true" href="#"></span>', ['name' => 'remove', 'class' => 'btn btn-borderless', "value" => Html::encode($data -> id), 'onclick' => 'return confirm("'.$remove['confirm'].'");']);
           		},
            	'headerOptions'=>['style'=>'text-align:center'],
            	'contentOptions'=>['style'=>'text-align:center'],
            ],

        ],
    ]); 
?>
		<div class="pull-right">
			<?= html::submitButton("<i class=\"glyphicon glyphicon-chevron-left\"></i> ".$continue_shopping['label'], ['name' => $continue_shopping['name'], 'class' => 'btn btn-success']) ?>
			<?= html::submitButton($checkout['label']." <i class=\"glyphicon glyphicon-chevron-right\"></i>", ['name' => $checkout['name'], 'class' => 'btn btn-success', "value"=>'']) ?>
		</div>
		<?php ActiveForm::end(); ?>

	</div>
</div>