<?php
// 公共模块
class commonMod {
	public $model; // 数据库模型对象
	public $mongo_model; // Mongo数据库模型对象
	public $tpl; // 模板对象
	public $config; // 全局配置
	static $global; // 静态变量，用来实现单例模式
	public $user; // 当前用户对象
	public $user_access_token;
	public $user_refresh_token;
	public $IsSign = FALSE; // 是否登录
	public $auth = NULL; // auth操作类
	public $fun = null; // 公共函数
	public $_Lang = null; // 语言包
	
	//单应用独立域名访问时
	public $appid = '';
	public $appinfoid = 0;
	
	public function __construct() {
		
		session_start (); // 开启session
		// 参数配置
		if (! isset ( self::$global ['config'] )) {
			global $config;
			self::$global ['config'] = $config;
		}
		$this->config = self::$global ['config']; // 配置
		
		//$this->auth = new DBORestApiService ( $this->config );
		$this->fun = new Fun ( $this->config );
		
		// 数据库模型初始化
		if (! isset ( self::$global ['model'] )) {
			self::$global ['model'] = new DBOModel ( $this->config ); // 实例化数据库模型类
		}
		$this->model = self::$global ['model']; // 数据库模型对象
		
		if (! isset ( self::$global ['mongo_model'] )) {
			self::$global ['mongo_model'] = new DBOMongo ( $this->config ); // 实例化数据库模型类
		}
		$this->mongo_model = self::$global ['mongo_model']; // 数据库模型对象
		                                                    
		// 模板初始化
		if (! isset ( self::$global ['tpl'] )) {
			// self::$global ['tpl'] = new DBOTemplate ( $this->config );
			// //实例化模板类
			
			// 加载并实例化smarty类
			if (! (file_exists ( $this->config ['SMARTY_TEMPLATE_DIR'] ) && is_dir ( $this->config ['SMARTY_TEMPLATE_DIR'] ))) {
				mkdir ( $this->config ['SMARTY_TEMPLATE_DIR'], 0755, true );
			}
			if (! (file_exists ( $this->config ['SMARTY_COMPILE_DIR'] ) && is_dir ( $this->config ['SMARTY_COMPILE_DIR'] ))) {
				mkdir ( $this->config ['SMARTY_COMPILE_DIR'], 0755, true );
			}
			if (! (file_exists ( $this->config ['SMARTY_CACHE_DIR'] ) && is_dir ( $this->config ['SMARTY_CACHE_DIR'] ))) {
				mkdir ( $this->config ['SMARTY_CACHE_DIR'], 0755, true );
			}
			require_once (DBO_PATH . 'ext/smarty/Smarty.class.php');
			$smarty = new Smarty ();
			$smarty->debugging = $this->config ['SMARTY_DEBUGGING'];
			$smarty->caching = $this->config ['SMARTY_CACHING'];
			$smarty->cache_lifetime = $this->config ['SMARTY_CACHE_LIFETIME'];
			$smarty->template_dir = $this->config ['SMARTY_TEMPLATE_DIR'];
			$smarty->compile_dir = $this->config ['SMARTY_COMPILE_DIR'];
			$smarty->cache_dir = $this->config ['SMARTY_CACHE_DIR'];
			$smarty->left_delimiter = $this->config ['SMARTY_LEFT_DELIMITER'];
			$smarty->right_delimiter = $this->config ['SMARTY_RIGHT_DELIMITER'];
			
			self::$global ['tpl'] = $smarty;
		}
		$this->tpl = self::$global ['tpl']; // 模板类对象
		                                    
		// 初始化语言包
		Lang::init ();
		$this->_Lang = Lang::getPack ();
		$this->assign ( 'Lang', $this->_Lang ); // 页面调用语言包数组{$Lang.参数}
		$this->assign ( 'ThisLang', __LANG__ ); // 当前语言
		
		$this->assign ( 'pop_lang', json_encode ( $this->_Lang ['POP_txt'] ) ); // pop语言包
		$this->assign('UserInfoMenu', json_encode ( $this->_Lang ['UserInfoMenu']));//用户信息菜单语言包
		$this->assign ( 'upfiletool_lang', json_encode ( $this->_Lang ['UPFileTool_txt'] ) ); // 上传组件语言包
		$this->assign ( 'file_server', $this->config ['FILE_SERVER_GET'] ); // 文件服务器地址
		$this->assign('js_tooltxt', json_encode ( $this->_Lang ['ToolTxt'] ));//
		
		//判断域名是否为应用域名,并获取应用信息
		$this->appid =strtolower(substr($_SERVER['HTTP_HOST'],0,strpos($_SERVER['HTTP_HOST'],'.')));
		$reg = '/app(\d+)/';
		preg_match_all($reg, $this->appid, $matches);
		if($matches){
			$this->appinfoid = $matches[1][0];
		}

		//应用域名
		if($this->appinfoid>0){
			//跳转到应用主页
			
			if(! in_array ( DBOApp::$module, $this->config ['AUTH_NO_CHECK_MOD'] )){
				$this->redirect ($this->config ['host'].'app/index-'.$this->appinfoid.'.html');
			}
		}else	{
			// 非account,file,api模块,必须重新登录
			if(! in_array ( DBOApp::$module, $this->config ['AUTH_NO_CHECK_MOD'] )){
				//$this->IsSign = self::CheckUser ();
				
				//$this->assign ( 'sign', ($this->IsSign ? 'true' : 'false') );
				
				//$this->assign ( 'user', json_encode ( $this->user ) );
				
				//if(! $this->IsSign){
				//	$this->redirect ( $this->config ['AUTH_LOGIN_URL'] . '?Re=' . urlencode ( $this->fun->GetThisURL () ) );
				//}
			}
		}
	}
	/**
	 * 加载数据处理类,并返回类对象
	 *
	 * @param string $ClassName
	 * @param
	 *        	$model
	 * @param
	 *        	$config
	 * @return Object new ClassName
	 */
	public function RequireClass($ClassName, $model, $config, $model_mongo = null) {
		return DBOApp::RequireClass ( $ClassName, $model, $config, $model_mongo );
	}
	/**
	 * 验证是否登录
	 */
	public function CheckUser() {

		$UserKeyID = 0; // 用户授权信息ID
		$UserID = ''; // 用户识别码
		$UserAccessToKen = ''; // Passport授权后的会话令牌
		
		$UserKeyID = ( int ) $this->fun->GetSessionValue ( 'UserKeyID' );
		$UserID = ( string ) $this->fun->GetSessionValue ( 'user_id' );
		$access_token = ( string ) $this->fun->GetSessionValue ( 'access_token' );
		
		
		if ($UserKeyID > 0 && $UserID) {
			$_db = $this->RequireClass ( 'UserKeyInfo', $this->model, $this->config );
			
			if (isset ( $_db )) {
				
				$re = $_db->GetUserInfoForUserKeyIDAndUserID ( $UserKeyID, $UserID );
				
				if ($re) {
					
					$this->user = $this->auth->GetUserInfo ( $access_token, $re ['uKey'], $re ['UserID'] ); // 通过OAuth向Passport获取用户信息
					//var_dump($this->user);
					//exit;
					if ($this->user) {
						if($this->user['error']){
							$this->IsSign = false;
						}else{
							$this->user_access_token = $access_token;
							$this->user_refresh_token = $re ['uKey'];
							// 获取成功,更新Session
							// 取Dev上开发用户信息
							$_d = array (
									'IsDev' => false 
							);
							$_db = $this->RequireClass ( 'UserWorkSpaceInfo', $this->model, $this->config );
							if (isset ( $_db )) {
								$re = null;
								$re = $_db->GetByUserKeyID ( $UserKeyID );
								if ($re) {
									$_d = array (
											'IsDev' => true,
											'WorkSpace' => array (
													'Name' => $re ['uwsName'],
													'Info' => $re ['uwsInfo'],
													'ICO' => $re ['uwsIco'],
													'AppendTime' => $re ['uwsAppendTime'],
													'State' => $re ['uwsState'] 
											) 
									);
								}
							}
							
							$this->user = array (
									'UserKeyID' => $UserKeyID,
									'u' => $this->user,
									'd' => $_d 
							);
							$this->IsSign = true;
						}
					} else {
						$this->IsSign = false;
					}
				} else {
					$this->IsSign = false;
				}
			} else {
				$this->IsSign = false;
			}
		} else {
			$this->IsSign = false;
		}
		
		$this->assign ( 'sign', ($this->IsSign ? 'true' : 'false') );
		
		$this->assign ( 'user', json_encode ( $this->user ) );
		
		return $this->IsSign;
	}
	/**
	 * 取当前用户Token
	 */
	public  function GetUserToken(){
		if($this->CheckUser() == true){
			return array(
					"user_accesstoken" => $this->user_access_token,
					"user_refresh_token" => $this->user_refresh_token
					);
		}else{
			return null;
		}
	}
	/**
	 * 转换UserID为指定应用的UserID以及令牌
	 */
	public function GetUserTokenByAuth($UserID,$AppInfoID,$user_accesstoken,$user_refresh_token){
		$_rCode = md5($AppInfoID.$UserID);
		$_app_user_id = $this->fun->GetSessionValue('app_user_id'.$_rCode);
		$_app_refresh_token= $this->fun->GetSessionValue('app_refresh_token'.$_rCode);
		$_app_access_token = $this->fun->GetSessionValue('app_access_token'.$_rCode);
		$_app_user = array();
		
		if($_app_user_id){
			$_app_user = array(
					'user_id'=>$_app_user_id,
					'refresh_token'=>$_app_refresh_token,
					'access_token'=>$_app_access_token
			);
		}else{
			$AppSet = $this->RequireClass ( 'AppSetInfo', $this->model, $this->config );
			$AppSetInfo = $AppSet->Exist ( $AppInfoID );
			
			$_app_user = $this->auth->Register_user($UserID,$AppSetInfo['AppID'],$user_accesstoken,$user_refresh_token);
			
			$this->fun->SetSessionValue('app_user_id'.$_rCode,$_app_user['user_id']);
			$this->fun->SetSessionValue('app_refresh_token'.$_rCode,$_app_user['refresh_token']);
			$this->fun->SetSessionValue('app_access_token'.$_rCode,$_app_user['access_token']);
		}

		return $_app_user;
	}
	/**
	 * 登出
	 */
	public function ClearUser() {
		$UserKeyID = 0; // 用户授权信息ID
		$UserID = ''; // 用户识别码
		$UserAccessToKen = ''; // Passport授权后的会话令牌
		
		$UserKeyID = ( int ) $this->fun->GetSessionValue ( 'UserKeyID' );
		$UserID = ( string ) $this->fun->GetSessionValue ( 'user_id' );
		$access_token = ( string ) $this->fun->GetSessionValue ( 'access_token' );
		if ($UserKeyID > 0 && $UserID) {
			$_db = $this->RequireClass ( 'UserKeyInfo', $this->model, $this->config );
				
			if (isset ( $_db )) {
				$re = $_db->GetUserInfoForUserKeyIDAndUserID ( $UserKeyID, $UserID );
		
				if ($re) {
					$this->auth->SignOut($access_token,$re ['uKey']);
				}
			}
		}
		$this->fun->ClearSession();
	}
	/**
	 * 应用收费类型
	 */
	public function GetAppFeeType() {
		$_AppFeeType = $this->config ['AppFeeType'];
		$_AppFeeTypeLang = $this->_Lang ['AppFeeType'];
		
		foreach ( $_AppFeeType as $_key => $_val ) {
			array_push ( $_AppFeeType [$_key], array (
					'txt' => $_AppFeeTypeLang [$_key] 
			) );
		}
		return $_AppFeeType;
	}
	/**
	 * 应用数据对象类型
	 */
	public function GetAppDataObjType() {
		$_AppDataObjType = $this->config ['AppDataObjType'];
		$_AppDataObjTypeLang = $this->_Lang ['AppDataObjType'];
	
		foreach ( $_AppDataObjType as $_key => $_val ) {
			array_push ( $_AppDataObjType [$_key], array (
			'txt' => $_AppDataObjTypeLang [$_key]
			) );
		}
		return $_AppDataObjType;
	}
	/**
	 * 应用数据对象类型开放权限
	 */
	public function GetAppDataObjTypePermissions(){
		$_AppDataObjObjPermissions = $this->config ['AppDataObjObjPermissions'];
		$_AppDataObjObjPermissionsLang = $this->_Lang ['AppDataObjObjPermissions'];
		
		foreach ( $_AppDataObjObjPermissions as $_key => $_val ) {
			array_push ( $_AppDataObjObjPermissions [$_key], array (
			'txt' => $_AppDataObjObjPermissionsLang [$_key]
			) );
		}
		return $_AppDataObjObjPermissions;
	}
	// 模板变量解析
	protected function assign($name, $value) {
		return $this->tpl->assign ( $name, $value );
	
	}
	// 模板输出
	protected function display($tpl = '') {
		// return $this->tpl->display ( $tpl );
		// 在模板中使用定义的常量,使用方式如{$__ROOT__} {$__APP__}
		$this->assign ( "__ROOT__", __ROOT__ );
		$this->assign ( "__APP__", __APP__ );
		$this->assign ( "__URL__", __URL__ );
		$this->assign ( "__PUBLIC__", __PUBLIC__ );
		
		// 实现不加参数时，自动加载相应的模板
		$tpl = empty ( $tpl ) ? $_GET ['_module'] . '/' . $_GET ['_action'] . $this->config ['TPL_TEMPLATE_SUFFIX'] : $tpl;
		return $this->tpl->display ( $tpl );
	}
	
