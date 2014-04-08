<?php
/**
 * Melon － 可用于php5.3或以上的开源框架
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link http://git.oschina.net/397574898/MelonFramework
 * @author Melon <denglh1990@qq.com>
 * @version 0.2.0
 */

namespace Melon\App\Lib;

use Melon\Util;

defined('IN_MELON') or die('Permission denied');

class View {
	
	private $_view;
	
	protected $_controller;

	public function __construct( $controller ) {
		$this->_controller = $controller;
		
		// 视图设置好基本的目录，方便管理和使用
		$this->_view = \Melon::template();
		$this->_view->setTemplateDir( \Melon::env( 'appDir' ) . DIRECTORY_SEPARATOR . 'Module' .
			DIRECTORY_SEPARATOR . \Melon::env( 'config.privatePre' ) . \Melon::env( 'moduleName' ) . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . \Melon::env( 'controller' ) );
		$this->_view->setCompileDir( \Melon::env( 'appDir' ) . DIRECTORY_SEPARATOR .
			'Data' . DIRECTORY_SEPARATOR . 'TplCache' );
	}
	
	/**
	 * 注入一个变量
	 * 
	 * @param string $key 变量名
	 * @param mixed $value 值
	 * @return \Melon\App\Lib\View
	 */
	public function assign( $key, $value ) {
		$this->_view->assign( $key, $value );
		return $this;
	}
	
	/**
	 * 注入一组变量
	 * 
	 * @param array $vars 变量组，每个元素都表示一个变量
	 * @return \Melon\App\Lib\View
	 */
	public function assignItem( array $vars ) {
		$this->_view->assignItem( $vars );
		return $this;
	}
	
	/**
	 * 注入一个自定义标签
	 * 
	 * @param string $tagname 自定义标签名
	 * @param array $setting 标签设置
	 * 要提供的参数：
	 * 1. callable		string	可直接调用的函数的名称
	 * 2. args		array	参数数组，key是参数名称，value是默认值，数组元素必须按照callable函数的参数顺序一一对应
	 * @return \Melon\App\Lib\View
	 */
	public function assignTag( $tagname, $setting ) {
		$this->_view->assignTag( $tagname, $setting );
		return $this;
	}
	
	/**
	 * 注入一组自定义标签
	 * 
	 * @param array $tags 标签组，每个元素都表示一个自定义标签
	 * @return \Melon\App\Lib\View
	 */
	public function assignTagItem( array $tags ) {
		$this->_view->assignTagItem( $tags );
		return $this;
	}
	
	
	/**
	 * 把模板运行结果返回
	 * 
	 * @param string $template 模板路径，如果设置了模板目录，则它是相对于模板目录下的文件路径
	 * @return string
	 */
	public function fetch( $template ) {
		return $this->_view->fetch( $template );
	}
	
	/**
	 * 把模板运行结果输出
	 * 
	 * @param string $template 模板路径，如果设置了模板目录，则它是相对于模板目录下的文件路径
	 * @return void
	 */
	public function display( $template ) {
		if( isset( $this->_controller->lang ) && ( is_array( $this->_controller->lang ) || $this->_controller->lang instanceof Util\Set ) ) {
			$this->_view->assign( 'lang', $this->_controller->lang );
		}
		$this->_view->display( $template );
	}
	
}