<?php
/**
 * 
 * 参考了PHP Slim框架的Set类的接口，作者写得很好，几乎完美了，我简单的借鉴一下 :)
 * Slim官网：http://www.slimframework.com/
 */

namespace Melon\Helper;

defined( 'IN_MELON' ) or die( 'Permission denied' );

class Set implements \ArrayAccess, \Countable, \IteratorAggregate {
	
	// 替换模式
	// 你可以查看Set::set或者Set::setItems方法了解
	const REPLACE_NOT = 1;
	const REPLACE_RELAXED = 2;
	const REPLACE_ABSOLUTE = 3;
	
	/**
	 * 当前实例的替换模式
	 * @var enum
	 */
	protected $_replaceMode;
	
	/**
	 * 用于保存数据的数组
	 * @var array
	 */
	protected $_array;
	
	public function __construct( array $items = array(), $replaceMode = self::REPLACE_ABSOLUTE ) {
		$this->_replaceMode = $replaceMode;
		$this->setItems( $items );
	}
	
	/**
	 * 格式化键名
	 * 
	 * @param mixed $key 键名
	 * @return string 
	 */
	protected function _normalizeKey( $key ) {
		return $key;
	}
	
	/**
	 * 设置一个元素
	 * 
	 * @param mixed $key 键名
	 * @param mixed $value 值
	 * @param enum $replaceMode [可选] 替换模式，如果存在相同键名元素时被触发
	 * 替换模式分别有：
	 * 1. Set::REPLACE_NOT			不进行替换
	 * 2. Set::REPLACE_ABSOLUTE		[默认] 严格，无条件替换原来的值
	 * 3. Set::REPLACE_RELAXED		宽松，如果$value能够被PHP empty转为假值（null、''、0、false、空数组），则不替换
	 * @return Set $this
	 * @see Set::setItems
	 */
	public function set( $key, $value, $replaceMode = null ) {
		$normalizeKey = $this->_normalizeKey( $key );
		if( ! isset( $this->_array[ $normalizeKey ] ) ) {
			$this->_array[ $normalizeKey ] = $value;
			return;
		}
		$_replaceMode = ( is_null( $replaceMode ) ? $this->_replaceMode : $replaceMode );
		//替换模式
		switch( $_replaceMode ) {
			case self::REPLACE_NOT :
				//它不用做任何事情
				break;
			case self::REPLACE_RELAXED :
				if( ! empty( $value ) ) {
					$this->_array[ $normalizeKey ] = $value;
				}
				break;
			case self::REPLACE_ABSOLUTE :
			default :
				$this->_array[ $normalizeKey ] = $value;
				break;
		}
		return $this;
	}
	
	/**
	 * 设置一组元素
	 * 
	 * @param array $items 包含常规的键值对元素的数组，程序会将其合并到当前数组里
	 * @param enum $replaceMode [optional] 替换模式，如果存在相同键名元素时被触发
	 * 替换模式分别有：
	 * 1. Set::REPLACE_NOT			不进行替换
	 * 2. Set::REPLACE_ABSOLUTE		[默认] 严格，无条件替换原来的值
	 * 3. Set::REPLACE_RELAXED		宽松，如果$value能够被PHP empty转为假值（null、''、0、false、空数组），则不替换
	 * @return Set $this
	 */
	public function setItems( array $items, $replaceMode = null ) {
		foreach( $items as $key => $value ) {
			$this->set( $key, $value, $replaceMode );
		}
		return $this;
	}
	
	/**
	 * 获取元素
	 * 
	 * @param mixed $key
	 * @return mixed
	 */
	public function get( $key ) {
		$normalizeKey = $this->_normalizeKey( $key );
		return ( isset( $this->_array[ $normalizeKey ] ) ? $this->_array[ $normalizeKey ] : null );
	}
	
	/**
	 * 获取包含指定键名的一组元素
	 * 
	 * @param array $keys [可选] 包含这些元素的键名，如果为空，则返回所有元素
	 * @return array
	 */
	public function getItems( array $keys = array() ) {
		$items = array();
		if( empty( $keys ) ) {
			return $this->_array;
		}
		foreach( $keys as $key ) {
			$normalizeKey = $this->_normalizeKey( $key );
			if( isset( $this->_array[ $normalizeKey ] ) ) {
				$items[ $normalizeKey ] = $this->_array[ $normalizeKey ];
			}
		}
		return $items;
	}
	
	/**
	 * 删除一个元素
	 * 
	 * @param mixed $key 键名
	 * @return void
	 */
	public function remove( $key ) {
		$normalizeKey = $this->_normalizeKey( $key ) ;
		if( isset( $this->_array[ $normalizeKey ] ) ) {
			unset( $this->_array[ $normalizeKey ] );
		}
	}
	
	/**
	 * 获取数组的所有键名
	 * 
	 * @return array
	 */
	public function keys() {
		return array_keys( $this->_array );
	}
	
	/**
	 * 检查是否存在某个元素
	 * 
	 * @param array $key 键名
	 * @return boolean
	 */
	public function has( $key ) {
		return isset( $this->_array[ $this->_normalizeKey( $key ) ] );
	}
	
	/**
	 * 统计数组有多少个元素
	 * 
	 * @return int
	 */
	public function count() {
		return count( $this->_array );
	}
	
	/**
	 * 清空数组
	 * 
	 * @return void
	 */
	public function clear() {
		$this->_array = array();
	}
	
	/**
	 * 获取一个数组迭代器，使得当前对象可以像数组一样使用foreach
	 * 
	 * @return \ArrayIterator
	 * @link http://www.php.net/manual/zh/class.arrayiterator.php 详情查阅官网说明
	 */
	public function getIterator() {
		return new \ArrayIterator( $this->_array );
	}
	
	//=================================================================
	// 实现ArrayAccess接口方法
	//=================================================================
	
	/**
	 * 设置一个元素 
	 * 
	 * @param mixed key 键名
	 * @param mixed value 值
	 * @return void
	 */
	public function offsetSet( $key, $value ) {
		$this->set( $key, $value );
	}

	/**
	 * 获取一个元素
	 * 
	 * @param mixed key 键名
	 * @return mixed
	 */
	public function offsetGet( $key ) {
		return $this->get( $key );
	}

	/**
	 * 删除一个元素
	 * 
	 * @param mixed key 键名
	 * @return void
	 */
	public function offsetUnset( $key ) {
		$this->remove( $key );
	}

	/**
	 * 检查是否存在某个元素
	 * 
	 * @param mixed key 键名
	 * @return boolean
	 */
	public function offsetExists( $key ) {
		return $this->has( $key );
	}
	
	//=================================================================
	// 实现PHP拦截器方法
	//=================================================================
	
	/**
	 * 获取一个元素
	 * 
	 * @param mixed key 键名
	 * @return mixed
	 */
	public function __set( $key, $value ) {
		$this->set( $key, $value );
	}
	
	/**
	 * 获取一个元素
	 * 
	 * @param mixed key 键名
	 * @return mixed
	 */
	public function __get( $key ) {
		return $this->get( $key );
	}
	
	/**
	 * 删除一个元素
	 * 
	 * @param mixed key 键名
	 * @return void
	 */
	public function __unset( $key ) {
		$this->remove( $key );
	}
	
	/**
	 * 检查是否存在某个元素
	 * 
	 * @param mixed key 键名
	 * @return boolean
	 */
	public function __isset( $key ) {
		return $this->has( $key );
	}
}