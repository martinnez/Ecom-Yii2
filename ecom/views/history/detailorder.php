<?php
	use yii\helpers\Html;
	use yii\grid\GridView;
	use yii\grid\DataColumn;
	use yii\bootstrap\ActiveForm;
	
	use app\controllers\ActiveDataProvider;
?>

<div class="panel well">
	<div class="panel-body">
		<h4><i class="<?= $icon ?>"></i> <?= $header ?></h4>
		<hr/>
		
<?php 
foreach ($groups as $group) : 
	$total_price = 0;
	$total_qty = 0;
	
	//Color Changer
	$status = $group['order'] -> orderStatus['name'];
	$btn_class = "btn-default";
	if(isset($status)){
		switch ($status){
			//wait, paid, send, cancel
			case "wait":
				$btn_class = "btn-info";
				break;
			case "paid":
				$btn_class = "btn-success";
				break;
			case "send":
				$btn_class = "btn-primary";
				break;
			case "cancel":
				$btn_class = "btn btn-danger";
				break;
		}
	}
	
?>
		<div class="grid-view">
			<div class='pull-left'><div class="btn <?= $btn_class ?>"><?= "Date : ".$group['order'] -> order_date ?></div> <div class="btn <?= $btn_class ?>"><?= "Status : ".$group['order'] -> orderStatus['name'] ?></div></div> <div class='pull-right'></div>
			<div class='clearfix'>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th class="col-md-3"><a href="/index.php/history/listorder?sort=product_id" data-sort="product_id">Product ID</a></th>
						<th class="col-md-3"><a href="/index.php/history/listorder?sort=order_id" data-sort="order_id">Order ID</a></th>
						<th class="col-md-2"><a href="/index.php/history/listorder?sort=price" data-sort="price">Price</a></th>
						<th class="col-md-2"><a href="/index.php/history/listorder?sort=qty" data-sort="qty">Qty</a></th>
						<th class="col-md-2">Total</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						foreach ($group['orderdetail'] as $r){
							$total_price += $r -> price;
							$total_qty += $r -> qty;
							echo "<tr><td>".$r -> product['name']."</td><td>".$group['order'] -> customer['fname']." ".$group['order'] -> customer['lname']."</td><td>".$r -> price."</td><td>".$r -> qty."</td><td>".(int)($r -> price) * (int)($r -> qty)."</td></tr>";
						}
					?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3"><strong><?= "Total" ?></strong></td>
						<td><strong><?= $total_qty ?></strong></td>
						<td><strong><?= $total_price ?></strong></td>
					</tr>
				</tfoot>
			</table>
			</div>
		</div>
<?php endforeach; ?>

	</div>
</div>