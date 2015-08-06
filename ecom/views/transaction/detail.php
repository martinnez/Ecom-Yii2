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
        'layout' => "<div class='pull-left'><h4>".$header2."</h4></div><div class='pull-right'>{summary}</div>\n<div class='clearfix'>{items}</div>\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            ['attribute' => 'product_id', 'value' => 'product.name'],
            'price',
            'qty',
        ],
    ]); 
?>
		<div class="pull-left">
			<?= Html::submitButton('<span class="glyphicon glyphicon-chevron-left" aria-hidden="true" href="#"></span> '.$back['label'], ['name' => $back['name'], 'class' => $back['class'], "value" => Html::encode($data -> id)]) ?>
		</div>
		<div class="pull-right">
			<?= Html::submitButton('<span class="glyphicon glyphicon-usd" aria-hidden="true" href="#"></span> '.$paid['label'], ['name' => $paid['name'], 'class' => $paid['class'], "value" => Html::encode($data -> id)]) ?>
			<?= Html::submitButton('<span class="glyphicon glyphicon-send" aria-hidden="true" href="#"></span> '.$send['label'], ['name' => $send['name'], 'class' => $send['class'], "value" => Html::encode($data -> id)]) ?>
			<?= Html::submitButton('<span class="glyphicon glyphicon-remove-circle" aria-hidden="true" href="#"></span> '.$cancel['label'], ['name' => $cancel['name'], 'class' => $cancel['class'], "value" => Html::encode($data -> id)]) ?>
		</div>
		
		<?php ActiveForm::end(); ?>

	</div>
</div>