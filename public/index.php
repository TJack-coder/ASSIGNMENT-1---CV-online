<?php
/*
http://localhost/ASSIGNMENT-1---CV-online-main/public/index.php?route=auth/login
 */

session_start();

$root = dirname(__DIR__);

function safeRequire($file)
{
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    return false;
}

function failPage($message, $code = 500)
{
    http_response_code($code);
    echo '<!doctype html><html><head><meta charset="utf-8"><title>Error</title></head><body>';
    echo '<h2>' . htmlspecialchars($message) . '</h2>';
    echo '<p><a href="?route=home">Back to home</a></p>';
    echo '</body></html>';
    exit;
}

function redirectTo($route, $extra = '')
{
    $base = strtok($_SERVER['REQUEST_URI'], '?');
    header('Location: ' . $base . '?route=' . urlencode($route) . $extra);
    exit;
}

function normalizeRole($role)
{
    $role = strtolower(trim((string)$role));
    $role = str_replace(array(' ', '-'), '_', $role);

    if ($role === 'jobseeker') {
        return 'job_seeker';
    }

    if ($role === 'administrator') {
        return 'admin';
    }

    return $role;
}

function currentUserId()
{
    $keys = array('id', 'user_id', 'User_ID', 'USER_ID', 'userId', 'UserId', 'UserID', 'userID', 'uid');

    if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
        foreach ($keys as $key) {
            if (isset($_SESSION['user'][$key]) && $_SESSION['user'][$key] !== '' && $_SESSION['user'][$key] !== null) {
                return $_SESSION['user'][$key];
            }
        }
    }

    foreach ($keys as $key) {
        if (isset($_SESSION[$key]) && $_SESSION[$key] !== '' && $_SESSION[$key] !== null) {
            return $_SESSION[$key];
        }
    }

    return null;
}

function currentRole()
{
    if (isset($_SESSION['user']) && is_array($_SESSION['user']) && isset($_SESSION['user']['role'])) {
        return normalizeRole($_SESSION['user']['role']);
    }
    if (isset($_SESSION['role'])) {
        return normalizeRole($_SESSION['role']);
    }
    return null;
}

function isLoggedIn()
{
    // Chỉ xem là đã login khi session có user id thật.
    // Nếu chỉ có $_SESSION['user'] nhưng id bị null thì sẽ gây vòng lặp redirect.
    return currentUserId() !== null;
}

function requireLogin()
{
    if (!isLoggedIn()) {
        redirectTo('auth/login');
    }
}

function requireRole($allowedRoles)
{
    requireLogin();

    $role = currentRole();
    $allowedRoles = array_map('normalizeRole', $allowedRoles);

    if (!in_array($role, $allowedRoles, true)) {
        failPage('403 - You do not have permission to access this page. Current role: ' . ($role ?: 'none'), 403);
    }
}

function goDashboardByRole()
{
    $role = currentRole();

    if ($role === 'admin') {
        redirectTo('admin/dashboard');
    }

    if ($role === 'employer') {
        redirectTo('employer/search');
    }

    if ($role === 'job_seeker' || $role === 'seeker') {
        redirectTo('seeker/cv/create');
    }

    redirectTo('auth/login');
}

function renderFallbackLogin($error = '')
{
    $action = '?route=auth/login';
    echo '<!doctype html><html><head><meta charset="utf-8"><title>Login</title></head><body>';
    echo '<h2>Login</h2>';
    if ($error !== '') {
        echo '<p style="color:red">' . htmlspecialchars($error) . '</p>';
    }
    echo '<form method="post" action="' . htmlspecialchars($action) . '">';
    echo '<p><label>Email<br><input type="email" name="email" required></label></p>';
    echo '<p><label>Password<br><input type="password" name="password" required></label></p>';
    echo '<button type="submit">Login</button>';
    echo '</form>';
    echo '<p><a href="?route=auth/register">Create account</a></p>';
    echo '</body></html>';
}

