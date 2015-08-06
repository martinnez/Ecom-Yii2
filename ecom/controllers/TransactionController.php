<?php

namespace app\controllers;

use Yii;
use app\models\HtmlHelper;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\web\session;
use yii\helpers\StringHelper;
use yii\db\Expression;

use app\models\AuthorizeHelper;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderStatus;

class TransactionController extends \yii\web\Controller
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
    
    public function actionListorder()
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
    	
    	if(isset($_POST['detail_order'])){
    		return $this->redirect(['detailorder', 'id'=>$_POST['detail_order']]);
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
    			'header2' => Yii::t("app/{$controller}_{$action}", 'header2'),
    			'detail_order' => ['label' => Yii::t("app/button", 'detail_order'), 'name' => 'detail_order'],
    			'msg' => $msg,
    	]);
    }
   	
	public function actionDetailorder($id)
	{
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = isset($id) && ($rs = Order::findOne($id)) !== null ? $rs:new Order();
		$mdl2 = new OrderDetail();
		
		$data = new ActiveDataProvider([
				'query' => $mdl2 -> find() -> where(['order_id'=>$id]) -> orderBy('id ASC'),
				'pagination' => [
						'pageSize' => 20,
				],
		]);
		
		if(!empty($_POST) && isset($mdl)){
			
			$has_status = false;
			if(isset($_POST['back'])){
				return $this->redirect(['listorder']);
			}
			elseif(isset($_POST['paid']) && ($rs = OrderStatus::findOne(['name'=>'paid'])) !== null){
				if(!isset($mdl -> paid_date)){
					$mdl -> order_status_id = $rs -> id; //paid
					$mdl -> paid_date = new Expression('NOW()');
					$has_status = true;
				}
				else{
					Yii::$app->session->set('msg', 'Already Paid!');
				}
			}
			elseif(isset($_POST['send']) && ($rs = OrderStatus::findOne(['name'=>'send'])) !== null){
				if(!isset($mdl -> send_date)){
					$mdl -> order_status_id = $rs -> id; //send
					$mdl -> send_date = new Expression('NOW()');
					$has_status = true;
				}
				else{
					Yii::$app->session->set('msg', 'Already Send!');
				}
			}
			elseif(isset($_POST['cancel']) && ($rs = OrderStatus::findOne(['name'=>'cancel'])) !== null){
				if(!isset($mdl -> cancel_date) && !isset($mdl -> send_date)){
					$mdl -> order_status_id = $rs -> id; //cancel
					$mdl -> cancel_date = new Expression('NOW()');
					$has_status = true;
				}
				else{
					$str = isset($mdl -> cancel_date) ? 'Already Cancel!':'Already Send!';
					Yii::$app->session->set('msg', $str);
				}
			}
			
			if ($has_status && $mdl->save()) {
				Yii::$app->session->set('msg', 'Save Complete!');
				return $this->redirect(['listorder']);
			}
		}

		//Set Message
		$msg = "";
		if(Yii::$app->session->has('msg')){
			$msg = HtmlHelper::getAlert(Yii::$app->session->get('msg'), 'danger');
			Yii::$app->session->remove('msg');
		}
		
		return $this->render('detail', [
				'data' => $data,
				'icon' => 'glyphicon glyphicon-list',
				'header' => Yii::t("app/{$controller}_{$action}", 'header'),
    			'header2' => Yii::t("app/{$controller}_{$action}", 'header2'),
    			'back' => ['label' => Yii::t("app/button", 'back'), 'name' => 'back', 'class' => 'btn btn-success'],
    			'paid' => ['label' => Yii::t("app/button", 'paid'), 'name' => 'paid', 'class' => 'btn btn-success'],
    			'send' => ['label' => Yii::t("app/button", 'send'), 'name' => 'send', 'class' => 'btn btn-primary'],
				'cancel' => ['label' => Yii::t("app/button", 'cancel'), 'name' => 'cancel', 'class' => 'btn btn-danger'],
				'msg' => $msg,
		]);
		
	}

}
