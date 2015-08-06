<?php

namespace app\controllers;

use Yii;
use app\models\HtmlHelper;
use app\models\ValidationHelper;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\web\session;
use yii\helpers\Arrayhelper;
use yii\helpers\StringHelper;

use app\models\AuthorizeHelper;

use app\models\Product;
use app\models\ProductImage;
use app\models\Category;
use app\models\CategoryProduct;
use app\models\Brand;

class ProductController extends \yii\web\Controller
{
	public $layout = "backend";
	
	public function init()
	{
		parent::init();
		
		//Check Authorize 
		if(!Yii::$app->session->has('staff_role_id') || !AuthorizeHelper::hasRight(Yii::$app->session->get('staff_role_id'), StringHelper::basename(__CLASS__))){
			return $this->redirect(Yii::getAlias('@authorize'));
		}
		
		//Error Handling
		if (Yii::$app->errorHandler->exception !== null) {
			return $this->redirect(Yii::getAlias('@error'));
		}
	}
	
    public function actionIndex()
    {
        return $this->redirect(Yii::getAlias('@backend'));
    }
    
    public function actionList()
    {
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	
    	$mdl = new Product();
    	
    	$data = new ActiveDataProvider([
    			'query' => $mdl -> find() -> orderBy('id DESC'),
    			'pagination' => [
    					'pageSize' => 20,
    			],
    	]);
    	
    	if(isset($_POST['create'])){
    		return $this->redirect(['create']);
    	}
    	elseif(isset($_POST['image'])){
    		return $this->redirect(['productimage/list', 'product_id'=>$_POST['image']]);
    	}
    	elseif(isset($_POST['edit'])){
    		return $this->redirect(['edit', 'id'=>$_POST['edit']]);
    	}
    	elseif(isset($_POST['delete'])){
    		return $this->redirect(['delete', 'id'=>$_POST['delete']]);
    	}
    	
    	//Set Message
    	$msg = "";
		if(Yii::$app->session->has('msg')){
			$msg = HtmlHelper::getAlert(Yii::$app->session->get('msg'), 'success');
			Yii::$app->session->remove('msg');
		}
    	
    	return $this -> render('list', [
    			'data' => $data,
    			'icon' => 'glyphicon glyphicon-list',
    			'header' => Yii::t("app/{$controller}_{$action}", 'header'),
    			'create' => ['label' => Yii::t("app/button", 'create')],
    			'image' => ['label' => Yii::t("app/button", 'image')],
    			'edit' => ['label' => Yii::t("app/button", 'edit')],
    			'delete' => ['label' => Yii::t("app/button", 'delete'), 'confirm' => Yii::t("app/msg", 'confirm')],
    			'msg' => $msg,
    	]);
    }
   	
	public function actionCreate()
	{
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = new Product();
		$mdl2 = new Category();
		$mdl3 = new Brand();
		
		//Set Default qty
		$mdl -> qty = isset($mdl -> qty) ? $mdl -> qty:0 ;

		if($mdl->load(Yii::$app->request->post())) {
			
			if(!empty($_FILES['Product']['name']['img']) && ValidationHelper::isAllowFileType($_FILES['Product']['tmp_name']['img'])){
				$img = $_FILES['Product']['name']['img'];
				$ext = end((explode(".", $img)));
					
				$name = microtime();
				$name = str_replace(' ', '', $name);
				$name = str_replace('.', '', $name);
					
				$name = $name.'.'.$ext;
				$tmp = $_FILES['Product']['tmp_name']['img'];
				$mdl -> img = $name;
					
				move_uploaded_file($tmp, '../web/uploads/products/'.$name);
			}
			
			if($mdl->save()){
				Yii::$app->session->set('msg', 'Save Complete!');
				return $this->redirect(['list']);
			}
		}
		
		//Set Message
		$msg = "";
		if(Yii::$app->session->has('msg')){
			$msg = HtmlHelper::getAlert(Yii::$app->session->get('msg'), 'danger');
			Yii::$app->session->remove('msg');
		}
		
		//Set DropdownList Array
		$category_id_ddl = [];
		if(($mdl2 = $mdl2 -> find() -> All()) !== null){
			foreach ($mdl2 as $obj){
				$str = $obj -> name;
				if($obj -> level > 0){
					$cond = ['id'=>$obj -> category_id];
					while(($rs = Category::findOne($cond)) !== null){
						$str = $rs -> name." -> ".$str;
						$cond = ['id'=>$rs -> category_id];
					}
				}
				$category_id_ddl[$obj->id] = $str;
			}
		}
		asort($category_id_ddl);
		
		return $this->render('form', [
				'mdl' => $mdl,
				'icon' => 'glyphicon glyphicon-file',
				'header' => Yii::t("app/{$controller}_{$action}", 'header'),
				'save' => ['label' => Yii::t("app/button", 'save'), 'name' => 'save', 'class' => 'btn btn-success'],
				'back' => ['label' => Yii::t("app/button", 'back'), 'name' => 'back', 'class' => 'btn btn-danger'],
				'msg' => $msg,
				
				'category_id_ddl' => ['items' => $category_id_ddl],
				'brand_id_ddl' => ['items' => ArrayHelper::map($mdl3 -> find() -> All(), 'id', 'name')],
		]);
		
	}
	