function renderFallbackRegister($error = '')
{
    $action = '?route=auth/register';
    echo '<!doctype html><html><head><meta charset="utf-8"><title>Register</title></head><body>';
    echo '<h2>Register</h2>';
    if ($error !== '') {
        echo '<p style="color:red">' . htmlspecialchars($error) . '</p>';
    }
    echo '<form method="post" action="' . htmlspecialchars($action) . '">';
    echo '<p><label>Name<br><input type="text" name="name" required></label></p>';
    echo '<p><label>Email<br><input type="email" name="email" required></label></p>';
    echo '<p><label>Password<br><input type="password" name="password" required></label></p>';
    echo '<p><label>Confirm Password<br><input type="password" name="password_confirmation" required></label></p>';
    echo '<p><label>Role<br><select name="role">';
    echo '<option value="job_seeker">Job Seeker</option>';
    echo '<option value="employer">Employer</option>';
    echo '<option value="admin">Admin</option>';
    echo '</select></label></p>';
    echo '<button type="submit">Register</button>';
    echo '</form>';
    echo '<p><a href="?route=auth/login">Login</a></p>';
    echo '</body></html>';
}

function renderView($name, $data = array())
{
    global $root;

    extract($data);

    $relative = str_replace('.', '/', $name);
    $candidates = array(
        $root . '/resources/view/' . $relative . '.php',
        $root . '/resources/view/' . $relative . '.blade.php',
        $root . '/resources/views/' . $relative . '.php',
        $root . '/resources/views/' . $relative . '.blade.php',
        $root . '/resources/' . $relative . '.php',
        $root . '/resources/' . $relative . '.blade.php',
    );

    foreach ($candidates as $file) {
        if (file_exists($file)) {
            include $file;
            return;
        }
    }

    if ($name === 'auth.login') {
        renderFallbackLogin($data['error'] ?? '');
        return;
    }

    if ($name === 'auth.register') {
        renderFallbackRegister($data['error'] ?? '');
        return;
    }

    failPage('View not found: ' . $name . '<br>Checked:<br>' . implode('<br>', array_map('htmlspecialchars', $candidates)), 500);
}

function dbFetchOne($database, $sql, $params = array())
{
    if ($database instanceof PDO) {
        $stmt = $database->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($database instanceof mysqli) {
        $stmt = $database->prepare($sql);
        if (!$stmt) {
            return null;
        }
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }

    return null;
}

function dbExecute($database, $sql, $params = array())
{
    if ($database instanceof PDO) {
        $stmt = $database->prepare($sql);
        return $stmt->execute($params);
    }

    if ($database instanceof mysqli) {
        $stmt = $database->prepare($sql);
        if (!$stmt) {
            return false;
        }
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        return $stmt->execute();
    }

    return false;
}

function dbColumns($database, $table)
{
    static $cache = array();

    if (isset($cache[$table])) {
        return $cache[$table];
    }

    $columns = array();

    try {
        if ($database instanceof PDO) {
            $stmt = $database->query('SHOW COLUMNS FROM `' . str_replace('`', '', $table) . '`');
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $columns[] = $row['Field'];
            }
        } elseif ($database instanceof mysqli) {
            $result = $database->query('SHOW COLUMNS FROM `' . str_replace('`', '', $table) . '`');
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $columns[] = $row['Field'];
                }
            }
        }
    } catch (Exception $e) {
        $columns = array();
    }

    $cache[$table] = $columns;
    return $columns;
}



function dbColumnInfo($database, $table)
{
    static $cache = array();

    if (isset($cache[$table])) {
        return $cache[$table];
    }

    $info = array();
    $cleanTable = str_replace('`', '', $table);

    try {
        if ($database instanceof PDO) {
            $stmt = $database->query('SHOW COLUMNS FROM `' . $cleanTable . '`');
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $field = $row['Field'];
                $info[$field] = array(
                    'field' => $field,
                    'type' => $row['Type'] ?? '',
                    'null' => $row['Null'] ?? '',
                    'key' => $row['Key'] ?? '',
                    'default' => $row['Default'] ?? null,
                    'extra' => $row['Extra'] ?? '',
                );
            }
        } elseif ($database instanceof mysqli) {
            $result = $database->query('SHOW COLUMNS FROM `' . $cleanTable . '`');
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $field = $row['Field'];
                    $info[$field] = array(
                        'field' => $field,
                        'type' => $row['Type'] ?? '',
                        'null' => $row['Null'] ?? '',
                        'key' => $row['Key'] ?? '',
                        'default' => $row['Default'] ?? null,
                        'extra' => $row['Extra'] ?? '',
                    );
                }
            }
        }
    } catch (Exception $e) {
        $info = array();
    }

    $cache[$table] = $info;
    return $info;
}

