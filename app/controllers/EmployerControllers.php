<?php

namespace app\controllers;

use app\services\CVSearchService;
use app\model\CV;

use config\DataConfig;
use PDO;

class EmployerController
{
    private $cvSearchService;
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
        $this->cvSearchService = new CVSearchService($database);
    }


    /**
     * Display search form with filter options
     * GET /employer/search
     */
    public function showSearchForm()
    {
        $categories = $this->getAllCategories();
        $countries = $this->getAllCountries();
        $skills = $this->getAllSkills();
        $degrees = $this->getAllDegrees();

        view('employer/search', [
            'categories' => $categories,
            'countries' => $countries,
            'skills' => $skills,
            'degrees' => $degrees
        ]);
    }
    /**
     * Execute CV search with filters and sorting
     * POST /employer/search
     */
    public function searchCVs()
    {

        $filters = [
            'keyword' => trim($_POST['keyword'] ?? ''),
            'category_id' => $_POST['category_id'] ?? null,
            'country_id' => $_POST['country_id'] ?? null,
            'city' => trim($_POST['city'] ?? ''),
            'skills' => $_POST['skills'] ?? [],
            'min_proficiency' => intval($_POST['min_proficiency'] ?? 1),
            'degree_level' => $_POST['degree_level'] ?? null,
            'sort_by' => $_POST['sort_by'] ?? 'recent'
        ];

        $page = intval($_GET['page'] ?? 1);
        $perPage = 15;

        $data = $this->cvSearchService->search($filters, $page, $perPage);

        return view('employer/result', [
            'cvs' => $data,
            'filters' => $filters,
        ]);
    }
    /**
     * Display single CV in selected template
     * GET /employer/cv/{id}?template=modern
     */
    public function viewCV($cvId)
    {
        $template = $_GET['template'] ?? 'modern';
        // Validate template
        $validTemplates = ['modern', 'classic', 'minimal'];
        if (!in_array($template, $validTemplates)) {
            $template = 'modern';
        }

        // Fetch CV data
        $cv = $this->cvSearchService->getCVById($cvId);

        if (!$cv) {
            return view('errors/404', ['message' => 'CV not found']);
        }

        // Calculate total experience
        $cv['total_experience'] = $this->calculateExperience($cv['workHistory']);

        // Return template-specific view
        return view("cv_templates/{$template}", [
            'cv' => $cv,
            'template' => $template
        ]);
    }

    private function calculateExperience($workHistory)
    {
        $totalYears = 0;
        foreach ($workHistory as $job) {
            $end = $job['end_year'] ?? date('Y');
            $totalYears += $end - $job['start_year'];
        }
        return $totalYears;
    }

    private function getAllCategories()
    {
        $stmt = $this->db->prepare("SELECT id, name FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getAllCountries()
    {
        $stmt = $this->db->prepare("SELECT id, name FROM countries ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getAllSkills()
    {
        $stmt = $this->db->prepare("SELECT id, name FROM skills ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getAllDegrees()
    {
        $stmt = $this->db->prepare("SELECT id, name FROM degrees ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>