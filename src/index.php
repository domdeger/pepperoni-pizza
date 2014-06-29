
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
session_start();
// Installation check
if ( file_exists(BASEPATH . '/data/config.inc.php') )
{
    include_once BASEPATH . '/data/config.inc.php';
}

// Check that the config is legit and we didn't include an empty file
/* if(!defined('INSTALL_COMPLETE'))
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
{ */
//Dummy startup will propably change
if(!isset($_GET['c']) || !isset($_GET['a'])) {
    die('Invalid parameters.');
}
$controllerName = ucfirst(strtolower($_GET['c']));
$actionName = ucfirst(strtolower($_GET['a']));


$controller_dir = BASEPATH . '/controller/';
$controllerName = $controllerName . 'Controller';

$response = invokeControllerAction($controllerName,$actionName,$controller_dir);
if($response != null) {
    if($response->UseMasterPage) {
        ob_start();
        include "master.html";
        $masterPage = ob_get_clean();
        $response->ResponseContent = str_replace('<:content />',$response->ResponseContent, $masterPage);
    }

    header('Content-type: '. $response->ContentType);

    echo($response->ResponseContent);
}


//}
/*
Function which dynamically invokes the requested action of a controller.
*/
function invokeControllerAction($controllerClassName, $actionName, $controllerBaseFolder) {
    $controllerClassAbsolutePath = $controllerBaseFolder . $controllerClassName . '.php';
    if(file_exists($controllerClassAbsolutePath)) {
        require_once $controllerClassAbsolutePath;
        $classMethods = get_class_methods($controllerClassName);
        if(array_search($actionName,$classMethods) !== false) {
            $controller = new $controllerClassName();
            return $controller->$actionName();
        }
        die('Requested page not found.');
    }
}
?>


