<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classic CV</title>
    <link rel="stylesheet" href="css/cv_classic.css">
</head>
<body>

<div class="topbar">
    <a class="back-btn" href="index.php?route=employer/search">
        ← Back to Search
    </a>

    <div class="template-switcher">
        <a href="index.php?route=employer/cv&id=<?= $cv['id'] ?>&template=modern">Modern</a>
        <a class="active" href="index.php?route=employer/cv&id=<?= $cv['id'] ?>&template=classic">Classic</a>
        <a href="index.php?route=employer/cv&id=<?= $cv['id'] ?>&template=minimal">Minimal</a>
    </div>
</div>

<div class="cv-classic">
    <header class="header">
        <h1><?= htmlspecialchars($cv['full_name'] ?? 'Unknown') ?></h1>
        <p>
            <?= htmlspecialchars($cv['email'] ?? 'N/A') ?> |
            <?= htmlspecialchars($cv['phone_number'] ?? 'N/A') ?> |
            <?= htmlspecialchars($cv['city_name'] ?? '') ?>, <?= htmlspecialchars($cv['country_name'] ?? '') ?>
        </p>
        <p><?= htmlspecialchars($cv['category_name'] ?? '') ?></p>
    </header>

    <?php if (!empty($cv['summary'])): ?>
        <section>
            <h2>Professional Summary</h2>
            <p><?= nl2br(htmlspecialchars($cv['summary'])) ?></p>
        </section>
    <?php endif; ?>

    <section>
        <h2>Personal Information</h2>
        <p><strong>Birthday:</strong> <?= htmlspecialchars($cv['birthday'] ?? 'N/A') ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($cv['gender'] ?? 'N/A') ?></p>
        <p>
            <strong>Address:</strong>
            <?= htmlspecialchars($cv['address'] ?? '') ?>
            <?php if (!empty($cv['district_name'])): ?>, <?= htmlspecialchars($cv['district_name']) ?><?php endif; ?>
            , <?= htmlspecialchars($cv['city_name'] ?? '') ?>, <?= htmlspecialchars($cv['country_name'] ?? '') ?>
            <?= !empty($cv['postal_code']) ? ' - ' . htmlspecialchars($cv['postal_code']) : '' ?>
        </p>
    </section>

    <?php if (!empty($cv['educations'])): ?>
        <section>
            <h2>Education</h2>
            <?php foreach ($cv['educations'] as $edu): ?>
                <div class="entry">
                    <div class="entry-header">
                        <strong><?= htmlspecialchars($edu['institution_name'] ?? '') ?></strong>
                        <span>
                            <?= htmlspecialchars($edu['start_year'] ?? '') ?> -
                            <?= !empty($edu['end_year']) ? htmlspecialchars($edu['end_year']) : 'Present' ?>
                        </span>
                    </div>
                    <p><?= htmlspecialchars($edu['degree_name'] ?? '') ?>, <?= htmlspecialchars($edu['major_name'] ?? '') ?></p>
                    <?php if (!empty($edu['description'])): ?>
                        <p><?= nl2br(htmlspecialchars($edu['description'])) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <?php if (!empty($cv['workHistory'])): ?>
        <section>
            <h2>Work Experience</h2>
            <?php foreach ($cv['workHistory'] as $work): ?>
                <div class="entry">
                    <div class="entry-header">
                        <strong>
                            <?= htmlspecialchars($work['job_title_name'] ?? '') ?>
                            <?php if (!empty($work['company_name'])): ?>
                                - <?= htmlspecialchars($work['company_name']) ?>
                            <?php endif; ?>
                        </strong>
                        <span>
                            <?= htmlspecialchars($work['start_year'] ?? '') ?> -
                            <?= !empty($work['end_year']) ? htmlspecialchars($work['end_year']) : 'Present' ?>
                        </span>
                    </div>
                    <?php if (!empty($work['description'])): ?>
                        <p><?= nl2br(htmlspecialchars($work['description'])) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <?php if (!empty($cv['skills'])): ?>
        <section>
            <h2>Skills</h2>
            <ul>
                <?php foreach ($cv['skills'] as $skill): ?>
                    <li><?= htmlspecialchars($skill['skill_name'] ?? '') ?> - <?= htmlspecialchars($skill['proficiency_name'] ?? '') ?></li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <?php if (!empty($cv['certificates'])): ?>
        <section>
            <h2>Certificates</h2>
            <ul>
                <?php foreach ($cv['certificates'] as $cert): ?>
                    <li>
                        <?= htmlspecialchars($cert['certificate_name'] ?? '') ?>
                        <?php if (!empty($cert['organization_name'])): ?>
                            - <?= htmlspecialchars($cert['organization_name']) ?>
                        <?php endif; ?>
                        <?php if (!empty($cert['year_issued'])): ?>
                            (<?= htmlspecialchars($cert['year_issued']) ?>)
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>
</div>

</body>
</html>