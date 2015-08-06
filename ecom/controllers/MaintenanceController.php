<?php

namespace app\controllers;

use Yii;
use yii\web\session;

class MaintenanceController extends \yii\web\Controller
{
	public $layout = "maintenance";
	
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
        return $this->redirect(Yii::getAlias('@maintenance'));
    }
    
	public function actionHome()
    {
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	
    	return $this -> render('home', [
    			'icon' => 'glyphicon glyphicon-user',
    			'' => '',
    	]);
    }
}
