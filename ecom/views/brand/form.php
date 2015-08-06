<?php 
use yii\helpers\Html;
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
				'options' => ['enctype' => 'multipart/form-data'],
		]); ?>
		
    		<?= $form->field($mdl, 'name') ?>
    		<?= $form->field($mdl, 'detail') -> textarea(['rows' => '6']) ?>
    		<?= $form->field($mdl, 'img') -> fileInput(['class' => 'form-control']) ?>

    		<div class="form-group">
        		<?php echo Html::submitButton($save['label'], ['name' => $save['name'], 'class' => $save['class']]) ?>
    		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>
