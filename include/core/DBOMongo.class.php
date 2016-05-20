<?php
/**
 * Mongo操作类加载Mongo数据库
 * @author Cxty
 *
 */
class DBOMongo {
	public $mongo_db = NULL; // 当前数据库操作对象
	public $config = array ();
	// public $sql = '';//sql语句，主要用于输出构造成的sql语句
    public $pre = '';//表前缀，主要用于在其他地方获取表前缀
	private $data = array (); // 数据信息
	private $options=array(); // 查询表达式参数
	
	public function __construct($config = array()) {
		// 如果还没有加载配置文件，则加载配置文件
		if (! defined ( 'is_load_DBOConfig' ))
			require_once (dirname ( __FILE__ ) . '/DBOConfig.class.php');
		$this->config = is_array ( $config ) ? array_merge ( DBOConfig::get ( 'MONGO' ), $config ) : DBOConfig::get ( 'MONGO' ); // 参数配置
	
	}
	// 连接数据库
	public function connect() {
		// $this->db不是对象，则连接数据库
		if (! is_object ( $this->mongo_db )) {
			if (! file_exists ( dirname ( __FILE__ ) . '/db/mongodb.class.php' )) {
				$this->error ( 'Mongo 数据库类型没有驱动' );
			}
			require_once (dirname ( __FILE__ ) . '/db/mongodb.class.php'); // 加载数据库类
			
			if ($this->config ['MONGODB_NAME'] === "") {
				if (! $this->config ['MONGODB_AUTH'] && $this->config ['MONGODB_NAME']) {
					$db = $this->mongo_db;
				} else {
					$db = "admin";
				}
			}
			
			$this->mongo_db = new mongo_db (); // 连接数据库
			
			if($this->mongo_db->connect ( $this->config ['MONGODB_HOST'] . ':' . $this->config ['MONGODB_PORT'], $this->config ['MONGODB_USER'], $this->config ['MONGODB_PWD'], $this->config ['MONGODB_NAME'], $this->config ['MONGODB_AUTH'] ) === false){
				die('MongoDB Connect Error!');
			}
			
		}
	}
	// 设置表
	public function table($table) {
		
		$this->connect ();
		$this->mongo_db->table ( $table );
		
		return $this;
	}
	
