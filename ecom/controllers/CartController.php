<?php

namespace app\controllers;

use Yii;
use app\models\HtmlHelper;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\web\session;
use Faker\Provider\Address;
use yii\db\Expression;

use app\models\Product;
use app\models\Customer;
use app\models\Order;
use app\models\OrderDetail;

class CartController extends \yii\web\Controller
{
	public $layout = "frontend";
	
	public function init()
	{
		parent::init();
		
		if(!Yii::$app->session->has('customer_id') || !Yii::$app->session->has('customer_status') || Yii::$app->session->get('customer_status') === "suspend"){
			return $this->redirect(Yii::getAlias('@frontend'));
		}
		
		//Error Handling
// 		if (Yii::$app->errorHandler->exception !== null) {
// 			return $this->redirect(Yii::getAlias('@error'));
// 		}
	}
	
    public function actionIndex()
    {
        return $this->redirect(Yii::getAlias('@frontend'));
    }
    
    public function actionList()
    {
    	if(!Yii::$app -> session -> has('cart') || (Yii::$app -> session -> has('cart') && Yii::$app -> session -> get('cart') == []) ){
    		return $this->redirect(['frontend/listproduct']);
    	}
    	
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	 
    	$mdl = new Product();

    	$cart = Yii::$app -> session -> get('cart');
    	$cond = "";
    	for($i=0;$i<count($cart);$i++){
    		$cond .= " id=".$cart[$i]['id'];
    		if($i < count($cart) - 1){
    			$cond .= " OR ";
    		}
    	}
    	$query = $mdl -> find() -> where($cond) -> orderBy('id ASC');
    	 
    	$data = new ActiveDataProvider([
    			'query' => $query,
    			'pagination' => [
    					'pageSize' => 20,
    			],
    	]);
    	
    	if(isset($_POST['remove'])){
    		return $this->redirect(['remove', 'id'=>$_POST['remove']]);
    	}
    	elseif(isset($_POST['continue_shopping'])){
    		return $this->redirect(['frontend/listproduct']);
    	}
    	elseif(isset($_POST['checkout'])){
    		return $this->redirect(['checkout']);
    	}
    	 
    	//Set Message
    	$msg = "";
    	if(Yii::$app->session->has('msg')){
    		$msg = HtmlHelper::getAlert(Yii::$app->session->get('msg'), 'success');
    		Yii::$app->session->remove('msg');
    	}
    	 
    	return $this -> render('list', [
    			'data' => $data,
    			'icon' => 'glyphicon glyphicon-user',
    			'header' => Yii::t("app/{$controller}_{$action}", 'header'),
    			'header2' => Yii::t("app/{$controller}_{$action}", 'header2'),
    			'cart' => $cart,
    			'qty' => ['label' => Yii::t("app/button", 'qty')],
    			'remove' => ['label' => Yii::t("app/button", 'remove'), 'confirm' => Yii::t("app/msg", 'confirm')],
    			'continue_shopping' => ['label' => Yii::t("app/button", 'continue_shopping'), 'name'=>'continue_shopping'],
    			'checkout' => ['label' => Yii::t("app/button", 'checkout'), 'name'=>'checkout'],
    			'msg' => $msg,
    	]);
    }
    
