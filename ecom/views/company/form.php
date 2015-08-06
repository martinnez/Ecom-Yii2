<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\session;
use app\models\HtmlHelper;

?>

<!-- CKEditor -->
<script src="/ckeditor/ckeditor.js"></script>

<div class="panel well">
	<div class="panel-body">
		<h4><i class="<?= $icon ?>"></i> <?= $header ?></h4>
		<hr/>
		
		<?= $msg ?>
		
		<?php $form = ActiveForm::begin([
				'options' => ['enctype' => 'multipart/form-data'],
		]); ?>
    		<?= $form->field($mdl, 'name') ?>
    		<?= $form->field($mdl, 'title') ?>
    		<?= $form->field($mdl, 'tel') ?>
    		<?= $form->field($mdl, 'email') ?>
    		<?= $form->field($mdl, 'fax') ?>
    		<?= $form->field($mdl, 'website') ?>
    		<?= $form->field($mdl, 'facebook') ?>
    		<?= $form->field($mdl, 'line') ?>
    		<?= $form->field($mdl, 'address') ?>
    		<?= $form->field($mdl, 'tax_code') ?>
    		<?= $form->field($mdl, 'logo') -> fileInput() ?>
    		<?= $form->field($mdl, 'payment') -> textarea(['id'=>'ckeditor1']) ?>
    		<script>CKEDITOR.replace('ckeditor1');</script>
    		<?= $form->field($mdl, 'about') -> textarea(['id'=>'ckeditor2']) ?>
    		<script>CKEDITOR.replace('ckeditor2');</script>
    		<?= $form->field($mdl, 'help') -> textarea(['id'=>'ckeditor3']) ?>
    		<script>CKEDITOR.replace('ckeditor3');</script>
    		<?= $form->field($mdl, 'id') -> hiddenInput() -> label(false)?>

    		<div class="form-group">
        		<?php echo Html::submitButton($save['label'], ['name' => $save['name'], 'class' => $save['class']]) ?>
    		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>