<?php

namespace app\controllers;

use Yii;
use app\models\HtmlHelper;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\web\session;
use yii\helpers\StringHelper;

use app\models\AuthorizeHelper;
use app\models\Order;

class ReportController extends \yii\web\Controller
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
    
    public function actionListfinancial()
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
    	
		if(isset($_POST['list'])){
    		return $this->redirect(['orderdetail/list', 'order_id'=>$_POST['list']]);
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
    			'list' => ['label' => Yii::t("app/button", 'list')],
    			'msg' => $msg,
    	]);
    }
    
    public function actionListincome()
    {
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	 
    	$mdl = new Order();
   
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
    			'by_time' => ['label'=>Yii::t("app/button", 'by_time'), 'name'=>'by_time', 'class'=>'btn btn-default'],
    			'by_product' => ['label'=>Yii::t("app/button", 'by_product'), 'name'=>'by_product', 'class'=>'btn btn-default'],
    			'by_customer' => ['label'=>Yii::t("app/button", 'by_customer'), 'name'=>'by_customer', 'class'=>'btn btn-default'],
    			'by_brand' => ['label'=>Yii::t("app/button", 'by_brand'), 'name'=>'by_brand', 'class'=>'btn btn-default'],
    			'by_category' => ['label'=>Yii::t("app/button", 'by_category'), 'name'=>'by_category', 'class'=>'btn btn-default'],
    			'msg' => $msg,
    	]);
    }
}
