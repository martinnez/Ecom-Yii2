<?php

namespace app\controllers;

use Yii;
use app\models\HtmlHelper;
use yii\web\session;

use app\models\Staff;
use app\models\StaffRole;
use app\models\Anouncement;

class BackendController extends \yii\web\Controller
{
	public $layout = "backend";
	
	public function init()
	{
		parent::init();
		
		//Error Handling
		if (Yii::$app->errorHandler->exception !== null) {
			return $this->redirect(Yii::getAlias('@error'));
		}
	}
	
    public function actionIndex()
    {
    	return $this->redirect(Yii::getAlias('@backend'));
    }
    
    public function actionHome()
    {
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	
    	$rs = Anouncement::find() -> orderBy('create_date DESC') -> all();
    	
    	return $this -> render('home', [
    			'rs' => $rs,
    			'header' => Yii::t("app/{$controller}_{$action}", 'header'),
    			'user' => Yii::$app->session->has('staff_usr') ? Yii::$app->session->get('staff_usr') : '',
    	]);
    }
    
    public function actionLogin()
    {
    	$mdl = new Staff();
    	
    	if(!empty($_POST)){
    		
    		//Check Login
    		if (!empty($_POST['staff'])) {
    			$mdl = $mdl -> findOne(['usr'=>$_POST['staff']['usr'], 'pwd'=>$_POST['staff']['pwd']]);
    			if(!empty($mdl)){
    				Yii::$app->session->set('staff_id', $mdl -> id);
    				Yii::$app->session->set('staff_usr', $_POST['staff']['usr']);
    				Yii::$app->session->set('staff_role_id', $mdl -> staffRole['id']);
    				Yii::$app->session->set('staff_role', $mdl -> staffRole['name']);
    				return $this->redirect(['home']);
    			}
    			else{
    				return $this->redirect(['loginfail']);
    			}
			}
    	}
    	
    	return $this->redirect(Yii::getAlias('@backend'));
    }
    
    public function actionLoginfail()
    {
    	return $this -> render('login_fail');
    }
    
    public function actionLogout()
    {
    	Yii::$app->session->remove('staff_id');
    	Yii::$app->session->remove('staff_usr');
    	Yii::$app->session->remove('staff_role_id');
    	Yii::$app->session->remove('staff_role');
 
    	return $this->redirect(Yii::getAlias('@backend'));
    }
    
    public function actionChangeprofile($id)
	{
		$controller = Yii::$app->controller->id;
		$action = Yii::$app->controller->action->id;
		
		$mdl = isset($id) && ($rs = Staff::findOne($id)) !== null ? $rs:new Staff();
		$mdl2 = new StaffRole();
		
		if ($mdl->load(Yii::$app->request->post()) && $mdl->save()) {
			return $this->redirect(Yii::getAlias('@backend'));
		}
		
		//Set Message
		$msg = "";
		if(Yii::$app->session->has('msg')){
			$msg = HtmlHelper::getAlert(Yii::$app->session->get('msg'), 'danger');
			Yii::$app->session->remove('msg');
		}
		
		return $this->render('changeprofile', [
				'mdl' => $mdl,
				'icon' => 'glyphicon glyphicon-user',
				'header' => Yii::t("app/{$controller}_{$action}", 'header'),
				'save' => ['label' => Yii::t("app/{$controller}_{$action}", 'save'), 'name' => 'save', 'class' => 'btn btn-primary'],
				'msg' => $msg,
		]);
	}
    
    public function actionLanguage()
    {
    	Yii::$app->session->set('lang', isset($lang) ? $lang : 'th');
    	$this->redirect(Yii::$app->request->referrer);
    }

}