function dbPrimaryKeyColumn($database, $table)
{
    $info = dbColumnInfo($database, $table);

    foreach ($info as $field => $meta) {
        if (($meta['key'] ?? '') === 'PRI') {
            return $field;
        }
    }

    foreach (array('id', 'user_id', 'User_ID', 'USER_ID', 'UserID', 'uid') as $candidate) {
        if (isset($info[$candidate])) {
            return $candidate;
        }
    }

    return null;
}

function dbColumnIsAutoIncrement($database, $table, $column)
{
    $info = dbColumnInfo($database, $table);

    if (!isset($info[$column])) {
        return false;
    }

    return stripos((string)($info[$column]['extra'] ?? ''), 'auto_increment') !== false;
}

function dbNextId($database, $table, $column)
{
    $cleanTable = str_replace('`', '', $table);
    $cleanColumn = str_replace('`', '', $column);
    $sql = 'SELECT COALESCE(MAX(`' . $cleanColumn . '`), 0) + 1 AS next_id FROM `' . $cleanTable . '`';

    try {
        if ($database instanceof PDO) {
            $stmt = $database->query($sql);
            $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
            return $row ? (int)$row['next_id'] : 1;
        }

        if ($database instanceof mysqli) {
            $result = $database->query($sql);
            $row = $result ? $result->fetch_assoc() : null;
            return $row ? (int)$row['next_id'] : 1;
        }
    } catch (Exception $e) {
        return time();
    }

    return time();
}


function detectUserIdFromRow($row)
{
    if (!is_array($row)) {
        return null;
    }

    foreach (array('id', 'user_id', 'User_ID', 'USER_ID', 'userId', 'UserId', 'UserID', 'userID', 'uid') as $key) {
        if (isset($row[$key]) && $row[$key] !== '' && $row[$key] !== null) {
            return $row[$key];
        }
    }

    return null;
}

function dbLastInsertId($database)
{
    if ($database instanceof PDO) {
        $id = $database->lastInsertId();
        return $id !== '0' ? $id : null;
    }

    if ($database instanceof mysqli) {
        return $database->insert_id ?: null;
    }

    return null;
}

function makeInsertSql($database, $table, $data)
{
    $columns = dbColumns($database, $table);
    if (empty($columns)) {
        return null;
    }

    $filtered = array();
    foreach ($data as $key => $value) {
        if (in_array($key, $columns, true)) {
            $filtered[$key] = $value;
        }
    }

    if (empty($filtered)) {
        return null;
    }

    $fieldSql = array_map(function ($field) {
        return '`' . str_replace('`', '', $field) . '`';
    }, array_keys($filtered));

    $placeholders = array_fill(0, count($filtered), '?');

    return array(
        'sql' => 'INSERT INTO `' . str_replace('`', '', $table) . '` (' . implode(', ', $fieldSql) . ') VALUES (' . implode(', ', $placeholders) . ')',
        'params' => array_values($filtered),
    );
}

function handleLogin($database)
{
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        renderView('auth.login', array('error' => 'Please enter email and password.'));
        return;
    }

    $user = dbFetchOne($database, 'SELECT * FROM users WHERE email = ? LIMIT 1', array($email));

    if (!$user) {
        renderView('auth.login', array('error' => 'Invalid email or password.'));
        return;
    }

    $storedPassword = (string)($user['hash_pw'] ?? ($user['password'] ?? '')); 
    $validPassword = password_verify($password, $storedPassword) || $password === $storedPassword;

    if (!$validPassword) {
        renderView('auth.login', array('error' => 'Invalid email or password.'));
        return;
    }

    $_SESSION['user'] = array(
        'id' => detectUserIdFromRow($user),
        'name' => $user['name'] ?? ($user['full_name'] ?? ($user['username'] ?? '')),
        'email' => $user['email'] ?? '',
        'role' => normalizeRole($user['role'] ?? 'job_seeker'),
    );
    $_SESSION['user_id'] = $_SESSION['user']['id'];
    $_SESSION['role'] = $_SESSION['user']['role'];

    if ($_SESSION['user_id'] === null) {
        session_destroy();
        renderView('auth.login', array('error' => 'User id was not found in database. Please check users table primary key column.'));
        return;
    }

    goDashboardByRole();
}

