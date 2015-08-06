<?php

namespace app\controllers;

use Yii;
use app\models\HtmlHelper;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\web\session;
use yii\helpers\Arrayhelper;
use yii\helpers\StringHelper;

use app\models\AuthorizeHelper;

use app\models\CategoryProduct;
use app\models\Product;
use app\models\Category;

class CategoryproductController extends \yii\web\Controller
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
    	
    	$mdl = new CategoryProduct();
    	
    	$data = new ActiveDataProvider([
    			'query' => $mdl -> find() -> orderBy('id DESC'),
    			'pagination' => [
    					'pageSize' => 20,
    			],
    	]);
    	
    	if(isset($_POST['create'])){
    		return $this->redirect(['create']);
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
		
		$mdl = new CategoryProduct();
		$mdl2 = new Product();
		$mdl3 = new Category();

		if($mdl->load(Yii::$app->request->post())){
			if($mdl -> findOne(['category_id'=>$mdl -> category_id, 'product_id'=>$mdl -> product_id]) === null && $mdl->save()){
				Yii::$app->session->set('msg', 'Save Complete!');
				return $this->redirect(['list']);
			}
			else{
				Yii::$app->session->set('msg', "Unable to create. Data already match.");
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
		if(($mdl3 = $mdl3 -> find() -> All()) !== null){
			foreach ($mdl3 as $obj){
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
				
				'product_id_ddl' => ['items' => ArrayHelper::map($mdl2 -> find() -> All(), 'id', 'name')],
				'category_id_ddl' => ['items' => $category_id_ddl],
		]);
		
	}
	
	public function actionEdit($id)
	{
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = isset($id) && ($rs = CategoryProduct::findOne($id)) !== null ? $rs:new CategoryProduct();
		$mdl2 = new Product();
		$mdl3 = new Category();
		
		if($mdl->load(Yii::$app->request->post())){
			if($mdl -> findOne(['category_id'=>$mdl -> category_id, 'product_id'=>$mdl -> product_id]) === null && $mdl->save()){
				Yii::$app->session->set('msg', 'Save Complete!');
				return $this->redirect(['list']);
			}
			else{
				Yii::$app->session->set('msg', "Unable to edit. Data already match.");
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
		if(($mdl3 = $mdl3 -> find() -> All()) !== null){
			foreach ($mdl3 as $obj){
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
				
				'product_id_ddl' => ['items' => ArrayHelper::map($mdl2 -> find() -> All(), 'id', 'name')],
				'category_id_ddl' => ['items' => $category_id_ddl],
		]);
	}
	
	public function actionDelete($id)
	{
		if(isset($id) && ($rs = CategoryProduct::findOne($id)) !== null){
			//Delete Image
			if(!empty($rs -> img)){
				unlink('../web/uploads/products/'.$rs -> img);
			}
			$rs -> delete();
		}

        return $this->redirect(['list']);
	}

}
