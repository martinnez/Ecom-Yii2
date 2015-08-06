<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use app\models\HtmlHelper;
use app\models\CategoryHtmlHelper;
use yii\web\session;

use app\models\Brand;
use app\models\Product;
use app\models\ProductImage;
use app\models\Customer;
use app\models\CustomerStatus;
use app\models\OrderDetail;
use app\models\OrderStatus;
use app\models\Company;
use app\models\Category;
use app\models\CategoryProduct;

class FrontendController extends \yii\web\Controller
{
	public $layout = "frontend";
	
	public function init()
	{
		parent::init();
		
		//Error Handling
		if (Yii::$app->errorHandler->exception !== null) {
			return $this->redirect(Yii::getAlias('@error'));
		}
	}
	
    public function actionIndex()
    {
    	return $this->redirect(Yii::getAlias('@frontend'));
    }
    
    public function actionHome()
    {
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	
    	$mdl = new Product();
    	$mdl2 = new Category();
    	$mdl3 = new CategoryProduct();
    	
    	if(isset($_POST['add_cart'])){
    		return $this->redirect(['cart/add', 'id'=>$_POST['add_cart']]);
    	}

    	//Create New-Arrival Array
    	$new_arrival_arr = $mdl -> find() -> orderBy('id DESC') -> limit(6) -> all();
    	
    	//Create Brand Array
    	$brand_arr = Brand::find() -> all();
    	
    	//Get Product for each category 
    	$category_types = $mdl2 -> findAll(['level'=>0]);
    	
    	//Create Group Product array 
    	$product_arr = [];
    	$total_arr = [];
    	foreach($category_types as $category_type){
    		
    		//Create Product array 
    		if(($rs = $mdl3 -> find() -> where(['category_id'=>$category_type -> id]) -> limit(4) -> all()) !== []){
    			$arr = [];
    			foreach($rs as $r){
    				$arr[] = $mdl -> findOne(['id'=>$r -> product_id]);
    			}
    			$product_arr[$category_type -> name] = $arr;
    		}
    		
    	}	
    	
    	//Create Category Html
    	$category_menu = "";
    	$rs2 = $mdl2 -> find() -> all();
    	
    	for($i=0;$i<count($rs2);$i++){
    		
    		if(isset($rs2[$i] -> level) && (int)($rs2[$i] -> level) == 0){
    			$category_menu .= "<div><a href=".Url::toRoute(['frontend/listproduct', 'search'=>null, 'category_id' => $rs2[$i] -> id]).">".$rs2[$i] -> name."</a></div>";
    			
    			//Check if has and Recursive get Child
    			$category_menu .= CategoryHtmlHelper::getChild($rs2[$i]);
    			
    			if($i < count($rs2)-1){
    				$category_menu .= "<hr/>";
    			}
    		}
    		
    	}
    	
    	return $this->render('home', [
    			'brands' => ['header'=>Yii::t("app/{$controller}_{$action}", 'brand'), 'brand_arr' => $brand_arr],
    			'new_arrivals' => ['header'=>Yii::t("app/{$controller}_{$action}", 'new_arrival'), 'new_arrival_arr' => $new_arrival_arr],
    			'categories' => ['header'=>Yii::t("app/{$controller}_{$action}", 'categories'), 'product_arr' => $product_arr],
    			'category_menu' => ['header'=>Yii::t("app/{$controller}_{$action}", 'category_menu'), 'category_menu' => $category_menu],
    			'pagination' => isset($pagination) ? LinkPager::widget(['pagination' => $pagination]):'',
    			'sort' => '',
    			'icon' => 'glyphicon glyphicon-user',
    			'header' => Yii::t("app/{$controller}_{$action}", 'header'),
    			'add_cart' => ['label' => Yii::t("app/{$controller}_{$action}", 'add_cart'), 'name' => 'add_cart', 'class' => 'btn btn-block btn-borderless'],
    	]);
    }
    
