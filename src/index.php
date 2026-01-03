<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use lib\core\TmhDomain;
use lib\core\TmhEntityController;
use lib\core\TmhJson;
use lib\core\TmhLocale;
use lib\core\TmhRoute;
use lib\core\TmhRouteController;

require_once('lib/core/TmhDomain.php');
require_once('lib/core/TmhEntityController.php');
require_once('lib/core/TmhJson.php');
require_once('lib/core/TmhLocale.php');
require_once('lib/core/TmhRoute.php');
require_once('lib/core/TmhRouteController.php');

$json = new TmhJson();
$domain = new TmhDomain($json);
$locale = new TmhLocale($domain, $json);
$route = new TmhRoute($json);
$routeController = new TmhRouteController($domain, $locale, $route);
$entityController = new TmhEntityController($json, $routeController);
$entity = $entityController->find();
//echo "<pre>";
//print_r($entity);
//echo "</pre>";
