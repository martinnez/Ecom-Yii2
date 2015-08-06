<?php

namespace app\controllers;

use Yii;
use app\models\HtmlHelper;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\web\session;
use yii\helpers\StringHelper;

use app\models\AuthorizeHelper;

use app\models\Category;
use app\models\BrandCategory;
use app\models\CategoryProduct;

class CategoryController extends \yii\web\Controller
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
    	
    	$mdl = new Category();
    	
    	$data = new ActiveDataProvider([
    			'query' => $mdl -> find() -> orderBy('code,level,name ASC'),
    			'pagination' => [
    					'pageSize' => 20,
    			],
    	]);
    	
    	if(isset($_POST['create'])){
    		return $this->redirect(['create']);
    	}
    	elseif(isset($_POST['add'])){
    		return $this->redirect(['add', 'id'=>$_POST['add']]);
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
    			'add' => ['label' => Yii::t("app/button", 'add')],
    			'edit' => ['label' => Yii::t("app/button", 'edit')],
    			'delete' => ['label' => Yii::t("app/button", 'delete'), 'confirm' => Yii::t("app/msg", 'confirm')],
    			'msg' => $msg,
    	]);
    }
   	
	public function actionCreate()
	{
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = new Category();
		
		//Set default level if not set
		if(!isset($mdl -> level)) {
			$mdl -> level = 0;
			$mdl -> category_id = null;
		}

		if($mdl->load(Yii::$app->request->post()) && $mdl->save()) {
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
				'back' => ['label' => Yii::t("app/button", 'back'), 'name' => 'back', 'class' => 'btn btn-danger'],
				'msg' => $msg,
		]);
		
	}
	
	public function actionAdd($id)
	{
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = new Category();
		$mdl2 = new Category();
		
		if(isset($id)){
			$mdl2 = [];
			$cond = ['id'=>$id];
			while(($rs = Category::findOne($cond)) !== null){
				$mdl2[] = $rs;
				$cond = ['id'=>$rs -> category_id];
				//Set latest category_id
				!isset($mdl -> category_id) ? $mdl -> category_id = $rs->id:''; //data arrange from last to first
				//Set latest level
				!isset($mdl -> level) ? $mdl -> level = ((int)$rs->level) + 1:''; //data arrange from last to first
			}
		}
		//Set default level if not set
		!isset($mdl -> level) ? $mdl -> level = 0:'';
		
		if($mdl->load(Yii::$app->request->post()) && $mdl->save()) {
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
				'mdl2' => $mdl2,
				'detail_view' => [
						'label' => [
							'code'=>Yii::t("app/category", 'Code'), 
							'name'=>Yii::t("app/category", 'Name'), 
							'remark'=>Yii::t("app/category", 'Remark'),
							'level'=>Yii::t("app/category", 'Level'),
						]
				],
				'icon' => 'glyphicon glyphicon-plus',
				'header' => Yii::t("app/{$controller}_{$action}", 'header'),
				'save' => ['label' => Yii::t("app/button", 'save'), 'name' => 'save', 'class' => 'btn btn-primary'],
				'back' => ['label' => Yii::t("app/button", 'back'), 'name' => 'back', 'class' => 'btn btn-danger'],
				'msg' => $msg,
		]);
	
	}
	
	public function actionEdit($id)
	{
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = isset($id) && ($rs = Category::findOne($id)) !== null ? $rs:new Category();
		$mdl2 = new Category();
		
		if(isset($id)){
			$mdl2 = [];
			$cond = ['id'=>$id];
			$count = 0;
			while(($rs = Category::findOne($cond)) !== null){
				//ignore the last data
				if($count > 0){
					$mdl2[] = $rs;
				}
				$count++;
				$cond = ['id'=>$rs -> category_id];
				//Set latest category_id
				!isset($mdl -> category_id) ? $mdl -> category_id = $rs->id:''; //data arrange from last to first
				//Set latest level
				!isset($mdl -> level) ? $mdl -> level = ((int)$rs->level) + 1:''; //data arrange from last to first
			}
		}
		//Set default level if not set
		if(!isset($mdl -> level)) {
			$mdl -> level = 0;
			$mdl -> category_id = null;
		}
		//Set category_id to null if level is 0
		elseif(isset($mdl -> level) && $mdl -> level == 0){
			$mdl -> category_id = null;
		}
		
		if($mdl->load(Yii::$app->request->post()) && $mdl->save()) {
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
				'mdl2' => $mdl2,
				'detail_view' => [
						'label' => [
								'code'=>Yii::t("app/category", 'Code'),
								'name'=>Yii::t("app/category", 'Name'),
								'remark'=>Yii::t("app/category", 'Remark'),
								'level'=>Yii::t("app/category", 'Level'),
						]
				],
				'icon' => 'glyphicon glyphicon-edit',
				'header' => Yii::t("app/{$controller}_{$action}", 'header'),
				'save' => ['label' => Yii::t("app/button", 'save'), 'name' => 'save', 'class' => 'btn btn-primary'],
				'back' => ['label' => Yii::t("app/button", 'back'), 'name' => 'back', 'class' => 'btn btn-danger'],
				'msg' => $msg,
		]);
	}
	
	public function actionDelete($id)
	{
		if(isset($id) && ($rs = Category::findOne($id)) !== null && Category::findOne(['category_id'=>$rs->id, 'level'=>(((int)$rs->level)+1)]) === null
				&& BrandCategory::findOne(['category_id'=>$rs->id]) === null && CategoryProduct::findOne(['category_id'=>$rs->id]) === null){
			$rs -> delete();
		}
		else{
			Yii::$app->session->set('msg', "Unable to deleted. Selected data inuse.");
		}
		
        return $this->redirect(['list']);
	}

}
