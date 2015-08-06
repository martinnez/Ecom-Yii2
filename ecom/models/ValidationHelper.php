<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\web\session;

class ValidationHelper
{
	
	public static function isAllowFileType($file){
		$pass = false;
		$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
		$detectedType = exif_imagetype($file); //ex. $_FILES['fupload']['tmp_name']
		if(in_array($detectedType, $allowedTypes)){
			$pass = true;
		}
		return in_array($detectedType, $allowedTypes);
	}
}