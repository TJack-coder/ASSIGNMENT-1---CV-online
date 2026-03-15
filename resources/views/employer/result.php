<h1>Search Results</h1>

<?php if (!empty($filters)): ?>

<div style="margin-bottom:20px;">
    <strong>Filters applied:</strong>

    <?php if(!empty($filters['keyword'])): ?>
        Keyword: <?= htmlspecialchars($filters['keyword']) ?> |
    <?php endif; ?>

    <?php if(!empty($filters['city'])): ?>
        City: <?= htmlspecialchars($filters['city']) ?> |
    <?php endif; ?>

    <?php if(!empty($filters['sort_by'])): ?>
        Sort: <?= htmlspecialchars($filters['sort_by']) ?>
    <?php endif; ?>
</div>

<?php endif; ?>

<hr>

<?php if (!empty($cvs)): ?>

<?php foreach ($cvs as $cv): ?>

<div class="cv-card" style="border:1px solid #ccc;padding:15px;margin-bottom:15px;">

<h2>
<?= htmlspecialchars($cv['full_name'] ?? 'Unknown') ?>
</h2>

<p>
<strong>Email:</strong>
<?= htmlspecialchars($cv['email'] ?? 'N/A') ?>
</p>

<p>
<strong>Phone:</strong>
<?= htmlspecialchars($cv['phone_number'] ?? 'N/A') ?>
</p>

<p>
<strong>Location:</strong>
<?= htmlspecialchars($cv['city_name'] ?? '') ?>
<?= htmlspecialchars($cv['country_name'] ?? '') ?>
</p>

<p>
<strong>Category:</strong>
<?= htmlspecialchars($cv['category_name'] ?? '') ?>
</p>

<div style="margin-top:10px;">

<a href="/ASSIGNMENT-1---CV-online/public/index.php?route=employer/cv&id=<?= $cv['id'] ?>&template=modern">
View Modern CV
</a>

|

<a href="/ASSIGNMENT-1---CV-online/public/index.php?route=employer/cv&id=<?= $cv['id'] ?>&template=classic">
Classic
</a>

|

<a href="/ASSIGNMENT-1---CV-online/public/index.php?route=employer/cv&id=<?= $cv['id'] ?>&template=minimal">
Minimal
</a>

</div>

</div>

<?php endforeach; ?>

<?php else: ?>

<p>No CVs found matching your criteria.</p>

<?php endif; ?>