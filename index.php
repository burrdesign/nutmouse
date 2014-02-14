<?php

/**
 * @NutMouse CMS
 * @author: Julian Burr
 * @lastchanged: 2014-02-11
 * @version: 0.1 
 **/

session_start();

//Include core class and create instance
include_once($_SERVER['DOCUMENT_ROOT'] . "/core/classes/BurrDesignCMS.php");
$nutmouse = new BurrDesignCMS();

