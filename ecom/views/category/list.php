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
        'layout' => "<div class='pull-left'>".Html::submitButton('<span class="glyphicon glyphicon-plus" aria-hidden="true" href="#"></span> '.$create['label'], ['name' => 'create', 'class' => 'btn btn-success'])."</div><div class='pull-right'>{summary}</div>\n<div class='clearfix'>{items}</div>\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'code',
            ['label'=>'Parent', 'attribute' => 'category_id', 'value' => 'category.name'],
            'name',
            'remark',
            'level',
            [
            	'header' => $add['label'],
            	'format' => 'raw',
            	'value' =>function($data) use ($add){
            		return Html::submitButton('<span class="glyphicon glyphicon-plus" aria-hidden="true" href="#"></span>', ['name' => 'add', 'class' => 'btn btn-borderless', "value" => Html::encode($data -> id)]);
            	},
            	'headerOptions'=>['style'=>'text-align:center'],
            	'contentOptions'=>['style'=>'text-align:center'],
            ],
            [
            	'header' => $edit['label'],
            	'format' => 'raw',
        		'value' =>function($data) use ($edit){
        				return Html::submitButton('<span class="glyphicon glyphicon-edit" aria-hidden="true" href="#"></span>', ['name' => 'edit', 'class' => 'btn btn-borderless', "value" => Html::encode($data -> id)]);
				},
				'headerOptions'=>['style'=>'text-align:center'],
				'contentOptions'=>['style'=>'text-align:center'],
            ],
            [
            	'header' => $delete['label'],
            	'format' => 'raw',
            	'value' =>function($data) use ($delete){
            			return Html::submitButton('<span class="glyphicon glyphicon-trash" aria-hidden="true" href="#"></span>', ['name' => 'delete', 'class' => 'btn btn-borderless', "value" => Html::encode($data -> id), 'onclick' => 'return confirm("'.$delete['confirm'].'");']);
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