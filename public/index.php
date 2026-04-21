<?php

require_once __DIR__ . '/../app/controllers/Admin.php';

$route = $_GET['route'] ?? 'home';

switch ($route) {
    case 'admin/dashboard':
        $controller = new AdminController();
        $controller->dashboard();
        break;

    case 'admin/manage':
        $controller = new AdminController();
        $controller->manage();
        break;

    case 'admin/create':
        $controller = new AdminController();
        $controller->create();
        break;

    case 'admin/delete':
        $controller = new AdminController();
        $controller->delete();
        break;

    default:
        echo "410 Not Found";
        break;
}
