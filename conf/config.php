<?php
//网站全局配置
$config['ver']										='1.0';
$config['host']									='http://soap.dbo.so/';//站点域名
$config['public_key']							='1qaz2wsx3edc';//公共加密密钥
$config['public_iv']								='1q2w3e4r';//公共加密向量


//手机端识别
$config['MOBILE_AGENTS'] = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");

//网站全局配置结束

//日志和错误调试配置
$config['DEBUG']								=true;								//是否开启调试模式，true开启，false关闭
$config['LOG_ON']								=true;								//是否开启出错信息保存到文件，true开启，false不开启
$config['LOG_PATH']								='./data/log/';					//出错信息存放的目录，出错信息以天为单位存放
$config['ERROR_URL']							='';							//出错信息重定向页面，为空采用默认的出错页面
$config['ERROR_HANDLE']							=false;

//应用配置
		//网址配置
$config['URL_REWRITE_ON']						=true;						//是否开启重写，true开启重写,false关闭重写
$config['URL_MODULE_DEPR']						='/';						//模块分隔符
$config['URL_ACTION_DEPR']						='-';						//操作分隔符
$config['URL_PARAM_DEPR']						='-';						//参数分隔符
$config['URL_HTML_SUFFIX']						='.html';					//伪静态后缀设置，，例如 .html 
		
		//模块配置
$config['MODULE_PATH']							='./module/';					//模块存放目录
$config['MODULE_SUFFIX']						='Mod.class.php';			//模块后缀
$config['MODULE_INIT']							='init.php';					//初始程序
$config['MODULE_DEFAULT']						='index';					//默认模块
$config['MODULE_EMPTY']							='empty';					//空模块		
		
		//操作配置
$config['ACTION_DEFAULT']						='index';					//默认操作
$config['ACTION_EMPTY']							='_empty';					//空操作

		//静态页面缓存
$config['HTML_CACHE_ON']						=false;						//是否开启静态页面缓存，true开启.false关闭
$config['HTML_CACHE_PATH']						='./cache/html_cache/';	//静态页面缓存目录
$config['HTML_CACHE_SUFFIX']					='.html';				//静态页面缓存后缀
$config['HTML_CACHE_RULE']['index']['index']	=1000;	//缓存时间,单位：秒


//数据库配置
$config['DB_TYPE']					='mysql';							//数据库类型
$config['DB_HOST']					='192.168.0.198';					//数据库主机
$config['DB_USER']					='dbo_so_db';							//数据库用户名
$config['DB_PWD']					='2err5y7yu';							//数据库密码
$config['DB_PORT']					=3306;							//数据库端口，mysql默认是3306
$config['DB_NAME']					='dbo_so_db';				//数据库名
$config['DB_CHARSET']				='utf8';						//数据库编码
$config['DB_PREFIX']				='';						//数据库前缀
$config['DB_PCONNECT']				=false;						//true表示使用永久连接，false表示不适用永久连接，一般不使用永久连接

$config['DB_CACHE_ON']				=false;						//是否开启数据库缓存，true开启，false不开启
$config['DB_CACHE_PATH']			='./cache/db_cache/';		//数据库查询内容缓存目录，地址相对于入口文件
$config['DB_CACHE_TIME']			=600;						//缓存时间,0不缓存，-1永久缓存
$config['DB_CACHE_CHECK']			=true;						//是否对缓存进行校验
$config['DB_CACHE_FILE']			='cachedata';				//缓存的数据文件名
$config['DB_CACHE_SIZE']			='15M';						//预设的缓存大小，最小为10M，最大为1G
$config['DB_CACHE_FLOCK']			=true;						//是否存在文件锁，设置为false，将模拟文件锁

//MongoDB配置
$config['MONGODB_HOST']				='192.168.0.199';			//MongoDB数据库主机
$config['MONGODB_PORT']				=27017;						//数据库端口
$config['MONGODB_USER']				='';	//数据库用户名
$config['MONGODB_PWD']				='';					//数据库密码
$config['MONGODB_NAME']				='';		//数据库表名
$config['MONGODB_TIMEOUT']			=30;						//连接超时
$config['MONGODB_AUTH']				=false;						//是否登录鉴权

//模板配置
$config['TPL_TEMPLATE_PATH']		='./templates/';			//模板目录
$config['TPL_TEMPLATE_SUFFIX']		='.html';					//模板后缀
$config['TPL_CACHE_ON']				=false;						//是否开启模板缓存，true开启,false不开启
$config['TPL_CACHE_PATH']			='./cache/tpl_cache/';		//模板缓存目录
$config['TPL_CACHE_SUFFIX']			='.php';					//模板缓存后缀,一般不需要修改

// smarty 配置
$config['SMARTY_DEBUGGING']         = false;              		//是否开启调试模式
$config['SMARTY_CACHING']           = FALSE;              		//是否开启缓存
$config['SMARTY_TEMPLATE_DIR']      = './templates/';      		//缓存时间
$config['SMARTY_CACHE_LIFETIME']    = 30;                 		//缓存时间
$config['SMARTY_COMPILE_DIR']       = './data/smarty/compile_dir'; //smarty模板编译文件存放的目录
$config['SMARTY_CACHE_DIR']         = './data/smarty/cache_dir';   //smarty模板缓存文件存放的目录
$config['SMARTY_LEFT_DELIMITER']    = '{';                         //左定界符
$config['SMARTY_RIGHT_DELIMITER']   = '}';                         //右定界符

//QRCode配置
$config['QRCODE_DIR'] = '/data/qrcode/';//生成图片文件地址

//多语言配置 
$config['LANG_DEFAULT']				='zh-cn';       	//默认语言
$config['LANG_PACK_PATH']			='./lang/';      	//语言包目录
$config['LANG_PACK_SUFFIX']			='.lang.php';    	//语言包后缀 
$config['LANG_PACK_COMMON']			='common';   		//公用语言包，默认会自动加载

//权限认证配置
$config['AUTH_LOGIN_URL']='/account/login.html';			//登录地址
$config['AUTH_LOGIN_NO']=null;							//不需要认证的模块和操作
$config['AUTH_SESSION_PREFIX']='auth_';					//认证session前缀
$config['AUTH_POWER_CACHE']=false;						//是否缓存权限信息，如果设置为false，每次都需要从数据库读取数据

$config['AUTH_NO_CHECK_MOD']=array('account','file','api','app','js');//不需要登录验证的模块



//SOAP服务端配置
$config['SOAP_SERVER_USER']='soap_server';				//SOAP客户端登录用户
$config['SOAP_SERVER_PWD']='soap_pwd';					//SOAP客户端登录密码
$config['SOAP_SERVER_IV']='12345678';							//SOAP数据加密向量
$config['SOAP_SERVER_CLIENTIP']=array('127.0.0.1','192.168.0.2','192.168.0.100','192.168.0.111');			//允许访问的客户端IP


//DBO配置
$config['DBO_PATH']='./include/ext/DBO/';
//文件服务器
$config['FILE_SERVER_UP']='http://file.dbowner.com:80/index.php?act=up';	//须带端口
$config['FILE_SERVER_GET']='http://file.dbowner.com:80/index.php?act=get&filecode=%s&w=%s';//使用 sprintf()替换参数


?>