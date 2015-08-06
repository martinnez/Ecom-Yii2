<?php

namespace app\controllers;

use Yii;
use app\models\HtmlHelper;
use app\models\ValidationHelper;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\web\session;
use yii\helpers\StringHelper;

use app\models\AuthorizeHelper;

use app\models\Product;
use app\models\ProductImage;

class ProductimageController extends \yii\web\Controller
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
    
    public function actionList($product_id)
    {
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	
    	$mdl = new ProductImage();
    	$mdl2 = isset($product_id) && ($rs = Product::findOne($product_id)) !== null ? $rs:new Product();
    	
    	$data = new ActiveDataProvider([
    			'query' => $mdl -> find() -> orderBy('id,name ASC') -> where(['product_id'=>$product_id]),
    			'pagination' => [
    					'pageSize' => 20,
    			],
    	]);
    	
    	if(isset($_POST['create'])){
    		return $this->redirect(['create', 'product_id'=>$_POST['create']]);
    	}
    	elseif(isset($_POST['delete'])){
    		return $this->redirect(['delete', 'product_id'=>$product_id, 'id'=>$_POST['delete']]);
    	}
    	
    	//Set Message
    	$msg = "";
		if(Yii::$app->session->has('msg')){
			$msg = HtmlHelper::getAlert(Yii::$app->session->get('msg'), 'success');
			Yii::$app->session->remove('msg');
		}

    	return $this -> render('list', [
    			'data' => $data,
    			'icon' => 'glyphicon glyphicon-picture',
    			'header' => Yii::t("app/{$controller}_{$action}", 'header'),
    			'create' => ['label' => Yii::t("app/button", 'create'), 'product_id' => $product_id],
    			'delete' => ['label' => Yii::t("app/button", 'delete'), 'confirm' => Yii::t("app/msg", 'confirm')],
    			'msg' => $msg,
    			
    			'mdl2' => $mdl2,
    			'image' => !empty($mdl2 -> img) ? HtmlHelper::getImage('/products/'.$mdl2 -> img, ['width' => '250px']) : HtmlHelper::getImage('/defaults/image.png', ['width' => '150px']),
    	]);
    }
   	
	public function actionCreate($product_id)
	{
		if(!isset($product_id)){
			return $this->redirect(['product/list']);
		}
		
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = new ProductImage();
    	$mdl2 = isset($product_id) && ($rs = Product::findOne($product_id)) !== null ? $rs:new Product();

		if($mdl->load(Yii::$app->request->post())) {
			
			if(!empty($_FILES['ProductImage']['name']['url'])  && ValidationHelper::isAllowFileType($_FILES['ProductImage']['tmp_name']['url'])){
				$img = $_FILES['ProductImage']['name']['url'];
				$ext = end((explode(".", $img)));
					
				$name = microtime();
				$name = str_replace(' ', '', $name);
				$name = str_replace('.', '', $name);
					
				$name = $name.'.'.$ext;
				$tmp = $_FILES['ProductImage']['tmp_name']['url'];
				$mdl -> url = $name;
					
				move_uploaded_file($tmp, '../web/uploads/products/'.$name);
			}
			
			//Set Product_id
			$mdl -> product_id = $product_id;
			
			if($mdl->save()){
				Yii::$app->session->set('msg', 'Save Complete!');
				return $this->redirect(['list', 'product_id' => $product_id]);
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
				'icon' => 'glyphicon glyphicon-picture',
				'header' => $mdl2 -> name,
				'save' => ['label' => Yii::t("app/{$controller}_{$action}", 'save'), 'name' => 'save', 'class' => 'btn btn-success'],
				'back' => ['label' => Yii::t("app/{$controller}_{$action}", 'back'), 'name' => 'back', 'class' => 'btn btn-danger'],
				'msg' => $msg,
		]);
		
	}
	
	public function actionDelete($product_id, $id)
	{
		if(!isset($product_id)){
			return $this->redirect(['product/list']);
		}
		
		if(isset($id) && ($rs = ProductImage::findOne($id)) !== null){
			//Delete Image
			if(!empty($rs -> url) && file_exists("../web/uploads/products/".$rs -> url)){
				unlink('../web/uploads/products/'.$rs -> url);
			}
			$rs -> delete();
		}

        return $this->redirect(['list', 'product_id' => $product_id]);
	}

}
