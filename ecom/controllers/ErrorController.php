<?php

namespace app\controllers;

use Yii;
use yii\web\session;

class ErrorController extends \yii\web\Controller
{
	public $layout = "error";
	
	public function init()
	{
		parent::init();
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