    public function actionAdd($id)
    {
    	$mdl = isset($id) && ($rs = Product::findOne($id)) !== null ? $rs:new Product();
    	
    	$cart = [];
    	
    	if(Yii::$app -> session -> has('cart')){
    		$cart = Yii::$app -> session -> get('cart');
    	}
    	
    	//Product
    	if(isset($_POST) && isset($mdl)){
    		
    		$qty = 1;
    		if(!empty($cart)){
    			//Check if duplicate product
    			$has_data = false;
    			for($i=0;$i<count($cart);$i++){
    				if(isset($cart[$i]['id']) && isset($mdl -> id) && $cart[$i]['id'] == $mdl -> id){
    					$cart[$i]['qty'] += 1;
    					$has_data = true;
    					break;
    				}
    			}
    			
    			//Case if not found
    			if(!$has_data){
    				$temp = [
    						'id' => $mdl -> id,
    						'code' => $mdl -> code,
    						'name' => $mdl -> name,
    						'price' => $mdl -> price,
    						'qty' => $qty,
    				];
    				
    				$cart[count($cart)] = $temp;
    			}
    				
    		}
    		else{
    			$temp = [
    					'id' => $mdl -> id,
    					'code' => $mdl -> code,
    					'name' => $mdl -> name,
    					'price' => $mdl -> price,
    					'qty' => $qty,
    			];
    			
    			$cart[count($cart)] = $temp;
    		}
    		
    	}
    	
    	Yii::$app -> session -> set('cart', $cart);
    	
    	return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionRemove($id)
    {
    	$cart = [];
    	
    	if(Yii::$app -> session -> has('cart')){
    		$cart = Yii::$app -> session -> get('cart');
    	}
    	 
    	//Product
    	if(isset($_POST) && !empty($cart)){
    		//Remove selected product
    		for($i=0;$i<count($cart);$i++){
    			if(isset($cart[$i]['id']) && $cart[$i]['id'] == $id){
    				unset($cart[$i]);
    			}
    		}
    		
    		//Rearrange array
    		$arr = [];
    		foreach($cart as $product){
    			$arr[] = $product;
    		}
    		$cart = $arr;
    		
    		Yii::$app -> session -> set('cart', $cart);
    	}

    	return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionCheckout()
    {
    	if(!Yii::$app -> session -> has('cart') || (Yii::$app -> session -> has('cart') && Yii::$app -> session -> get('cart') == [])){
    		return $this->redirect(['frontend/listproduct']);
    	}
    	
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	
    	$mdl = new Order();
    	$mdl2 = Yii::$app -> session -> has('customer_id') && ($rs = Customer::findOne(Yii::$app -> session -> get('customer_id'))) !== null ? $rs:new Customer();
    	$mdl3 = new Product();
    	$mdl4 = new OrderDetail();
    	
    	$cart = Yii::$app -> session -> get('cart');
    	$cond = "";
    	for($i=0;$i<count($cart);$i++){
    		$cond .= " id=".$cart[$i]['id'];
    		if($i < count($cart) - 1){
    			$cond .= " OR ";
    		}
    	}
    	
    	$data = new ActiveDataProvider([
    			'query' => $mdl3 -> find() -> where($cond) -> orderBy('id ASC'),
    			'pagination' => [
    					'pageSize' => 20,
    			],
    	]);
    	
    	//Set default value
    	$mdl -> name = isset($mdl2) ? $mdl2 -> fname." ".$mdl2 -> lname: $mdl -> name;
    	$mdl -> address = isset($mdl2) ? $mdl2 -> address : $mdl -> address;
    	$mdl -> tel = isset($mdl2) ? $mdl2 -> tel : $mdl -> tel;
    	
		if(isset($_POST['cart_management'])){
    		return $this->redirect(['list']);
    	}
    	elseif(isset($_POST['confirm'])){
    		
    		if(!empty($_POST)){
    			$mdl -> customer_id = Yii::$app -> session -> get('customer_id');
    			$mdl -> ip = $_SERVER['REMOTE_ADDR'];
    			$mdl -> order_status_id = 1; //wait
    			$mdl -> order_date = new Expression('NOW()');
    		}
    		
    		if ($mdl->load(Yii::$app->request->post()) && $mdl->save()) {
    			
    			//order_detail
    			$pass = true;
    			foreach($cart as $product){
    				
    				$mdl4 = new OrderDetail(); //reset
    				$mdl4 -> product_id = $product['id'];
    				$mdl4 -> order_id = $mdl -> id;
    				$mdl4 -> price = $product['price'];
    				$mdl4 -> qty = $product['qty'];
    				
    				if(!$mdl4->save()){
    					$pass = false;
    				}
    			}
    			
    			if($pass){
    				Yii::$app->session->set('msg', 'Save Complete!');
    			}
    			
    			//Clear Cart
    			Yii::$app -> session -> remove('cart');
    			
    			return $this->redirect(['checkoutsuccess']);
    		}
    	}
    	
    	//Set Message
    	$msg = "";
    	if(Yii::$app->session->has('msg')){
    		$msg = HtmlHelper::getAlert(Yii::$app->session->get('msg'), 'danger');
    		Yii::$app->session->remove('msg');
    	}
    	
    	return $this->render('form', [
    			'mdl' => $mdl,
    			'data' => $data,
    			'icon' => 'glyphicon glyphicon-user',
    			'header' => Yii::t("app/{$controller}_{$action}", 'header'),
    			'header2' => Yii::t("app/{$controller}_{$action}", 'header2'),
    			'cart' => $cart,
    			'qty' => ['label' => Yii::t("app/button", 'qty')],
    			'cart_management' => ['label' => Yii::t("app/button", 'cart_management'), 'name'=>'cart_management'],
    			'confirm' => ['label' => Yii::t("app/button", 'confirm'), 'name'=>'confirm'],
    			'save' => ['label' => Yii::t("app/{$controller}_{$action}", 'save'), 'name' => 'save', 'class' => 'btn btn-success'],
    			'msg' => $msg,
    	]);
    }
    
    public function actionCheckoutsuccess()
    {
    	return $this->render('success');
    }
    
    public function actionDelete()
    {
    	$cart = Yii::$app -> session -> get('cart');
    	Yii::$app -> session -> remove('cart', $cart);
    	
    	return $this->redirect(['frontend/listproduct']);
    }

}
