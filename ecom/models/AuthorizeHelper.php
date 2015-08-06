<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\web\session;

//Model that required
use app\models\Staff;
use app\models\StaffRole;
use app\models\StaffPermission;
use app\models\StaffRule;

class AuthorizeHelper
{
	public static function hasRight($staff_role_id, $name){
		
		$pass = false;
		if(!isset($staff_role_id) || !isset($name)){
			return $pass;
		}
		
		//Allow all if is master role
		if(($r = StaffRole::findOne(['id'=>$staff_role_id])) !== null && $r -> name == "master"){
			$pass = true;
			return $pass;
		}
		
		//Get staff_rule id
		if(($r = StaffRule::findOne(['name'=>$name])) !== null && isset($r -> id)){
			$staff_rule_id = $r -> id;
		}
		else{
			return $pass;
		}
		
		//Get staff_permission auth
		if(($r = StaffPermission::findOne(['staff_role_id'=>$staff_role_id, 'staff_rule_id'=>$staff_rule_id])) !== null){
			$pass = isset($r -> staff_rule_id) && ($r2 = StaffRule::findOne(['id'=>$r -> staff_rule_id])) && $r2 !== null && $r -> auth == '1'? true:false;
		}
		
		return $pass;
	}
}