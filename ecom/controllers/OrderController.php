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
use app\models\OrderStatus;
use app\models\Customer;
use app\models\OrderDetail;

class OrderController extends \yii\web\Controller
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
    	
    	$mdl = new Order();
    	
    	$data = new ActiveDataProvider([
    			'query' => $mdl -> find() -> orderBy('id ASC'),
    			'pagination' => [
    					'pageSize' => 20,
    			],
    	]);
    	
    	if(isset($_POST['create'])){
    		return $this->redirect(['create']);
    	}
    	elseif(isset($_POST['list'])){
    		return $this->redirect(['orderdetail/list', 'order_id'=>$_POST['list']]);
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
    			'list' => ['label' => Yii::t("app/button", 'list')],
    			'edit' => ['label' => Yii::t("app/button", 'edit')],
    			'delete' => ['label' => Yii::t("app/button", 'delete'), 'confirm' => Yii::t("app/msg", 'confirm')],
    			'msg' => $msg,
    	]);
    }
   	
	public function actionCreate()
	{
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = new Order();
		$mdl2 = new OrderStatus();
		$mdl3 = new Customer();
		
		//Set default ip address
		$mdl -> ip = $_SERVER['REMOTE_ADDR'];

		if ($mdl->load(Yii::$app->request->post()) && $mdl->save()) {
			Yii::$app->session->set('msg', 'Save Complete!');
			return $this->redirect(['list']);
		}
		
		//Set DropdownList Array
		$customer_id_ddl = [];
		if(($mdl3 = $mdl3 -> find() -> All()) !== null){
			foreach ($mdl3 as $obj){
				$str = $obj -> fname." ".$obj -> lname;
				$customer_id_ddl[$obj->id] = $str;
			}
		}
		asort($customer_id_ddl);
		
		//Set Message
		$msg = "";
		if(Yii::$app->session->has('msg')){
			$msg = HtmlHelper::getAlert(Yii::$app->session->get('msg'), 'danger');
			Yii::$app->session->remove('msg');
		}
		
		return $this->render('form', [
				'mdl' => $mdl,
				'icon' => 'glyphicon glyphicon-file',
				'header' => Yii::t("app/{$controller}_{$action}", 'header'),
				'save' => ['label' => Yii::t("app/button", 'save'), 'name' => 'save', 'class' => 'btn btn-success'],
				'msg' => $msg,
				
				'customer_id_ddl' => ['items' => $customer_id_ddl],
				'order_status_id_ddl' => ['items' => ArrayHelper::map($mdl2 -> find() -> All(), 'id', 'name')],
		]);
		
	}
	
	public function actionEdit($id)
	{
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = isset($id) && ($rs = Order::findOne($id)) !== null ? $rs:new Order();
		$mdl2 = new OrderStatus();
		$mdl3 = new Customer();
		
		if ($mdl->load(Yii::$app->request->post()) && $mdl->save()) {
			Yii::$app->session->set('msg', 'Save Complete!');
			return $this->redirect(['list']);
		}
		
		//Set DropdownList Array
		$customer_id_ddl = [];
		if(($mdl3 = $mdl3 -> find() -> All()) !== null){
			foreach ($mdl3 as $obj){
				$str = $obj -> fname." ".$obj -> lname;
				$customer_id_ddl[$obj->id] = $str;
			}
		}
		asort($customer_id_ddl);
		
		//Set Message
		$msg = "";
		if(Yii::$app->session->has('msg')){
			$msg = HtmlHelper::getAlert(Yii::$app->session->get('msg'), 'danger');
			Yii::$app->session->remove('msg');
		}
		
		return $this->render('form', [
				'mdl' => $mdl,
				'icon' => 'glyphicon glyphicon-edit',
				'header' => Yii::t("app/{$controller}_{$action}", 'header'),
				'save' => ['label' => Yii::t("app/button", 'save'), 'name' => 'save', 'class' => 'btn btn-primary'],
				'msg' => $msg,
				
				'customer_id_ddl' => ['items' => $customer_id_ddl],
				'order_status_id_ddl' => ['items' => ArrayHelper::map($mdl2 -> find() -> All(), 'id', 'name')],
		]);
	}
	
	public function actionDelete($id)
	{
		if(isset($id) && ($rs = Order::findOne($id)) !== null  && ($rs2 = OrderDetail::findOne(['order_id'=>$id])) === null){
			$rs -> delete();
		}
		else{
			Yii::$app->session->set('msg', "Unable to deleted. Selected data inuse.");
		}

        return $this->redirect(['list']);
	}

}
