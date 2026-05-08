<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = 'manage';
$currentTable = $currentTable ?? ($_GET['table'] ?? '');
$pageTitle = 'Manage ' . ucwords(str_replace('_', ' ', $currentTable));

$base = '/ASSIGNMENT-1---CV-online/public/index.php';

$items = $items ?? [];
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

function formatTableName($tableName) {
    return ucwords(str_replace('_', ' ', $tableName));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="/ASSIGNMENT-1---CV-online/public/css/admin.css">
</head>
<body>

    <div class="admin-layout">
        <?php include __DIR__ . '/partials/sidebars.php'; ?>

        <div class="admin-main">
            <?php include __DIR__ . '/partials/topbars.php'; ?>

            <main class="admin-content">
                <section class="admin-section">
                    <h2><?= htmlspecialchars(formatTableName($currentTable)) ?></h2>
                    <p>Manage data for the selected table.</p>
                </section>

                <?php if ($success === 'created'): ?>
                    <section class="admin-section">
                        <p>Record created successfully.</p>
                    </section>
                <?php endif; ?>

                <?php if ($success === 'deleted'): ?>
                    <section class="admin-section">
                        <p>Record deleted successfully.</p>
                    </section>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                    <section class="admin-section">
                        <p>Error: <?= htmlspecialchars($error) ?></p>
                    </section>
                <?php endif; ?>

                <section class="admin-section">
                    <h3>Add New <?= htmlspecialchars(formatTableName($currentTable)) ?></h3>

                    <form action="<?= $base ?>?route=admin/create" method="POST">
                        <input type="hidden" name="table" value="<?= htmlspecialchars($currentTable) ?>">

                        <div>
                            <label for="name">Name</label>
                        </div>
                        <div>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                placeholder="Enter name..."
                                required
                            >
                        </div>
                        <div style="margin-top: 10px;">
                            <button type="submit">Add New</button>
                        </div>
                    </form>
                </section>

                <section class="admin-section">
                    <h3>Existing Records</h3>

                    <table border="1" cellpadding="10" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?= (int)($item['id'] ?? 0) ?></td>
                                        <td><?= htmlspecialchars($item['name'] ?? '') ?></td>
                                        <td>
                                            <form action="<?= $base ?>?route=admin/delete" method="POST">
                                                <input type="hidden" name="table" value="<?= htmlspecialchars($currentTable) ?>">
                                                <input type="hidden" name="id" value="<?= (int)($item['id'] ?? 0) ?>">
                                                <button type="submit">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">No records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </section>
            </main>
        </div>
    </div>

</body>
</html>