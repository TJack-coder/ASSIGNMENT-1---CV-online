<?php

namespace app\services;

use PDO;

class CVSearchService
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    /**
     * Search CVs with filters
     */
    public function search($filters, $page = 1, $perPage = 15)
    {
        $sql = "
        SELECT DISTINCT
            cvs.id,
            cvs.full_name,
            cvs.email,
            cvs.phone_number,
            countries.name AS country_name,
            cities.name AS city_name,
            categories.name AS category_name
        FROM cvs

        LEFT JOIN users ON users.id = cvs.user_id
        LEFT JOIN countries ON countries.id = cvs.countries_id
        LEFT JOIN cities ON cities.id = cvs.cities_id
        LEFT JOIN categories ON categories.id = cvs.categories_id

        LEFT JOIN cv_skills ON cv_skills.cv_id = cvs.id
        LEFT JOIN skills ON skills.id = cv_skills.skills_id

        LEFT JOIN educations ON educations.cv_id = cvs.id

        WHERE 1=1
        ";

        $params = [];

        /* ---------- Keyword search ---------- */

        if (!empty($filters['keyword'])) {

            $sql .= " AND (users.name LIKE :keyword OR cvs.full_name LIKE :keyword)";

            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }

        /* ---------- Category ---------- */

        if (!empty($filters['category_id'])) {

            $sql .= " AND cvs.categories_id = :category";

            $params[':category'] = $filters['category_id'];
        }

        /* ---------- Country ---------- */

        if (!empty($filters['country_id'])) {

            $sql .= " AND cvs.countries_id = :country";

            $params[':country'] = $filters['country_id'];
        }

        /* ---------- City ---------- */

        if (!empty($filters['city'])) {

            $sql .= " AND cities.name LIKE :city";

            $params[':city'] = '%' . $filters['city'] . '%';
        }

        /* ---------- Degree ---------- */

        if (!empty($filters['degree_level'])) {

            $sql .= " AND educations.degree_level_id = :degree";

            $params[':degree'] = $filters['degree_level'];
        }

        /* ---------- Skills ---------- */

        if (!empty($filters['skills'])) {

            $skillConditions = [];

            foreach ($filters['skills'] as $i => $skillId) {

                $param = ":skill$i";

                $skillConditions[] = $param;

                $params[$param] = $skillId;
            }

            $sql .= " AND skills.id IN (" . implode(',', $skillConditions) . ")";
        }

        /* ---------- Proficiency ---------- */

        if (!empty($filters['min_proficiency'])) {

            $sql .= " AND cv_skills.proficients_id >= :proficiency";

            $params[':proficiency'] = $filters['min_proficiency'];
        }

        /* ---------- Sorting ---------- */

        switch ($filters['sort_by'] ?? 'recent') {

            case 'alphabetical':
                $sql .= " ORDER BY cvs.full_name ASC";
                break;

            case 'experience':
                $sql .= " ORDER BY cvs.id DESC"; // placeholder
                break;

            default:
                $sql .= " ORDER BY cvs.id DESC"; // recent
        }

        /* ---------- Pagination ---------- */

        $offset = ($page - 1) * $perPage;

        $sql .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        /* bind filters */

        foreach ($params as $key => $value) {

            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get CV detail
     */
    public function getCVById($cvId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM cvs
            WHERE id = ?
        ");

        $stmt->execute([$cvId]);

        $cv = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cv) {
            return null;
        }

        /* work history */

        $stmt = $this->db->prepare("
            SELECT *
            FROM work_histories
            WHERE cv_id = ?
        ");

        $stmt->execute([$cvId]);

        $cv['workHistory'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /* education */

        $stmt = $this->db->prepare("
            SELECT *
            FROM educations
            WHERE cv_id = ?
        ");

        $stmt->execute([$cvId]);

        $cv['education'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /* skills */

        $stmt = $this->db->prepare("
            SELECT skills.name, cv_skills.proficients_id
            FROM cv_skills
            JOIN skills ON skills.id = cv_skills.skills_id
            WHERE cv_skills.cv_id = ?
        ");

        $stmt->execute([$cvId]);

        $cv['skills'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $cv;
    }
}