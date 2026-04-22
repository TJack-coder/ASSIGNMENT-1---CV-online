<?php

require_once __DIR__ . '/../app/controllers/Admin.php';
require_once __DIR__ . '/../config/DataConfig.php';
require_once __DIR__ . '/../app/controllers/EmployerControllers.php';
require_once __DIR__ . '/../app/services/CVSearchService.php';
require_once __DIR__ . '/../app/functions/function.php';

use config\DataConfig;
use app\controllers\EmployerController;

$database = DataConfig::getInstance()->getConnection();

$employerController = new EmployerController($database);
$adminController = new AdminController();

$route = $_GET['route'] ?? 'home';

switch ($route) {
    case 'admin/dashboard':
        $adminController->dashboard();
        break;

    case 'admin/manage':
        $adminController->manage();
        break;

    case 'admin/create':
        $adminController->create();
        break;

    case 'admin/delete':
        $adminController->delete();
        break;

    case 'employer/search':
        $employerController->showSearchForm();
        break;

    case 'employer/search/result':
        $employerController->searchCVs();
        break;

    case 'employer/cv':
        $id = $_GET['id'] ?? null;
        $employerController->viewCV($id);
        break;

    default:
        echo "404 - Page not found";
        break;
}