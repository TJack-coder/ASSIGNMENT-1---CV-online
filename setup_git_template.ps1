# config
$USERNAME_GITHUB = "TJack-coder"
$REPO_NAME = "ASSIGNMENT-1---CV-online"
$REMOTE_URL = "https://github.com/$USERNAME_GITHUB/$REPO_NAME.git"

$branches = @{
    "feature-education" = "Person B"
    "feature-work-history" = "Person B"
    "feature-certificates" = "Person B"
    "feature-cv-search" = "Person C"
    "feature-db-admin" = "Person A"
}

# 1. Init git
if (-not (Test-Path ".git")) {
    Write-Host "Initializing Git repository..."
    git init
} else {
    Write-Host "Git repository already exists."
}

# 2. Add remote
$existingRemote = git remote
if ($existingRemote -notcontains "origin") {
    Write-Host "Adding remote origin..."
    git remote add origin $REMOTE_URL
} else {
    Write-Host "Remote origin already exists."
}

# 3. Commit files
git add .
git commit -m "Initial commit from ZIP"

# 4. Create develop and push
git checkout -b develop
git push -u origin develop

# 5. Create branch templates
foreach ($branch in $branches.Keys) {
    git checkout -b $branch
    git push -u origin $branch
    Write-Host "Created branch $branch for $($branches[$branch])"
}

Write-Host "All done! Repo is ready with develop and branch templates."