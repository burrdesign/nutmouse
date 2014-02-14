<?php

/**
 * @NutMouse CMS
 * @author: Julian Burr
 * @lastchanged: 2014-02-11
 * @version: 0.1 
 *
 * Controllers/Frontend/Content
 * Controller for basic content pages
 * NOTE: I will try to implement the MVC with only one view, not inner and outer ... so the inner template is called from\
 *  within the main template (still as a variable, so still flexible!)
 **/
 
include_once($_SERVER['DOCUMENT_ROOT'] . '/core/classes/Models/Frontend/Content.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/core/classes/Controller.php');

class Controllers_Frontend_Content extends Controller {

	public function __construct($get,$post){
		parent::__construct($get,$post);
	}

	public function display(){
		/**
		 * Builds up output and returns it
		 *
		 * Returns
		 * this->view->loadTemplate: View method that creates the output
		 **/
		
		//Load content using the model class
		$entry = Models_Frontend_Content::getByPath($this->request['BD']['core']['path']);
		
		if(is_array($entry)){
			//Determine and set template
			$template = 'frontend/default';
			if(!empty($entry['info']['content_outertemplate'])){
				$template = $entry['info']['content_outertemplate'];
			}
			$this->view->setTemplate($template);
			
			//Assign Variables for easy access within the template(s)
			$this->view->assign('title', $entry['meta']['title']);
			$this->view->assign('content', $entry['info']['content_content']);
			$template = 'frontend/page/default';
			if(!empty($entry['info']['content_template'])){
				$template = $entry['info']['content_template'];
			}
			$this->view->assign('template', $template);
			$this->view->assign('info', $entry['info']);
			$this->view->assign('meta', $entry['meta']);
			$this->view->assign('custom', $entry['custom']);
		} else {
			//No content found >> 404 error
			$this->view->header("HTTP/1.0 404 Not Found");
			$this->view->setTemplate('frontend/default');
			$this->view->assign('title', "404 ERROR");
			$this->view->assign('content', "Page not found!");
			$this->view->assign('template', "frontend/page/error/404");
		}

		return $this->view->loadTemplate();
	}
	
}