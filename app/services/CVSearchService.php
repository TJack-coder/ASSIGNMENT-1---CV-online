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
    
        LEFT JOIN educations e ON e.cv_id = cvs.id
        LEFT JOIN work_histories wh ON wh.cv_id = cvs.id
        LEFT JOIN certificates cert ON cert.cv_id = cvs.id
    
        WHERE 1=1
        ";
    
        $params = [];
    
        /* ---------- Keyword search ---------- */
    
        if (!empty($filters['keyword'])) {
            $sql .= "
                AND (
                    cvs.full_name LIKE :keyword
                    OR cvs.email LIKE :keyword
                    OR cvs.phone_number LIKE :keyword
                    OR cvs.address LIKE :keyword
                    OR cities.name LIKE :keyword
                    OR countries.name LIKE :keyword
                    OR wh.company_name LIKE :keyword
                    OR wh.description LIKE :keyword
                    OR e.description LIKE :keyword
                    OR cert.description LIKE :keyword
                )
            ";
    
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }
    
        /* ---------- Category ---------- */
    
        if (!empty($filters['category_id'])) {
            $sql .= " AND cvs.categories_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }
    
        /* ---------- Country ---------- */
    
        if (!empty($filters['country_id'])) {
            $sql .= " AND cvs.countries_id = :country_id";
            $params[':country_id'] = $filters['country_id'];
        }
    
        /* ---------- City ---------- */
    
        if (!empty($filters['city'])) {
            $sql .= " AND cities.name LIKE :city";
            $params[':city'] = '%' . $filters['city'] . '%';
        }
    
        /* ---------- Degree ---------- */
    
        if (!empty($filters['degree_level'])) {
            $sql .= " AND e.degree_level_id = :degree_level";
            $params[':degree_level'] = $filters['degree_level'];
        }
    
        /* ---------- Skills ---------- */
    
        if (!empty($filters['skills'])) {
            $skillPlaceholders = [];
    
            foreach ($filters['skills'] as $i => $skillId) {
                $param = ":skill_$i";
                $skillPlaceholders[] = $param;
                $params[$param] = $skillId;
            }
    
            $sql .= " AND skills.id IN (" . implode(',', $skillPlaceholders) . ")";
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
                $sql .= " ORDER BY cvs.id DESC"; // bạn có thể cải tiến sau
                break;
            
            default:
                $sql .= " ORDER BY cvs.id DESC";
        }
            
        /* ---------- Pagination ---------- */
            
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT :limit OFFSET :offset";
            
        $stmt = $this->db->prepare($sql);
            
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
            SELECT 
            c.id,
            c.user_id,
            c.full_name,
            c.birthday,
            c.gender,
            c.email,
            c.phone_number,
            c.address,
            c.postal_code,
            cat.name AS category_name,
            co.name AS country_name,
            ci.name AS city_name,
            d.name AS district_name
            FROM cvs c
            INNER JOIN categories cat ON c.categories_id = cat.id
            INNER JOIN countries co ON c.countries_id = co.id
            INNER JOIN cities ci ON c.cities_id = ci.id
            LEFT JOIN district d ON c.district_id = d.id
            WHERE c.id = ?;
        ");

        $stmt->execute([$cvId]);

        $cv = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cv) {
            return null;
        }

        /* work history */
        $stmt = $this->db->prepare("
            SELECT
            w.id,
            w.cv_id,
            jt.name AS job_title_name,
            et.name AS employment_type_name,
            ind.name AS industry_name,
            w.company_name,
            w.start_year,
            w.end_year,
            w.description
            FROM work_histories w
            INNER JOIN job_title jt ON w.job_title_id = jt.id
            INNER JOIN employment_types et ON w.employment_types_id = et.id
            INNER JOIN industries ind ON w.industries_id = ind.id
            WHERE w.cv_id = ?
            ORDER BY w.start_year DESC, w.id DESC;
        ");
        $stmt->execute([$cvId]);
        $cv['workHistory'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /* education */
        $stmt = $this->db->prepare("
            SELECT
            e.id,
            e.cv_id,
            i.name AS institution_name,
            deg.name AS degree_name,
            m.name AS major_name,
            e.start_year,
            e.end_year,
            e.description
            FROM educations e
            INNER JOIN institutions i ON e.institution_id = i.id
            INNER JOIN degrees deg ON e.degree_level_id = deg.id
            INNER JOIN majors m ON e.major_id = m.id
            WHERE e.cv_id = ?
            ORDER BY e.start_year DESC, e.id DESC;
        ");

        $stmt->execute([$cvId]);
        $cv['education'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /* skills */
        $stmt = $this->db->prepare("
            SELECT
            cs.id,
            cs.cv_id,
            s.name AS skill_name,
            p.name AS proficiency_name
            FROM cv_skills cs
            INNER JOIN skills s ON cs.skills_id = s.id
            INNER JOIN proficients p ON cs.proficients_id = p.id
            WHERE cs.cv_id = ?
            ORDER BY cs.id ASC;
        ");
        $stmt->execute([$cvId]);
        $cv['skills'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /*certificates*/
        $stmt = $this->db->prepare("
            SELECT *
            FROM certificates
            WHERE cv_id = ?
        ");
        $stmt->execute([$cvId]);
        $cv['certificates'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /* category */
        $stmt = $this->db->prepare("
            SELECT
            c.id,
            c.cv_id,
            cn.name AS certificate_name,
            o.name AS organization_name,
            c.year_issued,
            c.description
            FROM certificates c
            INNER JOIN certificate_name cn ON c.certificate_name_id = cn.id
            INNER JOIN organizations o ON c.organizations_id = o.id
            WHERE c.cv_id = ?
            ORDER BY c.year_issued DESC, c.id DESC;
        ");
        $stmt->execute([$cvId]);
        $cv['category_name'] = $stmt->fetchColumn();

        return $cv;
    }
}