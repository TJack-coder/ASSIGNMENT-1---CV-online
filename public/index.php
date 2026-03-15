<?php

require_once __DIR__ . '/../config/DataConfig.php';
require_once __DIR__ . '/../app/controllers/EmployerControllers.php';
require_once __DIR__ . '/../app/services/CVSearchService.php';
require_once __DIR__ . '/../app/functions/function.php';
use config\DataConfig;
use app\controllers\EmployerController;

$database = DataConfig::getInstance()->getConnection();

$controller = new EmployerController($database);

$route = $_GET['route'] ?? 'employer/search';

switch ($route) {

    case 'employer/search':
        $controller->showSearchForm();
        break;

    case 'employer/search/result':
        $controller->searchCVs();
        break;

    case 'employer/cv':
        $id = $_GET['id'] ?? null;
        $controller->viewCV($id);
        break;

    default:
        echo "404 - Page not found";
}