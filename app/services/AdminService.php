<?php

require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../../config/DataConfig.php';

use config\DataConfig;

class AdminService {
    private $model;

    private $allowedTables = [
        'categories',
        'certificate_name',
        'countries',
        'degrees',
        'employment_types',
        'industries',
        'institutions',
        'job_title',
        'majors',
        'organizations',
        'proficients',
        'skills'
    ];

    public function __construct() {
        $db = DataConfig::getInstance()->getConnection();
        $this->model = new AdminModel($db);
    }

    public function getAllowedTables() {
        return $this->allowedTables;
    }

    private function validateTable($table) {
        if (!in_array($table, $this->allowedTables, true)) {
            throw new Exception('Invalid table');
        }
    }

    private function validateName($name) {
        $name = trim($name);

        if ($name === '') {
            throw new Exception('Name is required');
        }

        return $name;
    }

    private function validateId($id) {
        if (!is_numeric($id) || (int)$id <= 0) {
            throw new Exception('Invalid id');
        }

        return (int)$id;
    }

    public function getAll($table) {
        $this->validateTable($table);
        return $this->model->getAll($table);
    }

    public function create($table, $name) {
        $this->validateTable($table);
        $name = $this->validateName($name);
        return $this->model->create($table, $name);
    }

    public function delete($table, $id) {
        $this->validateTable($table);
        $id = $this->validateId($id);
        return $this->model->delete($table, $id);
    }
}