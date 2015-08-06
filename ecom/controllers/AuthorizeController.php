<?php

namespace app\controllers;

use Yii;
use yii\web\session;

use app\models\AuthorizeHelper;

class AuthorizeController extends \yii\web\Controller
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
        return $this->redirect(Yii::getAlias('@error'));
    }
    
	public function actionHome()
    {
    	return $this -> render('home', [
    			'icon' => 'glyphicon glyphicon-user',
    	]);
    }

}
