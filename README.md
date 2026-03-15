# ASSIGNMENT-1---CV-online

## C: Employer & Search Focus ( LONG)

1.Implement search: Keywords, filters (category, location, skills, proficiency, degree) with AND logic.

2.Add sorting (recent, alphabetical, experience length).

3.Build CV views: Read-only, 3+ templates (e.g., Modern/Classic/Minimal) using same data; responsive UI.

4.Integrate DB queries; test filters/sorting.

Rationale: Covers 15% search + 10% UI/templates; employer usability.

# PROJECT STRUCTURE

CV-online
│
├── app
│   ├── controllers
│   │   └── EmployerController.php
│   │
│   ├── services
│   │   └── CVSearchService.php
│   │
│   └── functions
│
├── config
│   └── DataConfig.php
│
├── public
│   ├── index.php
│   ├── css
│   └── js
│
├── resources
│   └── views
│       ├── employer
│       │   ├── search.php
│       │   └── result.php
│       │
│       └── cv_templates
│           ├── modern.php
│           ├── classic.php
│           └── minimal.php
│
└── database
    └── cv_online.sql
