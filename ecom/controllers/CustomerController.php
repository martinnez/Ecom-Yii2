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

use app\models\Customer;
use app\models\CustomerStatus;

class CustomerController extends \yii\web\Controller
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
    	
    	$mdl = new Customer();
    	
    	$data = new ActiveDataProvider([
    			'query' => $mdl -> find() -> orderBy('id ASC'),
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
    			'edit' => ['label' => Yii::t("app/button", 'edit')],
    			'delete' => ['label' => Yii::t("app/button", 'delete'), 'confirm' => Yii::t("app/msg", 'confirm')],
    			'msg' => $msg,
    	]);
    }
   	
	public function actionCreate()
	{
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = new Customer();
		$mdl2 = new CustomerStatus();

		if ($mdl->load(Yii::$app->request->post()) && $mdl->save()){
				Yii::$app->session->set('msg', 'Save Complete!');
				return $this->redirect(['list']);
		}
		
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
				
				'customer_status_id_ddl' => ['items' => ArrayHelper::map($mdl2 -> find() -> All(), 'id', 'name')],
		]);
		
	}
	
	public function actionEdit($id)
	{
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = isset($id) && ($rs = Customer::findOne($id)) !== null ? $rs:new Customer();
		$mdl2 = new CustomerStatus();
		
		if ($mdl->load(Yii::$app->request->post()) && $mdl->save()){
				Yii::$app->session->set('msg', 'Save Complete!');
				return $this->redirect(['list']);
		}
		
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
				
				'customer_status_id_ddl' => ['items' => ArrayHelper::map($mdl2 -> find() -> All(), 'id', 'name')],
		]);
	}
	
	public function actionDelete($id)
	{
		isset($id) && ($rs = Customer::findOne($id)) !== null ? $rs -> delete():'';

        return $this->redirect(['list']);
	}

}
