<?php

?>

<div class="panel well">
	<div class="panel-body">
		<h4><?= $header ?></h4>
		<br/>
		<?php foreach ($rs as $r) :?>
		<p><?= $r -> text ?></p>
		<div class="pull-right"><?= $r -> create_date ?></div>
		<div class="clearfix"></div>
		<hr/>
		<?php endforeach; ?>
	</div>
</div>
