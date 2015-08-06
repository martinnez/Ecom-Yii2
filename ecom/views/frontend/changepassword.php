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
		
		<?php $form = ActiveForm::begin(); ?>
    		<?= $form->field($mdl, 'pwd') -> passwordInput(['value'=>'']) ?>
    		<div class="form-group">
				<label class="confirm_pwd" for="confirm_pwd"><?= $confirm_pwd['label'] ?></label>
				<input type="password" id="confirm_pwd" class="form-control" name="confirm_pwd">
				<div class="help-block"><?php if(isset($confirm_pwd['help_block'])) echo $confirm_pwd['help_block']; ?></div>
			</div>

    		<div class="form-group">
        		<?php echo Html::submitButton($save['label'], ['name' => $save['name'], 'class' => $save['class']]) ?>
    		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>
