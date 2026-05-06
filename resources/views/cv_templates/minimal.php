<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimal CV</title>
    <link rel="stylesheet" href="css/cv_minimal.css">
</head>
<body>

<div class="topbar">
    <a class="back-btn" href="index.php?route=employer/search">
        ← Back to Searc
    </a>

    <div class="template-switcher">
        <a href="index.php?route=employer/cv&id=<?= $cv['id'] ?>&template=modern">Modern</a>
        <a href="index.php?route=employer/cv&id=<?= $cv['id'] ?>&template=classic">Classic</a>
        <a class="active" href="index.php?route=employer/cv&id=<?= $cv['id'] ?>&template=minimal">Minimal</a>
    </div>
</div>

<div class="cv-minimal">
    <header class="top">
        <h1><?= htmlspecialchars($cv['full_name'] ?? 'Unknown') ?></h1>
        <p class="role"><?= htmlspecialchars($cv['category_name'] ?? '') ?></p>
        <p class="contact">
            <?= htmlspecialchars($cv['email'] ?? 'N/A') ?> ·
            <?= htmlspecialchars($cv['phone_number'] ?? 'N/A') ?> ·
            <?= htmlspecialchars($cv['city_name'] ?? '') ?>, <?= htmlspecialchars($cv['country_name'] ?? '') ?>
        </p>
    </header>

    <?php if (!empty($cv['summary'])): ?>
        <section>
            <h2>About</h2>
            <p><?= nl2br(htmlspecialchars($cv['summary'])) ?></p>
        </section>
    <?php endif; ?>

    <section>
        <h2>Personal Details</h2>

        <div class="row">
            <div class="year">Birthday</div>
            <div class="details"><p><?= htmlspecialchars($cv['birthday'] ?? 'N/A') ?></p></div>
        </div>

        <div class="row">
            <div class="year">Gender</div>
            <div class="details"><p><?= htmlspecialchars($cv['gender'] ?? 'N/A') ?></p></div>
        </div>

        <div class="row">
            <div class="year">Address</div>
            <div class="details">
                <p>
                    <?= htmlspecialchars($cv['address'] ?? '') ?>
                    <?php if (!empty($cv['district_name'])): ?>, <?= htmlspecialchars($cv['district_name']) ?><?php endif; ?>
                    , <?= htmlspecialchars($cv['city_name'] ?? '') ?>, <?= htmlspecialchars($cv['country_name'] ?? '') ?>
                    <?= !empty($cv['postal_code']) ? ' - ' . htmlspecialchars($cv['postal_code']) : '' ?>
                </p>
            </div>
        </div>
    </section>

    <?php if (!empty($cv['workHistory'])): ?>
        <section>
            <h2>Experience</h2>
            <?php foreach ($cv['workHistory'] as $work): ?>
                <div class="row">
                    <div class="year">
                        <?= htmlspecialchars($work['start_year'] ?? '') ?> -
                        <?= !empty($work['end_year']) ? htmlspecialchars($work['end_year']) : 'Present' ?>
                    </div>
                    <div class="details">
                        <h3><?= htmlspecialchars($work['job_title_name'] ?? '') ?></h3>
                        <p class="sub"><?= htmlspecialchars($work['company_name'] ?? '') ?></p>
                        <?php if (!empty($work['description'])): ?>
                            <p><?= nl2br(htmlspecialchars($work['description'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <?php if (!empty($cv['educations'])): ?>
        <section>
            <h2>Education</h2>
            <?php foreach ($cv['educations'] as $edu): ?>
                <div class="row">
                    <div class="year">
                        <?= htmlspecialchars($edu['start_year'] ?? '') ?> -
                        <?= !empty($edu['end_year']) ? htmlspecialchars($edu['end_year']) : 'Present' ?>
                    </div>
                    <div class="details">
                        <h3><?= htmlspecialchars($edu['degree_name'] ?? '') ?></h3>
                        <p class="sub">
                            <?= htmlspecialchars($edu['institution_name'] ?? '') ?>
                            <?php if (!empty($edu['major_name'])): ?>
                                · <?= htmlspecialchars($edu['major_name']) ?>
                            <?php endif; ?>
                        </p>
                        <?php if (!empty($edu['description'])): ?>
                            <p><?= nl2br(htmlspecialchars($edu['description'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <?php if (!empty($cv['skills'])): ?>
        <section>
            <h2>Skills</h2>
            <div class="minimal-skills">
                <?php foreach ($cv['skills'] as $skill): ?>
                    <span><?= htmlspecialchars($skill['skill_name'] ?? '') ?> (<?= htmlspecialchars($skill['proficiency_name'] ?? '') ?>)</span>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if (!empty($cv['certificates'])): ?>
        <section>
            <h2>Certificates</h2>
            <?php foreach ($cv['certificates'] as $cert): ?>
                <div class="row">
                    <div class="year"><?= htmlspecialchars($cert['year_issued'] ?? '') ?></div>
                    <div class="details">
                        <h3><?= htmlspecialchars($cert['certificate_name'] ?? '') ?></h3>
                        <p class="sub"><?= htmlspecialchars($cert['organization_name'] ?? '') ?></p>
                        <?php if (!empty($cert['description'])): ?>
                            <p><?= nl2br(htmlspecialchars($cert['description'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</div>

</body>
</html>