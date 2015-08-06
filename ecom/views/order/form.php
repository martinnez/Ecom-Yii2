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
    		<?= $form->field($mdl, 'customer_id') -> dropDownList($customer_id_ddl['items']) ?>
    		<?= $form->field($mdl, 'ip') -> textInput(['readonly' => true])?>
    		<?= $form->field($mdl, 'order_status_id') -> dropDownList($order_status_id_ddl['items']) ?>
    		<?= $form->field($mdl, 'order_date') ?>
    		<?= $form->field($mdl, 'pay_date') -> passwordInput(['value'=>'']) ?>
    		<?= $form->field($mdl, 'send_date') ?>
    		<?= $form->field($mdl, 'cancel_date') ?>
    		<?= $form->field($mdl, 'name') ?>
    		<?= $form->field($mdl, 'address') ?>
    		<?= $form->field($mdl, 'tel') ?>

    		<div class="form-group">
        		<?php echo Html::submitButton($save['label'], ['name' => $save['name'], 'class' => $save['class']]) ?>
    		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>
