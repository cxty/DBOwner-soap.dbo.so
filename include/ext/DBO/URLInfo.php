<?php
/**
 * URLInfo操作类
 * @author Cxty
 *
 */
class URLInfo {
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
	 * @param unknown_type $uCode
	 */
	public function Exist($uCode) {
		try {
			$condition = array ();
			$condition ['uCode'] = $uCode;
			return $this->model->table ( 'tbURLInfo', false )->field ( 'URLID,
					uCode,
					URLStr,
					uAppendTime,
					uUpdateTime,
					uCount,
					uState
					' )->where ( $condition )->find ();
		} catch ( Exception $e ) {
			return null;
		}
	}
	/**
	 * 取单个记录
	 *
	 * @param int $URLID
	 */
	public function Get($URLID) {
		try {
			$condition = array ();
			$condition ['URLID'] = $URLID;
			return $this->model->table ( 'tbURLInfo', false )->field ( 'URLID,
					uCode,
					URLStr,
					uAppendTime,
					uUpdateTime,
					uCount,
					uState
					' )->where ( $condition )->find ();
		} catch ( Exception $e ) {
			return null;
		}
	}
	/**
	 * 添加一条记录
	 *
	 */
	public function Insert($uCode,
					$URLStr,
					$uAppendTime,
					$uCount,
					$uState) {
		try {
			$data = array ();
			$data ['uCode'] = $uCode;
			$data ['URLStr'] = $URLStr;
			$data ['uAppendTime'] = $uAppendTime;
			$data ['uState'] = $uState;
			$data ['uUpdateTime'] = $uAppendTime;
				
			return $this->model->table ( 'tbURLInfo', false )->data ( $data )->insert ();
		} catch ( Exception $e ) {
			return null;
		}
	}
	
	/**
	 * 更新记录
	 *
	 */
	public function Update($URLID, $uCode,
					$URLStr,
					$uAppendTime,
					$uCount,
					$uState) {
		try {
			$condition = array ();
			$data = array ();
			$condition ['URLID'] = $URLID;
				
			$data ['uCode'] = $uCode;
			$data ['URLStr'] = $URLStr;
			$data ['uAppendTime'] = $uAppendTime;
			$data ['uState'] = $uState;
			$data ['uCount'] = $uCount;
			$data ['uUpdateTime'] = time();
			
			return $this->model->table ( 'tbURLInfo', false )->data ( $data )->where ( $condition )->update ();
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
			return $this->model->table ( 'tbURLInfo', false )->where ( $condition )->delete ();
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
	public function GetListForPage($condition='', $order='', $pagesize=10, $page=1) {
		
		try {
			
			$limit_start = ($page - 1) * $pagesize;
			$limit = $limit_start . ',' . $pagesize;
			
			// 获取行数
			$count = $this->model->table ( 'tbURLInfo', false )->field ( 'URLID' )->where ( $condition )->count ();
			
			$list = $this->model->table ( 'tbURLInfo', false )->field ( 'URLID,
				uCode,
					URLStr,
					uAppendTime,
					uUpdateTime,
					uCount,
					uState
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