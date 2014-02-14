<?php

/**
 * @NutMouse CMS
 * @author: Julian Burr
 * @lastchanged: 2014-02-11
 * @version: 0.1 
 *
 * Model
 * Basis for all model classes
 **/

class Model {
	
	protected static $entry = array();
	protected static $sql;
	
	public function __construct(){
		//Initialize SQL manager for this instance
		self::$sql = new SqlManager();
	}
	
}