<?php
$currentPage = $currentPage ?? '';
$currentTable = $currentTable ?? '';

// 👉 CHỈNH CHỖ NÀY 1 LẦN DUY NHẤT
$base = '/ASSIGNMENT-1---CV-online/public/index.php';

function isActive($type, $key, $currentPage, $currentTable) {
    if ($type === 'page' && $currentPage === $key) return 'active';
    if ($type === 'table' && $currentTable === $key) return 'active';
    return '';
}
?>

<aside class="sidebar">

    <div class="sidebar-header">
        <h2>Admin</h2>
        <span>CV System</span>
    </div>

    <ul class="sidebar-menu">

        <!-- Dashboard -->
        <li>
            <a href="<?= $base ?>?route=admin/dashboard"
               class="<?= isActive('page', 'dashboard', $currentPage, $currentTable) ?>">
                Dashboard
            </a>
        </li>

        <li class="menu-title">Manage Data</li>

        <?php
        $tables = [
            'categories' => 'Categories',
            'skills' => 'Skills',
            'proficients' => 'Proficients',
            'degrees' => 'Degrees',
            'majors' => 'Majors',
            'institutions' => 'Institutions',
            'organizations' => 'Organizations',
            'industries' => 'Industries',
            'employment_types' => 'Employment Types',
            'job_title' => 'Job Titles',
            'countries' => 'Countries',
            'certificate_name' => 'Certificate Names'
        ];
        ?>

        <?php foreach ($tables as $key => $label): ?>
            <li>
                <a href="<?= $base ?>?route=admin/manage&table=<?= $key ?>"
                   class="<?= isActive('table', $key, $currentPage, $currentTable) ?>">
                    <?= $label ?>
                </a>
            </li>
        <?php endforeach; ?>

    </ul>

    <div class="sidebar-footer">
        <a href="<?= $base ?>?route=logout" class="logout-btn">
            Logout
        </a>
    </div>

</aside>