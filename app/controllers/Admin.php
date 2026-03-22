<?php

require_once "../app/models/AdminModel.php";
require_once "../app/services/AdminService.php";
require_once "../config/DataConfig.php";
require_once "../app/middleware/AdminMiddleware.php";

class AdminController {

    private $service;

    public function __construct(){
        $db = DataConfig::getInstance()->getConnection();

        $model = new AdminModel($db);
        $this->service = new AdminService($model);
    }

    // GET /admin?table=skills
    public function getAll(){
        AdminMiddleware::handle();

        $table = $_GET['table'] ?? 'skills';

        try {
            $data = $this->service->getAll($table);

            echo json_encode([
                "status" => "success",
                "data" => $data
            ]);

        } catch (Exception $e){
            http_response_code(400);
            echo json_encode([
                "error" => $e->getMessage()
            ]);
        }
    }

    // POST /admin/create
    public function create(){
        AdminMiddleware::handle();

        $table = $_POST['table'] ?? '';
        $name = $_POST['name'] ?? '';

        try {
            $this->service->create($table, $name);

            echo json_encode(["status" => "created"]);

        } catch (Exception $e){
            http_response_code(400);
            echo json_encode([
                "error" => $e->getMessage()
            ]);
        }
    }

    // POST /admin/delete
    public function delete(){
        AdminMiddleware::handle();

        $table = $_POST['table'] ?? '';
        $id = $_POST['id'] ?? '';

        try {
            $this->service->delete($table, $id);

            echo json_encode(["status" => "deleted"]);

        } catch (Exception $e){
            http_response_code(400);
            echo json_encode([
                "error" => $e->getMessage()
            ]);
        }
    }
}