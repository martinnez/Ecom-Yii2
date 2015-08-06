<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\session;

//Model that required
use app\models\Category;

class CategoryHtmlHelper
{
	public static function getChild($parent){
		
		//if no child is found
		if(($childs = Category::findAll(['category_id'=>$parent -> id, 'level'=>(int)($parent -> level) + 1])) === null){
			return "";
		}
		
		$html = "";
		foreach($childs as $child){
			
			//Create nest html
			$nest="";
			$i=0;
			while($i < (int)($parent -> level) + 1){
				$nest .= "&nbsp&nbsp&nbsp";
				$i++;
			}
			$nest .= "> ";
			
			$html .= "<div>".$nest."<a href=".Url::toRoute(['frontend/listproduct', 'search'=>null, 'category_id' => $child -> id]).">".$child -> name."</a></div>";
			
			//call same function
			$html .= CategoryHtmlHelper::getChild($child);
		}
		
		return $html;
	}
	
	public static function getNavChild($parent){

		//return if is the last child
		if(($childs = Category::findAll(['category_id'=>$parent -> id, 'level'=>(int)($parent -> level) + 1])) === null){
			return "";
		}
		
		$html = "";

		foreach($childs as $child){
			
			//Check if current child has child
			$hasChild = false;
			if(Category::findOne(['category_id'=>$child -> id, 'level'=>(int)($child  -> level) + 1]) !== null){
				$hasChild = true;
			}

			$html .= $hasChild ? 
			"<li class=\"dropdown dropdown-submenu\"><a href=".Url::toRoute(['frontend/listproduct', 'search'=>null, 'category_id' => $child -> id])." class=\"dropdown-toggle\" data-toggle=\"dropdown\">". $child -> name ."</a><ul class=\"dropdown-menu\">" 
					: "<li><a href=".Url::toRoute(['frontend/listproduct', 'search'=>null, 'category_id' => $child -> id]).">".$child -> name."</a>";
				
			//call same function
			$html .= CategoryHtmlHelper::getNavChild($child);
			
			$html .= $hasChild ? "</ul></li>" : "</li>";
		}
	
		return $html;
	}
}