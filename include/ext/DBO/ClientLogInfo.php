<?php
/**
 * ClientLogInfo操作类
 * @author Cxty
 *
 */
class ClientLogInfo {
	public $model; // 数据库模型对象
	public $config; // 全局配置

	public function __construct($_model, $_config) {
		if (! isset ( $this->model )) {
			$this->model = $_model;
		}
		if (! isset ( $this->config )) {
			$this->config = $_config;
		}
	}
	
	/**
	 * 验证是否存在
	 *
	 */
	public function Exist($cIP,$URLID) {
		try {
			$condition = array ();
			$condition ['cIP'] = $cIP;
			$condition ['URLID'] = $URLID;
			return $this->model->table ( 'tbClientLogInfo', false )->field ( 'ClientLogID,
URLID,
cIP,
cData,
cAppendTime' )->where ( $condition )->find ();
		} catch ( Exception $e ) {
			return null;
		}
	}
	/**
	 * 取单个记录
	 *
	 * @param int $ClientLogID
	 */
	public function Get($ClientLogID) {
		try {
			$condition = array ();
			$condition ['ClientLogID'] = $ClientLogID;
			return $this->model->table ( 'tbClientLogInfo', false )->field ( 'ClientLogID,
URLID,
cIP,
cData,
cAppendTime
					' )->where ( $condition )->find ();
		} catch ( Exception $e ) {
			return null;
		}
	}
	/**
	 * 添加一条记录
	 *
	 */
	public function Insert($URLID,
$cIP,
$cData,
$cAppendTime) {
		try {
			$data = array ();
			$data ['URLID'] = $URLID;
			$data ['cIP'] = $cIP;
			$data ['cData'] = $cData;
			$data ['cAppendTime'] = $cAppendTime;
				
			return $this->model->table ( 'tbClientLogInfo', false )->data ( $data )->insert ();
		} catch ( Exception $e ) {
			return null;
		}
	}
	
	/**
	 * 更新记录
	 *
	 */
	public function Update($ClientLogID, $URLID,
$cIP,
$cData,
$cAppendTime) {
		try {
			$condition = array ();
			$data = array ();
			$condition ['ClientLogID'] = $ClientLogID;
				
			$data ['URLID'] = $URLID;
			$data ['cIP'] = $cIP;
			$data ['cData'] = $cData;
			$data ['cAppendTime'] = $cAppendTime;
			
			return $this->model->table ( 'tbClientLogInfo', false )->data ( $data )->where ( $condition )->update ();
		} catch ( Exception $e ) {
			return null;
		}
	}
	
	/**
	 * 删除记录
	 *
	 * @param string/array $condition
	 */
	public function Delete($condition) {
		try {
			return $this->model->table ( 'tbClientLogInfo', false )->where ( $condition )->delete ();
		} catch ( Exception $e ) {
			return null;
		}
	}
	
	/**
	 * 分页查询
	 * @param unknown $condition
	 * @param unknown $order
	 * @param unknown $pagesize
	 * @param unknown $page
	 * @return multitype:unknown |multitype:number NULL
	 */
	public function GetListForPage($condition, $order, $pagesize, $page) {
		try {
			$limit_start = ($page - 1) * $pagesize;
			$limit = $limit_start . ',' . $pagesize;
	
			// 获取行数
			$count = $this->model->table ( 'tbClientLogInfo', false )->field ( 'ClientLogID' )->where ( $condition )->count ();
			$list = $this->model->table ( 'tbClientLogInfo', false )->field ( 'ClientLogID,
				URLID,
				cIP,
				cData,
				cAppendTime
				' )->where ( $condition )->order ( $order )->limit ( $limit )->select ();
	
			return array (
					'count' => $count,
					'list' => $list
			);
		} catch ( Exception $e ) {
			return array (
					'count' => 0,
					'list' => null
			);
		}
	}
}
?>