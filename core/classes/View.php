<?php

/**
 * @NutMouse CMS
 * @author: Julian Burr
 * @lastchanged: 2014-03-10
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
		$file = $_SERVER['DOCUMENT_ROOT'] . $this->getTemplatePath($this->template);
		
		//Get the output and return it
		ob_start();
			include($file);
			$output = ob_get_contents();
			$output = $this->parseTemplate($output);
		ob_end_clean();
		return $output;
	}
	
	public function getTemplatePath($rel){
		/**
		 * Get absolute template path from given relative path
		 *
		 * Parameters
		 * rel: relative file path
		 * 
		 * Returns
		 * abs: absolute file path
		 **/
		 
		$abs = '/' . $this->template_dirpath . '/' . $this->theme . '/' . $this->template . '.tpl';
		$exists = file_exists($_SERVER['DOCUMENT_ROOT'] . $abs);
		
		if(!$exists){
			//If it wasn't fount, load it from the default theme directory
			$abs = '/' . $this->template_dirpath . '/' . $this->default_theme . '/' . $this->template . '.tpl';
			$exists = file_exists($_SERVER['DOCUMENT_ROOT'] . $abs);
		}

		if(!$exists){
			//Template not found
			throw new Exception("Template '{$this->template}' not found!");
			return;
		}
		
		return $abs;
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
		
		//Parse template calls -> {{template PATH}}
		$cnt = preg_match_all("/\{\{template (.+?)\}\}/", $text, $templates);
		for($i=0; $i<$cnt; $i++){
			$this->setTemplate($templates[1][$i]);
			$value = $this->loadTemplate();
			$text = str_replace($templates[0][$i], $value, $text);
		}
		
		//Get absolute template path from relative path -> {{templatepath PATH}}
		$cnt = preg_match_all("/\{\{templatepath (.+?)\}\}/", $text, $paths);
		for($i=0; $i<$cnt; $i++){
			$value = $this->getTemplatePath($paths[1][$i]);
			$text = str_replace($paths[0][$i], $value, $text);
		}
		
		//Parse if-loops from inner to outer loops -> {{if VAR OP VAR2}}...{{else}}...{{endif}}
		while(strpos($text, '{{endif}}') !== false){
			//parse until there are no if-loops left
			
			//get all loops without inner loops
			$cnt = preg_match_all("/\{\{if (((?!\}).)+?)\}\}(((?!\{\{endif\}\})(?!\{\{if).)*?)\{\{endif\}\}/s", $text, $loops);
			
			for($i=0; $i<$cnt; $i++){
				//seperate the condition statements
				$condition = explode(" ", $loops[1][$i]);
				if(count($condition) < 1 || count($condition) > 3){
					throw new Exception("Parsing Error: to many parameters for if statement");	
				}
				if(!$condition[1]){
					$condition[1] = "true";
				}
				
				$ret = false;
				switch($condition[1]){
					case "==":
						//equal
						if($condition[0] == $condition[2]) $ret = true;
						break;
					case "!=":
						//not equal
						if($condition[0] != $condition[2]) $ret = true;
						break;
					case ">":
						//greater
						if($condition[0] > $condition[2]) $ret = true;
						break;
					case ">=":
						//greater or equal
						if($condition[0] >= $condition[2]) $ret = true;
						break;
					case "<":
						//smaller
						if($condition[0] > $condition[2]) $ret = true;
						break;
					case "<=":
						//smaller or equal
						if($condition[0] >= $condition[2]) $ret = true;
						break;
					case "eq":
						//numerical equal
						if((int)$condition[0] == (int)$condition[2]) $ret = true;
						break;
					case "ne":
						//numerical unequal
						if((int)$bed[0] != (int)$bed[2]) $ret = true;
						break;
					case "gt":
						//greater
						if((int)$condition[0] > (int)$condition[2]) $ret = true;
						break;
					case "ge":
						//greater or equal
						if($condition[0] >= $condition[2]) $ret = true;
						break;
					case "lt":
						//smaller
						if($condition[0] > $condition[2]) $ret = true;
						break;
					case "le":
						//smaller or equal
						if($condition[0] >= $condition[2]) $ret = true;
						break;
					case "true":
						//true
						if($condition[0]) $ret = true;
						break;
					case "false":
						//false
						if(!$condition[0]) $ret = true;
						break;
					default:
						//unknown operator
						throw new Exception("Parsing Error: unknown operator");
				}
				
				$ifelse = array();
				$replace = $loops[3][$i];
				
				//check if there is an else-loop
				if(strpos($replace, "{{else}}") !== false){
					preg_match("/^(.*?)\{\{else\}\}(.*?)$/s", $replace, $ifelse);
					if($ret === true){
						$replace = $ifelse[1];
					} else {
						$replace = $ifelse[2];
					}
				}
				
				//if without else and condition is false
				if(count($ifelse) < 2 && !$ret){
					$replace = "";
				}
				
				//finally do the replacement in the text
				$text = str_replace($loops[0][$i], $replace, $text);
			}
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