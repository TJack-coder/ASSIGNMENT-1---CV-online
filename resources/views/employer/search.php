<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search CVs</title>
<link rel="stylesheet" href="/ASSIGNMENT-1---CV-online-main/public/css/employer.css"></head>
<body>

<div class="page-container">
    <div class="page-header">
        <h1>Search CVs</h1>
        <p>
            Search and filter candidate CVs by keyword, category, location, skills, proficiency,
            degree level, and sorting options.
        </p>
    </div>

    <div class="search-wrapper">
        <!-- Left: Search Form -->
        <div class="search-card">
            <h2>Filter Candidates</h2>
            <p class="card-subtitle">
                Use the fields below to quickly narrow down CVs based on employer requirements.
            </p>

            <form method="POST" action="/ASSIGNMENT-1---CV-online/public/index.php?route=employer/search/result">

                <!-- Keyword -->
                <div class="form-group">
                    <label for="keyword">Keyword</label>
                    <input type="text" id="keyword" name="keyword" placeholder="Search by name, summary, description...">
                </div>

                <!-- Category -->
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select name="category_id" id="category_id">
                        <option value="">All categories</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>">
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Country -->
                <div class="form-group">
                    <label for="country_id">Country</label>
                    <select name="country_id" id="country_id">
                        <option value="">All countries</option>
                        <?php foreach ($countries as $country): ?>
                            <option value="<?= $country['id'] ?>">
                                <?= htmlspecialchars($country['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- City -->
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" placeholder="Enter city name">
                </div>

                <!-- Skills -->
                <div class="form-group">
                    <span class="section-label">Skills</span>
                    <div class="skills-box">
                        <?php foreach ($skills as $skill): ?>
                            <label class="skill-item">
                                <input type="checkbox" name="skills[]" value="<?= $skill['id'] ?>">
                                <span><?= htmlspecialchars($skill['name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Minimum proficiency -->
                <div class="form-group">
                    <label for="min_proficiency">Minimum Proficiency</label>
                    <select name="min_proficiency" id="min_proficiency">
                        <option value="1">Beginner</option>
                        <option value="2">Intermediate</option>
                        <option value="3">Advanced</option>
                    </select>
                </div>

                <!-- Degree -->
                <div class="form-group">
                    <label for="degree_level">Degree Level</label>
                    <select name="degree_level" id="degree_level">
                        <option value="">Any degree</option>
                        <?php foreach ($degrees as $degree): ?>
                            <option value="<?= $degree['id'] ?>">
                                <?= htmlspecialchars($degree['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Sorting -->
                <div class="form-group">
                    <label for="sort_by">Sort By</label>
                    <select name="sort_by" id="sort_by">
                        <option value="recent">Most Recent</option>
                        <option value="alphabetical">Alphabetical</option>
                        <option value="experience">Experience Length</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Search CVs</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </form>
        </div>

        <!-- Right: UI Preview / Information -->
        <div class="preview-card">
            <h2>Employer Search Panel</h2>
            <p class="card-subtitle">
                This panel gives employers a cleaner experience when searching for the most suitable CVs.
            </p>

            <div class="preview-grid">
                <div class="preview-box">
                    <h3>Powerful Filtering</h3>
                    <p>
                        Combine multiple search conditions such as category, location, skills, and degree level
                        to narrow down results efficiently.
                    </p>
                </div>

                <div class="preview-box">
                    <h3>Better Readability</h3>
                    <p>
                        A card-based, modern interface improves usability and makes the search form look more professional.
                    </p>
                </div>

                <div class="preview-box">
                    <h3>Responsive Layout</h3>
                    <p>
                        The layout adapts to desktop, tablet, and mobile screens so employers can search CVs anywhere.
                    </p>
                </div>

                <div class="preview-box">
                    <h3>Consistent UX</h3>
                    <p>
                        All inputs use the same spacing, style, and hierarchy, making the interface easier to understand.
                    </p>
                </div>
            </div>

            <div class="highlight-box">
                <h3>Good next step</h3>
                <ul>
                    <li>Show search results as CV cards on the next page</li>
                    <li>Add selected filter badges above results</li>
                    <li>Add pagination for large numbers of CVs</li>
                    <li>Add template switch buttons when viewing a CV</li>
                </ul>
            </div>
        </div>
    </div>
</div>

</body>
</html>