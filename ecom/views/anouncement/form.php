<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\session;
use app\models\HtmlHelper;

use yii\widgets\DetailView;
?>

<!-- CKEditor -->
<script src="/ckeditor/ckeditor.js"></script>

<div class="panel well">
	<div class="panel-body">
		<h4><i class="<?= $icon ?>"></i> <?= $header ?></h4>
		<hr/>
		
		<?= $msg ?>
		
		<?php $form = ActiveForm::begin(); ?>
    		<?= $form->field($mdl, 'text') -> textarea(['id'=>'ckeditor']) ?>
    		<script>CKEDITOR.replace('ckeditor');</script>

    		<div class="form-group">
        		<?php echo Html::submitButton($save['label'], ['name' => $save['name'], 'class' => $save['class']]) ?>
    		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>
