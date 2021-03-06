<?php
/**
 * 后台管理操作类,负责实现SOAP远程管理平台调用
 * @author Cxty
 *
 */
class ManageService extends MasterService {
	public $model; // 数据库模型对象
	public $mongo_model;
	public $config; // 全局配置
	static $global; // 静态变量，用来实现单例模式
	
	public function __construct($config = null) {
		// 参数配置
		if (! isset ( self::$global ['config'] )) {
			global $config;
			self::$global ['config'] = $config;
		}
		$this->config = self::$global ['config']; // 配置
		                                          
		// 数据库模型初始化
		if (! isset ( self::$global ['model'] )) {
			self::$global ['model'] = new DBOModel ( $this->config ); // 实例化数据库模型类
		}
		$this->model = self::$global ['model']; // 数据库模型对象
		
		if (! isset ( self::$global ['mongo_model'] )) {
			self::$global ['mongo_model'] = new DBOMongo ( $this->config ); // 实例化数据库模型类
		}
		$this->mongo_model = self::$global ['mongo_model']; // 数据库模型对象
		
		parent::__construct ( $this->config );
	
	}
	
	// 系统日志 开始
	/**
	 * 取指定一条系统日志
	 *
	 * @param unknown_type $d        	
	 */
	public function GetSysLog($d) {
		if ($this->authorized) {
			$re = null;
			if (isset ( $d )) {
				
				$data = $this->_value ( json_decode ( $d->data )->data );
				
				$SysLog = $this->RequireClass ( 'SysLogInfo', $this->model, $this->config );
				
				if (isset ( $SysLog )) {
					$re = $SysLog->Get ( $this->_addslashes ( $data->SysLogID ) );
				}
				$this->AddSysLog ( 0, "SysLogInfo", json_encode ( $data ), json_encode ( $re ) );
				return $this->_return ( true, 'OK', $re );
			} else {
				
				return $this->_return ( false, 'Data Error', $re );
			}
		
		} else {
			return $this->Unauthorized_User;
		}
	}
	
	/**
	 * 获取系统日志列表
	 *
	 * @param array $d        	
	 * @throws SoapFault
	 */
	public function GetSysLogList($d) {
		
		if ($this->authorized) {
			$re = null;
			if (isset ( $d )) {
				
				$data = $this->_value ( json_decode ( $d->data )->data );
				
				$SysLog = $this->RequireClass ( 'SysLogInfo', $this->model, $this->config );
				
				if (isset ( $SysLog )) {
					$re = $SysLog->GetListForPage ( $data->condition, $this->_addslashes ( $data->order ), $this->_addslashes ( $data->pagesize ), $this->_addslashes ( $data->page ) );
				}
				
				$this->AddSysLog ( 0, "SysLogInfo", json_encode ( $data ), json_encode ( $re ) );
				return $this->_return ( true, 'OK', $re );
			} else {
				
				return $this->_return ( false, 'Data Error', $re );
			}
		
		} else {
			return $this->Unauthorized_User;
		}
	}
	/**
	 * 删除系统日志
	 *
	 * @param array $d        	
	 * @throws SoapFault
	 */
	public function DelSysLog($d) {
		if ($this->authorized) {
			$re = null;
			if (isset ( $d )) {
				
				$data = $this->_value ( json_decode ( $d->data )->data );
				
				$SysLog = $this->RequireClass ( 'SysLogInfo', $this->model, $this->config );
				
				if (isset ( $SysLog )) {
					$re = $SysLog->Delete ( $data->condition );
				}
				
				$this->AddSysLog ( 2, "SysLogInfo", json_encode ( $data ), json_encode ( $re ) );
				return $this->_return ( true, 'OK', $re );
			} else {
				
				return $this->_return ( false, 'Data Error', $re );
			}
		
		} else {
			return $this->Unauthorized_User;
		}
	}
	// 系统日志结束
	
	
	// 客户端请求记录开始
	/**
	 * 取指定一条系统日志
	 *
	 * @param unknown_type $d
	 */
	public function GetClientLog($d) {
		if ($this->authorized) {
			$re = null;
			if (isset ( $d )) {
	
				$data = $this->_value ( json_decode ( $d->data )->data );
	
				$ClientLog = $this->RequireClass ( 'ClientLogInfo', $this->model, $this->config );
	
				if (isset ( $ClientLog )) {
					$re = $ClientLog->Get ( $this->_addslashes ( $data->ClientLogID ) );
				}
				$this->AddSysLog ( 0, "ClientLogInfo", json_encode ( $data ), json_encode ( $re ) );
				return $this->_return ( true, 'OK', $re );
			} else {
	
				return $this->_return ( false, 'Data Error', $re );
			}
	
		} else {
			return $this->Unauthorized_User;
		}
	}
	
