<?php

/**
 * @NutMouse CMS
 * @author: Julian Burr
 * @lastchanged: 2014-02-11
 * @version: 0.1 
 *
 * Models/Frontend/Content
 * Model class for basic content pages, which loads the content and additional information from the database
 * and returns it to the controller
 **/

include_once($_SERVER['DOCUMENT_ROOT'] . '/core/classes/Model.php');

class Models_Frontend_Content extends Model {

	public static function getByPath($path){
		/**
		 * Get content by requestet url path
		 *
		 * Parameters
		 * path: Requested url path
		 **/
		 
		self::$entry = array(
			'info' => null,
			'meta' => null,
			'custom' => null
		);
		
		//try to load data from database
		$sql = new SqlManager();
		
		$content = array();
		$sql->setQuery("
			SELECT * FROM bd_frontend_content
			WHERE content_url = '{{path}}'
				AND ( content_type = 'content' OR content_type = '' )
				AND content_published <= NOW()
				AND content_active = 1
		");
		$sql->bindParam('{{path}}', $path);
		$content = $sql->result();
		
		if(empty($content['content_id'])){
			//no content found
			return null;
		}
		
		$sql->setQuery("
			SELECT * FROM bd_frontend_meta 
			WHERE meta_content_id = {{id}}
				AND meta_language = {{lang}}
		");
		$sql->bindParam('{{id}}', $content['content_id'], 'int');
		$sql->bindParam('{{lang}}', '1', 'int');
		$sql->execute();
		
		$meta = array('title' => '');
		while($row = $sql->fetch()){
			$meta[$row['meta_key']] = $row['meta_value'];	
		}
		
		$sql->setQuery("
			SELECT * FROM bd_frontend_element 
			WHERE element_content_id = {{id}}
				AND element_language = {{lang}}
				AND element_active = 1
			ORDER BY element_sortkey, element_id
		");
		$sql->bindParam('{{id}}', $content['content_id'], 'int');
		$sql->bindParam('{{lang}}', '1', 'int');
		$sql->execute();
		
		$text = '';
		while($row = $sql->fetch()){
			$view = new View();
			$view->setTemplate('frontend/page/element/' . $row['element_type']);
			$view->assignArray($row);
			$text .= $view->loadTemplate();
		}
		$content['content_content'] = $text;
		
		//build up array with content data
		self::$entry['info'] = $content;
		self::$entry['meta'] = $meta;
			
		return self::$entry;
	}
	
}