function handleRegister($database)
{
    $name = trim($_POST['name'] ?? ($_POST['full_name'] ?? ($_POST['username'] ?? '')));
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirmation'] ?? ($_POST['confirm_password'] ?? $password);
    $role = normalizeRole($_POST['role'] ?? 'job_seeker');

    if (!in_array($role, array('job_seeker', 'employer', 'admin'), true)) {
        $role = 'job_seeker';
    }

    if ($name === '' || $email === '' || $password === '') {
        renderView('auth.register', array('error' => 'Please fill in all required fields.'));
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        renderView('auth.register', array('error' => 'Email is invalid.'));
        return;
    }

    if ($password !== $confirm) {
        renderView('auth.register', array('error' => 'Password confirmation does not match.'));
        return;
    }

    $existing = dbFetchOne($database, 'SELECT * FROM users WHERE email = ? LIMIT 1', array($email));
    if ($existing) {
        renderView('auth.register', array('error' => 'Email already exists.'));
        return;
    }
    $userData = array(
        'name' => $name,
        'full_name' => $name,
        'username' => $name,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'hash_pw' => password_hash($password, PASSWORD_DEFAULT),
        'role' => $role,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    );

    // Nếu khóa chính của bảng users không AUTO_INCREMENT, tự sinh id mới.
    // Tránh lỗi: Duplicate entry '0' for key 'PRIMARY'.
    $primaryKey = dbPrimaryKeyColumn($database, 'users');
    if ($primaryKey !== null && !dbColumnIsAutoIncrement($database, 'users', $primaryKey)) {
        if (!isset($userData[$primaryKey])) {
            $userData[$primaryKey] = dbNextId($database, 'users', $primaryKey);
        }
    }

    $insert = makeInsertSql($database, 'users', $userData);

    if (!$insert || !dbExecute($database, $insert['sql'], $insert['params'])) {
        renderView('auth.register', array('error' => 'Cannot create account. Please check users table columns.'));
        return;
    }

    $user = dbFetchOne($database, 'SELECT * FROM users WHERE email = ? LIMIT 1', array($email));
    $newUserId = detectUserIdFromRow($user);
    if ($newUserId === null) {
        $newUserId = dbLastInsertId($database);
    }

    $_SESSION['user'] = array(
        'id' => $newUserId,
        'name' => $user['name'] ?? ($user['full_name'] ?? $name),
        'email' => $email,
        'role' => $role,
    );
    $_SESSION['user_id'] = $newUserId;
    $_SESSION['role'] = $role;

    if ($newUserId === null) {
        session_destroy();
        renderView('auth.login', array('error' => 'Account was created but user id was not found. Please log in again.'));
        return;
    }

    goDashboardByRole();
}

function callControllerAction($controller, $method, $params = array())
{
    if (!is_object($controller)) {
        failPage('Controller not found. Please check require path and class name.');
    }

    if (!method_exists($controller, $method)) {
        failPage('Method not found: ' . get_class($controller) . '::' . $method);
    }

    call_user_func_array(array($controller, $method), $params);
}

// =========================
// Load project files
// =========================
safeRequire($root . '/config/DataConfig.php') || failPage('Missing config/DataConfig.php');
safeRequire($root . '/app/functions/function.php');
safeRequire($root . '/app/controllers/Admin.php');
safeRequire($root . '/app/controllers/EmployerControllers.php');
safeRequire($root . '/app/services/CVSearchService.php');

// Sau khi bạn đã thay CvController.php bằng bản PHP thuần, require file này là an toàn.
safeRequire($root . '/app/Http/Controllers/CvController.php');

use config\DataConfig;
use app\controllers\EmployerController;

$database = DataConfig::getInstance()->getConnection();

$employerController = class_exists('app\\controllers\\EmployerController')
    ? new EmployerController($database)
    : null;

$adminController = class_exists('AdminController')
    ? new AdminController()
    : null;

$cvController = class_exists('CvController')
    ? new CvController($database, $root)
    : null;

$route = trim($_GET['route'] ?? 'home', '/');
$isPost = ($_SERVER['REQUEST_METHOD'] === 'POST');