	// 选择库
	public function select_db($dbname) {
		$this->connect ();
		$this->mongo_db->select_db ( $dbname );
		return $this;
	
	}
	/**
	 * 指定属性值
	 *
	 * @param string|array $nameOrAttrs
	 *        	属性名或一组属性值
	 * @param string $value
	 *        	属性值
	 */
	public function attr($nameOrAttrs, $value = null) {
		$this->connect ();
		$this->mongo_db->attr ( $nameOrAttrs, $value );
		return $this;
	}
	/**
	 * 返回知道字段
	 */
	public function field($_field){
		if($_field){
			$this->connect ();
			$this->mongo_db->field ( $_field );
			return $this;
		}
	}
	/**
	 * 指定返回的结果属性。在当前的mongo版本中（<1.4.x），不能混合使用result()和exclude()
	 *
	 * @param string $attr1
	 *        	第一个属性
	 * @param
	 *        	...
	 */
	public function result($attr1 = null) {
		$this->connect ();
		$this->mongo_db->result ( $attr1 );
		return $this;
	}
	/**
	 * 指定返回的结果中要排除的属性
	 *
	 * @param string $attr1
	 *        	第一个属性
	 * @param
	 *        	...
	 */
	public function exclude($attr1 = null) {
		$this->connect ();
		$this->mongo_db->exclude ( $attr1 );
		return $this;
	}
	/**
	 * 设置正排序条件
	 *
	 * @param string $attr
	 *        	需要排序的属性
	 */
	public function asc($attr = "_id") {
		$this->connect ();
		$this->mongo_db->asc ( $attr );
		return $this;
	}
	/**
	 * 设置倒排序条件
	 *
	 * @param string $attr
	 *        	需要排序的属性
	 */
	public function desc($attr = "_id") {
		$this->connect ();
		$this->mongo_db->desc ( $attr );
		return $this;
	}
	/**
	 * 设置记录开始的位置
	 *
	 * @param integer $offset
	 *        	开始位置
	 */
	public function offset($offset) {
		$this->connect ();
		$this->mongo_db->offset ( $offset );
		return $this;
	}
	/**
	 * 设置需要查询的记录行数
	 *
	 * @param integer $size
	 *        	行数
	 */
	public function limit($size) {
		$this->connect ();
		$this->mongo_db->limit ( $size );
		return $this;
	}
	/**
	 * 设置需要查询的开始记录行
	 *
	 * @param integer $index
	 *        	行数
	 */
	public function skip($index = 0) {
		$this->connect ();
		$this->mongo_db->skip ( $index );
		return $this;
	}
	/**
	 * 增加查询条件
	 *
	 * @param array $cond
	 *        	查询条件
	 */
	public function cond(array $cond) {
		$this->connect ();
		$this->mongo_db->cond ( $cond );
		return $this;
	}
	/**
	 * 添加操作符
	 *
	 * @param string $attr
	 *        	属性名
	 * @param string $operator
	 *        	操作符，比如$gt, $lt ...
	 * @param mixed $value
	 *        	操作符对应的值
	 */
	public function operator($attr, $operator, $value) {
		$this->connect ();
		$this->mongo_db->operator ( $attr, $operator, $value );
		return $this;
	}
	/**
	 * 分割一个是集合（相当于PHP中的索引数组）的属性值
	 *
	 * @param string $attr
	 *        	属性名
	 * @param integer $subOffset
	 *        	开始位置
	 * @param integer $subLimit
	 *        	要取出的条数
	 *        	
	 */
	public function slice($attr, $subOffset, $subLimit) {
		$this->connect ();
		$this->mongo_db->slice ( $attr, $subOffset, $subLimit );
		return $this;
	}
	/**
	 * 使用函数。如果数据较多话，将会非常慢。
	 *
	 * @param string $func
	 *        	Javascript函数
	 *        	
	 */
	public function func($func) {
		$this->connect ();
		$this->mongo_db->func ( $func );
		return $this;
	}
	/**
	 * 设置否是不返回主键（_id）
	 *
	 * @param unknown_type $returnPk        	
	 *
	 */
	public function noPk($noPk = true) {
		$this->connect ();
		$this->mongo_db->noPk ( $noPk );
		return $this;
	}
	/**
	 * 设置查询的主键值
	 *
	 * @param string $pk1
	 *        	主键1
	 * @param
	 *        	string ...
	 *        	
	 */
	public function id($pk1) {
		$this->connect ();
		$this->mongo_db->id ( $pk1 );
		return $this;
	}
	/**
	 * add hints for query
	 *
	 * @param unknown_type $hint        	
	 */
	public function hint($hint) {
		$this->connect ();
		$this->mongo_db->hint ( $hint );
		return $this;
	}
	/**
	 * 取得当前查询的游标
	 *
	 * @return MongoCursor
	 */
	public function cursor() {
		$this->connect ();
		return $this->mongo_db->cursor ();
	}
	/**
	 * 查找一行数据，并以一个对象的形式返回
	 *
	 * @param string $id
	 *        	主键_id值
	 * @return row
	 */
	public function find($id = null) {
		$this->connect ();
		
		return $this->mongo_db->find ( $id );
	}
	/**
	 * 查找一行数据，以数组的形式返回
	 *
	 * @param string $id
	 *        	主键_id值
	 * @return array
	 */
	public function findOne($id = null) {
		$this->connect ();
		return $this->mongo_db->findOne ( $id );
	}
	/**
	 * 查找一行数据，但只返回ID数据
	 *
	 * @param string $id
	 *        	主键_id值
	 */
	public function findId($id = null) {
		$this->connect ();
		return $this->mongo_db->findId ( $id );
	}
	/**
	 * 取出所有记录
	 *
	 * @param boolean $keepId
	 *        	是否保留ID的原始状态
	 * @return array
	 */
	public function findAll($keepId = true) {
		$this->connect ();
		return $this->mongo_db->findAll ( $keepId );
	}
	/**
	 * 计算符合条件的行数
	 * @return integer
	 */
	public function count($withLimit = false) {
		$this->connect ();
		return $this->mongo_db->count ( $withLimit );
	}
	/**
	 * Insert new record
	 *
	 * @param array $attrs
	 *        	attributes of new record
	 * @param boolean $safe
	 *        	check result
	 * @return boolean
	 */
	public function insert(array $attrs, $safe = false) {
		
		$this->connect();
		
		return $this->mongo_db->insert ( $attrs, $safe );
	}
	/**
	 * 插入新的行，_id是上一行的ID加1
	 *
	 * @param array $attrs
	 *        	新行的属性集
	 * @return boolean
	 */
	public function insertNext(array $attrs) {
		$this->connect ();
		return $this->mongo_db->insertNext ( $attrs );
	}
	/**
	 * 删除符合条件的记录
	 *
	 * @return boolean
	 */
	public function delete() {
		$this->connect ();
		return $this->mongo_db->delete ();
	}
	/**
	 * 更改或插入新的对象
	 *
	 * 在当前驱动下不能正常工作
	 *
	 * @param array $obj
	 *        	新的对象
	 * @return boolean
	 */
	public function upsert(array $obj) {
		$this->connect ();
		return $this->mongo_db->upsert ( $obj );
	}
	/**
	 * 更改对象
	 * @param array $obj
	 * @param array $option
	 * @return Ambigous <boolean, multitype:>
	 */
	public function update(array $obj,array $option){
		$this->connect ();
		return $this->mongo_db->update ( $obj,$option );
	}
	/**
	 * 批量插入一组新的数据
	 *
	 * @param array $array
	 *        	每一个元素包含一个要插入的行
	 * @return boolean
	 */
	public function batchInsert(array $array) {
		$this->connect ();
		return $this->mongo_db->batchInsert ( $array );
	}
	
	/**
	 * 当前操作的集合
	 *
	 * @return MongoCollection
	 */
	public function collection() {
		$this->connect ();
		return $this->mongo_db->collection ();
	}
	/**
	 * 当前操作的数据库
	 *
	 * @return MongoDB
	 */
	public function db() {
		$this->connect ();
		return $this->mongo_db;
	}
	/**
	 * 获取当前错误信息
	 *
	 * 参数：无
	 *
	 * 返回值：当前错误信息
	 */
	function getError()
	{
		$this->connect ();
		return $this->mongo_db->getError();
	}
}

?>