<?php

namespace app\controllers;

use Yii;
use app\models\HtmlHelper;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\web\session;
use yii\helpers\StringHelper;

use app\models\AuthorizeHelper;

use app\models\StaffRule;
use app\models\StaffPermission;

class StaffruleController extends \yii\web\Controller
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
    	
    	$mdl = new StaffRule();
    	
    	$data = new ActiveDataProvider([
    			'query' => $mdl -> find(),
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
    			'icon' => 'glyphicon glyphicon-user',
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
		
		$mdl = new StaffRule();
		
		//Set Message
		$msg = "";
		if(Yii::$app->session->has('msg')){
			$msg = HtmlHelper::getAlert(Yii::$app->session->get('msg'), 'danger');
			Yii::$app->session->remove('msg');
		}
		
		if($mdl->load(Yii::$app->request->post()) && $mdl->save()){
			Yii::$app->session->set('msg', 'Save Complete!');
			return $this->redirect(['list']);
		}

		return $this->render('form', [
				'mdl' => $mdl,
				'icon' => 'glyphicon glyphicon-user',
				'header' => Yii::t("app/{$controller}_{$action}", 'header'),
				'save' => ['label' => Yii::t("app/button", 'save'), 'name' => 'save', 'class' => 'btn btn-success'],
				'back' => ['label' => Yii::t("app/button", 'back'), 'name' => 'back', 'class' => 'btn btn-danger'],
				'msg' => $msg,
		]);
		
	}
	
	public function actionEdit($id)
	{
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = isset($id) && ($rs = StaffRule::findOne($id)) !== null ? $rs:new StaffRule();
		
		//Set Message
		$msg = "";
		if(Yii::$app->session->has('msg')){
			$msg = HtmlHelper::getAlert(Yii::$app->session->get('msg'), 'danger');
			Yii::$app->session->remove('msg');
		}
		
		if($mdl->load(Yii::$app->request->post()) && $mdl->save()){
			Yii::$app->session->set('msg', 'Save Complete!');
			return $this->redirect(['list']);
		}
		
		return $this->render('form', [
				'mdl' => $mdl,
				'icon' => 'glyphicon glyphicon-user',
				'header' => Yii::t("app/{$controller}_{$action}", 'header'),
				'save' => ['label' => Yii::t("app/button", 'save'), 'name' => 'save', 'class' => 'btn btn-success'],
				'back' => ['label' => Yii::t("app/button", 'back'), 'name' => 'back', 'class' => 'btn btn-danger'],
				'msg' => $msg,
		]);
	}
	
	public function actionDelete($id)
	{
		if(isset($id) && ($rs = StaffRule::findOne($id)) !== null && ($rs2 = StaffPermission::findOne(['staff_rule_id'=>$id])) === null){
			$rs -> delete();
		}
		else{
			Yii::$app->session->set('msg', "Unable to deleted. Selected data inuse.");
		}

        return $this->redirect(['list']);
	}

}