switch ($route) {
    // =========================
    // Main flow
    // =========================
    case '':
    case 'home':
    case 'dashboard':
        if (isLoggedIn()) {
            goDashboardByRole();
        }
        redirectTo('auth/login');
        break;

    // =========================
    // Auth flow
    // =========================
    case 'auth/login':
    case 'login':
        if (isLoggedIn() && !$isPost) {
            goDashboardByRole();
        }
        if ($isPost) {
            handleLogin($database);
        } else {
            renderView('auth.login');
        }
        break;

    case 'auth/register':
    case 'register':
        if (isLoggedIn() && !$isPost) {
            goDashboardByRole();
        }
        if ($isPost) {
            handleRegister($database);
        } else {
            renderView('auth.register');
        }
        break;

    case 'auth/logout':
    case 'logout':
        session_destroy();
        redirectTo('auth/login');
        break;

    // =========================
    // Job Seeker flow
    // =========================
    case 'seeker/cv':
    case 'seeker/cv/create':
    case 'job_seeker/cv':
    case 'job_seeker/cv/create':
    case 'cv/create':
        requireRole(array('job_seeker', 'jobseeker', 'seeker'));
        callControllerAction($cvController, 'create');
        break;

    case 'seeker/cv/store':
    case 'job_seeker/cv/store':
    case 'cv/store':
        requireRole(array('job_seeker', 'jobseeker', 'seeker'));
        callControllerAction($cvController, 'store');
        break;

    case 'seeker/cv/edit':
    case 'job_seeker/cv/edit':
    case 'cv/edit':
        requireRole(array('job_seeker', 'jobseeker', 'seeker'));
        callControllerAction($cvController, 'edit');
        break;

    case 'seeker/cv/update':
    case 'job_seeker/cv/update':
    case 'cv/update':
        requireRole(array('job_seeker', 'jobseeker', 'seeker'));
        callControllerAction($cvController, 'update');
        break;

    case 'seeker/cv/view':
    case 'job_seeker/cv/view':
    case 'cv/view':
        requireLogin();
        $id = $_GET['id'] ?? null;
        callControllerAction($cvController, 'view', array($id));
        break;

    case 'seeker/cv/templates':
    case 'job_seeker/cv/templates':
    case 'cv/templates':
        requireLogin();
        $id = $_GET['id'] ?? null;
        callControllerAction($cvController, 'templates', array($id));
        break;

    // =========================
    // Employer flow
    // =========================
    case 'employer/search':
        requireRole(array('employer'));
        callControllerAction($employerController, 'showSearchForm');
        break;

    case 'employer/search/result':
    case 'employer/search/results':
        requireRole(array('employer'));
        callControllerAction($employerController, 'searchCVs');
        break;

    case 'employer/cv':
    case 'employer/cv/view':
        requireRole(array('employer'));
        $id = $_GET['id'] ?? null;
        callControllerAction($employerController, 'viewCV', array($id));
        break;

    // =========================
    // Admin flow
    // =========================
    case 'admin/dashboard':
        requireRole(array('admin', 'administrator'));
        callControllerAction($adminController, 'dashboard');
        break;

    case 'admin/manage':
        requireRole(array('admin', 'administrator'));
        callControllerAction($adminController, 'manage');
        break;

    case 'admin/create':
        requireRole(array('admin', 'administrator'));
        callControllerAction($adminController, 'create');
        break;

    case 'admin/delete':
        requireRole(array('admin', 'administrator'));
        callControllerAction($adminController, 'delete');
        break;

    default:
        http_response_code(404);
        echo '<!doctype html><html><head><meta charset="utf-8"><title>404</title></head><body>';
        echo '<h1>404 - Page not found</h1>';
        echo '<p>Route không tồn tại: <b>' . htmlspecialchars($route) . '</b></p>';
        echo '<ul>';
        echo '<li><a href="?route=auth/login">Login</a></li>';
        echo '<li><a href="?route=auth/register">Register</a></li>';
        echo '<li><a href="?route=seeker/cv/create">Job Seeker - Create CV</a></li>';
        echo '<li><a href="?route=employer/search">Employer - Search CV</a></li>';
        echo '<li><a href="?route=admin/dashboard">Admin - Dashboard</a></li>';
        echo '</ul>';
        echo '</body></html>';
        break;
}
