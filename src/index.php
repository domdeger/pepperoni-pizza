<?php
/**
 * @package    Pepperoni.Pizza
 *
 * @copyright  Copyright (C) 2014 Boris Bopp
 * @license    MIT License (MIT) see LICENSE
 */

// ######### DEBUG ###################
error_reporting(E_ALL);
ini_set("display_errors", 1);
//####################################

define('BASEPATH', __DIR__);

// Installation check
if ( file_exists(BASEPATH . '/data/config.inc.php') )
{
	include_once BASEPATH . '/data/config.inc.php';
}

// Check that the config is legit and we didn't include an empty file
if(!defined('INSTALL_COMPLETE'))	
{
	if (file_exists(BASEPATH . '/install/index.php'))
	{
		header('Location: ' . substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], 'index.php')) . 'install/index.php');
		exit;
	}
	else
	{
		die('Invalid configuration and missing installer.');
	}
}
// Run the website
else
{
	//Dummy startup will propably change
	require_once BASEPATH . '/controller/ShoutBoxController.php';

	$controller = new ShoutBoxController();
	$controller->Index();
}

?>