	/**
	 * 获取系统日志列表
	 *
	 * @param array $d
	 * @throws SoapFault
	 */
	public function GetClientLogList($d) {
	
		if ($this->authorized) {
			$re = null;
			if (isset ( $d )) {
	
				$data = $this->_value ( json_decode ( $d->data )->data );
	
				$ClientLog = $this->RequireClass ( 'ClientLogInfo', $this->model, $this->config );
	
				if (isset ( $ClientLog )) {
					$re = $ClientLog->GetListForPage ( $data->condition, $this->_addslashes ( $data->order ), $this->_addslashes ( $data->pagesize ), $this->_addslashes ( $data->page ) );
				}
	
				$this->AddSysLog ( 0, "ClientLogInfo", json_encode ( $data ), json_encode ( $re ) );
				return $this->_return ( true, 'OK', $re );
			} else {
	
				return $this->_return ( false, 'Data Error', $re );
			}
	
		} else {
			return $this->Unauthorized_User;
		}
	}
	/**
	 * 删除系统日志
	 *
	 * @param array $d
	 * @throws SoapFault
	 */
	public function DelClientLog($d) {
		if ($this->authorized) {
			$re = null;
			if (isset ( $d )) {
	
				$data = $this->_value ( json_decode ( $d->data )->data );
	
				$ClientLog = $this->RequireClass ( 'ClientLogInfo', $this->model, $this->config );
	
				if (isset ( $ClientLog )) {
					$re = $ClientLog->Delete ( $data->condition );
				}
	
				$this->AddSysLog ( 2, "ClientLogInfo", json_encode ( $data ), json_encode ( $re ) );
				return $this->_return ( true, 'OK', $re );
			} else {
	
				return $this->_return ( false, 'Data Error', $re );
			}
	
		} else {
			return $this->Unauthorized_User;
		}
	}
	// 客户端请求记录结束
	
	// URL资源开始
	/**
	 * 取指定一条系统日志
	 *
	 * @param unknown_type $d
	 */
	public function GetURL($d) {
		if ($this->authorized) {
			$re = null;
			if (isset ( $d )) {
	
				
				
				$data = $this->_value ( json_decode ( $d->data )->data );
	
				$URL = $this->RequireClass ( 'URLInfo', $this->model, $this->config );
	
				if (isset ( $URL )) {
					$re = $URL->Get ( $this->_addslashes ( $data->URLID ) );
				}
				$this->AddSysLog ( 0, "URLInfo", json_encode ( $data ), json_encode ( $re ) );
				return $this->_return ( true, 'OK', $re );
			} else {
	
				return $this->_return ( false, 'Data Error', $re );
			}
	
		} else {
			return $this->Unauthorized_User;
		}
	}
	
	/**
	 * 获取系统日志列表
	 *
	 * @param array $d
	 * @throws SoapFault
	 */
	public function GetURLList($d) {
	
		
		if ($this->authorized) {
			$re = null;
			if (isset ( $d )) {
	
				//return $this->_return ( true, 'OK', $d );
				
				$data = $this->_value ( json_decode ( $d->data )->data );
	
				$URL = $this->RequireClass ( 'URLInfo', $this->model, $this->config );
	
				if (isset ( $URL )) {
					$re = $URL->GetListForPage ( $data->condition, $this->_addslashes ( $data->order ), $this->_addslashes ( $data->pagesize ), $this->_addslashes ( $data->page ) );
				}
				
				
	
				$this->AddSysLog ( 0, "URLInfo", json_encode ( $data ), json_encode ( $re ) );
				return $this->_return ( true, 'OK', $re );
			} else {
	
				return $this->_return ( false, 'Data Error', $re );
			}
	
		} else {
			return $this->Unauthorized_User;
		}
	}
	/**
	 * 删除系统日志
	 *
	 * @param array $d
	 * @throws SoapFault
	 */
	public function DelURL($d) {
		if ($this->authorized) {
			$re = null;
			if (isset ( $d )) {
	
				$data = $this->_value ( json_decode ( $d->data )->data );
	
				$URL = $this->RequireClass ( 'URLInfo', $this->model, $this->config );
	
				if (isset ( $URL )) {
					$re = $URL->Delete ( $data->condition );
				}
	
				$this->AddSysLog ( 2, "URLInfo", json_encode ( $data ), json_encode ( $re ) );
				return $this->_return ( true, 'OK', $re );
			} else {
	
				return $this->_return ( false, 'Data Error', $re );
			}
	
		} else {
			return $this->Unauthorized_User;
		}
	}
	// URL资源结束
}

?>