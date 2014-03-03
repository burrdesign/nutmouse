<?php

/**
 * @NutMouse CMS
 * @author: Julian Burr
 * @lastchanged: 2014-03-03
 * @version: 0.1 
 *
 * Locale
 * Manager for system locales and language settings
 **/
 
include_once($_SERVER['DOCUMENT_ROOT'] . '/core/classes/System/Cache.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/core/classes/Db/SqlManager.php');
 
class Locale {
	
	public static function load($lang=null){
		/**
		 * Load locales (of current language) into cache file for faster access
		 *
		 * Parameters
		 * lang (optional): language key which will be used to load locale from database
		 **/
		 
		if(!$lang){
			$lang = self::getLanguage();
		}
		 
		$cachekey = "locale:" . $lang;
		$locale = Cache::loadCache($cachekey);
	
		if(!is_array($locale)){
			//No cache available -> load from database
			$sql = new SqlManager();
			$sql->setQuery("SELECT * FROM bd_sys_locale WHERE locale_language = {{lang}}");
			$sql->bindParam('{{lang}}', $lang, "int");
			$result = $sql->execute();
			$locale = array();
			while($res = mysql_fetch_array($result)){
				$locale[$res['locale_key']] = $res['locale_text'];
			}
			
			//Save loaded locales into cache file for later use 
			Cache::saveCache($cachekey,$locale);
		}
		
		return $locale;
	}
	
	public static function get($key, $lang=null, $defaultvalue=null){
		/**
		 * Get specific locale value by given key
		 *
		 * Parameters
		 * key: unique locale key
		 * lang (optional): Language key if requested language differs from current language
		 * defaultvalue (optional): default value for creating locale entry if not found
		 *
		 * Returns
		 * value: value of the locale entry
		 **/
		
		if(!$lang){
			$lang = self::getLanguage();
		}
		$cachekey = "locale:" . $lang;
		$cache = Cache::loadCache($cachekey);
		
		//If not found, create new locale with default value
		if(!$cache[$key]){
			self::save($key, $defaultvalue, $lang);
			$cache[$key] = $defaultvalue;
		}
		
		$value = $cache[$key];
		return $value;
	}
	
	public static function save($key, $value, $lang=null){
		/**
		 * Get specific locale value by given key
		 *
		 * Parameters
		 * key: unique locale key
		 * value: string that should be saved for given locale entry
		 * lang (optional): Language key if requested language differs from current language
		 **/
		
		if(!$lang){
			$lang = self::getLanguage();
		}
		
		$sql = new SqlManager();
		
		//Check if locale exists
		$sql->setQuery("
			SELECT locale_key FROM bd_sys_locale 
			WHERE locale_key = '{{key}}' 
			AND locale_language = '{{lang}}'
			LIMIT 1");
		$sql->bindParam('{{key}}', $key);
		$sql->bindParam('{{lang}}', $lang, "int");
		$check = $sql->result();
		
		$loc = array(
			'locale_key' => $sql->escape($key),
			'locale_language' => $sql->escape($lang, "int"),
			'locale_text' => $sql->escape($value),
			'locale_lastchanged' => date("Y-m-d H:i:s", time())
		);

		//Either update database or insert new entry for given locale
		if(!$check['locale_key']){
			$sql->insert("bd_sys_locale", $loc);
		} else {
			$sql->update("bd_sys_locale", $loc);
		}
		
		//Refresh cache to make sure new locale entry will be used
		$cachekey = "locale:" . $lang;
		Cache::clearCache($cachekey);
		self::load($lang);
	}
	
	public static function getLanguage(){
		return 1;
	}
	
	public static function setLanguage($key){
		return;
	}
	
}