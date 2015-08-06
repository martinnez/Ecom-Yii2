<?php

namespace app\controllers;

use Yii;
use app\models\HtmlHelper;
use app\models\ValidationHelper;
use yii\web\session;
use yii\helpers\StringHelper;

use app\models\AuthorizeHelper;
use app\models\Company;

class CompanyController extends \yii\web\Controller
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
    
    public function actionDetail()
    {
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	
    	$mdl = ($rs = Company::find() ->one()) !== null ? $rs:new Company();
    	
    	//Check if new record
    	$isNew = $mdl -> getIsNewRecord();
    	
    	if(isset($_POST['form'])){
    		return $this->redirect(['form', 'id'=>$_POST['form']]);
    	}
    	
    	//Set Message
    	$msg = "";
		if(Yii::$app->session->has('msg')){
			$msg = HtmlHelper::getAlert(Yii::$app->session->get('msg'), 'success');
			Yii::$app->session->remove('msg');
		}
    	
    	return $this -> render('detail', [
    			'mdl' => $mdl,
    			'detail_view' => [
    					'name' => ['label' => Yii::t("app/{$controller}_{$action}", 'name')],
    					'title' => ['label' => Yii::t("app/{$controller}_{$action}", 'title')],
    					'tel' =>	['label' => Yii::t("app/{$controller}_{$action}", 'tel')],
    					'email' => ['label' => Yii::t("app/{$controller}_{$action}", 'email')],
    					'fax' => ['label' => Yii::t("app/{$controller}_{$action}", 'fax')],
    					'website' => ['label' => Yii::t("app/{$controller}_{$action}", 'website')],
    					'facebook' => ['label' => Yii::t("app/{$controller}_{$action}", 'facebook')],
    					'line' => ['label' => Yii::t("app/{$controller}_{$action}", 'line')],
    					'address' => ['label' => Yii::t("app/{$controller}_{$action}", 'address')],
    					'tax_code' => ['label' => Yii::t("app/{$controller}_{$action}", 'tax_code')],
    					'logo' => ['label' => Yii::t("app/{$controller}_{$action}", 'logo')],
    					'payment' => ['label' => Yii::t("app/{$controller}_{$action}", 'payment')],
    					'about' => ['label' => Yii::t("app/{$controller}_{$action}", 'about')],
    					'help' => ['label' => Yii::t("app/{$controller}_{$action}", 'help')],
    			],
    			'icon' => 'glyphicon glyphicon-user',
    			'header' => Yii::t("app/{$controller}_{$action}", 'header'),
    			'form' => ['label' => $isNew ? Yii::t("app/button", 'create'):Yii::t("app/button", 'edit'), 'name'=>'form', 'class'=>$isNew ? 'btn btn-success':'btn btn-primary'],
    			'msg' => $msg,
    	]);
    }
	
	public function actionForm($id=null)
	{
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = isset($id) && ($rs = Company::findOne($id)) !== null ? $rs:new Company();
		
		//Backup image
		$temp = [];
		if(isset($mdl -> logo)){
			$temp['image'] = $mdl -> logo;
		}
		
		if($mdl->load(Yii::$app->request->post())) {
			
			if(!empty($_FILES['Company']['name']['logo'])  && ValidationHelper::isAllowFileType($_FILES['Company']['tmp_name']['logo'])){
				
				//Remove old img
				if(!empty($mdl -> getOldAttributes()['logo'])){
					unlink('../web/uploads/logos/'.$mdl -> getOldAttributes()['logo']);
				}
				
				//Upload new img
				$img = $_FILES['Company']['name']['logo'];
				$ext = end((explode(".", $img)));
					
				$name = microtime();
				$name = str_replace(' ', '', $name);
				$name = str_replace('.', '', $name);
					
				$name = $name.'.'.$ext;
				$tmp = $_FILES['Company']['tmp_name']['logo'];
				$mdl -> logo = $name;
					
				move_uploaded_file($tmp, '../web/uploads/logos/'.$name);
			}
				
			//Set Product_id if edit
			isset($id) ? $mdl -> id = $id:'';
			
			//Place back old image if there is no new image
			if(empty($_FILES['Company']['name']['logo']) && isset($temp['image'])){
				$mdl -> logo = $temp['image'];
			}
				
			if($mdl->save()){
				Yii::$app->session->set('msg', 'Save Complete!');
				return $this->redirect(['detail']);
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
				'save' => ['label' => Yii::t("app/{$controller}_{$action}", 'save'), 'name' => 'save', 'class' => 'btn btn-primary'],
				'msg' => $msg,
		]);
	}
	
	public function actionDelete($id)
	{
		if(isset($id) && ($rs = Company::findOne($id)) !== null){
			//Delete Image
			if(!empty($rs -> logo)){
				unlink('../web/uploads/logos/'.$rs -> logo);
			}
			$rs -> delete();
		}

        return $this->redirect(['list']);
	}

}