	public function actionEdit($id)
	{
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = isset($id) && ($rs = Product::findOne($id)) !== null ? $rs:new Product();
		$mdl2 = new Category();
		$mdl3 = new Brand();
		
		//Set Default qty
		$mdl -> qty = isset($mdl -> qty) ? $mdl -> qty:0 ;
		
		//Backup image
		$temp = [];
		if(isset($mdl -> img)){
			$temp['image'] = $mdl -> img;
		}
		
		if($mdl->load(Yii::$app->request->post())) {
				
			if(!empty($_FILES['Product']['name']['img']) && ValidationHelper::isAllowFileType($_FILES['Product']['tmp_name']['img'])){
				
				//Remove old img
				if(!empty($mdl -> getOldAttributes()['img'])){
					unlink('../web/uploads/products/'.$mdl -> getOldAttributes()['img']);
				}
			
				//Upload new img
				$img = $_FILES['Product']['name']['img'];
				$ext = end((explode(".", $img)));
			
				$name = microtime();
				$name = str_replace(' ', '', $name);
				$name = str_replace('.', '', $name);
			
				$name = $name.'.'.$ext;
				$tmp = $_FILES['Product']['tmp_name']['img'];
				$mdl -> img = $name;
			
				move_uploaded_file($tmp, '../web/uploads/products/'.$name);
			}
			
			//Place back old image if there is no new image
			if(empty($_FILES['Product']['name']['img']) && isset($temp['image'])){
				$mdl -> img = $temp['image'];
			}
				
			if($mdl->save()){
				Yii::$app->session->set('msg', 'Save Complete!');
				return $this->redirect(['list']);
			}
		}
		
		//Set Message
		$msg = "";
		if(Yii::$app->session->has('msg')){
			$msg = HtmlHelper::getAlert(Yii::$app->session->get('msg'), 'danger');
			Yii::$app->session->remove('msg');
		}
		
		//Set DropdownList Array
		$category_id_ddl = [];
		if(($mdl2 = $mdl2 -> find() -> All()) !== null){
			foreach ($mdl2 as $obj){
				$str = $obj -> name;
				if($obj -> level > 0){
					$cond = ['id'=>$obj -> category_id];
					while(($rs = Category::findOne($cond)) !== null){
						$str = $rs -> name." -> ".$str;
						$cond = ['id'=>$rs -> category_id];
					}
				}
				$category_id_ddl[$obj->id] = $str;
			}
		}
		asort($category_id_ddl);
		
		return $this->render('form', [
				'mdl' => $mdl,
				'icon' => 'glyphicon glyphicon-edit',
				'header' => Yii::t("app/{$controller}_{$action}", 'header'),
				'save' => ['label' => Yii::t("app/button", 'save'), 'name' => 'save', 'class' => 'btn btn-success'],
				'back' => ['label' => Yii::t("app/button", 'back'), 'name' => 'back', 'class' => 'btn btn-danger'],
				'msg' => $msg,
				
				'category_id_ddl' => ['items' => $category_id_ddl],
				'brand_id_ddl' => ['items' => ArrayHelper::map($mdl3 -> find() -> All(), 'id', 'name')],
		]);
	}
	
	public function actionDelete($id)
	{
		if(isset($id) && ($rs = Product::findOne($id)) !== null && ProductImage::findOne(['product_id'=>$id]) === null
				&& CategoryProduct::findOne(['product_id'=>$rs->id]) === null){
			//Delete Image
			if(!empty($rs -> img) && file_exists("../web/uploads/products/".$rs -> img)){
				unlink('../web/uploads/products/'.$rs -> img);
			}
			$rs -> delete();
		}
		else{
			Yii::$app->session->set('msg', "Unable to deleted. Selected data inuse.");
		}

        return $this->redirect(['list']);
	}

}
