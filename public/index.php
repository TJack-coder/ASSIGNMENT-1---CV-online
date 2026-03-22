<?php

    require_once "../app/controllers/Admin.php";

    $uri = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];

    $admin = new AdminController();

    // ROUTES
    if ($uri === '/admin/skills' && $method === 'GET') {
        $admin->getAll();
    }

    elseif ($uri === '/admin/skills/create' && $method === 'POST') {
        $admin->create();
    }

    elseif ($uri === '/admin/skills/delete' && $method === 'POST') {
        $admin->delete();
    }

    else {
        echo "404 Not Found";
    }
