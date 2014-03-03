<?php

/**
 * @NutMouse CMS
 * @author: Julian Burr
 * @lastchanged: 2014-03-03
 * @version: 0.1 
 *
 * Config
 * Main class to handle config matters, like loading the sysrem configuartions into the session
 * or getting a specific configuration value by its key
 **/
 
include_once($_SERVER['DOCUMENT_ROOT'] . '/core/classes/System/Cache.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/core/classes/Db/SqlManager.php');
 
class Config {
	
	public static function load($cachekey="config"){
		/**
		 * Load system config into array, save this in session and return array
		 *
		 * Parameters
		 * cachekey (optional): key which will be used to load config from cache file
		 *
		 * Returns
		 * config: loaded config array for possible further use
		 **/
		 
		$config = Cache::loadCache($cachekey);
	
		if(!is_array($config)){
			//No cache available -> load from database
			$sql = new SqlManager();
			$sql->setQuery("SELECT * FROM bd_sys_config");
			$result = $sql->execute();
			$config = array();
			while($res = mysql_fetch_array($result)){
				$config[$res['config_key']] = $res['config_value'];
			}
			
			//Save config in session
			$_SESSION['BD']['config'] = $config;
			
			//Also save loaded config into cache file for later use 
			Cache::saveCache($cachekey,$config);
		}
		
		return $config;
	}
	
	public static function get($key){
		/**
		 * Get specific config value by given key
		 *
		 * Parameters
		 * key: unique config key
		 *
		 * Returns
		 * $_SESSION['BD']['config'][$key]: value from session array
		 **/
		 
		if(!is_array($_SESSION['BD']['config'])){
			//Load config
			$_SESSION['BD']['config'] = self::load();
		}
		return $_SESSION['BD']['config'][$key];
	}
	
}