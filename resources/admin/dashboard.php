<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = 'dashboard';
$currentTable = '';
$pageTitle = 'Admin Dashboard';

$base = '/GROUP/public/index.php';

/*
|------------------------------------------------------------------
| Nếu controller đã truyền $tableCounts xuống rồi thì dùng luôn.
| Nếu chưa có, tạo mặc định để tránh báo undefined variable.
|------------------------------------------------------------------
*/
$tableCounts = $tableCounts ?? [
    'categories' => 0,
    'skills' => 0,
    'proficients' => 0,
    'degrees' => 0,
    'majors' => 0,
    'institutions' => 0,
    'organizations' => 0,
    'industries' => 0,
    'employment_types' => 0,
    'job_title' => 0,
    'countries' => 0,
    'certificate_name' => 0
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel= "stylesheet" href="/GROUP/public/css/admin.css">
<body>

    <div class="admin-layout">
        <?php include __DIR__ . '/partials/sidebars.php'; ?>

        <div class="admin-main">
            <?php include __DIR__ . '/partials/topbars.php'; ?>

            <main class="admin-content">
                <section class="admin-section">
                    <h2>Dashboard Overview</h2>
                    <p>Welcome to the admin management panel.</p>
                </section>

                <section class="admin-section">
                    <h3>Quick Access</h3>

                    <div class="admin-quick-links">
                        <div class="admin-card">
                            <h4>Categories</h4>
                            <p>Total: <?= (int)$tableCounts['categories'] ?></p>
                            <a href="<?= $base ?>?route=admin/manage&table=categories">Manage</a>
                        </div>

                        <div class="admin-card">
                            <h4>Skills</h4>
                            <p>Total: <?= (int)$tableCounts['skills'] ?></p>
                            <a href="<?= $base ?>?route=admin/manage&table=skills">Manage</a>
                        </div>

                        <div class="admin-card">
                            <h4>Majors</h4>
                            <p>Total: <?= (int)$tableCounts['majors'] ?></p>
                            <a href="<?= $base ?>?route=admin/manage&table=majors">Manage</a>
                        </div>

                        <div class="admin-card">
                            <h4>Institutions</h4>
                            <p>Total: <?= (int)$tableCounts['institutions'] ?></p>
                            <a href="<?= $base ?>?route=admin/manage&table=institutions">Manage</a>
                        </div>

                        <div class="admin-card">
                            <h4>Industries</h4>
                            <p>Total: <?= (int)$tableCounts['industries'] ?></p>
                            <a href="<?= $base ?>?route=admin/manage&table=industries">Manage</a>
                        </div>

                        <div class="admin-card">
                            <h4>Job Titles</h4>
                            <p>Total: <?= (int)$tableCounts['job_title'] ?></p>
                            <a href="<?= $base ?>?route=admin/manage&table=job_title">Manage</a>
                        </div>
                    </div>
                </section>

                <section class="admin-section">
                    <h3>All Managed Tables</h3>

                    <table border="1" cellpadding="10" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Table</th>
                                <th>Total Records</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tableCounts as $tableName => $count): ?>
                                <tr>
                                    <td><?= htmlspecialchars($tableName) ?></td>
                                    <td><?= (int)$count ?></td>
                                    <td>
                                        <a href="<?= $base ?>?route=admin/manage&table=<?= urlencode($tableName) ?>">
                                            Open
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            </main>
        </div>
    </div>

</body>
</html>