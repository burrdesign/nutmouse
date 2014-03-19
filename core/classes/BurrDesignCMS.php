<?php

/**
 * @NutMouse CMS
 * @author: Julian Burr
 * @lastchanged: 2014-03-10
 * @version: 0.1 
 *
 * BurrDesignCMS
 * Core class of the Nutmouse CMS system. Initializes and manages the output for front- end backend.
 * Also the most important library classes are included here.
 **/
 
//Controllers
include_once($_SERVER['DOCUMENT_ROOT'] . '/core/classes/Controllers/Frontend/Content.php');

//Other libraries
include_once($_SERVER['DOCUMENT_ROOT'] . '/core/classes/System/Cache.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/core/classes/System/Config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/core/classes/System/Locale.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/core/classes/System/Session.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/core/classes/Db/SqlManager.php');
 
class BurrDesignCMS {
	
	private $view;
	private $controller;
	
	public function __construct(){
		//Initialize config
		Config::load();
		
		//Initialize session
		Session::init();
		
		//Determine requested view
		$this->view = $this->determineView($_REQUEST['url']);
		
		//Print output while catching exceptions
		try {
			echo $this->display();
		} catch(Exception $e){
			echo "<pre>Aaahuu ... something happened!!\n\nCaught exception:\n{$e->getMessage()}\n\nTrace:\n{$e->getTraceAsString()}</pre>";
		}
	}
	
	private function determineView($url){
		/**
		 * Determine which view class (from the MVC engine) should be called, according to the requested url
		 *
		 * Parameters
		 * url: URL string, for which the view should be determined
		 *
		 * Returns
		 * view: Name of the determined view
		 **/
		
		$view = false;
		$view_section = false;
		$url_parts = explode('/', $url);
		
		//TODO: Here should come the part, where the url sheme should be analized according to the system configuration
		if($url_parts[0] == "admin"){
			$view = "Admin_Index";
		} else {
			$view = "Frontend_Content";
		}
		
		$_GET['BD']['core']['view']['name'] = $this->view;
		$_GET['BD']['core']['url'] = $url;
		$_GET['BD']['core']['path'] = $url;
		
		return $view;
	}
	
	private function display(){
		/**
		 * Call determined view to print output to screen
		 **/
		 
		if(empty($this->view)){
			throw new Exception("Empty view requested!");
		}
		
		$controller_class = "Controllers_" . $this->view;
		
		if(!class_exists($controller_class)){
			throw new Exception("Controller not found for determined view '{$this->view}'");
			return;
		} 
		
		$this->controller = new $controller_class($_GET, $_POST);
		
		$output = $this->controller->display();
		return $output;
	}
	
}