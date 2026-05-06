<?php
class CvController
{
    private $db;
    private $root;
    private $columnCache = [];
    private $tableCache = [];

    public function __construct($database = null, $rootPath = null)
    {
        $this->db = $database;
        $this->root = $rootPath ?: dirname(__DIR__, 3);
    }

    public function index() { $this->create(); }
    public function edit() { $this->create(); }
    public function update() { $this->store(); }

    public function create()
    {
        $this->requireRole(['job_seeker', 'jobseeker', 'seeker']);

        $userId = $this->currentUserId();
        $cv = $this->findCvByUserId($userId);
        $cvId = $this->cvId($cv);

        $data = $this->buildCvData($cv, $cvId);
        $data['old'] = $data['old'] ?? [];

        $this->render('seeker.cv.form', $data, function () use ($data) {
            $this->fallbackForm($data);
        });
    }

    public function store()
    {
        $this->requireRole(['job_seeker', 'jobseeker', 'seeker']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('seeker/cv/create');
        }

        $errors = $this->validateCvRequest();
        if (!empty($errors)) {
            $data = $this->buildCvData($this->findCvByUserId($this->currentUserId()), null);
            $data['errors'] = $errors;
            $data['old'] = $_POST;
            $data['educations'] = $this->educationRows();
            $data['workHistories'] = $this->workHistoryRows();
            $data['certificatesOfCv'] = $this->certificateRows();
            $data['skillsOfCv'] = $this->skillRows();
            $this->render('seeker.cv.form', $data, function () use ($data) {
                $this->fallbackForm($data);
            });
            return;
        }

        try {
            $cvId = $this->saveCvWithRelations();
            $_SESSION['flash_success'] = 'CV saved successfully.';
            $this->redirect('seeker/cv/view', '&id=' . urlencode((string)$cvId) . '&template=modern');
        } catch (Exception $e) {
            $data = $this->buildCvData($this->findCvByUserId($this->currentUserId()), null);
            $data['errors'] = ['Cannot save CV: ' . $e->getMessage()];
            $data['old'] = $_POST;
            $data['educations'] = $this->educationRows();
            $data['workHistories'] = $this->workHistoryRows();
            $data['certificatesOfCv'] = $this->certificateRows();
            $data['skillsOfCv'] = $this->skillRows();
            $this->render('seeker.cv.form', $data, function () use ($data) {
                $this->fallbackForm($data);
            });
        }
    }

