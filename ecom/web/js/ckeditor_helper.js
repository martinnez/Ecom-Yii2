/**
 *  Referrence : 
 *  http://stackoverflow.com/questions/3147670/ckeditor-update-textarea
 *  http://stackoverflow.com/questions/28059167/yii-2-ckeditor-not-passing-through-validation
 */

(function($){
	$(document).ready(function(){
		
		//To define textarea value before yii2 validation process.
		$('#w0').on('beforeValidate', function (event, messages, deferreds) {
			event.preventDefault(); 
			event.stopPropagation(); 
		    for(var instanceName in CKEDITOR.instances) { 
		    	CKEDITOR.instances[instanceName].updateElement();
		    }
		    return true;
		}
		
	});
})(jQuery);