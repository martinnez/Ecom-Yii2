<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\DataColumn;
use yii\widgets\ActiveForm;
use yii\web\session;

use app\models\HtmlHelper;
?>

<div class="panel well">
	<div class="panel-body">
		<h4><i class="<?= $icon ?>"></i> <?= $header ?></h4>
		<hr/>
		
		<?= $msg ?>
		
		<?php $form = ActiveForm::begin([
			'enableClientValidation' => false,
			'enableAjaxValidation' => false,
		]); ?>
    		<?= $form->field($mdl, 'name') ?>
    		<?= $form->field($mdl, 'address') ?>
    		<?= $form->field($mdl, 'tel') ?>
    		
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

        ],
    ]); 
?>
		<div class="pull-right">
			<?= html::submitButton("<i class=\"glyphicon glyphicon-chevron-left\"></i> ".$cart_management['label'], ['name' => $cart_management['name'], 'class' => 'btn btn-success']) ?>
			<?= html::submitButton($confirm['label']." <i class=\"glyphicon glyphicon-chevron-right\"></i>", ['name' => $confirm['name'], 'class' => 'btn btn-success']) ?>
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>
