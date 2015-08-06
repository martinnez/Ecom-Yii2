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
            ['attribute' => 'customer.fname', 'value' => 'customer.fname'],
            ['attribute' => 'customer.lname', 'value' => 'customer.lname'],
            ['attribute' => 'order_status_id', 'value' => 'orderStatus.name'],
            'order_date',
            'paid_date',
            'send_date',
            'cancel_date',
            'name',
            'address',
            'tel',
            [
            	'header' => $detail_order['label'],
            	'format' => 'raw',
            	'value' =>function($data) use ($detail_order){
            		return Html::submitButton('<span class="glyphicon glyphicon-list" aria-hidden="true" href="#"></span>', ['name' => $detail_order['name'], 'class' => 'btn btn-borderless', "value" => Html::encode($data -> id)]);
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