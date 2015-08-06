<?php
	use yii\helpers\Html;
	use yii\widgets\DetailView;
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
	DetailView::widget([
		'model' => $mdl,
		'attributes' => [
				['label' => $detail_view['name']['label'], 'value' => $mdl->name],
				['label' => $detail_view['title']['label'], 'value' => $mdl->title],
				['label' => $detail_view['tel']['label'], 'value' => $mdl->tel],
				['label' => $detail_view['email']['label'], 'value' => $mdl->email],
				['label' => $detail_view['fax']['label'], 'value' => $mdl->fax],
				['label' => $detail_view['website']['label'], 'value' => $mdl->website],
				['label' => $detail_view['facebook']['label'], 'value' => $mdl->facebook],
				['label' => $detail_view['line']['label'], 'value' => $mdl->line],
				['label' => $detail_view['address']['label'], 'value' => $mdl->address],
				['label' => $detail_view['tax_code']['label'], 'value' => $mdl->tax_code],
				['label' => $detail_view['logo']['label'], 'value' => $mdl->logo],
				['label' => $detail_view['payment']['label'], 'format' => 'html', 'value' => $mdl->payment],
				['label' => $detail_view['about']['label'], 'format' => 'html', 'value' => $mdl->about],
				['label' => $detail_view['help']['label'], 'format' => 'html', 'value' => $mdl->help],
		],
		'options' => ['class'=>'table table-striped table-bordered detail-view']
	]);
?>
			<?= Html::submitButton('<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true" href="#"></span> '.$form['label'], ['name' => $form['name'], 'class' => $form['class'], "value" => $mdl -> id]) ?>
		<?php ActiveForm::end(); ?>

	</div>
</div>
