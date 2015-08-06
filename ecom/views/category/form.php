<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\session;
use app\models\HtmlHelper;

use yii\widgets\DetailView;
?>

<div class="panel well">
	<div class="panel-body">
		<h4><i class="<?= $icon ?>"></i> <?= $header ?></h4>
		<hr/>
		
		<?= $msg ?>
		
		<?php 
			//display parent for action add
			if(isset($mdl2)){
				for($i=count($mdl2)-1;$i>=0;$i--){
					echo DetailView::widget([
							'model' => $mdl2[$i],
							'attributes' => [
									['label' => $detail_view['label']['code'], 'value' => $mdl2[$i]->code],
									['label' => $detail_view['label']['name'], 'value' => $mdl2[$i]->name],
									['label' => $detail_view['label']['remark'], 'value' => $mdl2[$i]->remark],
									['label' => $detail_view['label']['level'], 'value' => $mdl2[$i]->level],
							],
							'options' => ['class'=>'table table-striped table-bordered detail-view']
					]);
				}
			}
		?>
		
		<?php $form = ActiveForm::begin(); ?>
    		<?= $form->field($mdl, 'code') ?>
    		<?= $form->field($mdl, 'name') ?>
    		<?= $form->field($mdl, 'remark') -> textarea(['rows'=>'4']) ?>
    		<?= isset($mdl -> category_id) ? $form->field($mdl, 'category_id') -> hiddenInput() -> label(false):''; ?>
    		<?= $form->field($mdl, 'level') -> textInput(['readonly'=>true]) ?>

    		<div class="form-group">
        		<?php echo Html::submitButton($save['label'], ['name' => $save['name'], 'class' => $save['class']]) ?>
    		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>