    public function actionSearch()
    {
    	if(isset($_POST['btn_search']) && !empty($_POST['search'])){
    		return $this->redirect(['listproduct', 'search'=>$_POST['search']]);
    	}
    	return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionListproduct($search = null, $category_id = null, $brand_id = null)
    {
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	
    	$mdl = new Product();
    	$mdl2 = new Category();
    	$mdl3 = new CategoryProduct();
    	
    	if(isset($_POST['add_cart'])){
    		return $this->redirect(['cart/add', 'id'=>$_POST['add_cart']]);
    	}
    	
    	//Search
    	if(isset($search)){
    		
    		//Get Product lists
    		$query = $mdl -> find() -> where("name LIKE '%".$search."%'");
    		$total = $query -> count();
    		
    		$pagination = new Pagination(['totalCount'=>$total, 'pageSize'=>12]);
    		
    		$rs = $mdl -> find() -> where("name LIKE '%".$search."%' OR price LIKE '%".$search."%'") -> all();
    		
    		//Create Breadcrumbs
    		$breadcrumb = "Your search result.";
    	}
    	elseif(isset($category_id)){
    		
    		//Get Category Lists
    		$rs = $mdl3 -> find() -> where(['category_id'=>$category_id]) -> all();
    		
    		if($rs !== []){
    			
    			$cond = "";
    			for($i=0;$i<count($rs);$i++){
    				
    				
    				
    				$cond .= " id=".$rs[$i] -> product_id;
    				if($i < count($rs)-1){
    					$cond .= " OR ";
    				}
    			}
    			
    			//Get Product Lists
    			if(isset($cond)){
    				$query = $mdl -> find() -> where($cond);
    				$total = $query -> count();
    				$pagination = new Pagination(['totalCount'=>$total, 'pageSize'=>12]);
    				$rs = $query -> all();
    			}	

    			//Create Breadcrumbs Text ** by reverse data from child back to it's parent.
    			$breadcrumb_text = "";
    			$isLast = true;
    			$current_id = $category_id;
    			while(($r = Category::findOne(['id'=>$current_id])) !== null){
    				
    				if($isLast){
    					$breadcrumb_text = "<li class=\"active\">".$r -> name."</li>".$breadcrumb_text;
    					$isLast = false;
    				}
    				else{
    					$breadcrumb_text = "<li><a href=\"".Url::toRoute(['frontend/listproduct', 'search'=>null, 'category_id' => $r -> id]).">".$r -> name."\">".$r -> name."</a></li>".$breadcrumb_text;
    				}
    				
    				$current_id = $r -> category_id; //change to parent_id
    			}
    		}
    		
    		//Create Breadcrumbs
    		$breadcrumb = isset($breadcrumb_text) ? $breadcrumb_text : "No result is found.";
    		
    	}
    	elseif(isset($brand_id)){
    	
    		//Get Product Lists
    		$rs = $mdl -> find() -> where(['brand_id'=>$brand_id]);
    		$total = $rs -> count();
    		
    		$pagination = new Pagination(['totalCount'=>$total, 'pageSize'=>12]);
    		
    		$rs = $rs -> orderBy('id DESC') -> offset($pagination -> offset) -> limit($pagination -> limit) -> all();
    		
    		//Create Breadcrumbs
    		$breadcrumb = "All product of ".Brand::findOne(['id'=>$brand_id]) -> name;
    		
    	}
    	else{
    		
    		//Get Product Lists
    		$rs = $mdl -> find();
    		$total = $rs -> count();
    		
    		$pagination = new Pagination(['totalCount'=>$total, 'pageSize'=>12]);
    		
    		$rs = $rs -> orderBy('id DESC') -> offset($pagination -> offset) -> limit($pagination -> limit) -> all();
    		
    		//Create Breadcrumbs
    		$breadcrumb = "All product lists.";
    	}
    	
    	//Category
    	$rs2 = $mdl2 -> find() -> all();
    	
    	//Create Category Html
    	$category_menu = "";
    	for($i=0;$i<count($rs2);$i++){
    		if(isset($rs2[$i] -> level) && (int)($rs2[$i] -> level) == 0){
    			
    			//Check if parent has child
//     			$hasChild = false;
//     			if(Category::findOne(['category_id'=>$rs2[$i] -> id, 'level'=>(int)($rs2[$i]  -> level) + 1]) !== null){
//     				$hasChild = true;
//     			}
    			
//     			$caret = $hasChild ? "<span class=\"caret\"></span>" : "";
    			$caret = "<span class=\"caret\"></span>";
    			
    			$category_menu .= "
    					<li class=\"dropdown\">
    						<a href=".Url::toRoute(['frontend/listproduct', 'search'=>null, 'category_id' => $rs2[$i] -> id])." class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">".$rs2[$i] -> name." {$caret} </a>
    						";
//     			$category_menu .= $hasChild ? "<ul class=\"dropdown-menu\">" : "";
    			$category_menu .= "<ul class=\"dropdown-menu\">";
    			
    			//Create Display all category link
    			$category_menu .= "<li><a href=".Url::toRoute(['frontend/listproduct', 'search'=>null, 'category_id' => $rs2[$i] -> id])."><strong> All ".$rs2[$i] -> name."</strong></a></li>";
    			 
    			//Check if has and Recursive get Child
    			$category_menu .= CategoryHtmlHelper::getNavChild($rs2[$i]);
    			
//     			$category_menu .= $hasChild ? "</ul>" : "";
    			$category_menu .= "</ul>";
    			$category_menu .= "</li>";
    		}
    	}
    	
//     	//Create Category Html
//     	$category_menu = "";
//     	for($i=0;$i<count($rs2);$i++){
//     		if(isset($rs2[$i] -> level) && (int)($rs2[$i] -> level) == 0){
//     			$category_menu .= "<div><a href=".Url::toRoute(['frontend/listproduct', 'search'=>null, 'category_id' => $rs2[$i] -> id]).">".$rs2[$i] -> name."</a></div>";
    			
//     			//Check if has and Recursive get Child
//     			$category_menu .= CategoryHtmlHelper::getChild($rs2[$i]);
    			
//     			if($i < count($rs2)-1){
//     				$category_menu .= "<hr/>";
//     			}
//     		}
    		
//     	}
    	
    	return $this->render('listproduct', [
    			'rs' => $rs,
    			'breadcrumb' => $breadcrumb,
    			'category_menu' => $category_menu,
    			'pagination' => isset($pagination) ? LinkPager::widget(['pagination' => $pagination]):'',
    			'sort' => '',
    			'icon' => 'glyphicon glyphicon-user',
    			'header' => Yii::t("app/{$controller}_{$action}", 'header'),
    			'add_cart' => ['label' => Yii::t("app/{$controller}_{$action}", 'add_cart'), 'name' => 'add_cart', 'class' => 'btn btn-block btn-borderless'],
    	]);
    }
    
    public function actionDetailproduct($id)
    {
    	
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	
    	$r = isset($id) ? Product::findOne($id):new Product();
    	$rs2 = isset($id) ? ProductImage::findAll(['product_id'=>$id]):new ProductImage();
    	
    	if(isset($_POST['add_cart'])){
    		return $this->redirect(['cart/add', 'id'=>$_POST['add_cart']]);
    	}
    	
    	//Create categories html
    	$categories = "";
    	if(isset($id)){
    		$relations = CategoryProduct::findAll(['product_id'=>$id]);
    		for($i=0;$i<count($relations);$i++){
    			if(($category = Category::findOne(['id'=>$relations[$i] -> category_id])) !== null){
    				$categories .= "<a href=\"".Url::toRoute(['frontend/listproduct', 'search'=>null, 'category_id' => $category -> id])."\">".$category -> name."</a>";
    				if($i < count($relations)-1){
    					$categories .= ", ";
    				}
    			}
    		}
    	}
    	
    	return $this->render('detailproduct', [
    			'r' => $r,
    			'rs2' => $rs2,
    			'categories' => $categories,
    			'icon' => 'glyphicon glyphicon-user',
    			'header' => Yii::t("app/{$controller}_{$action}", 'header'),
    			'add_cart' => ['label' => Yii::t("app/{$controller}_{$action}", 'add_cart'), 'name' => 'add_cart', 'class' => 'btn btn-block btn-borderless'],
    	]);
    }
    
    public function actionRegister()
    {
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	
    	$mdl = new Customer();
    	$mdl2 = new CustomerStatus();
    	
    	//Set default Customer status
    	$mdl -> customer_status_id = 1; //normal
    	
    	$help_block = "";
    	$pass = true;
    	if(Yii::$app->request->post()){
    		if(empty($_POST['confirm_pwd'])){
    			$help_block = Yii::t("app/{$controller}_{$action}", 'must_enter');
    			$pass = false;
    		}
			elseif($mdl -> pwd != $_POST['confirm_pwd']){
				$help_block = Yii::t("app/{$controller}_{$action}", 'not_match');
				$pass = false;
			}
    	}
    	
    	if ($pass && $mdl->load(Yii::$app->request->post()) && $mdl->save()){
    		Yii::$app->session->set('msg', 'Save Complete!');
    		return $this->redirect(Yii::getAlias('@frontend'));
    	}
    	
    	//Set Message
    	$msg = "";
    	if(Yii::$app->session->has('msg')){
    		$msg = HtmlHelper::getAlert(Yii::$app->session->get('msg'), 'danger');
    		Yii::$app->session->remove('msg');
    	}
    	
    	return $this->render('register', [
    			'mdl' => $mdl,
    			'icon' => 'glyphicon glyphicon-user',
    			'header' => Yii::t("app/{$controller}_{$action}", 'header'),
    			'confirm_pwd' => ['label' => Yii::t("app/{$controller}_{$action}", 'confirm_pwd'), 'help_block'=>$help_block],
    			'save' => ['label' => Yii::t("app/{$controller}_{$action}", 'save'), 'name' => 'save', 'class' => 'btn btn-success'],
    			'msg' => $msg,
    	]);
    }
    
	public function actionChangeprofile($id)
	{
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = isset($id) && ($rs = Customer::findOne($id)) !== null ? $rs:new Customer();
		$mdl2 = new CustomerStatus();
		
		if ($mdl->load(Yii::$app->request->post()) && $mdl->save()) {
			Yii::$app->session->set('msg', 'Save Complete!');
			return $this->redirect(Yii::getAlias('@frontend'));
		}
		
		//Set Message
		$msg = "";
		if(Yii::$app->session->has('msg')){
			$msg = HtmlHelper::getAlert(Yii::$app->session->get('msg'), 'danger');
			Yii::$app->session->remove('msg');
		}
		
		return $this->render('changeprofile', [
				'mdl' => $mdl,
				'icon' => 'glyphicon glyphicon-user',
				'header' => Yii::t("app/{$controller}_{$action}", 'header'),
				'save' => ['label' => Yii::t("app/{$controller}_{$action}", 'save'), 'name' => 'save', 'class' => 'btn btn-success'],
				'msg' => $msg,
		]);
		
	}
	
	public function actionChangepassword()
	{
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = isset($id) && ($rs = Customer::findOne($id)) !== null ? $rs:new Customer();
		$mdl2 = new CustomerStatus();
		
		$help_block = "";
		$pass = true;
		if(Yii::$app->request->post()){
			if(empty($_POST['confirm_pwd'])){
				$help_block = Yii::t("app/{$controller}_{$action}", 'must_enter');
				$pass = false;
			}
			elseif($mdl -> pwd != $_POST['confirm_pwd']){
				$help_block = Yii::t("app/{$controller}_{$action}", 'not_match');
				$pass = false;
			}
		}
		
		if ($mdl->load(Yii::$app->request->post()) && $mdl->save()) {
			Yii::$app->session->set('msg', 'Save Complete!');
			return $this->redirect(Yii::getAlias('@frontend'));
		}
		
		//Set Message
		$msg = "";
		if(Yii::$app->session->has('msg')){
			$msg = HtmlHelper::getAlert(Yii::$app->session->get('msg'), 'danger');
			Yii::$app->session->remove('msg');
		}
		
		return $this->render('changepassword', [
				'mdl' => $mdl,
				'icon' => 'glyphicon glyphicon-user',
				'header' => Yii::t("app/{$controller}_{$action}", 'header'),
				'confirm_pwd' => ['label' => Yii::t("app/{$controller}_{$action}", 'confirm_pwd'), 'help_block'=>$help_block],
				'save' => ['label' => Yii::t("app/{$controller}_{$action}", 'save'), 'name' => 'save', 'class' => 'btn btn-success'],
				'msg' => $msg,
		]);
	}
    
    public function actionLogin()
    {
    	$mdl = new Customer();
    	 
    	if(!empty($_POST)){
    
    		//Check Login
    		if (!empty($_POST['customer'])) {
    			$mdl = $mdl -> findOne(['usr'=>$_POST['customer']['usr'], 'pwd'=>$_POST['customer']['pwd']]);
    			if(!empty($mdl)){
    				Yii::$app->session->set('customer_id', $mdl -> id);
    				Yii::$app->session->set('customer_usr', $_POST['customer']['usr']);
    				Yii::$app->session->set('customer_status', $mdl -> customerStatus['name']);
    				return $this->redirect(Yii::getAlias('@frontend'));
    			}
    			else{
    				return $this->redirect('loginfail');
    			}
    		}
    	}
    	 
    	return $this->redirect(Yii::getAlias('@frontend'));
    }
    
    public function actionLoginfail()
    {
    	return $this -> render('login_fail');
    }
    
    public function actionLogout()
    {
    	Yii::$app->session->remove('customer_id');
    	Yii::$app->session->remove('customer_usr');
    	Yii::$app->session->remove('customer_status');
    
    	//Check if has cart
    	if(Yii::$app->session->has('cart')){
    		Yii::$app->session->remove('cart');
    	}
    	
    	return $this->redirect(Yii::getAlias('@frontend'));
    }
    
    public function actionAbout()
    {
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	
    	$rs = Company::find() -> one();
    	
    	$content = "";
    	if(isset($rs) && isset($rs -> about)){
    		$content = $rs -> about;
    	}
    	else{
    		$content = "Your about infomation.";
    	}
    	
    	return $this->render('about', [
    			'content' => $content,
    			'icon' => 'glyphicon glyphicon-user',
    			'header' => Yii::t("app/{$controller}_{$action}", 'header'),
    	]);
    }
    
    public function actionPayment()
    {
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;

    	$rs = Company::find() -> one();
    	
    	$content = "";
    	if(isset($rs) && isset($rs -> payment)){
    		$content = $rs -> payment;
    	}
    	else{
    		$content = "Your payment infomation.";
    	}
    	
    	return $this->render('payment', [
    			'content' => $content,
    			'icon' => 'glyphicon glyphicon-user',
    			'header' => Yii::t("app/{$controller}_{$action}", 'header'),
    	]);
    }
    
    public function actionHelp()
    {
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	
    	$rs = Company::find() -> one();
    	
    	$content = "";
    	if(isset($rs) && isset($rs -> help)){
    		$content = $rs -> help;
    	}
    	else{
    		$content = "Your help infomation.";
    	}
    	
    	return $this->render('help', [
    			'content' => $content,
    			'icon' => 'glyphicon glyphicon-user',
    			'header' => Yii::t("app/{$controller}_{$action}", 'header'),
    	]);
    }
    
    public function actionContact()
    {
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	
    	$rs = Company::find() -> one();
    	
    	$content = "";
    	if(isset($rs) && isset($rs -> tel) && isset($rs -> email) && isset($rs -> fax) 
    			&& isset($rs -> website) && isset($rs -> facebook) && isset($rs -> line) 
    			&& isset($rs -> address) && isset($rs -> tax_code)){
    		$content = "
    				<div class=\"col-md-6\">
    					<div>&copy; ".$rs -> name." ".date('Y')."</div>
        				<div class=\"col-md-6\">Tel : </div><div class=\"col-md-6\">".$rs -> tel." </div>
        				<div class=\"col-md-6\">Fax : </div><div class=\"col-md-6\">".$rs -> fax." </div>
        				<div class=\"col-md-6\">Website : </div><div class=\"col-md-6\">".$rs -> website." </div>
        				<div class=\"col-md-6\">Email : </div><div class=\"col-md-6\">".$rs -> email." </div>
        				<div class=\"col-md-6\">Tax Code : </div><div class=\"col-md-6\">".$rs -> tax_code." </div>
        				<div class=\"col-md-6\">Address : </div><div class=\"col-md-6\">".$rs -> address." </div>
        			</div>
    				";
    	}
    	else{
    		$content = "Your contact infomation.";
    	}
    	
    	return $this->render('contact', [
    			'content' => $content,
    			'icon' => 'glyphicon glyphicon-user',
    			'header' => Yii::t("app/{$controller}_{$action}", 'header'),
    	]);
    }

}