	// 直接跳转
	protected function redirect($url) {
		header ( 'location:' . $url, false, 301 );
		exit ();
	}
	
	// 出错之后跳转，后退到前一页
	protected function error($msg) {
		header ( "Content-type: text/html; charset=utf-8" );
		$msg = "alert('$msg');";
		echo "<script>$msg history.go(-1);</script>";
		exit ();
	}
	
	/*
	 * 功能:分页 $url，基准网址，若为空，将会自动获取，不建议设置为空 $total，信息总条数 $perpage，每页显示行数
	 * $pagebarnum，分页栏每页显示的页数 $mode，显示风格，参数可为整数1，2，3，4任意一个
	 */
	protected function page($url, $total, $perpage = 10, $pagebarnum = 5, $mode = 1) {
		$page = new page ();
		return $page->show ( $url, $total, $perpage, $pagebarnum, $mode );
	}
	
	/**
	 * addslashes() 别名函数,加强对数组类型(array)的数据处理
	 * 该函数并添加了对MSSQL 的转义字符异常的支持,但前提是SQL 的分界符为’ 即单引号
	 *
	 * @param
	 *        	string | array $string
	 * @param boolean $force
	 *        	是否强制转换转义字符
	 * @return string | array
	 */
	public function _addslashes($string, $force = 0) {
		global $db_type;
		if (! get_magic_quotes_gpc () || $force) {
			if (is_array ( $string )) {
				foreach ( $string as $key => $val ) {
					$string [$key] = $this->_addslashes ( $val, $force );
				}
			} else {
				$string = addslashes ( $string );
			}
		}
		return $string;
	}
	/**
	 * 取传来的参数值,防SQL注入
	 *
	 * @param unknown_type $key        	
	 * @param int $len        	
	 * @param unknown_type $def        	
	 */
	public function GetString($key, $len = 0, $def = null) {
		$_val = $_GET [$key] ? $_GET [$key] : $_POST [$key];
		if ($_val) {
			$_val = $this->_addslashes ( $_val );
			if ($len > 0) {
				return substr ( $_val, 0, $len );
			} else {
				return $_val;
			}
		} else if ($def) {
			return $def;
		} else {
			return null;
		}
	}
	public function GetStringNOaddslashes($key, $len = 0, $def = null) {
		$_val = $_GET [$key] ? $_GET [$key] : $_POST [$key];
		if ($_val) {
			if ($len > 0) {
				return substr ( $_val, 0, $len );
			} else {
				return $_val;
			}
		} else if ($def) {
			return $def;
		} else {
			return null;
		}
	}
	/**
	 * 向页面输出指定格式的字符串
	 *
	 * @param unknown_type $str        	
	 */
	public function Write($state = false, $msg = '', $data = array(), $format = 'json',$jsonp=null) {
		$format = $format?$format:'json';
		$_re = array (
				'state' => $state,
				'msg' => $msg,
				'data' => $data,
				'time'=>time() 
		);
		switch ($format) {
			case 'json' :
				if($jsonp){
					echo $jsonp.'('.json_encode ( $_re ).')';
				}else{
					echo json_encode ( $_re );
				}
				break;
			case 'xml' :
				echo XML::Array2XML ( $_re );

				break;
		}
		exit;
	}
	/**
	 * 转换Ico文件代码为URL
	 * @param unknown_type $IcoFileCode
	 */
	public function IcoFileCode2Url($IcoFileCode,$Size){
		$re = '';
		if($IcoFileCode){
			$CodeArray = explode (',', $IcoFileCode);
			for($i=0;$i<count($CodeArray);$i++){
				$CodeArray[$i]=explode ('|', $CodeArray[$i]);
				if($CodeArray[$i][1]==$Size){
					$re = sprintf($this->config['FILE_SERVER_GET'],$CodeArray[$i][0],$CodeArray[$i][1]) ;
				}
			}
		}
		return $re;
	}
	/**
	 * 返回浏览器类型,判断手机类型
	 */
	public function is_mobile() {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$mobile_agents = $this->config['MOBILE_AGENTS'];
		$is_mobile = false;
		foreach ($mobile_agents as $device) {
			if (stristr($user_agent, $device)) {
				$is_mobile = true;
				break;
			}
		}
		return $is_mobile;
	}
}
?>