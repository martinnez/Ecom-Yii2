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
    		<?= $form->field($mdl, 'fname') ?>
    		<?= $form->field($mdl, 'lname') ?>
    		<?= $form->field($mdl, 'staff_role_id') -> dropDownList($staff_role_id_ddl['items']) ?>
    		<?= $form->field($mdl, 'usr') ?>
    		<?= $form->field($mdl, 'pwd') -> passwordInput(['value'=>'']) ?>
    		<?= $form->field($mdl, 'email') ?>

    		<div class="form-group">
        		<?php echo Html::submitButton($save['label'], ['name' => $save['name'], 'class' => $save['class']]) ?>
    		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>
