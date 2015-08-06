<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\web\session;

class HtmlHelper
{
		
	public static function getAlert($content, $type)
	{
		$session = new session();
		$session -> open();
		
		$html = "";
		if(isset($content)){
			if($type == "success"){
				$html = HtmlHelper::alertSuccess($content);
			}
			elseif($type == "danger"){
				$html = HtmlHelper::alertDanger($content);
			}
		}
		return $html;
	}
	
	private static function alertSuccess($content){
		$html = "<div class=\"alert alert-success\">";
		$html .= "<i class=\"glyphicon glyphicon-floppy-ok\"></i>";
		$html .= $content;
		$html .= "</div>";
		return $html;
	}
	
	private static function alertDanger($content){
		$html = "<div class=\"alert alert-danger\">";
		$html .= "<i class=\"glyphicon glyphicon-floppy-ok\"></i>";
		$html .= $content;
		$html .= "</div>";
		return $html;
	}
	
	public static function getImage($name, $options=[]){
		$option = "";
		if(isset($options) && is_array($options)){
			foreach($options as $attr => $value){				
				$option .= " {$attr}=\"{$value}\" ";
			}
		}
		$html = "<div><a href=\"#\" class=\"thumbnail\">";
		$html .= "<img src=\"".Yii::$app->request->baseUrl."/uploads/{$name}\" {$option} >";
		$html .= "</a></div>";
		return $html;
	}
}