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

use app\models\Order;
use app\models\OrderDetail;
use app\models\Product;

class OrderdetailController extends \yii\web\Controller
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
    
    public function actionList($order_id)
    {
    	if(!isset($order_id)){
    		return $this->redirect(['order/list']);
    	}
    	
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	
    	$mdl = new OrderDetail();
    	$mdl2 = isset($order_id) && ($rs = Order::findOne($order_id)) !== null ? $rs:new Order();
    	
    	$data = new ActiveDataProvider([
    			'query' => $mdl -> find() -> orderBy('id ASC') -> where(['order_id'=>$order_id]),
    			'pagination' => [
    					'pageSize' => 20,
    			],
    	]);
    	
    	if(isset($_POST['create'])){
    		return $this->redirect(['create', 'order_id'=>$_POST['create']]);
    	}
    	elseif(isset($_POST['edit'])){
    		$unserial = unserialize($_POST['edit']);
    		return $this->redirect(['edit', 'order_id'=>$unserial['order_id'], 'id'=>$unserial['id']]);
    	}
    	elseif(isset($_POST['delete'])){
    		$unserial = unserialize($_POST['delete']);
    		return $this->redirect(['delete', 'order_id'=>$unserial['order_id'], 'id'=>$unserial['id']]);
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
    			'create' => ['label' => Yii::t("app/button", 'create'), 'order_id' => $order_id],
    			'edit' => ['label' => Yii::t("app/button", 'edit'), 'order_id' => $order_id],
    			'delete' => ['label' => Yii::t("app/button", 'delete'), 'order_id' => $order_id, 'confirm' => Yii::t("app/msg", 'confirm')],
    			'msg' => $msg,
    			 
    			'mdl2' => $mdl2,
    	]);
    }
   	
	public function actionCreate($order_id)
	{
		if(!isset($order_id)){
			return $this->redirect(['order/list']);
		}
		
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = new OrderDetail();
    	$mdl2 = isset($order_id) && ($rs = Order::findOne($order_id)) !== null ? $rs:new Order();
    	$mdl3 = new Product();

    	if(isset($mdl2)){
    		$mdl -> order_id = $mdl2 -> id;

    		if ($mdl->load(Yii::$app->request->post()) && $mdl->save()) {
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
		
		return $this->render('form', [
				'mdl' => $mdl,
				'icon' => 'glyphicon glyphicon-user',
				'header' => Yii::t("app/{$controller}_{$action}", 'header'),
				'save' => ['label' => Yii::t("app/button", 'save'), 'name' => 'save', 'class' => 'btn btn-success'],
				'msg' => $msg,
				
				'product_id_ddl' => ['items' => ArrayHelper::map($mdl3 -> find() -> All(), 'id', 'name')],
		]);
		
	}
	
	public function actionEdit($order_id, $id)
	{
		if(!isset($order_id)){
			return $this->redirect(['order/list']);
		}
		
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = isset($id) && ($rs = OrderDetail::findOne($id)) !== null ? $rs:new OrderDetail();
    	$mdl2 = isset($order_id) && ($rs = Order::findOne($order_id)) !== null ? $rs:new Order();
    	$mdl3 = new Product();
		
		if(isset($mdl2)){
    		$mdl -> order_id = $mdl2 -> id;

    		if ($mdl->load(Yii::$app->request->post()) && $mdl->save()) {
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
		
		return $this->render('form', [
				'mdl' => $mdl,
				'icon' => 'glyphicon glyphicon-user',
				'header' => Yii::t("app/{$controller}_{$action}", 'header'),
				'save' => ['label' => Yii::t("app/button", 'save'), 'name' => 'save', 'class' => 'btn btn-success'],
				'msg' => $msg,
				
				'product_id_ddl' => ['items' => ArrayHelper::map($mdl3 -> find() -> All(), 'id', 'name')],
		]);
	}
	
	public function actionDelete($order_id, $id)
	{
		if(!isset($order_id)){
			return $this->redirect(['order/list']);
		}
		
		isset($id) && ($rs = OrderDetail::findOne($id)) !== null ? $rs -> delete():'';

        return $this->redirect(['list', 'order_id'=>$order_id]);
	}

}
