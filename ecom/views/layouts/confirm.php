<?php
use yii\bootstrap\Modal;

	Modal::begin([
		'header' => '<h4>Test Modal</h4>',
		'id' => 'modal',
		'size' => 'modal-lg',
	]);
	
	echo "Yea!!";
	
	Modal::end();