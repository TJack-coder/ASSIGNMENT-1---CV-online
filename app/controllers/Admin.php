<?php

require_once __DIR__ . '/../services/AdminService.php';
require_once __DIR__ . '/../middleware/adminAuth.php';

class AdminController {
    private $service;

    public function __construct() {
        $this->service = new AdminService();
    }

    public function dashboard() {
        // AdminMiddleware::handle();

        $currentPage = 'dashboard';
        $currentTable = '';
        $pageTitle = 'Admin Dashboard';

        $allowedTables = $this->service->getAllowedTables();
        $tableCounts = [];

        foreach ($allowedTables as $table) {
            $tableCounts[$table] = count($this->service->getAll($table));
        }

        require __DIR__ . '/../../resources/admin/dashboard.php';
    }

    public function manage() {
        // AdminMiddleware::handle();

        $table = $_GET['table'] ?? 'categories';
        $items = $this->service->getAll($table);

        $currentPage = 'manage';
        $currentTable = $table;
        $pageTitle = 'Manage ' . ucwords(str_replace('_', ' ', $table));

        require __DIR__ . '/../../resources/admin/manage.php';
    }

    public function create() {
        // AdminMiddleware::handle();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Method Not Allowed';
            exit;
        }

        $table = $_POST['table'] ?? '';
        $name = $_POST['name'] ?? '';

        try {
            $this->service->create($table, $name);
            header('Location: /ASSIGNMENT-1---CV-online/public/index.php?route=admin/manage&table=' . urlencode($table) . '&success=created');
            exit;
        } catch (Exception $e) {
            header('Location: /ASSIGNMENT-1---CV-online/public/index.php?route=admin/manage&table=' . urlencode($table) . '&error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function delete() {
        // AdminMiddleware::handle();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Method Not Allowed';
            exit;
        }

        $table = $_POST['table'] ?? '';
        $id = $_POST['id'] ?? '';

        try {
            $this->service->delete($table, $id);
            header('Location: /ASSIGNMENT-1---CV-online/public/index.php?route=admin/manage&table=' . urlencode($table) . '&success=deleted');
            exit;
        } catch (Exception $e) {
            header('Location: /ASSIGNMENT-1---CV-online/public/index.php?route=admin/manage&table=' . urlencode($table) . '&error=' . urlencode($e->getMessage()));
            exit;
        }
    }
}