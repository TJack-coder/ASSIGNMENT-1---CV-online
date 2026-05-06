<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern CV</title>
    <link rel="stylesheet" href="css/cv_modern.css">
</head>
<body>

<?php $id = $cv['id'] ?? $cv['cv_id'] ?? ''; ?>
<div class="topbar">
    <div class="topbar-inner">
        <a class="back-btn" href="index.php?route=employer/search">
            ← Back to Search
        </a>

        <div class="template-switcher">
            <a class="active" href="index.php?route=employer/cv&id=<?= htmlspecialchars($cv['id'] ?? '') ?>&template=modern">Modern</a>
            <a href="index.php?route=employer/cv&id=<?= htmlspecialchars($cv['id'] ?? '') ?>&template=classic">Classic</a>
            <a href="index.php?route=employer/cv&id=<?= htmlspecialchars($cv['id'] ?? '') ?>&template=minimal">Minimal</a>
        </div>
    </div>
</div>

<div class="template-actions">
    <a class="change-template" href="?route=seeker/cv/templates<?= $id ? '&id=' . urlencode($id) : '' ?>">Change Template</a>
    <a class="edit-cv" href="?route=seeker/cv/create<?= $id ? '&id=' . urlencode($id) : '' ?>">Edit CV</a>
</div>

<div class="cv-modern">
    <aside class="sidebar">
        <div class="profile-box">
            <div class="avatar">
                <?= strtoupper(substr($cv['full_name'] ?? 'U', 0, 1)) ?>
            </div>
            <h1><?= htmlspecialchars($cv['full_name'] ?? 'Unknown') ?></h1>
            <p class="category"><?= htmlspecialchars($cv['category_name'] ?? 'No Category') ?></p>
        </div>

        <div class="section">
            <h2>Contact</h2>
            <p><strong>Email:</strong><br><?= htmlspecialchars($cv['email'] ?? 'N/A') ?></p>
            <p><strong>Phone:</strong><br><?= htmlspecialchars($cv['phone_number'] ?? 'N/A') ?></p>
            <p><strong>Birthday:</strong><br><?= htmlspecialchars($cv['birthday'] ?? 'N/A') ?></p>
            <p><strong>Gender:</strong><br><?= htmlspecialchars($cv['gender'] ?? 'N/A') ?></p>
            <p>
                <strong>Address:</strong><br>
                <?= htmlspecialchars($cv['address'] ?? '') ?><br>
                <?php if (!empty($cv['district_name'])): ?>
                    <?= htmlspecialchars($cv['district_name']) ?><br>
                <?php endif; ?>
                <?= htmlspecialchars($cv['city_name'] ?? '') ?>, <?= htmlspecialchars($cv['country_name'] ?? '') ?><br>
                <?= htmlspecialchars($cv['postal_code'] ?? '') ?>
            </p>
        </div>

        <?php if (!empty($cv['skills'])): ?>
            <div class="section">
                <h2>Skills</h2>
                <div class="skill-list">
                    <?php foreach ($cv['skills'] as $skill): ?>
                        <span class="skill-tag">
                            <?= htmlspecialchars($skill['skill_name'] ?? '') ?> - <?= htmlspecialchars($skill['proficiency_name'] ?? '') ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </aside>

    <main class="main-content">
        <?php if (!empty($cv['summary'])): ?>
            <section class="content-section">
                <h2>Profile Summary</h2>
                <p><?= nl2br(htmlspecialchars($cv['summary'])) ?></p>
            </section>
        <?php endif; ?>

        <?php if (!empty($cv['workHistory'])): ?>
            <section class="content-section">
                <h2>Work Experience</h2>
                <?php foreach ($cv['workHistory'] as $work): ?>
                    <div class="item">
                        <h3>
                            <?= htmlspecialchars($work['description'] ?? '') ?>
                            <?php if (!empty($work['company_name'])): ?>
                                - <?= htmlspecialchars($work['company_name']) ?>
                            <?php endif; ?>
                        </h3>
                        <span class="meta">
                            <?= htmlspecialchars($work['start_year'] ?? '') ?> -
                            <?= !empty($work['end_year']) ? htmlspecialchars($work['end_year']) : 'Present' ?>
                        </span>
                        <p><?= nl2br(htmlspecialchars($work['description'] ?? '')) ?></p>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <?php if (!empty($cv['education'])): ?>
            <section class="content-section">
                <h2>Education</h2>
                <?php foreach ($cv['education'] as $edu): ?>
                    <div class="item">
                        <h3>
                            <?= htmlspecialchars($edu['degree_name'] ?? '') ?>
                            <?php if (!empty($edu['institution_name'])): ?>
                                - <?= htmlspecialchars($edu['institution_name']) ?>
                            <?php endif; ?>
                        </h3>
                        <span class="meta">
                            <?= htmlspecialchars($edu['start_year'] ?? '') ?> -
                            <?= !empty($edu['end_year']) ? htmlspecialchars($edu['end_year']) : 'Present' ?>
                        </span>
                        <p><?= htmlspecialchars($edu['major_name'] ?? '') ?></p>
                        <?php if (!empty($edu['description'])): ?>
                            <p><?= nl2br(htmlspecialchars($edu['description'])) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <?php if (!empty($cv['certificates'])): ?>
            <section class="content-section">
                <h2>Certificates</h2>
                <?php foreach ($cv['certificates'] as $cert): ?>
                    <div class="item">
                        <h3><?= htmlspecialchars($cert['certificate_name'] ?? '') ?></h3>
                        <span class="meta">
                            <?= htmlspecialchars($cert['organization_name'] ?? '') ?>
                            <?php if (!empty($cert['year_issued'])): ?>
                                - <?= htmlspecialchars($cert['year_issued']) ?>
                            <?php endif; ?>
                        </span>
                        <?php if (!empty($cert['description'])): ?>
                            <p><?= nl2br(htmlspecialchars($cert['description'])) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>
</div>

</body>
</html>