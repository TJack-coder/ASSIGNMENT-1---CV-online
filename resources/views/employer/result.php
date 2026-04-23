

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="/ASSIGNMENT-1---CV-online/public/css/search_result.css">
</head>

<div class="results-page">
    <div class="results-header">
        <div>
            <h1>Search Results</h1>
            <p class="results-subtitle">Browse matching candidate CVs based on your selected filters.</p>
        </div>

        <a class="back-btn" href="/ASSIGNMENT-1---CV-online/public/index.php?route=employer/search">
            Back to Search
        </a>
    </div>

    <?php if (!empty($filters)): ?>
        <div class="filter-summary">
            <h3>Filters applied</h3>

            <div class="filter-tags">
                <?php if (!empty($filters['keyword'])): ?>
                    <span class="filter-tag">Keyword: <?= htmlspecialchars($filters['keyword']) ?></span>
                <?php endif; ?>

                <?php if (!empty($filters['city'])): ?>
                    <span class="filter-tag">City: <?= htmlspecialchars($filters['city']) ?></span>
                <?php endif; ?>

                <?php if (!empty($filters['sort_by'])): ?>
                    <span class="filter-tag">Sort: <?= htmlspecialchars($filters['sort_by']) ?></span>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($cvs)): ?>
        <div class="results-count">
            Found <strong><?= count($cvs) ?></strong> matching CV<?= count($cvs) > 1 ? 's' : '' ?>
        </div>

        <div class="cv-grid">
            <?php foreach ($cvs as $cv): ?>
                <div class="cv-card">
                    <div class="cv-card-top">
                        <div class="candidate-avatar">
                            <?= strtoupper(substr($cv['full_name'] ?? 'U', 0, 1)) ?>
                        </div>

                        <div class="candidate-main">
                            <h2><?= htmlspecialchars($cv['full_name'] ?? 'Unknown') ?></h2>
                            <p class="candidate-category">
                                <?= htmlspecialchars($cv['category_name'] ?? 'Uncategorized') ?>
                            </p>
                        </div>
                    </div>

                    <div class="cv-info-list">
                        <div class="cv-info-item">
                            <span class="label">Email</span>
                            <span class="value"><?= htmlspecialchars($cv['email'] ?? 'N/A') ?></span>
                        </div>

                        <div class="cv-info-item">
                            <span class="label">Phone</span>
                            <span class="value"><?= htmlspecialchars($cv['phone_number'] ?? 'N/A') ?></span>
                        </div>

                        <div class="cv-info-item">
                            <span class="label">Location</span>
                            <span class="value">
                                <?= htmlspecialchars($cv['city_name'] ?? '') ?>
                                <?php if (!empty($cv['city_name']) && !empty($cv['country_name'])): ?>, <?php endif; ?>
                                <?= htmlspecialchars($cv['country_name'] ?? '') ?>
                            </span>
                        </div>
                    </div>

                    <div class="cv-actions">
                        <a class="btn-primary" href="/ASSIGNMENT-1---CV-online/public/index.php?route=employer/cv&id=<?= $cv['id'] ?>&template=modern">
                            View Modern CV
                        </a>

                        <div class="template-links">
                            <a href="/ASSIGNMENT-1---CV-online/public/index.php?route=employer/cv&id=<?= $cv['id'] ?>&template=classic">
                                Classic
                            </a>
                            <a href="/ASSIGNMENT-1---CV-online/public/index.php?route=employer/cv&id=<?= $cv['id'] ?>&template=minimal">
                                Minimal
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h2>No CVs found</h2>
            <p>No candidates matched your search criteria. Try adjusting your filters and search again.</p>

            <a class="back-btn" href="/ASSIGNMENT-1---CV-online/public/index.php?route=employer/search">
                Return to Search
            </a>
        </div>
    <?php endif; ?>
</div>