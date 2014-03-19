<?php

/**
 * @NutMouse CMS
 * @author: Julian Burr
 * @lastchanged: 2014-03-10
 * @version: 0.1 
 *
 * Controller
 * Basis for all controller classes 
 **/
 
include_once($_SERVER['DOCUMENT_ROOT'] . '/core/classes/View.php');

class Controller {

	protected $request = array();
	protected $get = array();
	protected $post = array();
	protected $template = '';
	protected $view = null;

	public function __construct($get,$post){
		//Save request arrays into instance
		$this->get = $get;
		$this->post = $post;
		$this->request = array_merge($get, $post);
	
		//Create view
		$this->view = new View();
		
		$this->display = 'default';
		if(!empty($this->request['BD']['core']['view']['name'])){
			$this->display = $this->request['BD']['core']['view']['name'];
		}
	}

}