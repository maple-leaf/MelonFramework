<?php

return array(
	
	'charset' => 'utf-8',							// 脚本编码
	'timezone' => null,								// 时区
	
	'includePath' => array(),						// 包含路径
	
	'logDir' => 'Log',					// 系统日志目录，相对于Melon环境变量的root目录
	'logDisplayLevel' => 3,							// 显示系统日志等级，0不显示；1只显示异常和致命错误；2显示所有错误；3显示所有类型
	'logLevel' => 3,								// 记录系统日志等级，0不记录；1只记录异常和致命错误；2记录所有错误；3记录所有类型
	'logSplitSize' => 10,							// 系统日志分割大小，单位M
	'htmlShowCodeSnippet' => true,					// 是否在页面中显示代码片段
	
	'errorPage' => 'Data/errorPage.html',		// 浏览器访问发生错误时显示的页面，相对于Melon环境变量的root目录
	'errorMessage' => 'Server error.',					// 非浏览器访问（ajax、cgi等）或者errotPage不存在时输出的错误消息
	
	'templateTags' => array( '{', '}' ),			//默认模板标签
	
	'database' => array(						// 数据库接口配置
		'tablePrefix' => '',					// 表前缀
//		'driver' => array(
//			'dsn' => '',						// PDO DSN，例：mysql:host=localhost;dbname=test;
//			'username' => '',					// 数据库帐号
//			'password' => '',					// 数据库密码
//			'options' => array(),				// PDO属性
//		)
	)
);
