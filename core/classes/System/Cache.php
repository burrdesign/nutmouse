<?php

/**
 * @NutMouse CMS
 * @author: Julian Burr
 * @lastchanged: 2014-03-03
 * @version: 0.1 
 *
 * Cache
 * Simple caching system, which basicly just saves often used values into files and
 * loads them from there later on instead of requesting the database again (e.g.)
 **/
 
class Cache {

	public static $cache_dir = '/core/_cache/';
	
	public static function loadCache($key, $ttl="no_ttl"){
		/**
		 * Load data from cache file if possible (and if cache function is activated!)
		 *
		 * Parameters
		 * key: unique key to identify the requested cache file
		 * ttl (optional): time to live; time in seconds the cache file is valid
		 *
		 * Returns
		 * cache: loaded data from cache file (false, if cache not found or outdated!)
		 **/
		 
		if($_SESSION['BD']['config']['System.Cache.Enable'] != "1"){
			//Cache function deactivated
			return false;
		}
		//Create file name as hash from given key
		$file = md5($key);
		$path = $_SERVER['DOCUMENT_ROOT'] . self::$cache_dir . $file;
		
		if(is_file($path)){
			if($ttl == "no_ttl" || time() < (filemtime($path) + (int)$ttl)){
				//Cache file found and valid
				$cache = unserialize(file_get_contents($path));
				return $cache;
			}
			//Cache file outdated -> delete cache file
			unlink($path);
			return false;
		}
		//no cache file found!
		return false;
	}
	
	public static function saveCache($key, $data){
		/**
		 * Save data into cache file (if cache function is activated!)
		 *
		 * Parameters
		 * key: unique key to identify the cache file
		 * data: data to be saved in the cache file (e.g. an array, a string, ...)
		 **/
		 
		if($_SESSION['BD']['config']['System.Cache.Enable'] != "1"){
			//Cache function deaktivated
			return;
		}
		
		//Create cache file and save given data serialized
		$file = md5($key);
		$data = serialize($data);
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . self::$cache_dir . $file, $data);
	}
	
	public static function clearCache($key){
		/**
		 * Clear cache by deleting cache file according to given key
		 *
		 * Parameters
		 * key: unique key to identify the cache file
		 **/
		 
		$file = md5($key);
		$path = $_SERVER['DOCUMENT_ROOT'] . self::$cache_dir . $file;
		if(is_file($path)){
			unlink($path);
		}
	}
	
}