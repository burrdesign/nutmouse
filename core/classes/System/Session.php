<?php

/**
 * @NutMouse CMS
 * @author: Julian Burr
 * @lastchanged: 2014-03-19
 * @version: 0.1 
 *
 * Session
 * Class to manage session handling (static!) 
 **/
 
class Session {
	
	public static function init(){
		/**
		 * Initialize the Session
		 **/
		 
		if(!self::getId()){
			//No current session found -> create new session
			self::newSession();
		}
		
		if(!self::getId()){
			//Still no session found -> system error!
			throw new Exception("Session initialization failed!");
		}
	}
	
	public static function kill(){
		/**
		 * Kill current session (and delete Session array just to be sure)
		 **/
		 
		 unset($_SESSION['BD']);
		 session_destroy();
	}

	public static function getId(){
		/**
		 * Get the current session id
		 *
		 * Returns:
		 * $_SESSION['BD']['session']['id'] : current session id
		 **/
		
		return $_SESSION['BD']['session']['id'];
	}
	
	private static function newSession(){
		/**
		 * Generate new session id just to be sure
		 * and save new id in session array
		 **/
		
		session_regenerate_id();
		$_SESSION['BD']['session']['id'] = session_id();
		
		//TODO: Here would be a perfect place to collect stats
	}

}