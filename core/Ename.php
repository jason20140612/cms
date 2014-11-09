<?php
error_reporting(E_ALL);
ini_set('display_errors','on');
	class Ename
	{
		public function __construct()
		{
			if(!defined('SITE_ROOT'))
			{
				define('SITE_ROOT',dirname(dirname(__FILE__)));
			}
			if(!require_once(SITE_ROOT.'/core/function/global.func.php'))
			{
				exit('not found global.func.php');
			}
			global $_global;
			$_global = array();
			$_global['defaultModule'] = 'Index';
			$_global['defaultAction'] = 'index';
			$_global['module'] = '';
			$_global['action'] = '';
			$_global['config'] = array();
			$_global['lang'] = array();
			$_global['clientIp'] = getFClientIp();
 			$this->loadConfig();
// 			$this->loadLang();
			list($module,$action) = $this->getUrl();
			$_global['module'] = $module;
			$_global['action'] = $action;
			
			fimport('FormCheck','form');
			fimport('Form','form');
			$_global['site_url'] = 'http://'.$_SERVER['SERVER_NAME'].'/';
			if(!defined('IMAGE_CREATE_QUALITY'))
			{
				/* 图片生成质量 */
				define('IMAGE_CREATE_QUALITY',90);
			}
		}
		public function getUrl()
		{
			global $_global;
			$pathInfo = $_SERVER['PATH_INFO'];
			if($pathInfo)
			{
				$pathInfo = trim($pathInfo,'/');
				$arr = explode('/', $pathInfo);
				$module = isset($arr[0])&&$arr[0] ? ucfirst($arr[0]) : $_global['defaultModule'];
				$action = isset($arr[1])&&$arr[1] ? strtolower($arr[1]) : strtolower($_global['defaultAction']);
			}
			else
			{
				$module = ucfirst($_global['defaultModule']);
				$action = strtolower($_global['defaultAction']);
			}
			return array($module,$action);
		}
		public function loadConfig()
		{
			global $_global;
			$config = array();
			require_once SITE_ROOT.'/public/config.global.php';
			$_global['config'] = $config;
		}
		public function loadLang()
		{
			global $_global;
			$lang = array();
			$this->lang = fimport('language/common',$_global['setting']['lang']);
		}
		public static function &instance()
		{
			static $instance = null;
			if(null === $instance)
			{
				$instance = new Ename();
			}
			return $instance;
		}
		public function run()
		{
			global $_global;
			fimport(ucfirst($_global['module']),'module');
			$m = new $_global['module'];
			$action = $_global['action'];
			$m->$action();
		}
	}
?>
