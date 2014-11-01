<?php

/**
	 * 获取引用文件路径
	 * @param string $file_name 文件名称
	 * @param string $folder 所在目录(默认为空)
	 * @return string
	 */
function fimport($fileName, $folder = '')
{
	global $global;
	static $sufix = array('module' => '.module','class' => '.class','function' => '.func','language' => '.lang');
	//$fileName = strtolower($fileName);
	$filePath = SITE_ROOT . '/./core';
	if(strstr($fileName, '/'))
	{
		list($pre, $name) = explode('/', $fileName);
		$insert = '';
		if($pre == 'language')
		{
			$insert = $global['config']['defaultLang'] . '/';
		}
		
		require_once "$filePath/$pre/" . $insert . (empty($folder)? "" :$folder . '/' . $name . "$sufix[$pre]" . ".php");
	}
	else
	{
		require_once "$filePath/" . (empty($folder)? '' :$folder . '/' . $fileName . '.php');
	}
}

/**
 * 获取语言文本
 * 
 * @param string $file
 *        	所在文件
 * @param string $var
 *        	键
 * @param string $default
 *        	默认值
 * @return mixed
 */
function lang($file, $var = null, $defauld = null)
{
	global $global;
	$key = $file;
	if(!isset($global['lang'][$key]))
	{
		require_once fimport("language/$file");
		$global['lang'][$key] = $lang;
	}
	
	$return = $val !== null? (isset($global['lang'][$key][$var])? $global['lang'][$key][$var] :null) :$global['lang'][$key];
	$return = $return === null? ($defauld !== null? $defauld :$var) :$return;
	return $return;
}

function display()
{
	$content = null;
	ob_start();
	if($content == null)
	{
		$content = ob_get_contents();
// 		express($content);
	}
	ob_end_clean();
	var_dump($content);exit;
	echo $content;
}

function template($file, $tplDir='',$data=array())
{
	global $_global;
	$tplDir = $tplDir? $tplDir :"./tpl/" . $_global['config']['setting']['siteTmpl'];
	$tplFile = $tplDir . '/' . $file . '.php';
	ob_start();
	include $tplFile;
	$content = ob_get_contents();
	ob_end_clean();
	echo $content;
}

/**
 * 显示错误信息
 * 
 * @param string $title
 *        	标题
 * @param string $message
 *        	错误信息
 * @param string $jump_url
 *        	跳转地址
 * @param int $wait
 *        	等待时间
 * @param bool $is_close
 *        	是否显示网站关闭
 * @return void
 */
function showError($jumpUrl,$title='', $message='',$wait = 3, $isClose = false)
{
	global $_global;
	$_global['title'] = $title ? $title : '操作失败';
	$_global['message'] = explode('\n', $message);
	$_global['jumpUrl'] = $_global['site_url'].$jumpUrl;
	if($isClose)
	{
		template('common/close');
	}
	else
	{
		template('common/error');
	}
}
/**
 * 显示错误信息
 *
 * @param string $title
 *        	标题
 * @param string $message
 *        	错误信息
 * @param string $jump_url
 *        	跳转地址
 * @param int $wait
 *        	等待时间
 * @param bool $is_close
 *        	是否显示网站关闭
 * @return void
 */
function showSuccess($jumpUrl,$title='',$message='', $wait = 3, $isClose = false)
{
	global $_global;
	$_global['title'] = $title ? $title : '操作成功';
	$message = $message ? $message : '操作成功';
	$_global['message'] = explode('\n', $message);
	$_global['jumpUrl'] = $_global['site_url'].$jumpUrl;
	template('common/success');
}
/**
 * 获取客户端IP
 * 
 * @return string
 */
function getFClientIp()
{
	$ip = $_SERVER['REMOTE_ADDR'];
	if(isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP']))
	{
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) &&
		 preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches))
	{
		foreach($matches[0] as $xip)
		{
			if(!preg_match('#^(10|172\.16|192\.168)\.#', $xip))
			{
				$ip = $xip;
				break;
			}
		}
	}
	return $ip;
}

function getTplFile($file)
{
	global $_global;
	require_once SITE_ROOT.'/tpl/'.$_global['config']['setting']['siteTmpl'].'/'.$file;
}
function getCssFile($file)
{
	global $_global;
	return $_global['site_url'].'tpl/'.$_global['config']['setting']['siteTmpl'].'/css/'.$file;
}
function getJsFile($file)
{
	global $_global;
	return $_global['site_url'].'tpl/'.$_global['config']['setting']['siteTmpl'].'/js/'.$file;
}
?>
