# Online CV Management & Search System

Repository: https://github.com/TJack-coder/ASSIGNMENT-1---CV-online.git

## 1. Project Overview

This project is an **Online CV Management & Search System** for the Web Programming assignment.

The system allows:

- **Job Seekers** to register, log in, create, edit, save, and view one online CV.
- **Employers** to search, filter, and view CVs in read-only mode.
- **Administrators** to manage users and reference data such as categories, skills, degrees, majors, locations, industries, employment types, certificates, and organizations.

The project focuses on structured CV data, database normalization, dynamic CV forms, role-based access control, and multiple CV display templates.

---

## 2. Main Features

### Job Seeker

- Register and log in.
- Create one complete online CV.
- Edit and update CV information.
- Save structured CV data into the database.
- View CV using different templates.
- Manage:
  - Personal information
  - Structured address
  - CV category
  - Education records
  - Work history records
  - Certificates
  - Skills

### Employer

- Search CVs by multiple criteria.
- Filter CVs by:
  - Keyword
  - CV category
  - Location
  - Skills
  - Minimum proficiency level
  - Degree level
- View CVs in read-only mode.
- Switch between CV templates.

### Administrator

- Manage users.
- Manage reference tables:
  - Categories
  - Skills
  - Degrees
  - Majors
  - Industries
  - Countries
  - Cities
  - Districts
  - Employment types
  - Certificates
  - Organizations
  - Proficiency levels

---

## 3. Technologies Used

- PHP
- MySQL
- HTML
- CSS
- JavaScript
- XAMPP
- phpMyAdmin
- Git / GitHub

---

## 4. Project Structure

```text
ASSIGNMENT-1---CV-online/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php
│   │       └── CvController.php
│   │
│   ├── controllers/
│   ├── functions/
│   ├── middleware/
│   ├── model/
│   ├── models/
│   └── services/
│
├── config/
│
├── public/
│   └── index.php
│
├── resources/
│   ├── view/
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   └── register.blade.php
│   │   │
│   │   └── seeker/
│   │       └── cv/
│   │           ├── form.blade.php
│   │           ├── templates.blade.php
│   │           ├── modern.php
│   │           ├── classic.php
│   │           └── minimal.php
│   │
│   └── views/
│
├── routes/
├── storage/
├── README.md
└── .gitignore
```

---

## 5. Database Requirements

Create a MySQL database named:

```sql
cv_online
```

The system uses normalized tables such as:

```text
users
cvs
categories
countries
cities
district
educations
work_histories
certificates
cv_skills
skills
degrees
majors
institutions
job_title
employment_types
industries
certificate_name
organizations
proficients
```

Important relationships:

- One user can have one CV.
- One CV can have many education records.
- One CV can have many work history records.
- One CV can have many certificates.
- One CV can have up to five strongest skills.
- CV categories, skills, degrees, majors, industries, locations, certificates, and proficiency levels are stored in reference tables.

---

## 6. How to Run the Project Locally

### Step 1: Install XAMPP

Download and install XAMPP.

Start the following services:

```text
Apache
MySQL
```

---

### Step 2: Clone the Repository

Open terminal inside:

```text
C:\xampp\htdocs
```

Run:

```bash
git clone https://github.com/TJack-coder/ASSIGNMENT-1---CV-online.git
```

Move into the project folder:

```bash
cd ASSIGNMENT-1---CV-online
```

---

### Step 3: Create Database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Create a new database:

```sql
cv_online
```

If the project has a `.sql` database file, import it into the `cv_online` database.

In phpMyAdmin:

```text
cv_online → Import → Choose SQL file → Go
```

---

### Step 4: Check Database Connection

Open the configuration file in the project and make sure the database information is correct.

Example:

```php
$host = '127.0.0.1';
$dbname = 'cv_online';
$username = 'root';
$password = '';
```

For XAMPP, the default MySQL username is usually:

```text
root
```

and the default password is usually empty.

---

### Step 5: Run the Project

Open the browser and go to:

```text
http://localhost/ASSIGNMENT-1---CV-online/public/
```

If the project routes are handled by `public/index.php`, this URL should open the system.

---

## 7. Suggested User Flow

### Job Seeker Flow

```text
Register
→ Login
→ Create CV
→ Fill in CV information
→ Add education records
→ Add work history records
→ Add certificates
→ Select up to 5 skills
→ Save CV
→ View CV
→ Change template
→ Edit CV if needed
```

### Employer Flow

```text
Login as Employer
→ Open CV search page
→ Select search/filter criteria
→ View matching CVs
→ Open CV details
→ Switch CV template
```

### Admin Flow

```text
Login as Admin
→ Manage users
→ Manage reference tables
→ Add/update/delete invalid reference data
```

---

## 8. CV Templates

The system provides three CV view templates:

```text
Modern
Classic
Minimal
```

All templates use the same CV data stored in the database.

The templates only differ in layout and visual style.

---

## 9. Important Rules

- Each job seeker can create only one CV.
- CV data must be stored in structured database fields.
- Address must be separated into country, city/province, district, street address, and postal code.
- Free-text category input is not allowed.
- Free-text skill input is not allowed.
- Each CV can have a maximum of five strongest skills.
- Employers can only view CVs and cannot edit them.
- CV templates must use the same stored CV data.
- CV upload, job posting, job application, and AI recommendation features are not required.

---


