<?php

class AdminService {

    private $model;

    private $allowedTables = [
        'skills',
        'categories',
        'degrees',
        'majors',
        'industries',
        'employment_types',
        'certificate_name'
    ];

    public function __construct($model){
        $this->model = $model;
    }

    private function validateTable($table){
        if (!in_array($table, $this->allowedTables)) {
            throw new Exception("Invalid table");
        }
    }

    public function getAll($table){
        $this->validateTable($table);
        return $this->model->getAll($table);
    }

    public function create($table, $name){
        $this->validateTable($table);

        if (empty($name)) {
            throw new Exception("Name is required");
        }

        return $this->model->create($table, $name);
    }

    public function delete($table, $id){
        $this->validateTable($table);
        return $this->model->delete($table, $id);
    }
}