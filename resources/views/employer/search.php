<!DOCTYPE html>
<html>
<head>
    <title>CV Search</title>
</head>

<body>

<h1>Search CVs</h1>

<form method="POST" action="/ASSIGNMENT-1---CV-online/public/index.php?route=employer/search/result">

<!-- Keyword -->
<label>Keyword</label>
<br>
<input type="text" name="keyword" placeholder="Search keyword">
<br><br>

<!-- Category -->
<label>Category</label>
<br>
<select name="category_id">

<option value="">All categories</option>

<?php if(!empty($categories)): ?>
<?php foreach($categories as $category): ?>

<option value="<?= $category['id'] ?>">
<?= htmlspecialchars($category['name']) ?>
</option>

<?php endforeach; ?>
<?php endif; ?>

</select>
<br><br>

<!-- Country -->
<label>Country</label>
<br>
<select name="country_id">

<option value="">All countries</option>

<?php foreach($countries as $country): ?>

<option value="<?= $country['id'] ?>">
<?= htmlspecialchars($country['name']) ?>
</option>

<?php endforeach; ?>

</select>
<br><br>

<!-- City -->
<label>City</label>
<br>
<input type="text" name="city" placeholder="City name">
<br><br>

<!-- Skills -->
<label>Skills</label>
<br>

<?php foreach($skills as $skill): ?>

<label>
<input type="checkbox" name="skills[]" value="<?= $skill['id'] ?>">
<?= htmlspecialchars($skill['name']) ?>
</label>

<br>

<?php endforeach; ?>

<br>

<!-- Minimum proficiency -->
<label>Minimum Proficiency</label>
<br>

<select name="min_proficiency">

<option value="1">Beginner</option>
<option value="2">Intermediate</option>
<option value="3">Advanced</option>

</select>

<br><br>

<!-- Degree -->
<label>Degree Level</label>
<br>

<select name="degree_level">

<option value="">Any degree</option>

<?php foreach($degrees as $degree): ?>

<option value="<?= $degree['id'] ?>">
<?= htmlspecialchars($degree['name']) ?>
</option>

<?php endforeach; ?>

</select>

<br><br>

<!-- Sorting -->
<label>Sort By</label>
<br>

<select name="sort_by">

<option value="recent">Most Recent</option>
<option value="alphabetical">Alphabetical</option>
<option value="experience">Experience Length</option>

</select>

<br><br>

<button type="submit">Search</button>

</form>

</body>

</html>