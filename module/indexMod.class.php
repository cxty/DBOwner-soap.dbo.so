<?php
class indexMod extends commonMod {
	
	public function __construct() {
		parent::__construct ();
		
		
	}
	public function index() {
		
		// 字符串
		$title = "";
		
		$this->assign ( 'title', $title );
		
		if($this->appinfoid>0){
			
			//parent::CheckUser ();
			
			//$this->display (DBO_TEMPLATES_PATH.'index/appindex.html');
		}else{
			
			$this->display (); // 输出默认模板
		}
		
		
	}
	
}
?>