    public function view($id = null)
    {
        $this->requireLogin();

        $id = $id ?: ($_GET['id'] ?? null);
        $cv = $id ? $this->findCvById($id) : $this->findCvByUserId($this->currentUserId());

        if (!$cv) {
            $_SESSION['flash_error'] = 'Please create your CV first.';
            $this->redirect('seeker/cv/create');
        }

        $template = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)($_GET['template'] ?? 'modern'));
        if ($template === 'morden') $template = 'modern';
        if (!in_array($template, ['classic', 'minimal', 'modern'], true)) $template = 'modern';

        $data = $this->buildCvData($cv, $this->cvId($cv));
        $data['template'] = $template;

        $this->render('seeker.cv.' . $template, $data, function () use ($data) {
            $this->fallbackCvView($data);
        });
    }

    public function templates($id = null)
    {
        $this->requireLogin();
        $id = $id ?: ($_GET['id'] ?? null);
        $cv = $id ? $this->findCvById($id) : $this->findCvByUserId($this->currentUserId());
        if (!$cv) {
            $_SESSION['flash_error'] = 'Please create your CV first.';
            $this->redirect('seeker/cv/create');
        }
        $data = $this->buildCvData($cv, $this->cvId($cv));
        $data['templates'] = ['classic', 'minimal', 'modern'];
        $this->render('seeker.cv.templates', $data, function () use ($data) {
            $this->fallbackTemplateChooser($data);
        });
    }

    private function buildCvData($cv = null, $cvId = null)
    {
        $refs = $this->referenceData();
        if (!$cvId && $cv) $cvId = $this->cvId($cv);
        $cv = $this->decorateCv($cv ?: [], $refs);
        return array_merge($refs, [
            'cv' => $cv,
            'cvId' => $cvId,
            'lookups' => $this->lookupsForJs($refs),
            'educations' => $this->decorateRows($this->getChildren(['cv_educations', 'educations'], $cvId), 'education', $refs),
            'workHistories' => $this->decorateRows($this->getChildren(['cv_work_histories', 'work_histories'], $cvId), 'work', $refs),
            'certificatesOfCv' => $this->decorateRows($this->getChildren(['cv_certificates', 'certificates'], $cvId), 'certificate', $refs),
            'skillsOfCv' => $this->decorateRows($this->getChildren(['cv_skills'], $cvId), 'skill', $refs),
            'errors' => [],
            'old' => [],
        ]);
    }

    private function validateCvRequest()
    {
        $errors = [];
        if (trim($_POST['full_name'] ?? '') === '') $errors[] = 'Full name is required.';
        if (trim($_POST['email'] ?? '') === '') $errors[] = 'Email is required.';
        if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email format is invalid.';
        $skills = array_values(array_filter($this->skillRows(), function ($r) { return !empty($r['skill_id']) || !empty($r['skills_id']); }));
        if (count($skills) > 5) $errors[] = 'A job seeker can define a maximum of 5 strongest skills.';
        return $errors;
    }

    private function saveCvWithRelations()
    {
        if (!$this->db) throw new RuntimeException('Database connection is missing.');
        if (!$this->tableExists('cvs')) throw new RuntimeException('Table cvs does not exist.');

        $this->beginTransaction();
        try {
            $userId = $this->currentUserId();
            if (!$userId) throw new RuntimeException('Cannot identify logged-in user.');

            $categoryId = $this->validLookupId(['categories', 'cv_categories'], $this->postFirst(['cv_category_id','category_id','categories_id']));
            $countryId  = $this->validLookupId(['countries'], $this->postFirst(['country_id','countries_id']));
            $cityId     = $this->validLookupId(['cities','provinces'], $this->postFirst(['city_id','cities_id','province_id']));
            $districtId = $this->validLookupId(['district','districts'], $this->postFirst(['district_id','districts_id']));

            $phone = trim((string)$this->postFirst(['phone_number','phone']));
            $mainData = [
                'user_id' => $userId,
                'users_id' => $userId,
                'job_seeker_id' => $userId,
                'job_seekers_id' => $userId,
                'cv_category_id' => $categoryId,
                'category_id' => $categoryId,
                'categories_id' => $categoryId,
                'full_name' => trim($_POST['full_name'] ?? ''),
                'name' => trim($_POST['full_name'] ?? ''),
                'date_of_birth' => $this->emptyToNull($_POST['date_of_birth'] ?? null),
                'birthday' => $this->emptyToNull($_POST['date_of_birth'] ?? ($_POST['birthday'] ?? null)),
                'dob' => $this->emptyToNull($_POST['date_of_birth'] ?? ($_POST['birthday'] ?? null)),
                'gender' => $this->emptyToNull($_POST['gender'] ?? null),
                'email' => trim($_POST['email'] ?? ''),
                'phone_number' => $phone,
                'phone' => $phone,
                'country_id' => $countryId,
                'countries_id' => $countryId,
                'city_id' => $cityId,
                'cities_id' => $cityId,
                'province_id' => $cityId,
                'provinces_id' => $cityId,
                'district_id' => $districtId,
                'districts_id' => $districtId,
                'street_address' => trim($_POST['street_address'] ?? ''),
                'address' => trim($_POST['street_address'] ?? ''),
                'postal_code' => trim($_POST['postal_code'] ?? ''),
                'summary' => trim($_POST['summary'] ?? ''),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $existing = $this->findCvByUserId($userId);
            if ($existing) {
                $cvId = $this->cvId($existing);
                $this->updateRow('cvs', $mainData, [$this->primaryKeyName('cvs') => $cvId]);
            } else {
                $mainData['created_at'] = date('Y-m-d H:i:s');
                $cvId = $this->insertRow('cvs', $mainData);
            }
            if (!$cvId) {
                $saved = $this->findCvByUserId($userId);
                $cvId = $this->cvId($saved);
            }
            if (!$cvId) throw new RuntimeException('Cannot determine saved CV id.');

            $this->replaceChildren(['cv_educations', 'educations'], $cvId, $this->educationRows());
            $this->replaceChildren(['cv_work_histories', 'work_histories'], $cvId, $this->workHistoryRows());
            $this->replaceChildren(['cv_certificates', 'certificates'], $cvId, $this->certificateRows());
            $this->replaceChildren(['cv_skills'], $cvId, $this->skillRows());

            $this->commit();
            return $cvId;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    private function educationRows()
    {
        $rows = $this->rowsFromRequest('educations', ['institution_id','institutions_id','degree_level_id','degree_id','degrees_id','major_id','majors_id','start_year','end_year','description']);
        $out = [];
        foreach ($rows as $row) {
            $institutionId = $this->validLookupId(['institutions'], $row['institution_id'] ?? ($row['institutions_id'] ?? null));
            $degreeId = $this->validLookupId(['degrees','degree_levels'], $row['degree_level_id'] ?? ($row['degree_id'] ?? ($row['degrees_id'] ?? null)));
            $majorId = $this->validLookupId(['majors'], $row['major_id'] ?? ($row['majors_id'] ?? null));
            if (!$institutionId && !$degreeId && !$majorId && empty($row['description'])) continue;
            $out[] = [
                'institution_id' => $institutionId, 'institutions_id' => $institutionId,
                'degree_level_id' => $degreeId, 'degree_id' => $degreeId, 'degrees_id' => $degreeId,
                'major_id' => $majorId, 'majors_id' => $majorId,
                'start_year' => $this->emptyToNull($row['start_year'] ?? null),
                'end_year' => $this->emptyToNull($row['end_year'] ?? null),
                'description' => $this->emptyToNull($row['description'] ?? null),
            ];
        }
        return $out;
    }

    private function workHistoryRows()
    {
        $rows = $this->rowsFromRequest('work_histories', ['company_name','job_title_id','job_titles_id','employment_type_id','employment_types_id','industry_id','industries_id','start_year','end_year','is_present','job_description','description']);
        $out = [];
        foreach ($rows as $row) {
            $jobTitleId = $this->validLookupId(['job_title','job_titles'], $row['job_title_id'] ?? ($row['job_titles_id'] ?? null));
            $typeId = $this->validLookupId(['employment_types'], $row['employment_type_id'] ?? ($row['employment_types_id'] ?? null));
            $industryId = $this->validLookupId(['industries'], $row['industry_id'] ?? ($row['industries_id'] ?? null));
            $company = trim((string)($row['company_name'] ?? ''));
            if ($company === '' && !$jobTitleId && !$typeId && !$industryId) continue;
            $desc = $row['job_description'] ?? ($row['description'] ?? null);
            $out[] = [
                'company_name' => $company,
                'job_title_id' => $jobTitleId, 'job_titles_id' => $jobTitleId,
                'employment_type_id' => $typeId, 'employment_types_id' => $typeId,
                'industry_id' => $industryId, 'industries_id' => $industryId,
                'start_year' => $this->emptyToNull($row['start_year'] ?? null),
                'end_year' => $this->yearOrNull($row['end_year'] ?? null),
                'is_present' => (!empty($row['is_present']) || strtolower(trim((string)($row['end_year'] ?? ''))) === 'present') ? 1 : 0,
                'job_description' => $this->emptyToNull($desc),
                'description' => $this->emptyToNull($desc),
            ];
        }
        return $out;
    }

    private function certificateRows()
    {
        $rows = $this->rowsFromRequest('certificates', ['certificate_name_id','certificate_names_id','certificate_id','organization_id','organizations_id','issuing_organization_id','year_issued','description']);
        $out = [];
        foreach ($rows as $row) {
            $certId = $this->validLookupId(['certificate_name','certificate_names'], $row['certificate_name_id'] ?? ($row['certificate_names_id'] ?? ($row['certificate_id'] ?? null)));
            $orgId = $this->validLookupId(['organizations','issuing_organizations'], $row['organization_id'] ?? ($row['organizations_id'] ?? ($row['issuing_organization_id'] ?? null)));
            if (!$certId && !$orgId && empty($row['description'])) continue;
            $out[] = [
                'certificate_name_id' => $certId, 'certificate_names_id' => $certId, 'certificate_id' => $certId,
                'organization_id' => $orgId, 'organizations_id' => $orgId, 'issuing_organization_id' => $orgId,
                'year_issued' => $this->emptyToNull($row['year_issued'] ?? null),
                'description' => $this->emptyToNull($row['description'] ?? null),
            ];
        }
        return $out;
    }

    private function skillRows()
    {
        $rows = $this->rowsFromRequest('skills', ['skill_id','skills_id','proficiency_level_id','proficiency_id','proficients_id']);
        $out = [];
        foreach ($rows as $row) {
            $skillId = $this->validLookupId(['skills'], $row['skill_id'] ?? ($row['skills_id'] ?? null));
            if (!$skillId) continue;
            $profId = $this->validLookupId(['proficients','proficiency_levels','proficiencies'], $row['proficiency_level_id'] ?? ($row['proficiency_id'] ?? ($row['proficients_id'] ?? null)));
            $out[] = [
                'skill_id' => $skillId, 'skills_id' => $skillId,
                'proficiency_level_id' => $profId, 'proficiency_id' => $profId, 'proficients_id' => $profId,
            ];
        }
        return array_slice($out, 0, 5);
    }

    private function rowsFromRequest($group, array $fields)
    {
        $result = [];
        $raw = $_POST[$group] ?? null;
        if (is_array($raw)) {
            foreach ($raw as $item) {
                if (!is_array($item)) continue;
                $row = [];
                foreach ($fields as $field) if (array_key_exists($field, $item)) $row[$field] = $this->normalizeValue($item[$field]);
                $result[] = $row;
            }
            return $result;
        }
        $max = 0;
        foreach ($fields as $field) if (isset($_POST[$field]) && is_array($_POST[$field])) $max = max($max, count($_POST[$field]));
        for ($i = 0; $i < $max; $i++) {
            $row = [];
            foreach ($fields as $field) if (isset($_POST[$field]) && is_array($_POST[$field])) $row[$field] = $this->normalizeValue($_POST[$field][$i] ?? null);
            $result[] = $row;
        }
        return $result;
    }

    private function replaceChildren(array $tables, $cvId, array $rows)
    {
        $table = $this->firstExistingTable($tables);
        if (!$table || !$cvId) return;
        $cvCol = $this->firstExistingColumn($table, ['cv_id','cvs_id']);
        if (!$cvCol) return;
        $this->execute('DELETE FROM `' . $this->cleanName($table) . '` WHERE `' . $this->cleanName($cvCol) . '` = ?', [$cvId]);
        foreach ($rows as $row) {
            $row[$cvCol] = $cvId;
            $row['created_at'] = date('Y-m-d H:i:s');
            $row['updated_at'] = date('Y-m-d H:i:s');
            $this->insertRow($table, $row);
        }
    }

    private function getChildren(array $tables, $cvId)
    {
        $table = $this->firstExistingTable($tables);
        if (!$table || !$cvId) return [];
        $cvCol = $this->firstExistingColumn($table, ['cv_id','cvs_id']);
        if (!$cvCol) return [];
        return $this->fetchAll('SELECT * FROM `' . $this->cleanName($table) . '` WHERE `' . $this->cleanName($cvCol) . '` = ?', [$cvId]);
    }

    private function referenceData()
    {
        return [
            'categories' => $this->readLookup(['categories','cv_categories']),
            'countries' => $this->readLookup(['countries']),
            'cities' => $this->readLookup(['cities','provinces']),
            'districts' => $this->readLookup(['district','districts']),
            'degrees' => $this->readLookup(['degrees','degree_levels']),
            'majors' => $this->readLookup(['majors']),
            'institutions' => $this->readLookup(['institutions']),
            'jobTitles' => $this->readLookup(['job_title','job_titles']),
            'employmentTypes' => $this->readLookup(['employment_types']),
            'industries' => $this->readLookup(['industries']),
            'skillsList' => $this->readLookup(['skills']),
            'proficiencies' => $this->readLookup(['proficients','proficiency_levels','proficiencies']),
            'certificates' => $this->readLookup(['certificate_name','certificate_names']),
            'organizations' => $this->readLookup(['organizations','issuing_organizations']),
        ];
    }

    private function readLookup(array $tables)
    {
        $table = $this->firstExistingTable($tables);
        if (!$table) return [];
        $nameCol = $this->firstExistingColumn($table, ['name','title','level_name','category_name','skill_name','city_name','country_name','district_name','major_name','industry_name']) ?: $this->primaryKeyName($table);
        try {
            return $this->fetchAll('SELECT * FROM `' . $this->cleanName($table) . '` ORDER BY `' . $this->cleanName($nameCol) . '` LIMIT 1000');
        } catch (Exception $e) {
            return $this->fetchAll('SELECT * FROM `' . $this->cleanName($table) . '` LIMIT 1000');
        }
    }

    private function lookupsForJs($refs)
    {
        return [
            'categories' => $this->simpleLookup($refs['categories'] ?? []),
            'countries' => $this->simpleLookup($refs['countries'] ?? []),
            'cities' => $this->simpleLookup($refs['cities'] ?? []),
            'districts' => $this->simpleLookup($refs['districts'] ?? []),
            'degrees' => $this->simpleLookup($refs['degrees'] ?? []),
            'majors' => $this->simpleLookup($refs['majors'] ?? []),
            'institutions' => $this->simpleLookup($refs['institutions'] ?? []),
            'jobTitles' => $this->simpleLookup($refs['jobTitles'] ?? []),
            'employmentTypes' => $this->simpleLookup($refs['employmentTypes'] ?? []),
            'industries' => $this->simpleLookup($refs['industries'] ?? []),
            'skills' => $this->simpleLookup($refs['skillsList'] ?? []),
            'proficiencies' => $this->simpleLookup($refs['proficiencies'] ?? []),
            'certificates' => $this->simpleLookup($refs['certificates'] ?? []),
            'organizations' => $this->simpleLookup($refs['organizations'] ?? []),
        ];
    }

    private function simpleLookup($rows)
    {
        return array_values(array_map(function ($row) {
            return ['id' => $this->rowId($row), 'name' => $this->rowName($row)];
        }, is_array($rows) ? $rows : []));
    }

    private function decorateCv(array $cv, array $refs)
    {
        if (!$cv) return [];
        $cv['category_name'] = $this->lookupName($refs['categories'] ?? [], $this->pick($cv, ['cv_category_id','category_id','categories_id']));
        $cv['country_name'] = $this->lookupName($refs['countries'] ?? [], $this->pick($cv, ['country_id','countries_id']));
        $cv['city_name'] = $this->lookupName($refs['cities'] ?? [], $this->pick($cv, ['city_id','cities_id','province_id','provinces_id']));
        $cv['district_name'] = $this->lookupName($refs['districts'] ?? [], $this->pick($cv, ['district_id','districts_id']));
        return $cv;
    }

    private function decorateRows(array $rows, $type, array $refs)
    {
        foreach ($rows as &$row) {
            if ($type === 'education') {
                $row['institution_name'] = $this->lookupName($refs['institutions'] ?? [], $this->pick($row, ['institution_id','institutions_id']));
                $row['degree_name'] = $this->lookupName($refs['degrees'] ?? [], $this->pick($row, ['degree_level_id','degree_id','degrees_id']));
                $row['major_name'] = $this->lookupName($refs['majors'] ?? [], $this->pick($row, ['major_id','majors_id']));
            } elseif ($type === 'work') {
                $row['job_title_name'] = $this->lookupName($refs['jobTitles'] ?? [], $this->pick($row, ['job_title_id','job_titles_id']));
                $row['employment_type_name'] = $this->lookupName($refs['employmentTypes'] ?? [], $this->pick($row, ['employment_type_id','employment_types_id']));
                $row['industry_name'] = $this->lookupName($refs['industries'] ?? [], $this->pick($row, ['industry_id','industries_id']));
            } elseif ($type === 'certificate') {
                $row['certificate_name'] = $this->lookupName($refs['certificates'] ?? [], $this->pick($row, ['certificate_name_id','certificate_names_id','certificate_id']));
                $row['organization_name'] = $this->lookupName($refs['organizations'] ?? [], $this->pick($row, ['organization_id','organizations_id','issuing_organization_id']));
            } elseif ($type === 'skill') {
                $row['skill_name'] = $this->lookupName($refs['skillsList'] ?? [], $this->pick($row, ['skill_id','skills_id']));
                $row['proficiency_name'] = $this->lookupName($refs['proficiencies'] ?? [], $this->pick($row, ['proficiency_level_id','proficiency_id','proficients_id']));
            }
        }
        return $rows;
    }

    private function lookupName($rows, $id)
    {
        if ($id === null || $id === '') return '';
        foreach ($rows as $row) {
            if ((string)$this->rowId($row) === (string)$id) return $this->rowName($row);
        }
        return '';
    }

    private function rowId($row)
    {
        // Support all primary-key styles used by the lookup tables in this project.
        return $row['id']
            ?? $row['cv_id']
            ?? $row['user_id']
            ?? $row['category_id']
            ?? $row['categories_id']
            ?? $row['cv_category_id']
            ?? $row['country_id']
            ?? $row['countries_id']
            ?? $row['city_id']
            ?? $row['cities_id']
            ?? $row['province_id']
            ?? $row['provinces_id']
            ?? $row['district_id']
            ?? $row['districts_id']
            ?? $row['institution_id']
            ?? $row['institutions_id']
            ?? $row['degree_level_id']
            ?? $row['degree_id']
            ?? $row['degrees_id']
            ?? $row['major_id']
            ?? $row['majors_id']
            ?? $row['job_title_id']
            ?? $row['job_titles_id']
            ?? $row['employment_type_id']
            ?? $row['employment_types_id']
            ?? $row['industry_id']
            ?? $row['industries_id']
            ?? $row['skill_id']
            ?? $row['skills_id']
            ?? $row['proficiency_level_id']
            ?? $row['proficiency_id']
            ?? $row['proficients_id']
            ?? $row['certificate_name_id']
            ?? $row['certificate_names_id']
            ?? $row['certificate_id']
            ?? $row['organization_id']
            ?? $row['organizations_id']
            ?? $row['issuing_organization_id']
            ?? '';
    }

    private function rowName($row)
    {
        foreach ([
            'name', 'title', 'full_name',
            'category_name', 'country_name', 'city_name', 'province_name', 'district_name',
            'institution_name', 'degree_name', 'level_name', 'major_name',
            'job_title_name', 'employment_type_name', 'industry_name',
            'skill_name', 'proficiency_name',
            'certificate_name', 'organization_name'
        ] as $k) {
            if (isset($row[$k]) && $row[$k] !== '') return $row[$k];
        }
        return (string)$this->rowId($row);
    }

    private function findCvByUserId($userId)
    {
        if (!$userId || !$this->tableExists('cvs')) return null;

        $pk = $this->primaryKeyName('cvs');
        $orderCol = $this->hasColumn('cvs', 'updated_at') ? 'updated_at' : $pk;

        foreach (['user_id','users_id','job_seeker_id','job_seekers_id'] as $col) {
            if ($this->hasColumn('cvs', $col)) {
                $row = $this->fetchOne(
                    'SELECT * FROM `cvs` WHERE `' . $this->cleanName($col) . '` = ? ORDER BY `' . $this->cleanName($orderCol) . '` DESC LIMIT 1',
                    [$userId]
                );
                if ($row) return $row;
            }
        }
        return null;
    }

    private function findCvById($id)
    {
        if (!$id || !$this->tableExists('cvs')) return null;
        $pk = $this->primaryKeyName('cvs');
        return $this->fetchOne('SELECT * FROM `cvs` WHERE `' . $this->cleanName($pk) . '` = ? LIMIT 1', [$id]);
    }

    private function cvId($cv)
    {
        if (!is_array($cv)) return null;
        return $cv['id'] ?? $cv['cv_id'] ?? null;
    }

    private function postFirst(array $keys)
    {
        foreach ($keys as $key) if (isset($_POST[$key])) return $_POST[$key];
        return null;
    }

    private function pick(array $row, array $keys, $default = null)
    {
        foreach ($keys as $key) if (isset($row[$key]) && $row[$key] !== '') return $row[$key];
        return $default;
    }

    private function normalizeValue($value)
    {
        if (is_array($value)) return null;
        $value = trim((string)$value);
        return $value === '' ? null : $value;
    }


    private function yearOrNull($value)
    {
        $value = $this->emptyToNull($value);
        if ($value === null) return null;
        if (strtolower((string)$value) === 'present') return null;
        return preg_match('/^\d{4}$/', (string)$value) ? $value : null;
    }

    private function emptyToNull($value)
    {
        if ($value === null || is_array($value)) return null;
        $value = trim((string)$value);
        return ($value === '' || $value === '0') ? null : $value;
    }

    private function validLookupId(array $tables, $value)
    {
        $value = $this->emptyToNull($value);
        if ($value === null) return null;
        $table = $this->firstExistingTable($tables);
        if (!$table) return $value;
        $pk = $this->primaryKeyName($table);
        try {
            $row = $this->fetchOne('SELECT `' . $this->cleanName($pk) . '` FROM `' . $this->cleanName($table) . '` WHERE `' . $this->cleanName($pk) . '` = ? LIMIT 1', [$value]);
            return $row ? $value : null;
        } catch (Exception $e) {
            return null;
        }
    }

    private function firstExistingTable(array $tables)
    {
        $key = implode('|', $tables);
        if (array_key_exists($key, $this->tableCache)) return $this->tableCache[$key];
        foreach ($tables as $table) if ($this->tableExists($table)) return $this->tableCache[$key] = $table;
        return $this->tableCache[$key] = null;
    }

    private function firstExistingColumn($table, array $columns)
    {
        foreach ($columns as $col) if ($this->hasColumn($table, $col)) return $col;
        return null;
    }

    private function tableExists($table)
    {
        if (!$this->db) return false;
        $table = $this->cleanName($table);
        try { $this->fetchOne('SELECT 1 FROM `' . $table . '` LIMIT 1'); return true; }
        catch (Throwable $e) { return false; }
    }

    private function columns($table)
    {
        $table = $this->cleanName($table);
        if (isset($this->columnCache[$table])) return $this->columnCache[$table];
        $cols = [];
        try {
            $rows = $this->fetchAll('SHOW COLUMNS FROM `' . $table . '`');
            foreach ($rows as $r) if (isset($r['Field'])) $cols[] = $r['Field'];
        } catch (Throwable $e) {}
        return $this->columnCache[$table] = $cols;
    }

    private function hasColumn($table, $column) { return in_array($column, $this->columns($table), true); }

    private function primaryKeyName($table)
    {
        $cols = $this->columns($table);
        if (in_array('id', $cols, true)) return 'id';
        if (in_array('cv_id', $cols, true)) return 'cv_id';
        return $cols[0] ?? 'id';
    }

    private function isAutoIncrement($table, $column)
    {
        try {
            $rows = $this->fetchAll('SHOW COLUMNS FROM `' . $this->cleanName($table) . '` LIKE ?', [$column]);
            return isset($rows[0]['Extra']) && stripos($rows[0]['Extra'], 'auto_increment') !== false;
        } catch (Throwable $e) { return true; }
    }

    private function insertRow($table, array $data)
    {
        $table = $this->cleanName($table);
        $cols = $this->columns($table);
        $filtered = [];
        foreach ($data as $k => $v) if (in_array($k, $cols, true)) $filtered[$k] = $v;
        if (!$filtered) return null;

        $pk = $this->primaryKeyName($table);
        if (in_array($pk, $cols, true) && !array_key_exists($pk, $filtered) && !$this->isAutoIncrement($table, $pk)) {
            $row = $this->fetchOne('SELECT COALESCE(MAX(`' . $this->cleanName($pk) . '`), 0) + 1 AS next_id FROM `' . $table . '`');
            $filtered = [$pk => $row['next_id'] ?? 1] + $filtered;
        }

        $names = array_keys($filtered);
        $sql = 'INSERT INTO `' . $table . '` (`' . implode('`, `', array_map([$this, 'cleanName'], $names)) . '`) VALUES (' . implode(', ', array_fill(0, count($names), '?')) . ')';
        $this->execute($sql, array_values($filtered));
        $id = $this->lastInsertId();
        return $id ?: ($filtered[$pk] ?? null);
    }

    private function updateRow($table, array $data, array $where)
    {
        $table = $this->cleanName($table);
        $cols = $this->columns($table);
        $filtered = [];
        foreach ($data as $k => $v) if (in_array($k, $cols, true)) $filtered[$k] = $v;
        if (!$filtered || !$where) return false;
        $set = []; $params = [];
        foreach ($filtered as $k => $v) { $set[] = '`' . $this->cleanName($k) . '` = ?'; $params[] = $v; }
        $whereSql = [];
        foreach ($where as $k => $v) { $whereSql[] = '`' . $this->cleanName($k) . '` = ?'; $params[] = $v; }
        return $this->execute('UPDATE `' . $table . '` SET ' . implode(', ', $set) . ' WHERE ' . implode(' AND ', $whereSql), $params);
    }

    private function fetchOne($sql, array $params = []) { $rows = $this->fetchAll($sql, $params); return $rows[0] ?? null; }

    private function fetchAll($sql, array $params = [])
    {
        if ($this->db instanceof PDO) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        if ($this->db instanceof mysqli) {
            $stmt = $this->db->prepare($sql);
            if (!$stmt) throw new RuntimeException($this->db->error);
            if ($params) $this->bindParams($stmt, $params);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        }
        return [];
    }

    private function execute($sql, array $params = [])
    {
        if ($this->db instanceof PDO) {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        }
        if ($this->db instanceof mysqli) {
            $stmt = $this->db->prepare($sql);
            if (!$stmt) throw new RuntimeException($this->db->error);
            if ($params) $this->bindParams($stmt, $params);
            return $stmt->execute();
        }
        return false;
    }

    private function bindParams(mysqli_stmt $stmt, array $params)
    {
        $types = ''; $values = [];
        foreach ($params as $p) { $types .= is_int($p) ? 'i' : (is_float($p) ? 'd' : 's'); $values[] = $p; }
        $stmt->bind_param($types, ...$values);
    }

    private function lastInsertId()
    {
        if ($this->db instanceof PDO) return $this->db->lastInsertId();
        if ($this->db instanceof mysqli) return $this->db->insert_id;
        return null;
    }

    private function beginTransaction() { if ($this->db instanceof PDO) $this->db->beginTransaction(); elseif ($this->db instanceof mysqli) $this->db->begin_transaction(); }
    private function commit() { if ($this->db instanceof PDO) $this->db->commit(); elseif ($this->db instanceof mysqli) $this->db->commit(); }
    private function rollBack() { if ($this->db instanceof PDO && $this->db->inTransaction()) $this->db->rollBack(); elseif ($this->db instanceof mysqli) $this->db->rollback(); }
    private function cleanName($name) { return preg_replace('/[^a-zA-Z0-9_]/', '', (string)$name); }

    private function normalizeRole($role)
    {
        $role = strtolower(trim((string)$role));
        $role = str_replace([' ', '-'], '_', $role);
        if ($role === 'jobseeker') return 'job_seeker';
        if ($role === 'administrator') return 'admin';
        return $role;
    }

    private function currentUserId()
    {
        $keys = ['id','user_id','users_id','User_ID','USER_ID','userId','UserId','UserID','userID','uid'];
        if (isset($_SESSION['user']) && is_array($_SESSION['user'])) foreach ($keys as $k) if (isset($_SESSION['user'][$k]) && $_SESSION['user'][$k] !== '') return $_SESSION['user'][$k];
        foreach ($keys as $k) if (isset($_SESSION[$k]) && $_SESSION[$k] !== '') return $_SESSION[$k];
        return null;
    }

    private function currentRole()
    {
        $role = $_SESSION['user']['role'] ?? $_SESSION['role'] ?? null;
        return $role ? $this->normalizeRole($role) : null;
    }

    private function requireLogin() { if (!$this->currentUserId()) $this->redirect('auth/login'); }
    private function requireRole(array $roles)
    {
        $this->requireLogin();
        $roles = array_map([$this, 'normalizeRole'], $roles);
        if (!in_array($this->currentRole(), $roles, true)) {
            http_response_code(403);
            echo '<h1>403 - Access denied</h1><p>Only job seekers can manage CVs. Current role: ' . htmlspecialchars((string)$this->currentRole()) . '</p>';
            exit;
        }
    }

    private function redirect($route, $extra = '')
    {
        $base = strtok($_SERVER['REQUEST_URI'], '?');
        header('Location: ' . $base . '?route=' . urlencode($route) . $extra);
        exit;
    }

    private function render($name, array $data = [], callable $fallback = null)
    {
        extract($data);
        $relative = str_replace('.', '/', $name);
        $candidates = [
            $this->root . '/resources/view/' . $relative . '.php',
            $this->root . '/resources/view/' . $relative . '.blade.php',
            $this->root . '/resources/views/' . $relative . '.php',
            $this->root . '/resources/views/' . $relative . '.blade.php',
            $this->root . '/resources/' . $relative . '.php',
        ];
        foreach ($candidates as $file) {
            if (file_exists($file)) {
                $content = file_get_contents($file);
                if (preg_match('/(@extends|@section|@foreach|@if|@csrf|\{\{)/', $content)) continue;
                include $file;
                return;
            }
        }
        if ($fallback) { $fallback(); return; }
        http_response_code(500);
        echo '<h1>View not found</h1><p>' . htmlspecialchars($name) . '</p>';
    }

    private function fallbackForm($data) { echo '<h1>Create / Update CV</h1><p>View file not found. Please place form.php in resources/view/seeker/cv/.</p>'; }
    private function fallbackCvView($data) { echo '<h1>CV Preview</h1><pre>' . htmlspecialchars(print_r($data['cv'] ?? [], true)) . '</pre>'; }
    private function fallbackTemplateChooser($data) { echo '<h1>Choose Template</h1><a href="?route=seeker/cv/view&template=modern">Modern</a>'; }
}
