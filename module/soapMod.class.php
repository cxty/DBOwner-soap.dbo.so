<?php
/**
 * API
 * @author Cxty
 *
 */

class soapMod {
	public $model; //数据库模型对象
	public $mongo_model;
	public $config; //全局配置
	static $global; //静态变量，用来实现单例模式
	
	public function __construct() {
		//参数配置
		if(!isset(self::$global['config'])){
			global $config;
			self::$global['config']=$config;
		}
		$this->config=self::$global['config'];//配置
		
		//数据库模型初始化
		if (! isset ( self::$global ['model'] )) {
			self::$global ['model'] = new DBOModel ( $this->config ); //实例化数据库模型类
		}
		$this->model = self::$global ['model']; //数据库模型对象
		
		if (! isset ( self::$global ['mongo_model'] )) {
			self::$global ['mongo_model'] = new DBOMongo ( $this->config ); //实例化数据库模型类
		}
		$this->mongo_model = self::$global ['mongo_model']; //数据库模型对象
		
	}
	public function index() {
		
	}
	/**
	 * 后台管理
	 */
	public function manage()
	{
		ini_set("soap.wsdl_cache_enabled", "0");
		$server=new SoapServer('./service/ManageService.wsdl',array('soap_version' => SOAP_1_2));
		$server->setClass(ManageService);
		$server->handle();
	}
	
	
}
?>