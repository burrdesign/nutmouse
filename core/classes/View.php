<?php

/**
 * @NutMouse CMS
 * @author: Julian Burr
 * @lastchanged: 2014-02-11
 * @version: 0.1 
 *
 * Model
 * Basis for all view classes
 **/

class View {

	private $template_dirpath = 'core/templates';
	private $template = 'default';
	private $theme = null;
	private $default_theme = '_nutmouse';

	//Variables for within the template should be assigned here
	private $_ = array();
	
	public function __construct(){
		//TODO: here should the current theme be loadad from the system configuration!
		$this->theme = $this->default_theme;
	}

	public function assign($key, $value){
		/**
		 * Assign variables for usage within the template
		 *
		 * Parameters
		 * key: Parameter name as key for the array entry that will created
		 * value: Value of the assigned parameter
		 **/
		
		$this->_[$key] = $value;
	}
	
	public function assignArray($array){
		/**
		 * Assign whole array to the instance
		 *
		 * Parameters
		 * array: Array, that should be merged to be assigned to the instance
		 **/
		
		$this->_ = array_merge($this->_, $array);
	}

	public function setTemplate($template='default'){
		/**
		 * Sets template for this instance
		 *
		 * Parameters
		 * template (optional): Name of the template
		 **/
		 
		$this->template = $template;
	}

	public function loadTemplate(){
		/**
		 * Loads output from the currently set template and returns it
		 *
		 * Returns
		 * output: Output created by the set template
		 **/
		
		//First try if the template exists in the current theme directory
		$file = $_SERVER['DOCUMENT_ROOT'] . '/' . $this->template_dirpath . '/' . $this->theme . '/' . $this->template . '.tpl';
		$exists = file_exists($file);
		
		if(!$exists){
			//If it wasn't fount, load it from the default theme directory
			$file = $_SERVER['DOCUMENT_ROOT'] . '/' . $this->template_dirpath . '/' . $this->default_theme . '/' . $this->template . '.tpl';
			$exists = file_exists($file);
		}

		if(!$exists){
			//Template not found
			throw new Exception("Template '{$this->template}' not found!");
			return;
		}
		
		//Get the output and return it
		ob_start();
			include($file);
			$output = ob_get_contents();
			$output = $this->parseTemplate($output);
		ob_end_clean();
		return $output;
	}
	
	public function parseTemplate($text){
		/**
		 * Parsing the template
		 *
		 * Parameters
		 * text: given text that should be parsed
		 *
		 * Returns
		 * text: parsed text
		 **/
		 
		//Parse simple variables -> {{var CODE}}
		$cnt = preg_match_all("/\{\{var (.+?)\}\}/", $text, $vars);
		for($i=0; $i<$cnt; $i++){
			$text = str_replace($vars[0][$i], $this->_[$vars[1][$i]], $text);
		} 
		 
		//Parse locale tags -> {{locale CODE}}DEFAULTTEXT{{/locale}}
		$cnt = preg_match_all("/\{\{locale (.+?)\}\}(.*?)\{\{\/locale\}\}/", $text, $locales);
		for($i=0; $i<$cnt; $i++){
			$value = Locale::get($locales[1][$i], null, $locales[2][$i]);
			$text = str_replace($locales[0][$i], $value, $text);
		}

		if(!$text){
			throw new Exception("Text is empty");
		}
		
		return $text;
	}
	
	public function header($header){
		/**
		 * Simply sets HTTP header so far
		 *
		 * Parameters
		 * header: HTTP header code
		 **/
		 
		header($header);
	}
}