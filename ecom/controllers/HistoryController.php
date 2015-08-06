<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\web\session;

use app\models\Product;
use app\models\Order;
use app\models\OrderDetail;

class HistoryController extends \yii\web\Controller
{
	public $layout = "frontend";
	
	public function init()
	{
		parent::init();
		
		if(!Yii::$app->session->has('customer_id') || !Yii::$app->session->has('customer_status') || Yii::$app->session->get('customer_status') === "suspend"){
			return $this->redirect(Yii::getAlias('@frontend'));
		}
		
		//Error Handling
		if (Yii::$app->errorHandler->exception !== null) {
			return $this->redirect(Yii::getAlias('@error'));
		}
	}
	
    public function actionIndex()
    {
        return $this->redirect(Yii::getAlias('@frontend'));
    }
    
    public function actionListorder()
    {
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	
    	$rs = Order::find() -> where(['customer_id'=>Yii::$app->session->get('customer_id')]) -> orderBy('id DESC') -> all();
    	
    	//create custom list data
    	$groups = [];
    	for($i=0;$i<count($rs);$i++){
    		//create order result
    		$groups[$i]['order'] = $rs[$i];
    		
    		//create orderdetail result
    		$groups[$i]['orderdetail'] = OrderDetail::find() -> where(['order_id'=>$rs[$i] -> id]) -> orderBy('id DESC') -> all();
    		
    	}

    
    	return $this->render('detailorder', [
    			'groups' => $groups,
    			'icon' => 'glyphicon glyphicon-user',
    			'header' => Yii::t("app/{$controller}_{$action}", 'header'),
    	]);
    }

}
